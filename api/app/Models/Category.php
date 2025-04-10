<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'active', 'category_type', 'icon_url'];

    protected $hidden = ['created_at', 'updated_at', 'language'];

    public function scopeByCategoryId($query, $categoryId)
    {
        return $query->where('id', $categoryId);
    }

    public function scopeFindWithLanguage($query, $categoryId)
    {
        return $query->where('categories_languages.category_id', $categoryId)
            ->where('categories_languages.language', App::getLocale())
            ->join('categories_languages', 'categories.id', '=', 'categories_languages.category_id')
            ->select('name', 'description', 'icon_url', 'category_type')
            ->first();
    }

    public function languages()
    {
        return $this->hasMany(CategoriesLanguage::class);
    }
}
