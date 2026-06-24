<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MeetingLog extends Model
{
    protected $table = 'meeting_logs';

    protected $fillable = [
        'meeting_detail_id',
        'user_id',
        'participant_name',
        'status',
        'joined_at',
        'left_at',
        'is_moderator',
    ];

    protected $casts = [
        'joined_at' => 'datetime',
        'left_at' => 'datetime',
        'is_moderator' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function meetingDetail()
    {
        return $this->belongsTo(MeetingDetail::class, 'meeting_details_id');
    }
    public function getJoinedAtTzAttribute()
    {
        if (!$this->joined_at)
            return null;

        $timezone = auth()->user()?->timezone ?? 'UTC';

        return $this->joined_at->copy()->setTimezone($timezone);
    }

    public function getLeftAtTzAttribute()
    {
        if (!$this->left_at)
            return null;

        $timezone = auth()->user()?->timezone ?? 'UTC';

        return $this->left_at->copy()->setTimezone($timezone);
    }
}