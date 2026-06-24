<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Models\Activity;

class Page extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table = 'pages';

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['title', 'slug', 'footer', 'content'])
            ->useLogName('Page')
            ->logOnlyDirty();
    }

    public function tapActivity(Activity $activity)
    {
        $activity->properties = $activity->properties->merge([
            'attributes' => array_merge(
                $activity->properties->get('attributes', []),
                [
                    'title' => $this->title,
                ]
            ),
        ]);
    }
}
