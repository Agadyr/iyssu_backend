<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
USE Illuminate\Support\Facades\DB;
return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->index();
            $table->string('brand')->index();
            $table->unsignedInteger('quantity');
            $table->enum('unit', ['ml', 'pcs'])->default('ml');
            $table->boolean('is_new')->default(false)->index();
            $table->enum('gender', ['man', 'women', 'uni'])->default('man');
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();

            $table->jsonb('volume_options')->nullable();
            $table->jsonb('scent')->nullable();
            $table->jsonb('image_url')->nullable();
            $table->jsonb('brand_url')->nullable();

            $table->decimal('rating', 5, 2)->default(0)->index();
            $table->unsignedInteger('discount')->default(0)->index();

            $table->timestamps();

            $table->index(['price', 'rating']);
            $table->index(['brand', 'category_id']);
            $table->index(['discount', 'is_new']);
        });

        DB::statement("ALTER TABLE products ADD COLUMN search_vector tsvector");
        DB::statement("UPDATE products SET search_vector = to_tsvector('russian', name || ' ' || description)");

        DB::statement("CREATE INDEX products_search_idx ON products USING GIN(search_vector)");
        DB::statement('CREATE INDEX products_scent_idx ON products USING GIN(scent jsonb_ops)');


        DB::statement("
        CREATE FUNCTION products_tsvector_update() RETURNS trigger AS $$
        BEGIN
            NEW.search_vector := to_tsvector('russian', NEW.name || ' ' || NEW.description);
            RETURN NEW;
        END
        $$ LANGUAGE plpgsql;
    ");

        DB::statement("
        CREATE TRIGGER products_tsvector_update BEFORE INSERT OR UPDATE
        ON products FOR EACH ROW EXECUTE FUNCTION products_tsvector_update();
    ");

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP TRIGGER IF EXISTS products_tsvector_update ON products");
        DB::statement("DROP FUNCTION IF EXISTS products_tsvector_update");
        DB::statement("DROP INDEX IF EXISTS products_search_idx");
        DB::statement("ALTER TABLE products DROP COLUMN search_vector");
        DB::statement("DROP INDEX IF EXISTS products_scent_idx");

        Schema::dropIfExists('products');
    }
};
