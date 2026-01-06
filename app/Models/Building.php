<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'owner_id',
        'units_count',
        'floors_count',
        'address',
    ];

    // علاقة المبنى بالمالك
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    // علاقة المبنى بالشركات
    public function companies()
    {
        return $this->hasMany(Company::class);
    }

    // علاقة المبنى بالوحدات
    public function units()
    {
        return $this->hasMany(\App\PropertyManagement\Models\Unit::class);
    }
}
