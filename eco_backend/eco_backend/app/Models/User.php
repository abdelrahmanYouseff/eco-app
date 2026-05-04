<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
<<<<<<< HEAD:eco_backend/eco_backend/app/Models/User.php
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;class User extends Authenticatable
=======
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class User extends Authenticatable
>>>>>>> 214f60c165b7db18fb2afc2c6aa07b4401e9122d:app/Models/User.php
{
    use HasFactory, HasApiTokens, Notifiable, LogsActivity;

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

    public function locations()
    {
        return $this->hasMany(UserLocation::class);
    }

    /**
     * Configure activity log options for this model.
     * This will automatically log create, update, and delete events.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email', 'phone', 'role', 'company_id'])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => match($eventName) {
                'created' => "تم إنشاء مستخدم جديد: {$this->name}",
                'updated' => "تم تحديث بيانات المستخدم: {$this->name}",
                'deleted' => "تم حذف المستخدم: {$this->name}",
                default => "إجراء على المستخدم: {$this->name}",
            })
            ->useLogName('user');
    }
}

