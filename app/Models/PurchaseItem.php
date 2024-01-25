<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseItem extends Model
{
    use HasFactory;
    protected $guarded = [];
    function supplier()
    {
        return $this->belongsTo(Supplier::class)->select('id', 'name');
    }
    function product()
    {
        return $this->belongsTo(Product::class);
    }
}
