<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'service_id', 'quantity', 'employee_id', 'appointment_time'];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function appointment_time()
    {
        return $this->appointment_time ? Carbon::parse($this->appointment_time) : null;
    }

    public function getPriceAttribute(): float
    {
        if ($this->employee && $this->service) {
            $servicePrice = ServicePrice::where('service_id', $this->service_id)
                ->where('category_id', $this->employee->category_id)
                ->first();

            if ($servicePrice) {
                return $servicePrice->price;
            }
        }

        return $this->service ? $this->service->price : 0;
    }
}
