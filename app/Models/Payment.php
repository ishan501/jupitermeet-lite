<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Models\Activity;


class Payment extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'payments';

    protected $casts = [
        'product' => 'object',
        'tax_rates' => 'object',
        'coupon' => 'object',
        'customer' => 'object',
        'seller' => 'object'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('Transaction');
    }

    /**
     * Get the user that owns the payment.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * Get the plan of the payment.
     */
    public function plan()
    {
        return $this->belongsTo('App\Models\Plan');
    }

    public function scopeSearchPayment(Builder $query, $value)
    {
        return $query->where('payment_id', 'like', '%' . $value . '%');
    }

    public function scopeSearchInvoice(Builder $query, $value)
    {
        return $query->where([['invoice_id', 'like', '%' . $value . '%'], ['status', '=', 'completed']]);
    }

    public function scopeOfPlan(Builder $query, $value)
    {
        return $query->where('plan_id', '=', $value);
    }

    public function scopeOfUser(Builder $query, $value)
    {
        return $query->where('user_id', '=', $value);
    }

    public function scopeOfInterval(Builder $query, $value)
    {
        return $query->where('interval', '=', $value);
    }

    public function getCreatedAtCustomAttribute()
    {
        return convertToTimezone($this->created_at);
    }
}
