<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('plugins', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id');
            $table->string('product_name', 50);
            $table->string('license_code', 100);
            $table->string('domain', 50);
            $table->string('token', 64);
            $table->enum('status', ['active', 'inactive']);
            $table->softDeletes();
            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('plugins');
    }
};
