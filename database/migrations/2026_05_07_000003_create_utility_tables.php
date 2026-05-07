<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('mediable_id')->nullable();
            $table->string('mediable_type')->nullable();
            $table->string('file_path');
            $table->string('file_name');
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('size')->default(0);
            $table->jsonb('custom_properties')->nullable();
            $table->timestamps();

            $table->index(['mediable_id', 'mediable_type']);
        });

        Schema::create('activity_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('event')->index();
            $table->uuid('subject_id')->nullable();
            $table->string('subject_type')->nullable();
            $table->jsonb('properties')->nullable(); // For logging changes
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();

            $table->index(['subject_id', 'subject_type']);
            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('media');
    }
};
