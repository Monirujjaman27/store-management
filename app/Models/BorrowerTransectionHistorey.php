<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BorrowerTransectionHistorey extends Model
{
    use HasFactory;
    protected $guarded = [];
    function borrower()
    {
        return $this->belongsTo(Borrower::class)->select('id', 'name', 'phone');
    }
}
