<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmployeeCategory extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'is_active'];

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

    public function servicePrices(): HasMany
    {
        return $this->hasMany(ServicePrice::class);
    }
}
