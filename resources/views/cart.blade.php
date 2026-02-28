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
                        <div class="cart-item">
                            <div class="cart-item-info">
                                <img src="{{ asset('storage/' . $item->service->image) }}" alt="{{ $item->service->name }}" class="cart-item-img" />
                                <div>
                                    <div class="cart-item-name">{{ $item->service->name }}</div>
                                    <div class="cart-item-desc">{{ $item->service->description }}</div>
                                </div>
                            </div>
                            <div class="cart-item-price">{{ number_format($item->service->price, 0, '', ' ') }} руб.</div>
                            <div class="quantity-control">
                                <form action="{{ route('cart.update', $item->id) }}" method="POST" class="quantity-form">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" name="action" value="decrease" class="quantity-btn">-</button>
                                    <span class="quantity">{{ $item->quantity }}</span>
                                    <button type="submit" name="action" value="increase" class="quantity-btn">+</button>
                                </form>
                            </div>
                            <div class="cart-item-price">{{ number_format($item->service->price * $item->quantity, 0, '', ' ') }} руб.</div>
                            <div>
                                <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="remove-item">&times;</button>
                                </form>
                            </div>
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
