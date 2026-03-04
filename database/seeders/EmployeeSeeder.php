<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\EmployeeCategory;
use App\Models\EmployeeSchedule;
use App\Models\Service;
use App\Models\ServicePrice;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Создаем категории сотрудников
        $categories = [
            [
                'name' => 'Топ-мастер',
                'is_active' => true,
            ],
            [
                'name' => 'Мастер 1 категории',
                'is_active' => true,
            ],
            [
                'name' => 'Мастер 2 категории',
                'is_active' => true,
            ],
            [
                'name' => 'Начинающий мастер',
                'is_active' => true,
            ],
        ];

        $createdCategories = [];
        foreach ($categories as $category) {
            $createdCategories[$category['name']] = EmployeeCategory::create($category);
        }

        // Создаем сотрудников
        $employees = [
            [
                'name' => 'Анна Иванова',
                'category' => 'Топ-мастер',
                'is_active' => true,
            ],
            [
                'name' => 'Мария Петрова',
                'category' => 'Топ-мастер',
                'is_active' => true,
            ],
            [
                'name' => 'Елена Сидорова',
                'category' => 'Мастер 1 категории',
                'is_active' => true,
            ],
            [
                'name' => 'Ольга Козлова',
                'category' => 'Мастер 1 категории',
                'is_active' => true,
            ],
            [
                'name' => 'Наталья Новикова',
                'category' => 'Мастер 2 категории',
                'is_active' => true,
            ],
            [
                'name' => 'Светлана Морозова',
                'category' => 'Мастер 2 категории',
                'is_active' => true,
            ],
            [
                'name' => 'Дарья Волкова',
                'category' => 'Начинающий мастер',
                'is_active' => true,
            ],
            [
                'name' => 'Виктория Лебедева',
                'category' => 'Начинающий мастер',
                'is_active' => true,
            ],
        ];

        $createdEmployees = [];
        foreach ($employees as $employee) {
            $createdEmployee = Employee::create([
                'name' => $employee['name'],
                'category_id' => $createdCategories[$employee['category']]->id,
                'is_active' => $employee['is_active'],
            ]);

            $createdEmployees[$employee['name']] = $createdEmployee;

            // Создаем расписание для каждого сотрудника
            $daysOfWeek = [
                'Понедельник',
                'Вторник',
                'Среда',
                'Четверг',
                'Пятница',
                'Суббота',
                'Воскресенье',
            ];

            foreach ($daysOfWeek as $day) {
                // Сотрудники работают с 9:00 до 18:00 в будние дни и с 10:00 до 16:00 в выходные
                if ($day === 'Суббота' || $day === 'Воскресенье') {
                    $startTime = '10:00';
                    $endTime = '16:00';
                } else {
                    $startTime = '09:00';
                    $endTime = '18:00';
                }

                EmployeeSchedule::create([
                    'employee_id' => $createdEmployee->id,
                    'day_of_week' => $day,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                ]);
            }
        }

        // Создаем цены услуг для каждой категории сотрудников
        $services = Service::all();

        foreach ($services as $service) {
            // Базовая цена для топ-мастера
            $topMasterPrice = $service->price;

            // Цены для других категорий (процент от базовой цены)
            $categoryPrices = [
                'Топ-мастер' => $topMasterPrice,
                'Мастер 1 категории' => $topMasterPrice * 0.9,
                'Мастер 2 категории' => $topMasterPrice * 0.8,
                'Начинающий мастер' => $topMasterPrice * 0.7,
            ];

            foreach ($categoryPrices as $categoryName => $price) {
                ServicePrice::create([
                    'service_id' => $service->id,
                    'category_id' => $createdCategories[$categoryName]->id,
                    'price' => round($price, 2),
                ]);
            }
        }
    }
}
