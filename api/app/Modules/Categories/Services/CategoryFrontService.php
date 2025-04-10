<?php

namespace App\Modules\Categories\Services;


use App\Models\CategoriesLanguage;
use App\Models\Category;
use App\Models\Transaction;
use App\Modules\Core\Services\Service;
use Illuminate\Http\Request;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;

class CategoryFrontService extends Service
{

    public function __construct(Category $model)
    {
        parent::__construct($model);
    }

    public function fetchCategories($type, $search, $perPage, $sortBy, $sortDirection)
    {
        $query = $this->buildQuery($type, $search, $sortBy, $sortDirection);
        return $query->paginate($perPage)->withQueryString();
    }

    private function buildQuery($type, $search, $sortBy, $sortDirection)
    {
        $locale = App::getLocale();
        $categoryType = $this->mapCategoryType($type);

        $query = Category::where('language', $locale)
            ->join('categories_languages', 'categories.id', '=', 'categories_languages.category_id')
            ->where('active', true)
            ->select('icon_url', 'categories.id as id', 'name', 'description', 'category_type');

        foreach ($query as $category) {
            if ($category->icon_url !== 'noUrl') $category->icon_url = url("api/" . $category->icon_url);
        }

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

    private function mapCategoryType($type)
    {
        return match ($type) {
            __('constants.incomes') => __('constants.income'),
            __('constants.expenses') => __('constants.expense'),
            default => null,
        };
    }
}
