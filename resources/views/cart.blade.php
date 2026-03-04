@extends('layouts.layout')

@section('title', 'Nail Studio - Корзина')

@section('content')
    <section class="cart-page">
        <div class="container">
            <h1>Ваша корзина</h1>

            <div class="cart-container">
                @if($cartItems->isEmpty())
                    <div class="empty-cart">
                        <p>Ваша корзина пуста</p>
                        <a href="{{ url('/') }}#services" class="continue-shopping">
                            <i class="fas fa-arrow-left"></i> Перейти к услугам
                        </a>
                    </div>
                @else
                    <div class="cart-header">
                        <div>Услуга</div>
                        <div>Цена</div>
                        <div>Количество</div>
                        <div>Сумма</div>
                        <div></div>
                    </div>

                    @foreach($cartItems as $item)
                        <div class="cart-item" data-id="{{ $item->id }}">
                            <div class="cart-item-info">
                                <img src="{{ asset('storage/' . $item->service->image) }}" alt="{{ $item->service->name }}" class="cart-item-img" />
                                <div>
                                    <div class="cart-item-name">{{ $item->service->name }}</div>
                                    <div class="cart-item-desc">{{ $item->service->description }}</div>
                                </div>
                            </div>
                            <div class="cart-item-price" data-service-id="{{ $item->service_id }}">
                                {{ number_format($item->price, 0, '', ' ') }} руб.
                            </div>
                            <div class="quantity-control">
                                <form action="{{ route('cart.update', $item->id) }}" method="POST" class="quantity-form">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" name="action" value="decrease" class="quantity-btn">-</button>
                                    <span class="quantity">{{ $item->quantity }}</span>
                                    <button type="submit" name="action" value="increase" class="quantity-btn">+</button>
                                </form>
                            </div>
                            <div class="cart-item-total">
                                {{ number_format($item->price * $item->quantity, 0, '', ' ') }} руб.
                            </div>
                            <div>
                                <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="remove-item">&times;</button>
                                </form>
                            </div>
                        </div>

                        <!-- Блок выбора сотрудника и времени -->
                        <div class="cart-employee-selection" data-cart-item-id="{{ $item->id }}">
                            <h3>Выберите мастера и время записи</h3>

                            <form action="{{ route('cart.update.employee.time', $item->id) }}" method="POST" class="update-employee-time-form">
                                @csrf
                                @method('PATCH')

                                <div class="employees-grid">
                                    @foreach($item->service->getEmployeesAttribute() as $employee)
                                        <div class="employee-option">
                                            <input type="radio"
                                                   name="employee_id"
                                                   id="employee_{{ $item->id }}_{{ $employee->id }}"
                                                   value="{{ $employee->id }}"
                                                   data-price="{{ $employee->category_id }}"
                                                   data-service-id="{{ $item->service_id }}"
                                                   {{ $item->employee_id == $employee->id ? 'checked' : '' }}
                                                   required>
                                            <label for="employee_{{ $item->id }}_{{ $employee->id }}" class="employee-card">
                                                <div class="employee-info">
                                                    <div class="employee-name">{{ $employee->name }}</div>
                                                    <div class="employee-category">{{ $employee->category->name ?? 'Мастер' }}</div>
                                                    <div class="employee-price">
                                                        @php
                                                            $servicePrice = \App\Models\ServicePrice::where('service_id', $item->service_id)
                                                                ->where('category_id', $employee->category_id)
                                                                ->first();
                                                            $price = $servicePrice ? $servicePrice->price : $item->service->price;
                                                        @endphp
                                                        {{ number_format($price, 0, '', ' ') }} руб.
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="appointment-date">
                                    <label for="appointment_date_{{ $item->id }}">Дата:</label>
                                    <input type="date"
                                           name="appointment_date"
                                           id="appointment_date_{{ $item->id }}"
                                           value="{{ $item->appointment_time ? $item->appointment_time->format('Y-m-d') : '' }}"
                                           required>
                                </div>

                                <div class="appointment-time" id="slots-container-{{ $item->id }}">
                                    <!-- Слоты времени будут загружены через JavaScript -->
                                </div>

                                <input type="hidden"
                                       name="appointment_time"
                                       id="appointment_time_{{ $item->id }}"
                                       value="{{ $item->appointment_time && is_object($item->appointment_time) ? $item->appointment_time->format('Y-m-d\TH:i') : '' }}"
                                >

                                <button type="submit" class="update-btn">Сохранить выбор</button>
                            </form>
                        </div>
                    @endforeach

                    <div class="cart-summary">
                        <div class="cart-total">
                            Итого: <span class="total-price">{{ number_format($total, 0, '', ' ') }}</span> руб.
                        </div>
                        <form action="{{ route('cart.checkout') }}" method="POST">
                            @csrf
                            <button type="submit" class="checkout-btn">Оформить запись</button>
                        </form>
                    </div>

                    <a href="{{ url('/') }}#services" class="continue-shopping">
                        <i class="fas fa-arrow-left"></i> Продолжить выбор услуг
                    </a>
                @endif
            </div>

        </div>
    </section>
@endsection
