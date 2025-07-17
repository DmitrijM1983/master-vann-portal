@extends('../layouts/base')
@section('title', 'Карточка мастера')

@section('header')
<!-- preloader -->
<div class="preloader">
    <span class="loader"></span>
</div>
<!-- preloader end -->

<!-- Header -->
    <div class="container">
        <div class="top-header">
            <div class="logo">
                <a href="#">
                    <img alt="logo" src="../assets/img/mvp-header-logo.png">
                </a>
            </div>
            <div class="profile-user">
                <h3 style="margin-left: 20px">{{ $user->name }}</h3>
                @if (!empty($user->mastersInfo->master_photo))
                    <img class="avatar" src="{{ $user->mastersInfo->getPhotoUrl() }}" alt="No avatar">
                @else
                    <img class="avatar" src="../masters/img/master.jpg" alt="No avatar">
                @endif
            </div>
        </div>
    </div>
<hr style="margin: 0">
@endsection

@section('content')
<div class="main-content">
    <div class="content-area">
        @if (session('success'))
            <div class="alert alert-success" style="font-size: 24px; text-align: center">
                {{ session('success') }}
            </div>
        @elseif (session('error'))
            <div class="alert alert-danger" style="font-size: 24px; text-align: center">
                {{ session('error') }}
            </div>
        @endif

            @if (!empty($reports))
                @php
                $sumPrice = 0;
                $sumMatPrice = 0;
                $sumTransPrice = 0;
                $sumOtherPrice = 0;
                foreach ($reports as $report) {
                    $sumPrice += $report->price;
                    $sumMatPrice += $report->materials_price;
                    $sumTransPrice += $report->transports_price;
                    $sumOtherPrice += $report->other_price;
            }
                @endphp

                <div>
                    <h4 style="text-align: center">Таблица учета отработанных заявок</h4>
                </div>
                <table class="report-table">
                    <thead>
                    <tr>
                        <th>Дата оказания услуги</th>
                        <th>Услуга</th>
                        <th>Общий доход</th>
                        <th>Расходы на материал</th>
                        <th>Транспортные расходы</th>
                        <th>Прочие расходы(реклама, налоги и пр.)</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($reports as $report)
                        <tr>
                            <td>{{ date('d.m.Y', strtotime($report->date))}}</td>
                            <td>@if ($report->service == 'enamel')
                                    Эмаль
                                @elseif ($report->service == 'acrylic')
                                    Акрил
                                @elseif ($report->service == 'liner')
                                    Вкладыш
                                @endif</td>
                            <td>{{ $report->price }}</td>
                            <td>{{ $report->materials_price }}</td>
                            <td>{{ $report->transports_price }}</td>
                            <td>{{ $report->other_price }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <th>Итого</th>
                        <th>{{ count($reports) }}</th>
                        <th>{{ $sumPrice }}</th>
                        <th>{{ $sumMatPrice }}</th>
                        <th>{{ $sumTransPrice }}</th>
                        <th>{{ $sumOtherPrice }}</th>
                    </tr>
                    </tfoot>
                </table>

                @php
                    // Инициализация переменных для статистики
                    $totalServices = 0;
                    $byService = [
                        'acrylic' => ['count' => 0, 'total_price' => 0, 'net_profit' => 0],
                        'enamel' => ['count' => 0, 'total_price' => 0, 'net_profit' => 0],
                        'liner' => ['count' => 0, 'total_price' => 0, 'net_profit' => 0],
                    ];
                    $overallTotal = 0;
                    $overallNetProfit = 0;

                    // Обработка каждого отчета
                    foreach ($reports as $report) {
                        $totalServices++;

                        // Добавление данных по каждой услуге
                        if (isset($byService[$report->service])) {
                            $byService[$report->service]['count']++;
                            $byService[$report->service]['total_price'] += $report->price;
                            $netProfit = $report->price - ($report->materials_price + $report->transports_price + $report->other_price);
                            $byService[$report->service]['net_profit'] += $netProfit;
                        }
                    }

                    // Подсчет общего дохода и чистого дохода
                    foreach ($byService as $serviceStats) {
                        $overallTotal += $serviceStats['total_price'];
                        $overallNetProfit += $serviceStats['net_profit'];
                    }
                @endphp

                <div>
                    <h4 style="text-align: center">Сводная таблица итогов работы</h4>
                </div>
                <table class="report-table">
                    <thead>
                    <tr>
                        <th>Услуга</th>
                        <th>Количество заказов</th>
                        <th>Общий доход</th>
                        <th>Чистый доход</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($byService as $service => $stats)
                        <tr>
                            <td>
                                @if ($service == 'enamel')
                                    Эмаль
                                @elseif ($service == 'acrylic')
                                    Акрил
                                @elseif ($service == 'liner')
                                    Вкладыш
                                @endif
                            </td>
                            <td>{{ $stats['count'] }}</td>
                            <td>{{ number_format($stats['total_price'], 2) }}</td>
                            <td>{{ number_format($stats['net_profit'], 2) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <th>Итого</th>
                        <th>{{ $totalServices }}</th>
                        <th>{{ number_format($overallTotal, 2) }}</th>
                        <th>{{ number_format($overallNetProfit, 2) }}</th>
                    </tr>
                    </tfoot>
                </table>

                <table class="rep-table">
                    <thead>
                    <tr>
                        <th>Чистый доход: {{ $sumPrice-$sumMatPrice-$sumTransPrice-$sumOtherPrice }} рублей</th>
                        <th> Затрачено: {{ $sumMatPrice+$sumTransPrice+$sumOtherPrice }} рублей</th>
                    </tr>
                    </thead>
                </table>

            @else
        <div>
            <h3>Заполняйте отчеты об оказзании услуг. Здесь будет отражена ваша статистика.</h3>
        </div>
        @endif
    </div>
    <div class="sidebar">
        <ul>
            <li><a class="hover-link" href="#profile" onclick="openProfileWindow()">Личные данные</a></li>
            <li><a class="hover-link" href="#settings" onclick="openProfileMasterWindow()">Мой профиль</a></li>
            <li>

                <a class="hover-link" href="#messages" onclick="openMessagesWindow()">
                    @if($noReadMessagesCount > 0)
                        <span class="mark-no-read"></span>
                    @endif
                Мои сообщения
                <span class="message-count">{{ count($messages) }}</span>
            </a></li>
            <a style="margin-left: 20px; font-size: 17px !important;" class="hover-link" href="#settings" onclick="openNoReadMessagesWindow()">
                Непрочитанные
                <span
                    @if ($noReadMessagesCount > 0)
                    style="font-size: 17px !important;color: red;"
                    @else
                    style="font-size: 17px !important;"
                    @endif
                    class="message-count">
                    {{ $noReadMessagesCount }}
                </span>
            </a>
            <li><a class="hover-link" href="#services" onclick="openServicesWindow()">Мои услуги</a></li>
            <li><a class="hover-link" href="#sities" onclick="openCitiesWindow()">Мои города</a></li>
            <li><a class="hover-link" href="#job_images" onclick="openJobImagesWindow()">Фотографии работ</a></li>
            <li><a class="hover-link" href="#orders" onclick="openMyOrdersWindow()">Мои заказы</a></li>
            <li><a class="hover-link" href="#feedbacks" onclick="openFeedbacksWindow()">
                    @if($noReadFeedbacks > 0)
                        <span class="mark-no-read"></span>
                    @endif
                    Мои отзывы
                        <span class="message-count">{{ count($feedbacks) }}</span>
                </a></li>
            <li><a class="hover-link" href="#support" onclick="openUserSupportsWindow()">
                    Мои обращения
                    <span class="message-count">{{ count($supports) }}</span>
                </a></li>
            <li><a class="hover-link" href="#report" onclick="openReportWindow()">Заполнить отчёт</a></li>
            <li><a class="hover-link" href="{{ route('logout') }}">Выйти</a></li>
        </ul>
    </div>
</div>

<!-- Модальное окно личные данные -->
<div id="profile-window" class="profile">
    <div class="profile-content">
        <span class="profile-close" onclick="closeProfileWindow()">&times;</span>
        <form id="profileForm" role="form" action="{{ route('profile_update', ['id' => $user->id]) }}" class="get-a-quote" method="post">
            @csrf
            <div class="center-div">
                <h3>Редактировать</h3>
            </div>
            <div id="error-container">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
            <div class="group-img">
                <input type="text" name="name" placeholder="Имя" value="{{ old('name', $user->name) }}">
            </div>
            <div class="group-img">
                <input type="text" name="middle_name" placeholder="Отчество" value="{{ old('middle_name', $user->middle_name) }}">
            </div>
            <div class="group-img">
                <input type="text" name="last_name" placeholder="Фамилия" value="{{ old('last_name', $user->last_name) }}">
            </div>
            <div class="group-img">
                <input type="text" name="phone" placeholder="Телефон" value="{{ old('phone', $user->phone) }}">
            </div>
            <div class="form-btn">
                <button type="submit" class="btn"><span>Сохранить изменения</span></button>
            </div>
        </form>
    </div>
</div>

<!-- Модальное окно профиль мастера -->
<div id="profile-master-window" class="profile">
    <div class="profile-master-content">
        <span class="profile-close" onclick="closeProfileMasterWindow()">&times;</span>
        <div class="center-div" style="margin-top: 20px">
            <h3>Редактировать профиль</h3>
        </div>
        <form id="masterForm" role="form" action="{{ route('master_info_update', ['id' => $user->id]) }}" class="get-a-quote" style="margin-top: 0" method="post" enctype="multipart/form-data">
            @csrf
            <div id="error-container">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
            <div class="group-img">
                <h5 style="margin-bottom: 10px">Фото мастера</h5>
                <label for="photo" class="file-upload">
                    <input type="file" name="master_photo" id="photo" accept="image/*" class="form-control" style="display: none;" onchange="dispFileName()">
                    Установить фото
                    <span id="file-name">Файл не выбран</span>
                </label>
            </div>
            @if (!empty($user->mastersInfo->master_photo))
                <h5 style="margin-bottom: 5px">Текущее фото</h5>
                <img loading="lazy" class="img" style="height: 100%; margin-bottom: 20px" src="{{ $user->mastersInfo->getPhotoUrl() }}" alt="Мастер">
            @endif
            <div class="group-img">
                <label for="experience" class="experience-label">Опыт работы(год начала оказания услуги)</label>
                <input style="font-size: 20px" type="text" name="experience" placeholder="Введите год с каторого занимаетесь реставрацией ванн" @if(!empty($user->mastersInfo->experience)) value="{{ $user->mastersInfo->experience }}"@endif>
            </div>
            <!--Гарантия -->
            <div class="group-img">
                <label for="experience" class="experience-label">Гарантия на работу</label>
                <div class="all-check-box">
                    <div class="check-box">
                        <input class="form-check-input" name="guarantee" type="radio" id="noGuarantee" value="0" @if(!empty($user->mastersInfo->guarantee) && $user->mastersInfo->guarantee === '0') checked @endif>
                        <label class="form-check-label" for="noGuarantee">Без гарантии</label>
                    </div>
                    <div class="check-box">
                        <input class="form-check-input" name="guarantee" type="radio" id="threeMonths" value="3 месяца" @if(!empty($user->mastersInfo->guarantee) && $user->mastersInfo->guarantee === '3 месяца') checked @endif>
                        <label class="form-check-label" for="threeMonths">3 месяца</label>
                    </div>
                    <div class="check-box">
                        <input class="form-check-input" name="guarantee" type="radio" id="sixMonths" value="6 месяцев" @if(!empty($user->mastersInfo->guarantee) && $user->mastersInfo->guarantee === '6 месяцев') checked @endif>
                        <label class="form-check-label" for="sixMonths">6 месяцев</label>
                    </div>
                    <div class="check-box">
                        <input class="form-check-input" name="guarantee" type="radio" id="oneYear" value="1 год" @if(!empty($user->mastersInfo->guarantee) && $user->mastersInfo->guarantee === '1 год') checked @endif>
                        <label class="form-check-label" for="oneYear">1 год</label>
                    </div>
                    <div class="check-box">
                        <input class="form-check-input" name="guarantee" type="radio" id="twoYears" value="2 года" @if(!empty($user->mastersInfo->guarantee) && $user->mastersInfo->guarantee === '2 года') checked @endif>
                        <label class="form-check-label" for="twoYears">2 года</label>
                    </div>
                    <div class="check-box">
                        <input class="form-check-input" name="guarantee" type="radio" id="threeYears" value="3 года" @if(!empty($user->mastersInfo->guarantee) && $user->mastersInfo->guarantee === '3 года') checked @endif>
                        <label class="form-check-label" for="threeYears">3 года</label>
                    </div>
                    <div class="check-box">
                        <input class="form-check-input" name="guarantee" type="radio" id="fourYears" value="4 года" @if(!empty($user->mastersInfo->guarantee) && $user->mastersInfo->guarantee === '4 года') checked @endif>
                        <label class="form-check-label" for="fourYears">4 года</label>
                    </div>
                    <div class="check-box">
                        <input class="form-check-input" name="guarantee" type="radio" id="upToFiveYears" value="до 5 лет" @if(!empty($user->mastersInfo->guarantee) && $user->mastersInfo->guarantee === 'до 5 лет') checked @endif>
                        <label class="form-check-label" for="upToFiveYears">до 5 лет</label>
                    </div>
                </div>
            </div>
            <div class="group-img" style="margin-top: 20px; margin-bottom: 20px">
                <label for="description" class="experience-label">О себе</label>
                <textarea name="description">@if(!empty($user->mastersInfo->description)){{ $user->mastersInfo->description }}@endif</textarea>
            </div>
            <div class="master-form-btn">
                <button type="submit" class="btn"><span>Сохранить изменения</span></button>
            </div>
        </form>
    </div>
</div>

<!-- Модальное окно города -->
<div id="cities-window" class="profile">
    <div class="cities-content-up">
        <span class="cities-close" onclick="closeCitiesWindow()">&times;</span>
        <form style="margin-top: 40px" id="profileForm" role="form" action="{{ route('set_city', ['id' => $user->id]) }}" class="city-get-a-quote" method="post" onsubmit="return validateForm()">
            @csrf
            <div class="center-div">
                <h3>Выбрать город</h3>
            </div>
            <div class="city-input">
                <div class="city-group-img">
                    <i>
                        <img src="../assets/img/city.png" alt="Город" width="40" />
                    </i>
                    <input list="cities" name="city" id="city" placeholder="Выберите город(начните вводить...)">
                    <datalist id="cities">
                        @foreach($cities as $city)
                            <option value="{{ $city }}">{{ $city }}</option>
                        @endforeach
                    </datalist>
                </div>
            </div>
            <div class="form-btn">
                <button style="width: 100%" type="submit" class="btn"><span>Добавить</span></button>
            </div>
        </form>

        @if(!$cityUser->isEmpty())
        <form style="margin-bottom: 40px" action="{{ route('destroy_city', ['id' => $user->id]) }}" method="post" class="city-down-get-a-quote">
                <div class="center-div">
                    <div class="city_user">
                        <h3>Города оказания услуг:</h3>
                        <ul>
                            @foreach($cityUser as $city)
                                <li style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                                    <span>- {{ $city->city->name }}</span>
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="city" value="{{ $city->city->name }}">
                                        <button type="submit" class="btn btn-danger" style="background-color: red; color: white; border: none; cursor: pointer; padding: 5px 10px; margin-left: 10px;">Удалить</button>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
        @else
        <form style="margin-bottom: 40px" action="" class="city-down-get-a-quote">
            <div style="padding-bottom: 0" class="center-div">
                <div class="city_user">
                    <h3>Город не выбран</h3>
                </div>
            </div>
        </form>
        @endif
        </form>
    </div>
</div>

<!-- Модальное окно услуги -->
<div id="services-window" class="profile">
    <div class="cities-content-up">
        <span class="cities-close" onclick="closeServicesWindow()">&times;</span>
        <form style="" id="serviceForm" style="margin: 40px" role="form" action="{{ route('services', ['id' => $user->id]) }}" class="get-a-quote-price" method="post">
            @csrf
            <div class="center-div">
                <h3>Выбрать услуги</h3>
            </div>
            <div id="services-error-container">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
            <div class="services-title">
                <h6>Наименоване услуги</h6>
                <h6>Начальная цена, руб.</h6>
            </div>
            <div class="checkboxes">
                <div class="services-labels">
                    <div style="margin-left: 0" class="check-box">
                        <input class="form-check-input" name="enamel" type="checkbox" id="enamel"
                               @if($servicesUser->contains('service_id', 1)) checked @endif>
                        <label style="font-weight: bold; font-size: 18px" class="form-check-label" for="enamel">Эмалировка</label>
                    </div>
                    <input class="input-price" name="price-enamel" @if($servicesUser->contains('service_id', 1)) value="{{ $servicesUser->firstWhere('service_id', 1)->price }}" @endif>
                </div>
                <div class="services-labels">
                    <div style="margin-left: 0" class="check-box">
                        <input class="form-check-input" name="acrylic" type="checkbox" id="acrylic"
                               @if($servicesUser->contains('service_id', 2)) checked @endif>
                        <label style="font-weight: bold; font-size: 18px" class="form-check-label" for="acrylic">Наливной акрил</label>
                    </div>
                    <input class="input-price" name="price-acrylic" @if($servicesUser->contains('service_id', 2)) value="{{ $servicesUser->firstWhere('service_id', 2)->price }}" @endif>
                </div>
                <div class="services-labels">
                    <div style="margin-left: 0" class="check-box">
                        <input class="form-check-input" name="liner" type="checkbox" id="liner"
                               @if($servicesUser->contains('service_id', 3)) checked @endif>
                        <label style="font-weight: bold; font-size: 18px" class="form-check-label" for="liner">Акриловый вкладыш</label>
                    </div>
                    <input class="input-price" name="price-liner" @if($servicesUser->contains('service_id', 3)) value="{{ $servicesUser->firstWhere('service_id', 3)->price }}" @endif>
                </div>
            </div>

            <div class="form-btn">
                <button id="services-save-btn" style="width: 100%" type="submit" class="btn"><span>Сохранить</span></button>
            </div>
        </form>
    </div>
</div>

<!-- Модальное окно Все сообщения -->
<div id="messages-window" class="profile">
    <div class="profile-master-content">
        <span class="profile-close" onclick="closeMessagesWindow()">&times;</span>

        @if ( count($messages) > 0)
            <div class="center-div" style="padding-bottom: 20px">
                <h3 style="margin-top: 20px">Сообщения</h3>
            </div>
            <form id="messageForm" action="{{ 'mark_messages_as_read' }}" style="margin: 0">
            @foreach ($messages as $message)
                    @csrf
                    <ul id="messageList">
                        <li data-id="{{ $message->id }}" class="message-item"></li>
                    </ul>
            </form>

                <form style="margin-top: 0 !important;" id="masterForm" role="form" action="{{ route('answer') }}" class="get-a-quote-message" method="post" enctype="multipart/form-data">
                    @csrf

                        <div class="message-cont">
                            <div class="message-content" style="margin-top: 20px; margin-bottom: 20px">
                                <h3 style="margin-bottom: 20px">Отправитель: {{ $message->userFrom->name . ' ' . $message->userFrom->last_name}}</h3>
                                <textarea>@if(!empty($message->content)){{ $message->content }}@endif</textarea>
                            </div>

                            @if (!empty($message->images))
                                @php
                                    $images = json_decode($message->images);
                                @endphp
                                <div class="message-image">
                                    @foreach($images as $image)
                                        <img src="{{ asset('storage/' . $image) }}" alt="Не загружено" class="clickable-image" style="cursor: pointer;" onclick="openInNewWindow('{{ asset('storage/' . $image) }}');">
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <div class="answer-content">

                            <input type="hidden" name="user_to" value="{{ $message->user_to }}">
                            <input type="hidden" name="message_id" value="{{ $message->id }}">

                            @if (empty($message->answer->id))
                                <div class="message-content" style="margin-top: 20px; margin-bottom: 20px">
                                    <h3 style="margin-bottom: 20px">Ответить</h3>
                                    <textarea name="content"></textarea>
                                </div>
                                <div class="group-img">
                                    <label for="files" class="file-upload">
                                        <input type="file" name="images[]" id="files" accept="image/*" class="form-control" style="display: none;" multiple onchange="displFileName()">
                                        Добавить фото
                                        <span id="files-name">Файл не выбран</span>
                                    </label>
                                </div>
                                <div class="master-form-btn">
                                    <button type="submit" class="btn"><span>Отправить</span></button>
                                </div>
                            @else
                                <div class="message-content" style="margin-top: 20px; margin-bottom: 20px">
                                    <h3 style="margin-bottom: 20px">Ваш ответ:</h3>
                                    <textarea name="content">{{ $message->answer->content }}</textarea>
                                </div>
                            @endif

                            @if (!empty($message->answer->images))
                                @php
                                    $images = json_decode($message->answer->images);
                                @endphp
                                <div class="message-image">
                                    @foreach($images as $image)
                                        <img src="{{ asset('storage/' . $image) }}" alt="Не загружено" class="clickable-image" style="cursor: pointer;" onclick="openInNewWindow('{{ asset('storage/' . $image) }}');">
                                    @endforeach
                                </div>
                            @endif
                        </div>
                </form>
            @endforeach
        @else
            <div class="center-div">
                <h3>Сообщений нет</h3>
            </div>
        @endif
    </div>
    <div id="fullscreenModal" class="fullscreen-modal" onclick="closeFullscreen()">
        <button class="close-button" onclick="closeFullscreen();">✖</button>
        <img id="fullscreenImage" class="fullscreen-image">
    </div>
</div>

<!-- Модальное окно Непрочитаные сообщения -->
<div id="no-read-messages-window" class="profile">
    <div class="profile-master-content">
        <span class="profile-close" onclick="closeNoReadMessagesWindow()">&times;</span>

        @if ( count($noReadMessages) > 0)
            <div class="center-div">
                <h3 style="margin-top: 20px">Сообщения</h3>
            </div>

            <form id="messageForm" action="{{ 'mark_as_read' }}">
            @foreach ($noReadMessages as $message)
                @csrf
                <ul id="messageList">
                    <li data-id="{{ $message->id }}" class="message-item"></li>
                </ul>
            </form>
                <form style="margin-top: 0 !important;" id="masterForm" role="form" action="{{ route('answer') }}" class="get-a-quote-message" method="post" enctype="multipart/form-data">
                    @csrf

                    <div class="message-cont">
                        <div class="message-content" style="margin-top: 20px; margin-bottom: 20px">
                            <h3 style="margin-bottom: 20px">Отправитель: {{ $message->userFrom->name . ' ' . $message->userFrom->last_name}}</h3>
                            <textarea>@if(!empty($message->content)){{ $message->content }}@endif</textarea>
                        </div>

                        @if (!empty($message->images))
                            @php
                                $images = json_decode($message->images);
                            @endphp
                            <div class="message-image">
                                @foreach($images as $image)
                                    <img src="{{ asset('storage/' . $image) }}" alt="Не загружено" class="clickable-image" style="cursor: pointer;" onclick="openInNewWindow('{{ asset('storage/' . $image) }}');">
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="answer-content">

                        <input type="hidden" name="user_to" value="{{ $message->user_to }}">
                        <input type="hidden" name="message_id" value="{{ $message->id }}">

                        @if (empty($message->answer->id))
                            <div class="message-content" style="margin-top: 20px; margin-bottom: 20px">
                                <h3 style="margin-bottom: 20px">Ответить</h3>
                                <textarea name="content"></textarea>
                            </div>
                            <div class="group-img">
                                <label for="files-no-read" class="file-upload">
                                    <input type="file" name="images[]" id="files-no-read" accept="image/*" class="form-control" style="display: none;" multiple onchange="disFileName()">
                                    Добавить фото
                                    <span id="file-no-read">Файл не выбран</span>
                                </label>
                            </div>
                            <div class="master-form-btn">
                                <button type="submit" class="btn"><span>Отправить</span></button>
                            </div>
                        @else
                            <div class="message-content" style="margin-top: 20px; margin-bottom: 20px">
                                <h3 style="margin-bottom: 20px">Ваш ответ:</h3>
                                <textarea name="content">{{ $message->answer->content }}</textarea>
                            </div>
                        @endif

                        @if (!empty($message->answer->images))
                            @php
                                $images = json_decode($message->answer->images);
                            @endphp
                            <div class="message-image">
                                @foreach($images as $image)
                                    <img src="{{ asset('storage/' . $image) }}" alt="Не загружено" class="clickable-image" style="cursor: pointer;" onclick="openInNewWindow('{{ asset('storage/' . $image) }}');">
                                @endforeach
                            </div>
                        @endif
                    </div>
                </form>
            @endforeach
        @else
            <div class="center-div" style="height: 100px">
                <h3>Непрочитанных сообщений нет</h3>
            </div>
        @endif

    </div>
    <div id="fullscreenModal" class="fullscreen-modal" onclick="closeFullscreen()">
        <button class="close-button" onclick="closeFullscreen();">✖</button>
        <img id="fullscreenImage" class="fullscreen-image">
    </div>
</div>

<!-- Модальное окно фото работ -->
<div id="job-images-window" class="profile">
    <div class="profile-master-content">
        <span class="profile-close" onclick="closeJobImagesWindow()">&times;</span>
            <div class="get-a-quote" style=" margin-left: 40px; margin-right: 40px">
                <div class="center-div">
                    <h3>Фотографии работ</h3>
                </div>
                <div id="error-container">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            @if (!empty($jobImages))
                @foreach($jobImages as $image)
                    <div class="my-images">
                        <div class="image-info">
                            <div class="img-title">
                                    @if (!empty($image->title))
                                    <label for="img-title">Название</label>
                                    <p style="padding: 0">{{ $image->title }}</p>
                                @else
                                    <p style="padding: 0">Добавьте название</p>
                                @endif
                            </div>
                            <div class="img-description">
                                @if (!empty($image->description))
                                    <label for="img-description">Описание</label>
                                    <p style="padding: 0">{{ $image->description }}</p>
                                @else
                                    <p style="padding: 0">Добавьте описание</p>
                                @endif
                            </div>

                        </div>
                        <div class="img-upload">
                            <img class="job-img" loading="lazy" src="{{ asset('storage/' . $image->image) }}" alt="Фото">
                        </div>
                    </div>
                    <div class="buttons">
                        <a href="#update_job_image"
                           onclick="openUpdateJobImagesWindow('{{ $image->id }}', '{{ $image->title }}', '{{ $image->description }}', '{{ $image->image }}')"
                           type="button" class="btn btn-info" style="display: flex; justify-content: center; align-items: center !important; width: 100px; height: 26px; border-radius: 20px; background-color: blue; color: white; border: none; cursor: pointer; padding: 5px 10px; margin-left: 10px;">
                            <p style="color: white">Изменить</p>
                        </a>
                        <form action="{{ route('delete_job_image', ['id' => $user->id, 'image_id' => $image->id]) }}" method="post">
                            @method('delete')
                            @csrf
                            <button type="submit" class="btn btn-danger" style="width: 90px; height: 26px; border-radius: 20px; background-color: red; color: white; border: none; cursor: pointer; padding: 5px 10px; margin-left: 10px;">Удалить</button>
                        </form>

                    </div>
                @endforeach
            @endif

            <form id="imagesForm" role="form" action="{{ route('job_images', ['id' => $user->id]) }}" method="post" enctype="multipart/form-data">
                @csrf
                <h4 style="text-align: center; margin-top: 40px">Добавить фотографию</h4>
                <div class="job-images">
                    <div>
                        <label class="image-title" for="title">Название</label>
                        <input id="title" name="title" type="text">
                    </div>
                    <div>
                        <label class="image-title" for="description">Описание</label>
                        <textarea id="description" name="description" type="text" class="image-description"></textarea>
                    </div>
                    <div class="group-img" style="margin-top: 40px">
                        <label for="image" class="file-upload">
                            <input type="file" name="image" id="image" accept="image/*" class="form-control" style="display: none;" onchange="dispImagesFileName()">
                            Выбрать
                            <span id="image-file-name">Файл не выбран</span>
                        </label>
                    </div>
                </div>
                <div class="master-form-btn">
                    <button type="submit" class="btn"><span>Сохранить</span></button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Модальное окно редактрирование фото работ -->
<div id="update-job-images-window" class="profile">
    <div class="profile-master-content">
        <span class="profile-close" onclick="closeUpdateJobImagesWindow()">&times;</span>
        <div id="error-container">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
        <form id="imagesForm" role="form" action="{{ route('edit_job_image', ['id' => $user->id]) }}" method="post" enctype="multipart/form-data" class="get-a-quote">
            @csrf
            @method('put')
            <h4 style="text-align: center; margin-top: 40px">Изменить фотографию</h4>
            <input type="hidden" id="update_image_id" name="image_id">
            <div class="job-images">
                <div>
                    <label class="image-title" for="title">Название</label>
                    <input id="update-title" name="title" type="text">
                </div>
                <div>
                    <label class="image-title" for="description">Описание</label>
                    <textarea id="update-description" name="description" type="text" class="image-description">
                    </textarea>
                </div>
                <div class="group-img" style="margin-top: 40px">
                    <label for="update-image" class="file-upload">
                        <input type="file" name="image" id="update-image" accept="image/*" class="form-control" style="display: none;" onchange="displayUpdateImageFileName()">
                        Выбрать
                        <span id="update-image-file-name">Файл не выбран</span>
                    </label>

                    <h5 style="margin-top: 20px; margin-bottom: 5px; text-align: start">Текущее фото</h5>
                    <img id="current-image" loading="lazy" class="img" style="height: 100%;" src="" alt="Текущее изображение">

                </div>
            </div>
            <div class="master-form-btn">
                <button type="submit" class="btn"><span>Сохранить</span></button>
            </div>
        </form>
    </div>
</div>

<!-- Модальное окно Мои заказы -->
<div id="my-orders-window" class="profile">
    <div class="profile-master-content" style="padding-left: 40px; padding-right: 40px">
        <span class="profile-close" onclick="closeMyOrdersWindow()">&times;</span>

        @if (count($orders) > 0)
            <div class="get-a-quote" style="width: 100%">
                <div class="center-div" style="padding-bottom: 20px">
                    <h3 style="margin-top: 20px">Заказы</h3>
                </div>
                @foreach ($orders as $order)
                <div class="my-orders">
                    <div>
                        <div class="img-title">
                            <label for="img-title">Имя клиента</label>
                            <p style="padding: 0">{{ $order->name }}</p>
                        </div>
                        <div class="img-description">
                            <label for="img-description">Телефон</label>
                            <p style="padding: 0">{{ $order->phone }}</p>
                        </div>
                    </div>
                    <div>
                        <label for="img-description">Сообщение</label>
                        <p style="padding: 0">{{ $order->content }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="center-div">
                <h3 style="padding-top: 20px">Заказов нет</h3>
            </div>
        @endif
    </div>
</div>

<!-- Модальное окно Мои отзывы -->
<div id="feedback-window" class="profile">
    <div class="profile-master-content">
        <span class="profile-close" onclick="closeFeedbacksWindow()">&times;</span>

        @if ( count($feedbacks) > 0)
            <div class="center-div" style="padding-bottom: 20px">
                <h3 style="margin-top: 20px">Отзывы</h3>
            </div>
            <form id="feedbackForm" action="{{ 'mark_feedbacks_as_read' }}" style="margin: 0">
                @foreach ($feedbacks as $feedback)
                    @csrf
                    <ul id="feedbackList">
                        <li data-id="{{ $feedback->id }}" class="feedback-item"></li>
                    </ul>
            </form>

            <form style="margin-top: 0 !important;" id="masterForm" role="form" action="{{ route('answer_feedback') }}" class="get-a-quote-message" method="post" enctype="multipart/form-data">
                @csrf

                <input type="hidden" name="id" value="{{ $feedback->id }}">

                <div class="message-cont">
                    <div class="message-content" style="margin-top: 20px; margin-bottom: 20px">
                        <h3 style="margin-bottom: 20px">Автор: {{ $feedback->user->name . ' ' . $feedback->user->last_name}}</h3>
                        <textarea>@if(!empty($feedback->content)){{ $feedback->content }}@endif</textarea>
                    </div>

                    @if (!empty($feedback->images))
                        @php
                            $images = json_decode($feedback->images);
                        @endphp
                        <div class="message-image">
                            @foreach($images as $image)
                                <img src="{{ asset('storage/' . $image) }}" onclick="openInNewWindow('{{ asset('storage/' . $image) }}')" alt="Не загружено" class="clickable-image" style="cursor: pointer;">
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="answer-content">
                    @if (empty($feedback->answer))
                        <div class="message-content" style="margin-top: 20px; margin-bottom: 20px">
                            <h3 style="margin-bottom: 20px">Ответить</h3>
                            <textarea name="answer"></textarea>
                        </div>
                        <div class="master-form-btn">
                            <button type="submit" class="btn"><span>Отправить</span></button>
                        </div>
                    @else
                        <div class="message-content" style="margin-top: 20px; margin-bottom: 20px">
                            <h3 style="margin-bottom: 20px">Ваш ответ:</h3>
                            <textarea name="content">{{ $feedback->answer }}</textarea>
                        </div>
                    @endif
                </div>
            </form>
            @endforeach
        @else
            <div class="center-div">
                <h3>Отзывов нет</h3>
            </div>
        @endif
    </div>
    <div id="fullscreenModal" class="fullscreen-modal" onclick="closeFullscreen()">
        <button class="close-button" onclick="closeFullscreen();">✖</button>
        <img id="fullscreenImage" class="fullscreen-image">
    </div>
</div>

<!-- Модальное окно Отчет -->
<div id="report-window" class="profile">
    <div class="cities-content-up">
        <span class="cities-close" onclick="closeReportWindow()">&times;</span>
        <form style="" id="serviceForm" style="margin: 40px" role="form" action="{{ route('report') }}" class="get-a-quote-price" method="post">
            @csrf
            <input type="hidden" name="user_id" value="{{ $user->id }}">
            <div class="center-div">
                <h3>Заполнить отчёт</h3>
            </div>
            <div id="services-error-container">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
            <div class="group-img" style="margin-bottom: 0">
                <label style="font-weight: bold" for="experience" class="experience-label">Выберете оказанную услугу</label>
                <div class="all-check-box">
                    <div class="check-box">
                        <input class="form-check-input" name="service" type="radio" id="enamel" value="enamel">
                        <label style="font-weight: bold; font-size: 18px" class="form-check-label" for="enamel">Эмалировка</label>
                    </div>
                    <div class="check-box">
                        <input class="form-check-input" name="service" type="radio" id="acrylic" value="acrylic">
                        <label style="font-weight: bold; font-size: 18px" class="form-check-label" for="acrylic">Наливной акрил</label>
                    </div>
                    <div class="check-box">
                        <input class="form-check-input" name="service" type="radio" id="liner" value="liner">
                        <label style="font-weight: bold; font-size: 18px" class="form-check-label" for="liner">Вкладыш</label>
                    </div>
                </div>
            </div>
            <hr>
            <div class="services-labels">
                <label style="font-weight: bold; font-size: 18px" class="form-check-label" for="date">Дата оказания услуги</label>
                <input class="input-price" type="date" name="date" id="date">
            </div>
            <div class="services-labels">
                <label style="font-weight: bold; font-size: 18px" class="form-check-label" for="price">Цена</label>
                <input class="input-price" type="number" name="price" id="price">
            </div>
            <div class="services-labels">
                <label style="font-weight: bold; font-size: 18px" class="form-check-label" for="materials_price">Стоимость материалов</label>
                <input class="input-price" type="number" name="materials_price" id="date">
            </div>
            <div class="services-labels">
                <label style="font-weight: bold; font-size: 18px" class="form-check-label" for="transports_price">Транспортные расходы</label>
                <input class="input-price" type="number" name="transports_price" id="date">
            </div>
            <div class="services-labels">
                <label style="font-weight: bold; font-size: 18px" class="form-check-label" for="transports_price">Прочие расходы</label>
                <input class="input-price" type="number" name="other_price" id="date">
            </div>
            <div class="form-btn">
                <button id="services-save-btn" style="width: 100%" type="submit" class="btn"><span>Сохранить</span></button>
            </div>
        </form>
    </div>
</div>

<!-- Модальное окно Мои обращения -->
@extends('layouts.user-supports')

@endsection

@section('support')
    @include('layouts.support')
@endsection

<script>
    function openJobImagesWindow() {
        document.getElementById("job-images-window").style.display = "block";
    }

    function closeJobImagesWindow() {
        document.getElementById("job-images-window").style.display = "none";
    }

    function dispImagesFileName() {
        const input = document.getElementById('image');
        document.getElementById('image-file-name').textContent = input.files[0] ? input.files[0].name : 'Файл не выбран';
    }

    function openUpdateJobImagesWindow(image_id, title, description, image) {
        console.log('Открытие модального окна для изменения изображения');
        console.log('ID изображения:', image_id);
        console.log('Название изображения:', title);
        console.log('Описание изображения:', description);
        console.log('Изображение:', image);

        // Устанавливаем значения в элементы модального окна
        document.getElementById('update_image_id').value = image_id;
        document.getElementById('update-title').value = title;
        document.getElementById('update-description').value = description;

        // Устанавливаем путь к текущему изображению
        const basePath = '{{ asset('storage') }}'; // Получаем базовый путь к хранилищу
        document.getElementById('current-image').src = `${basePath}/${image}`; // Формируем полный путь
        document.getElementById('current-image').alt = "Текущее изображение";

        // Открываем модальное окно
        document.getElementById("update-job-images-window").style.display = "block";
    }

    function closeUpdateJobImagesWindow() {
        document.getElementById("update-job-images-window").style.display = "none";
    }

    function displayUpdateImageFileName() {
        const input = document.getElementById('update-image');
        document.getElementById('update-image-file-name').textContent = input.files[0] ? input.files[0].name : 'Файл не выбран';
    }

    function openMyOrdersWindow() {
        document.getElementById('my-orders-window').style.display = "block";

    }

    function closeMyOrdersWindow() {
        document.getElementById('my-orders-window').style.display = "none";
    }

    function openReportWindow() {
        document.getElementById('report-window').style.display = "block";
    }

    function closeReportWindow() {
        document.getElementById('report-window').style.display = "none";
    }
</script>

