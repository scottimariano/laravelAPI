<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DiscountRange extends Model
{
    use SoftDeletes;
    use HasFactory;
    
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
