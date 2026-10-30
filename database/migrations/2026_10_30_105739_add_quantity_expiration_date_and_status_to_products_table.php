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
        Schema::table('products', function (Blueprint $table) {
            $table->renameColumn('stock', 'quantity');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('price');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->date('expiration_date')->nullable();
            $table->string('status')->default('active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['expiration_date', 'status']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->decimal('price', 10, 2)->default(0);
            $table->renameColumn('quantity', 'stock');
        });
    }
};
