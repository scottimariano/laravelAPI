<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountRange extends Model
{
    protected $table = 'discount_ranges';

    protected $fillable = [
        'from_days',
        'to_days',
        'discount',
        'code',
        'discount_id',
    ];

    public function discount()
    {
        return $this->belongsTo('App\Models\Discount');
    }
}
