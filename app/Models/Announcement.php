<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'sub_title',
        'body',
        'image',
        'type',
        'published_by',
        'visible_to',
    ];

    // علاقة الإعلان بالمستخدم
    public function user()
    {
        return $this->belongsTo(User::class, 'published_by');
    }
}
