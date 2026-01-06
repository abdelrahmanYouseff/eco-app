<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
        'company_id',
        'badge_id',
        'is_inside',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if (empty($user->badge_id)) {
                $user->badge_id = Str::uuid();
            }
        });
    }

    // علاقة المستخدم بالشركة
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    // علاقة المستخدم بالإعلانات
    public function announcements()
    {
        return $this->hasMany(Announcement::class, 'published_by');
    }

    // علاقة المستخدم بالزيارات
    public function visits()
    {
        return $this->hasMany(Visit::class, 'created_by_id');
    }
}

