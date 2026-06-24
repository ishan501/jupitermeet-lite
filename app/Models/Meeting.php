<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Models\Activity;

class Meeting extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table = 'meetings';

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['meeting_id', 'title', 'password', 'user_id', 'date', 'time', 'timezone', 'status'])
            ->useLogName('Meeting')
            ->logOnlyDirty();
    }

    public function tapActivity(Activity $activity)
    {
        $activity->properties = $activity->properties->merge([
            'attributes' => array_merge(
                $activity->properties->get('attributes', []),
                [
                    'meeting_id' => $this->meeting_id,
                    'title' => $this->title,
                ]
            ),
        ]);
    }

    public function getCreatedAtCustomAttribute()
    {
        return convertToTimezone($this->created_ate);
    }

    public function meetingDetails()
    {
        return $this->hasMany(MeetingDetail::class, 'meeting_id')->orderBy(column: 'id', direction: 'desc');
    }
}
