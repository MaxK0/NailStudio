<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'price', 'image', 'duration'];

    public function servicePrices(): HasMany
    {
        return $this->hasMany(ServicePrice::class);
    }

    public function getEmployeesAttribute()
    {
        // Получаем все категории, для которых есть цена на эту услугу
        $categoryIds = $this->servicePrices->pluck('category_id')->toArray();

        // Получаем всех сотрудников из этих категорий
        return Employee::whereIn('category_id', $categoryIds)->get();
    }
    public function getMinPriceAttribute(): float
    {
        return $this->servicePrices()->min('price') ?? $this->price;
    }

    public function getMaxPriceAttribute(): float
    {
        return $this->servicePrices()->max('price') ?? $this->price;
    }
}
