<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'phone',
        'description',
        'owner_id',
    ];

    // علاقة المبنى بالشركات
    public function companies()
    {
        return $this->hasMany(Company::class);
    }
}
