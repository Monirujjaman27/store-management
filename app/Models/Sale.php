<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;
    protected $guarded = [];

    function customer()
    {
        return $this->belongsTo(Customer::class)->select('id', 'name', 'phone');
    }
    function sale_items()
    {
        return $this->hasMany(SalesItem::class);
    }
}
