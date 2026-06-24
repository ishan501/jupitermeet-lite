<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meeting_details', function (Blueprint $table) {
            $table->id();

            // Link to existing meetings table
            $table->unsignedBigInteger('meeting_id');

            // ongoing / ended
            $table->enum('status', ['ongoing', 'ended'])->default('ongoing');

            // Optional summary (since you mentioned it)
            $table->longText('transcription')->nullable();
            $table->longText('summary')->nullable();

            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();

            $table->unsignedInteger('duration')->default(0);

            $table->timestamps();
            $table->softDeletes();

            // Index for faster lookup
            $table->index('meeting_id');

            // Foreign key (adjust if meetings table name differs)
            $table->foreign('meeting_id')
                  ->references('id')
                  ->on('meetings')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meeting_details');
    }
};