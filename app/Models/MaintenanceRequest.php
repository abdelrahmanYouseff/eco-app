<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'company_id',
        'requested_by',
        'category_id',
        'description',
        'status',
        'priority',
    ];

    // علاقة الطلب بالشركة
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    // علاقة الطلب بالمستخدم
    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    // علاقة الطلب بفئة الصيانة
    public function category()
    {
        return $this->belongsTo(MaintenanceCategory::class);
    }
}
