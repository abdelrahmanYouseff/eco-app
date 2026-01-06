<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    use HasFactory;

    protected $fillable = [
        'visitor_name',
        'visitor_access_at',
        'visitor_expires_at',
        'created_by_id',
    ];

    // علاقة الزيارة بالمستخدم
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }
}
