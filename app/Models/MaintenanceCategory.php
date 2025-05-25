<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    // علاقة الفئة بطلبات الصيانة
    public function maintenanceRequests()
    {
        return $this->hasMany(MaintenanceRequest::class);
    }
}
