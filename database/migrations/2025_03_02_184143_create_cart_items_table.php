<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Migrations\UserProductMigration;

return new class extends UserProductMigration
{
    protected string $tableName = 'cart_items';

    public function up(): void
    {
        parent::up();
        Schema::table($this->tableName, static function (Blueprint $table) {
            $table->unsignedInteger('quantity')->default(1);
            $table->unsignedInteger('volume_option')->default(3);
        });
    }
};
