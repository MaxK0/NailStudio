@extends('layouts.layout')

@section('title', 'Nail Studio - Профиль')

@section('content')
    <section class="profile-page">
        <div class="container">
            <h1>Мои заказы</h1>

            @if($orders->isEmpty())
                <div class="empty-orders">
                    <p>У вас пока нет заказов</p>
                    <a href="{{ url('/') }}#services" class="continue-shopping">
                        <i class="fas fa-arrow-left"></i> Перейти к услугам
                    </a>
                </div>
            @else
                <div class="orders-container">
                    <table class="orders-table">
                        <thead>
                        <tr>
                            <th>Номер заказа</th>
                            <th>Услуги</th>
                            <th>Мастер</th>
                            <th>Дата и время</th>
                            <th>Статус</th>
                            <th>Сумма</th>
                            <th>Дата создания</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td>#{{ $order->id }}</td>
                                <td>
                                    <ul class="order-services">
                                        @foreach($order->items as $item)
                                            <li>{{ $item->service->name }} ({{ $item->quantity }} шт.)</li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td>
                                    <ul class="order-employees">
                                        @foreach($order->items as $item)
                                            <li>
                                                @if($item->employee)
                                                    {{ $item->employee->name }} ({{ $item->employee->category->name }})
                                                @else
                                                    Не назначен
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td>
                                    <ul class="order-times">
                                        @foreach($order->items as $item)
                                            <li>
                                                @if($item->appointment_time)
                                                    {{ $item->appointment_time->format('d.m.Y H:i') }}
                                                @else
                                                    Не назначено
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td>
                                <span class="status-badge status-{{ \Illuminate\Support\Str::slug($order->status) }}">
                                    {{ $order->status }}
                                </span>
                                </td>
                                <td>{{ number_format($order->total_price, 0, '', ' ') }} руб.</td>
                                <td>{{ $order->created_at->format('d.m.Y H:i') }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </section>
@endsection
