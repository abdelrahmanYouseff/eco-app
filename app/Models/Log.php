<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'scanned_by',
        'location',
        'qr_code_snapshot',
    ];

    // علاقة السجل بالمستخدمين
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
