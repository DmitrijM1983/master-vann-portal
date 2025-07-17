@extends('../layouts/base')
@section('title', 'Поиск мастера')

@section('header')
<!-- preloader -->
<div class="preloader">
    <span class="loader"> </span>
</div>
<!-- preloader end -->

<!-- Header -->
<header id="stickyHeader">
    <div class="container">
        <div class="top-header">
            <div class="logo">
                <a href="#">
                    <img alt="logo" src="assets/img/mvp-header-logo.png">
                </a>
            </div>

            <div class="top-bar">

                <div class="login">
                    <a href="javascript:history.back()">Назад</a>
                </div>

                @include('layouts.auth_user_menu')

            </div>
        </div>

    </div>
</header>
@endsection

@section('content')
<div class="centered-search-form">
    <div class="col-lg-5">
        <form role="form" action="{{ route('search') }}" class="get-a-quote" method="post">
            @csrf
            <div class="center-div">
                <h3>Поиск мастера</h3>
            </div>
            <div class="input">
                <div class="group-img">
                    <i>
                        <img src="assets/img/city.png" alt="Город" width="40" />
                    </i>
                    <input list="cities" name="city" id="city" placeholder="Выберите город(начните вводить...)">
                    <datalist id="cities">
                        @foreach($cities as $city)
                            <option value="{{ $city }}">{{ $city }}</option>
                        @endforeach
                    </datalist>
                </div>
            </div>

            <div class="input">
                <div class="group-img">
                    <i>
                        <img src="assets/img/service.png" alt="Услуга" width="40" />
                    </i>
                    <input list="services" name="service" id="service" placeholder="Выберите услугу">
                    <datalist id="services">
                        @foreach($services as $service)
                            <option value="{{ $service }}">{{ $service }}</option>
                        @endforeach
                    </datalist>
                </div>
            </div>

            <div class="login">
                <button style="width: 100%; margin-right: 15px; margin-bottom: 20px" type="submit" class="btn"><span>Найти</span></button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('messages_window')
    @include('../layouts.messages_window')
@endsection

@section('my-orders')
    @include('layouts.my-orders')
@endsection


