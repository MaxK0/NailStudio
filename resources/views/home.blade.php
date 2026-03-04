@extends('layouts.layout')

@section('title', 'Nail Studio - Главная')

@section('content')
    <section class="hero">
        <div class="container">
            <h2>Добро пожаловать в Nail Studio</h2>
            <p>Профессиональный уход за вашими ногтями</p>
            <a href="#services" class="btn">Наши услуги</a>
        </div>
    </section>

    <section id="services" class="services">
        <div class="container">
            <h2>Наши услуги</h2>
            <div class="services-grid">
                @foreach($services as $service)
                    <div class="service-item">
                        <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->name }}">
                        <h3>{{ $service->name }}</h3>
                        <p>{{ $service->description }}</p>
                        <div class="price">
                            @if($service->servicePrices->isNotEmpty())
                                {{ number_format($service->min_price, 0, '', ' ') }} - {{ number_format($service->max_price, 0, '', ' ') }} руб.
                            @else
                                {{ number_format($service->price, 0, '', ' ') }} руб.
                            @endif
                        </div>
                        <div class="duration">
                            <i class="far fa-clock"></i> {{ $service->duration }} мин.
                        </div>
                        <form action="{{ route('cart.add', $service->id) }}" method="POST">
                            @csrf
                            <input type="number" name="quantity" value="1" min="1" class="quantity-input" style="display: none;">
                            <button type="submit" class="add-to-cart">Добавить в корзину</button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="about">
        <div class="container">
            <h2>О нас</h2>
            <p>Nail Studio - это современный салон красоты, специализирующийся на ногтевом сервисе. Наши мастера - профессионалы с многолетним опытом работы, которые постоянно совершенствуют свои навыки и следят за последними тенденциями в индустрии красоты.</p>
        </div>
    </section>

    <section class="contact">
        <div class="container">
            <h2>Контакты</h2>
            <div class="contact-info">
                <div>
                    <i class="fas fa-map-marker-alt"></i>
                    <p>г. Москва, ул. Примерная, д. 1</p>
                </div>
                <div>
                    <i class="fas fa-phone"></i>
                    <p>+7 (999) 999-99-99</p>
                </div>
                <div>
                    <i class="fas fa-envelope"></i>
                    <p>info@nailstudio.ru</p>
                </div>
            </div>
        </div>
    </section>
@endsection
