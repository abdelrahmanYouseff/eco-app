<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id(); // BIGINT [pk, increment]
            $table->string('title'); // عنوان الإيفنت
            $table->string('sub_title')->nullable(); // عنوان فرعي (nullable)
            $table->text('body'); // نص الإيفنت
            $table->string('image')->nullable(); // حقل الصورة (nullable)
            $table->enum('type', ['news', 'event']); // نوع الإيفنت
            $table->foreignId('published_by')->constrained('users')->onDelete('cascade'); // [ref: > users.id]
            $table->enum('visible_to', ['all', 'employees', 'visitors']); // من يمكنه رؤية الإيفنت
            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
