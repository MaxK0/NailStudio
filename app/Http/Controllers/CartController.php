<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Employee;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = CartItem::where('user_id', Auth::id())->with(['service', 'employee'])->get();

        // Преобразуем строку времени в объект Carbon для каждого элемента корзины
        foreach ($cartItems as $item) {
            if ($item->appointment_time && is_string($item->appointment_time)) {
                $item->appointment_time = \Carbon\Carbon::parse($item->appointment_time);
            }
        }

        $total = $cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        return view('cart', compact('cartItems', 'total'));
    }


    public function add(Request $request, $serviceId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $service = Service::findOrFail($serviceId);

        $cartItem = CartItem::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'service_id' => $serviceId,
            ],
            [
                'quantity' => $request->quantity,
            ]
        );

        return redirect()->back()->with('success', 'Услуга добавлена в корзину');
    }

    public function update(Request $request, $id)
    {
        $cartItem = CartItem::findOrFail($id);

        if ($cartItem->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'У вас нет прав для редактирования этого элемента корзины');
        }

        if ($request->action === 'increase') {
            $cartItem->quantity++;
        } elseif ($request->action === 'decrease' && $cartItem->quantity > 1) {
            $cartItem->quantity--;
        }

        $cartItem->save();

        return redirect()->back()->with('success', 'Корзина обновлена');
    }

    public function remove($id)
    {
        $cartItem = CartItem::findOrFail($id);

        if ($cartItem->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'У вас нет прав для удаления этого элемента корзины');
        }

        $cartItem->delete();

        return redirect()->back()->with('success', 'Услуга удалена из корзины');
    }

    public function getBusySlots(Request $request)
    {
        $employeeId = $request->input('employee_id');
        $date = $request->input('date');
        $cartItemId = $request->input('cart_item_id');

        // Получаем все заказы на выбранную дату для выбранного сотрудника
        $orders = Order::whereDate('ready_at', $date)
            ->whereHas('items', function($query) use ($employeeId) {
                $query->where('employee_id', $employeeId);
            })
            ->get();

        // Получаем элементы корзины для того же сотрудника и даты (исключая текущий элемент)
        $cartItems = CartItem::where('employee_id', $employeeId)
            ->where('id', '!=', $cartItemId)
            ->whereDate('appointment_time', $date)
            ->get();

        // Формируем список занятых слотов
        $busySlots = [];

        // Добавляем слоты из заказов
        foreach ($orders as $order) {
            $orderTime = $order->ready_at->format('H:i');
            $busySlots[] = $orderTime;

            // Добавляем следующие слоты, если услуга длится более 30 минут
            $service = $order->items->first()->service;
            if ($service && $service->duration > 30) {
                $additionalSlots = ceil(($service->duration - 30) / 30);
                for ($i = 1; $i <= $additionalSlots; $i++) {
                    $nextSlot = date('H:i', strtotime($orderTime) + ($i * 30 * 60));
                    $busySlots[] = $nextSlot;
                }
            }
        }

        // Добавляем слоты из элементов корзины
        foreach ($cartItems as $cartItem) {
            $cartTime = Carbon::parse($cartItem->appointment_time)->format('H:i');
            $busySlots[] = $cartTime;

            // Добавляем следующие слоты, если услуга длится более 30 минут
            $service = $cartItem->service;
            if ($service && $service->duration > 30) {
                $additionalSlots = ceil(($service->duration - 30) / 30);
                for ($i = 1; $i <= $additionalSlots; $i++) {
                    $nextSlot = date('H:i', strtotime($cartTime) + ($i * 30 * 60));
                    $busySlots[] = $nextSlot;
                }
            }
        }

        // Формируем список всех возможных слотов
        $slots = [];
        for ($hour = 9; $hour <= 17; $hour++) {
            $slots[] = sprintf('%02d:00', $hour);
            $slots[] = sprintf('%02d:30', $hour);
        }

        // Формируем список доступных слотов
        $availableSlots = array_diff($slots, $busySlots);

        return response()->json([
            'slots' => $slots,
            'busySlots' => $busySlots,
            'availableSlots' => array_values($availableSlots)
        ]);
    }



    public function updateEmployeeAndTime(Request $request, $id)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'appointment_time' => 'required|date|after:now',
        ]);

        $cartItem = CartItem::findOrFail($id);

        if ($cartItem->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'У вас нет прав для редактирования этого элемента корзины');
        }

        $employee = Employee::findOrFail($request->employee_id);

        // Проверяем, доступен ли сотрудник в выбранное время
        $dayOfWeek = date('l', strtotime($request->appointment_time));
        $daysOfWeek = [
            'Monday' => 'Понедельник',
            'Tuesday' => 'Вторник',
            'Wednesday' => 'Среда',
            'Thursday' => 'Четверг',
            'Friday' => 'Пятница',
            'Saturday' => 'Суббота',
            'Sunday' => 'Воскресенье',
        ];

        $schedule = $employee->schedules()->where('day_of_week', $daysOfWeek[$dayOfWeek])->first();

        if (!$schedule) {
            return redirect()->back()->with('error', 'Сотрудник не работает в этот день');
        }

        $appointmentTime = date('H:i', strtotime($request->appointment_time));
        $startTime = date('H:i', strtotime($schedule->start_time));
        $endTime = date('H:i', strtotime($schedule->end_time));

        if ($appointmentTime < $startTime || $appointmentTime > $endTime) {
            return redirect()->back()->with('error', 'Сотрудник не работает в это время');
        }

        // Проверяем, не занят ли сотрудник в это время
        $service = Service::findOrFail($cartItem->service_id);
        $endTimeOfAppointment = date('Y-m-d H:i:s', strtotime($request->appointment_time) + ($service->duration * 60));

        $busy = CartItem::where('employee_id', $request->employee_id)
            ->where('id', '!=', $id)
            ->where(function ($query) use ($request, $endTimeOfAppointment) {
                $query->where('appointment_time', '<=', $request->appointment_time)
                    ->whereRaw('DATE_ADD(appointment_time, INTERVAL (SELECT duration FROM services WHERE id = cart_items.service_id) MINUTE) > ?',
                        [$request->appointment_time])
                    ->orWhere('appointment_time', '<=', $endTimeOfAppointment)
                    ->whereRaw('DATE_ADD(appointment_time, INTERVAL (SELECT duration FROM services WHERE id = cart_items.service_id) MINUTE) > ?',
                        [$endTimeOfAppointment]);
            })
            ->exists();

        if ($busy) {
            return redirect()->back()->with('error', 'Сотрудник занят в это время');
        }

        $cartItem->employee_id = $request->employee_id;
        $cartItem->appointment_time = $request->appointment_time;
        $cartItem->save();

        return redirect()->back()->with('success', 'Выбор сотрудника и времени обновлен');
    }



    public function checkout()
    {
        $cartItems = CartItem::where('user_id', Auth::id())->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Ваша корзина пуста');
        }

        // Проверяем, что для всех элементов корзины выбран сотрудник и время
        foreach ($cartItems as $item) {
            if (!$item->employee_id || !$item->appointment_time) {
                return redirect()->route('cart.index')->with('error',
                    'Пожалуйста, выберите сотрудника и время для всех услуг');
            }
        }

        // Вычисляем общую сумму заказа
        $totalPrice = $cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        $order = Auth::user()->orders()->create([
            'status' => 'Новое',
            'total_price' => $totalPrice,
        ]);

        foreach ($cartItems as $item) {
            $order->items()->create([
                'service_id' => $item->service_id,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'employee_id' => $item->employee_id,
                'appointment_time' => $item->appointment_time,
            ]);

            $item->delete();
        }

        return redirect()->route('profile')->with('success', 'Заказ успешно оформлен');
    }

}
