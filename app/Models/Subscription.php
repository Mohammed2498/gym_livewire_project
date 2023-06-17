<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Subscription extends Model
{

    use HasFactory;
    protected $table = 'subscriptions';
    protected $fillable = ['subscriber_id', 'end_date', 'start_date', 'status', 'duration', 'subscription_type'];
    protected $dates = ['start_date', 'end_date'];

    public function subscriber()
    {
        return $this->belongsTo(Subscriber::class, 'subscriber_id', 'id')->withDefault();
    }

    public function getEndDateAttribute($value)
    {
        return Carbon::parse($value);
    }

    public function getDurationInMonthsAttribute()
    {
        $start = Carbon::parse($this->start_date);
        $end = Carbon::parse($this->end_date);
        return $start->diffInMonths($end);
    }

    public function getRemainingDurationAttribute()
    {
        $endDate = $this->end_date;
        $now = Carbon::now();

        // Calculate the remaining duration in days
        $remainingDays = $endDate->diffInDays($now);

        // Calculate the remaining duration in months
        $remainingMonths = $endDate->diffInMonths($now);

        // Calculate the remaining duration in weeks
        $remainingWeeks = $endDate->diffInWeeks($now);

        return [
            'days' => $remainingDays,
            'months' => $remainingMonths,
            'weeks' => $remainingWeeks,
        ];
    }
}
