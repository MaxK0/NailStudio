<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Nail Studio - Салон ногтевого сервиса')</title>
    <link rel="stylesheet" href="{{ asset('styles/style.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    @stack('styles')
</head>
<body>
<div id="site">
    <header>
        <div class="container">
            <div class="logo">
                <img src="{{ asset('img/kkk_log.png') }}" width="45" alt="" />
                <h1>Nail<span>Studio</span></h1>
            </div>
            <div class="burger-menu">
                <div class="burger-icon" id="burger-icon">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
                <nav class="mobile-nav" id="mobile-nav">
                    <ul>
                        <li><a href="{{ url('/') }}">Главная</a></li>
                        <li><a href="/#services">Услуги</a></li>
                        <li><a href="/#about">О нас</a></li>
                        <li><a href="/#contact">Контакты</a></li>
                    </ul>
                </nav>
            </div>
            <nav class="desktop-nav">
                <ul>
                    <li><a href="{{ url('/') }}">Главная</a></li>
                    <li><a href="/#services">Услуги</a></li>
                    <li><a href="/#about">О нас</a></li>
                    <li><a href="/#contact">Контакты</a></li>
                </ul>
            </nav>
        </div>
    </header>

    @yield('content')

    <footer>
        <div class="container">
            <p>&copy; 2025 NailStudio. Все права защищены.</p>
            <div class="social">
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-vk"></i></a>
                <a href="#"><i class="fab fa-whatsapp"></i></a>
            </div>
        </div>
    </footer>
</div>
<script src="{{ asset('js/script.js') }}"></script>
@stack('scripts')
</body>
</html>
