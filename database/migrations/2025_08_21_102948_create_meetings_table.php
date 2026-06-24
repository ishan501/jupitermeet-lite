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
        Schema::create('meetings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('meeting_id', 9)->unique();
            $table->string('title', 100);
            $table->string('description', 1000)->nullable();
            $table->text('invites')->nullable();
            $table->unsignedBigInteger('user_id')->index('meetings_user_id_foreign');
            $table->string('password', 8)->nullable();
            $table->enum('status', ['active', 'inactive']);
            $table->date('date')->nullable();
            $table->time('time')->nullable();
            $table->string('timezone', 100)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meetings');
    }
};
