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
        Schema::create('payment_gateway_plan_relation', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('plan_id')->comment('Plan Id from plan table');
            $table->string('plan_id_gateway')->nullable()->comment('Plan Id from payment gateway');
            $table->string('payment_gateway')->nullable()->comment('Payment Gateway');
            $table->string('plan_code')->nullable()->comment('Plan Code from payment gateway');
            $table->string('amount')->nullable()->comment('Amount');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_gateway_plan_relation');
    }
};
