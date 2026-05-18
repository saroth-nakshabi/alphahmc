<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

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