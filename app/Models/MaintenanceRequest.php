<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'company_name',
        'requested_by',
        'description',
        'status',
    ];

    // علاقة الطلب بالمستخدم
    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }
}
