<?php

namespace App\Modules\Categories\Services;

use App\Models\Category;
use App\Modules\Core\Services\Service;
use App\Modules\Images\Services\ImageService;
use Exception;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class CategoryBackService extends Service
{


    public function __construct(Category $model)
    {
        parent::__construct($model);
        $this->rules = $this->getValidationRules();
    }

    public function fetchCategories($type, $search, $perPage, $sortBy, $sortDirection)
    {
        $query = $this->buildQuery($type, $search, $sortBy, $sortDirection);
        $result = $query->paginate($perPage)->withQueryString();
        foreach ($result as $category) {
            if ($category->icon_url !== 'noUrl') $category->icon_url = url("api/" . $category->icon_url);
        }
        return $result;
    }

    private function buildQuery($type, $search, $sortBy, $sortDirection)
    {
        $locale = App::getLocale();
        $categoryType = $this->mapCategoryType($type);

        $query = Category::where('language', $locale)
            ->join('categories_languages', 'categories.id', '=', 'categories_languages.category_id')
            ->select('categories.id as id', 'name', 'description', 'category_type', 'active', 'icon_url');

        if ($categoryType) {
            $query = $query->where('category_type', $categoryType);
        }

        if ($search) {
            $query = $query->where(function ($query) use ($search) {
                $query->where('name', 'LIKE', '%' . $search . '%')
                    ->orWhere('description', 'LIKE', '%' . $search . '%');
            });
        }

        return $query->orderBy($sortBy, $sortDirection);
    }


    public function get(int $categoryId)
    {
        $category = $this->model::with('languages')
            ->byCategoryId($categoryId)
            ->select('id', 'icon_url', 'active')
            ->firstOrFail();

        $category->setAttribute('icon_url', url("api/" . $category->icon_url));

        return $category;
    }

    public function create($data, $ruleKey)
    {
        $this->validate($data, $ruleKey);

        if ($this->hasErrors()) {
            return;
        }

        return $this->processCategory($data);
    }

    public function update(int $categoryId, array $data, string $ruleKey)
    {
        $this->validate($data, $ruleKey);

        if ($this->hasErrors()) {
            return;
        }

        return $this->processCategory($data, $categoryId);
    }

    public function delete(int $categoryId)
    {
        $category = $this->model::findOrFail($categoryId);
        return $category->delete();
    }


    private function processCategory($data, $categoryId = null)
    {
        DB::beginTransaction();
        try {
            $category = $categoryId
                ? $this->model::findOrFail($categoryId)
                : $this->model->create([
                    'active' => $data['active'],
                    'icon_url' => $this->uploadImage($data['image'])
                ]);

            if ($categoryId) {
                $category->update(['active' => $data['active'],]);

                if ($data['image']) {
                    $oldImage = $category->icon_url;
                    $category->update(['icon_url' => $this->uploadImage($data['image'])]);
                    $this->deleteImage($oldImage);
                }

            }

            $this->updateCategoryLanguages($category, $data['languages']);
            DB::commit();
            return $category->load('languages');
        } catch (Exception $e) {
            DB::rollBack();
            $this->errors->add('process_error', $e->getMessage());
            return;
        }
    }

    private function uploadImage($image): string
    {
        $service = new ImageService();
        return $service->upload($image, 'categoryIcons');
    }

    private function updateCategoryLanguages($category, $languages): void
    {
        $category->languages()->delete();
        $category->languages()->createMany($languages);
    }

    private function deleteImage($imagePath): void
    {
        $service = new ImageService();
        $service->delete($imagePath);
    }

    private function mapCategoryType($type)
    {
        return match ($type) {
            __('constants.incomes') => __('constants.income'),
            __('constants.expenses') => __('constants.expense'),
            default => null,
        };
    }

    private function getValidationRules(): array
    {
        return [
            "create-update" => [
                "active" => "required|boolean",
                "image" => "string|nullable",
                "languages" => "required|array|min:1",
                "languages.*.language" => [
                    'required',
                    'string',
                    Rule::in(config('language.available_languages')),
                ],
                "languages.*.name" => "required|string",
                "languages.*.description" => "required|string",
                "languages.*.category_type" => "required|string",
            ]
        ];
    }
}
