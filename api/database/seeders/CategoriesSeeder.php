<?php

namespace Database\Seeders;

use App\Models\CategoriesLanguage;
use App\Models\Category;
use App\Modules\Categories\Services\CategoryBackService;
use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $file = fopen(database_path('seeders/categories.ndjson'), 'r');

        while (($line = fgets($file)) !== false) {
            $category = json_decode($line, true)['category'];

            $categoryData = [
                'active' => $category['active'],
                'image' => $category['icon_url'],
                'languages' => $category['categories_languages']
            ];

            $service = new CategoryBackService(new Category());
            $service->create($categoryData, "create-update");
        }
    }
}
