<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sort_order',
    ];

    protected static function booted(): void
    {
        static::creating(function ($model) {
            if (!$model->sort_order) {
                $model->sort_order = (static::max('sort_order') ?? 0) + 1;
            }
        });
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function allCategories()
    {
        return $this->belongsToMany(Category::class, 'category_main_category');
    }

    public function getMergedCategoriesAttribute()
    {
        $direct = $this->categories ?? collect();
        $pivot = $this->allCategories ?? collect();

        return $direct->merge($pivot)->unique('id')->values();
    }
}