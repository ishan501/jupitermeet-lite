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
        Schema::create('coupons', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->index('name');
            $table->string('code')->index('code');
            $table->boolean('type')->index('type');
            $table->decimal('percentage', 5)->nullable();
            $table->integer('quantity')->nullable();
            $table->integer('days')->nullable();
            $table->integer('redeems')->nullable()->default(0);
            $table->tinyInteger('status')->nullable()->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
