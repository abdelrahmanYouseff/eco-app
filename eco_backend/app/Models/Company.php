<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'floor_number',
        'office_number',
        'admin_user_id',
        'building_id',
    ];

    // علاقة الشركة بالمستخدمين
    public function users()
    {
        return $this->hasMany(User::class);
    }

    // علاقة الشركة بالمباني
    public function building()
    {
        return $this->belongsTo(Building::class);
    }

    // علاقة الشركة بالطلبات الصيانة
    public function maintenanceRequests()
    {
        return $this->hasMany(MaintenanceRequest::class);
    }

    // علاقة الشركة بالمدير (admin)
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_user_id');
    }


    public function visitors() {
        return $this->hasManyThrough(Visitor::class, User::class);
    }
}
