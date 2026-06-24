<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meeting_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('meeting_detail_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('participant_name')->nullable();
            $table->enum('status', [
                'joined',
                'left',
                'disconnected',
                'kicked'
            ])->default('joined');
            $table->timestamp('joined_at')->nullable();
            $table->timestamp('left_at')->nullable();
            $table->unsignedInteger('duration')->default(0)->comment('Participant duration in seconds');
            $table->boolean('is_moderator')->default(false);
            $table->timestamps();

            $table->index('meeting_detail_id');
            $table->index('user_id');
            $table->index(['meeting_detail_id', 'joined_at']);
            $table->foreign('meeting_detail_id')
                  ->references('id')
                  ->on('meeting_details')
                  ->onDelete('cascade');

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meeting_logs');
    }
};  