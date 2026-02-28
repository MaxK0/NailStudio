<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Создаем администратора
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        // Создаем тестового пользователя
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Создаем услуги
        $services = [
            [
                'name' => 'Маникюр',
                'description' => 'Классический, аппаратный или комбинированный маникюр',
                'price' => 1500,
                'image' => 'services/f7db7e8908d46788e5e78d0c87296f5243163b32_original.jpeg',
            ],
            [
                'name' => 'Педикюр',
                'description' => 'Профессиональный уход за стопами и ногтями ног',
                'price' => 2000,
                'image' => 'services/pe.jpg',
            ],
            [
                'name' => 'Покрытие гель-лак',
                'description' => 'Долговременное покрытие с выбором из 100+ цветов',
                'price' => 1200,
                'image' => 'services/lack.jpg',
            ],
            [
                'name' => 'Наращивание ногтей',
                'description' => 'Наращивание гелем или акрилом любой сложности',
                'price' => 3000,
                'image' => 'services/narsh.jpg',
            ],
            [
                'name' => 'Дизайн ногтей',
                'description' => 'Художественный дизайн одной ногтевой пластины',
                'price' => 500,
                'image' => 'services/dizain.jpg',
            ],
            [
                'name' => 'SPA для рук',
                'description' => 'Комплексный уход с массажем и питательными масками',
                'price' => 2500,
                'image' => 'services/spa.jpg',
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
