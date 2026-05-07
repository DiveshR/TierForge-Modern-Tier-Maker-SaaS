<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tier_lists', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained()->cascadeOnDelete();
            $table->string('title')->index();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('category')->index();
            $table->boolean('is_public')->default(true)->index();
            $table->jsonb('metadata')->nullable(); // For extensible attributes
            $table->timestamps();
            $table->softDeletes();

            // Composite index for performance on user-specific listings
            $table->index(['user_id', 'created_at']);
        });

        Schema::create('tier_rows', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('tier_list_id')->constrained()->cascadeOnDelete();
            $table->string('label');
            $table->string('color')->default('#CCCCCC');
            $table->integer('order_index')->default(0);
            $table->timestamps();

            $table->index(['tier_list_id', 'order_index']);
        });

        Schema::create('tier_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->index();
            $table->text('description')->nullable();
            $table->jsonb('metadata')->nullable();
            $table->timestamps();
        });

        Schema::create('tier_item_positions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('tier_list_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('tier_row_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignUuid('tier_item_id')->constrained()->cascadeOnDelete();
            $table->integer('position')->default(0);
            $table->timestamps();

            // Index for fast retrieval of items in a specific row
            $table->index(['tier_list_id', 'tier_row_id', 'position']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tier_item_positions');
        Schema::dropIfExists('tier_items');
        Schema::dropIfExists('tier_rows');
        Schema::dropIfExists('tier_lists');
    }
};
