<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->string('title', 191);
            $table->text('description')->nullable();
            $table->enum('type', ['image', 'video', 'youtube', 'tiktok']); // Added tiktok
            $table->string('file_path', 191)->nullable();
            $table->text('video_url')->nullable(); // Changed from youtube_url to video_url (supports both YouTube and TikTok)
            $table->string('video_id', 100)->nullable(); // Store extracted video ID
            $table->string('thumbnail_path', 191)->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();

            // Add indexes for better performance
            $table->index('type');
            $table->index('order');
            $table->index('created_at');
            $table->index(['is_active', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
