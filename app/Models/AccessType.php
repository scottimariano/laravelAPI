<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessType extends Model
{
    protected $table = 'access_types';

    protected $fillable = [
        'code',
        'name',
        'display_order',
    ];
}
