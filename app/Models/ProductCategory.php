<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use HasFactory;
    protected $guarded = [];
    // realtion for parent category
    function parent_category()
    {
        return $this->belongsTo(ProductCategory::class, 'parent_category_id')->select('id', 'name');
    }
    // realtion for child category
    function child_category()
    {
        return $this->hasMany(ProductCategory::class, 'parent_category_id', 'id')->select('id', 'name');
    }
}
