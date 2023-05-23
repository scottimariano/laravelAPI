<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Discount extends Model
{
    use SoftDeletes;

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

        // Evento "deleting" para eliminar en cascada los rangos de descuento
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
