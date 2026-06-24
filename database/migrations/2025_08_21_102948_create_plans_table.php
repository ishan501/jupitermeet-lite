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
        Schema::create('plans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('currency', 12);
            $table->tinyInteger('decimals')->nullable();
            $table->double('amount_month')->nullable();
            $table->double('amount_year')->nullable();
            $table->text('coupons')->nullable();
            $table->text('tax_rates')->nullable();
            $table->tinyInteger('visibility')->nullable();
            $table->text('features')->nullable();
            $table->string('color', 32)->nullable();
            $table->tinyInteger('status')->nullable()->default(1);
            $table->enum('popular', ['false', 'true']);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
