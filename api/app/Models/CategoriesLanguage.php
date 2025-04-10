<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriesLanguage extends Model
{
    use HasFactory;

    protected $hidden = ['created_at', 'updated_at', 'id'];
    protected $fillable = ['name', 'description', 'category_type', 'language', 'category_id'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
