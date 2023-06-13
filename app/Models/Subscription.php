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

    public function getStatusAttribute($value)
    {
        if ($this->end_date && $this->end_date->isPast()) {
            return 'expired';
        }
        return $value;
    }

    public function getDurationInMonthsAttribute()
    {
        $start = Carbon::parse($this->start_date);
        $end = Carbon::parse($this->end_date);
        return $start->diffInMonths($end);
    }
}
