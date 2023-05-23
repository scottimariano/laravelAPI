<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $table = 'discounts';

    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'priority',
        'active',
        'region_id',
        'brand_id',
        'access_type_code',
    ];

    public function region()
    {
        return $this->belongsTo('App\Models\Region');
    }

    public function brand()
    {
        return $this->belongsTo('App\Models\Brand');
    }

    public function accessType()
    {
        return $this->belongsTo('App\Models\AccessType');
    }
}
