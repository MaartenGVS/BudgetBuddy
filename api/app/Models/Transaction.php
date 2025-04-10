<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['amount', 'month', 'year', 'description', 'category_id'];

    protected $hidden = ['id', 'created_at', 'updated_at'];


    public function scopeInPeriod($query, $month, $year)
    {
        return $query->where('month', $month)->where('year', $year);
    }


    public function scopeByTransactionNumber($query, $transactionNumber)
    {
        return $query->where('transaction_number', $transactionNumber);
    }

    public function scopeByUserId($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public static function boot(): void
    {
        parent::boot();

        self::creating(function ($model) {
            $model->transaction_number = (string)Str::uuid();
            $model->user_id = auth()->id();
        });

        self::created(function ($model) {
            $category = Category::findWithLanguage($model->category_id);
            $model->setAttribute('category', $category);
        });
    }
}
