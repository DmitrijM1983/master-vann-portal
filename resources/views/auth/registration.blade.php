@extends('../layouts/base')
@section('title', 'Регистрация')

@section('content')
<div class="centered-form">
    <div class="col-lg-5">
        @if (session('message'))
            <div class="get-a-quote">
                <h3>{{ session('message') }}</h3>
            </div>
        @else
            <form role="form" action="{{ route('register') }}" class="get-a-quote" method="post">
                @csrf
                <div class="center-div">
                    <h3>Регистрация</h3>
                </div>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="group-img">
                    <input type="text" name="name" placeholder="Ваше имя" required>
                </div>
                <div class="group-img">
                    <input type="email" name="email" placeholder="Ваша электронная почта" value="{{ old('email') }}" required>
                </div>
                <div class="group-img">
                    <input type="password" name="password" placeholder="Придумайте пароль" required>
                </div>
                <p>Вы:</p>
                <div class="d-flex align-items-center">
                    <div class="radio-button">
                        <input type="radio" id="master" name="role_id" value="1">
                        <label for="master">Исполнитель</label>
                    </div>
                    <div class="radio-button">
                        <input type="radio" id="client" name="role_id" value="2">
                        <label for="client">Заказчик</label>
                    </div>
                </div>
                <div class="login">
                    <button type="submit" class="btn"><span>Зарегистрироваться</span></button>
                    <a class="hover-link" href="{{ route('login') }}">Уже зарегистрированы? Войти</a>
                </div>
                <div class="back-reg">
                    <a class="hover-link" href="{{ route('index') }}">Назад</a>
                </div>
            </form>
        @endif
    </div>
</div>
@endsection
