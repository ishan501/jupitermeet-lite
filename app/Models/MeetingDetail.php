<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MeetingDetail extends Model
{
    protected $table = 'meeting_details';

    protected $fillable = [
        'meeting_id',
        'status',
        'transcription',
        'summary',
        'started_at',
        'ended_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    public function meeting()
    {
        return $this->belongsTo(Meeting::class, 'meeting_id');
    }

    public function meetingLogs()
    {
        return $this->hasMany(MeetingLog::class, 'meeting_detail_id')->orderBy('joined_at', 'asc');
    }

    // accessors to get started_at and ended_at in user's timezone
    public function getStartedAtTzAttribute()
    {
        if (!$this->started_at)
            return null;

        $timezone = auth()->user()?->timezone ?? 'UTC';

        return $this->started_at->copy()->setTimezone($timezone);
    }

    // accessors to get started_at and ended_at in user's timezone
    public function getEndedAtTzAttribute()
    {
        if (!$this->ended_at)
            return null;

        $timezone = auth()->user()?->timezone ?? 'UTC';

        return $this->ended_at->copy()->setTimezone($timezone);
    }
}