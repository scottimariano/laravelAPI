<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Discount extends Model
{
    use SoftDeletes;
    use HasFactory;

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

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($discount) {
            $discount->discountRanges()->delete();
        });

        static::restoring((function ($discount) {
            $discount->discountRanges()->restore();
        }));
    }

    public function region()
    {
        return $this->belongsTo('App\Models\Region');
    }

    public function brand()
    {
        return $this->belongsTo('App\Models\Brand');
    }

    public function accesstype()
    {
        return $this->belongsTo('App\Models\Accesstype', 'access_type_code', 'code');
    }

    public function discountRanges()
    {
        return $this->hasMany(DiscountRange::class);
    }

}
