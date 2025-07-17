@extends('../layouts/base')
@section('title', 'Результаты поиска')

@section('header')
<!-- preloader -->
<div class="preloader">
    <span class="loader"></span>
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
                    <a href="{{ route('index') }}">Вернуться на главную</a>
                </div>

                @include('layouts.auth_user_menu')

            </div>
        </div>

    </div>
</header>
@endsection

@section('content')
<!-- Masters List -->
<div class="container">
    @if ($masters->isNotEmpty())
        @foreach($masters as $master)
            <div class="card-master-name">
                <h4><b>{{$master->name . ' ' . ' ' .  $master->middle_name . ' ' . $master->last_name }}</b></h4>
            </div>
            <div class="card-master">
                @if (!empty($master->mastersInfo->master_photo))
                    <div>
                        <img class="img" src="{{ $master->mastersInfo->getPhotoUrl() }}" alt="3">
                    </div>
                @else
                <div>
                    <img class="img" src="masters/img/master.jpg" alt="3">
                </div>
                @endif
                <div>
                    <h4>Мастер:</h4>
                    <p> {{$master->name . ' ' . ' ' .  $master->middle_name . ' ' . $master->last_name }}</p>
                </div>
                <div>
                    <h4 style="margin-left: 40px">Опыт:</h4>
                    <p style="margin-left: 40px">с {{ $master->mastersInfo->experience }} года</p>
                </div>
                @if (!empty($master->mastersInfo->rating))
                <div>
                    <h4>Рейтинг мастера:</h4>
                    <p>{{ $master->mastersInfo->rating }} из 5</p>
                </div>
                @endif
            </div>
            <div class="card-master-btn">
                <a href="{{ route('master_card', $master->id) }}" class="btn"><span>Карточка мастера</span></a>
            </div>
        @endforeach
    @else
        <div class="centered-search-form">
            <div class="container">
                <h2 style="text-align: center">Мы не нашли мастера в вашем городе.</h2>
            </div>
        </div>
    @endif
</div>
<div class="search-result-footer">

</div>
@endsection

@section('messages_window')
    @include('../layouts.messages_window')
@endsection

@section('my-orders')
    @include('layouts.my-orders')
@endsection


