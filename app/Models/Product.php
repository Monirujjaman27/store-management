<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;
    protected $guarded = [];
    function sales_items()
    {
        return $this->hasMany(SalesItem::class);
    }
    function purchase_items()
    {
        return $this->hasMany(PurchaseItem::class);
    }
    public static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            $product->sku = DB::table('products')->max('sku') + 1; // Generate a unique numeric SKU

        });
    }
}
