@extends('layouts.layout')

@section('title', 'Nail Studio - Главная')

@section('content')
    <section id="home" class="hero">
        <div class="container">
            <h2>Профессиональный уход за вашими ногтями</h2>
            <p>Мы создаем красоту и уход для ваших рук и ног</p>
            <a href="#services" class="btn">Наши услуги</a>
        </div>
    </section>

    <section id="services" class="services">
        <div class="container">
            <h2>Наши услуги</h2>
            <div class="services-grid">
                @foreach($services as $service)
                    <div class="service-item">
                        <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->name }}" />
                        <h3>{{ $service->name }}</h3>
                        <p>{{ $service->description }}</p>
                        <p class="price">{{ number_format($service->price, 0, '', ' ') }} руб.</p>
                        @guest
                            <a href="{{ route('login') }}" class="add-to-cart">Добавить в корзину</a>
                        @else
                            <form action="{{ route('cart.add', $service->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="add-to-cart">Добавить в корзину</button>
                            </form>
                        @endguest
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section id="about" class="about">
        <div class="container">
            <h2>О нашем салоне</h2>
            <p>
                NailStudio - это современный салон ногтевого сервиса, где работают
                только профессиональные мастера с многолетним опытом. Мы используем
                качественные материалы и стерильные инструменты, чтобы обеспечить
                безопасность и комфорт наших клиентов.
            </p>
            <p>
                Наша миссия - делать вас красивыми и уверенными в себе с помощью
                безупречного маникюра и педикюра.
            </p>
        </div>
    </section>

    <section id="contact" class="contact">
        <div class="container">
            <h2>Контакты</h2>
            <div class="contact-info">
                <div>
                    <i class="fas fa-map-marker-alt"></i>
                    <p>г. Уфа, ул. Красивых Ногтей, д. 77</p>
                </div>
                <div>
                    <i class="fas fa-phone"></i>
                    <p>+7 (987) 123-45-67</p>
                </div>
                <div>
                    <i class="fas fa-envelope"></i>
                    <p>info@nailstudio.ru</p>
                </div>
                <div>
                    <i class="fas fa-clock"></i>
                    <p>Ежедневно с 10:00 до 21:00</p>
                </div>
            </div>
        </div>
    </section>
@endsection
