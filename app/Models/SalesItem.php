<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesItem extends Model
{
    use HasFactory;
    protected $guarded = [];
    function customer()
    {
        return $this->belongsTo(Customer::class)->select('id', 'name');
    }
    function product()
    {
        return $this->belongsTo(Product::class);
    }
}
