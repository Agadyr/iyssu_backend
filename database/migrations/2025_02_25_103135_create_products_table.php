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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->string('brand');
            $table->unsignedInteger('quantity');
            $table->enum('unit', ['ml', 'pcs'])->default('ml');
            $table->boolean('is_new')->default(false);
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();

            $table->json('volume_options')->nullable();
            $table->json('scent')->nullable();
            $table->json('image_url')->nullable();
            $table->decimal('rating', 5, 2)->default(0);
            $table->unsignedInteger('discount')->default(0);

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
