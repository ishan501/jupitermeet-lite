<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Models\Activity;

class Plan extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table = 'plans';

    protected $fillable = [
        'popular'
    ];

    protected $casts = [
        'items' => 'object',
        'tax_rates' => 'object',
        'coupons' => 'object',
        'features' => 'object'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'description', 'currency', 'amount_month', 'amount_year', 'coupons', 'tax_rates', 'status', 'features'])
            ->useLogName(logName: 'Plan')
            ->logOnlyDirty();
    }

    public function tapActivity(Activity $activity)
    {
        $activity->properties = $activity->properties->merge([
            'attributes' => array_merge(
                $activity->properties->get('attributes', []),
                [
                    'name' => $this->name,
                ]
            ),
        ]);
    }

    /**
     * Get the plan price status
     */
    public function hasPrice()
    {
        return $this->amount_month || $this->amount_year;
    }

    public function scopeSearchName(Builder $query, $value)
    {
        return $query->where('name', 'like', '%' . $value . '%');
    }

    public function scopeOfVisibility(Builder $query, $value)
    {
        return $query->where('visibility', '=', $value);
    }

    public function scopePriced(Builder $query)
    {
        return $query->where([['amount_month', '>', 0], ['amount_year', '>', 0]]);
    }

    public function scopeDefault(Builder $query)
    {
        return $query->where([['amount_month', '=', 0], ['amount_year', '=', 0]]);
    }

    public function scopeActive(Builder $query)
    {
        return $query->where('status', 1);
    }
}
