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
        Schema::create('addon_meeting_transcriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('meeting_detail_id');
            $table->string('participant_name')->nullable();
            $table->longText('transcription');
            $table->timestamp('segment_at')->nullable();
            $table->timestamps();

            $table->index('meeting_detail_id');

            $table->foreign('meeting_detail_id')
                  ->references('id')
                  ->on('meeting_details')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addon_meeting_transcription');
    }
};
