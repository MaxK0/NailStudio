@extends('layouts.auth')

@section('title', 'Nail Studio - Вход')

@section('content')
    <section id="auth" class="auth">
        <div class="container">
            <div class="auth-container">
                <div class="auth-form">
                    <h2>Вход в личный кабинет</h2>

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input id="email" type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password">Пароль</label>
                            <input id="password" type="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   name="password" required autocomplete="current-password">
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                            @enderror
                        </div>

                        <div class="form-group remember">
                            <label>
                                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                Запомнить меня
                            </label>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn">Войти</button>
                        </div>

                        <div class="form-group links">
                            <p>Нет аккаунта?
                                <a href="{{ route('register') }}" class="register-link">
                                    Зарегистрироваться
                                </a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
