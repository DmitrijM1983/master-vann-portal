@php use Carbon\Carbon; @endphp
@extends('../layouts/base')
@section('title', 'Карточка мастера')

@section('header')
    <!-- preloader -->
    <div class="preloader">
        <span class="loader"></span>
    </div>
    <!-- preloader end -->

    <!-- Header -->
    <div class="header1" id="stickyHeader">
        <div class="container">
            <div class="top-header">
                <div class="logo">
                    <a href="#">
                        <img alt="logo" src="../assets/img/mvp-header-logo.png">
                    </a>
                </div>
                <div class="top-bar">
                    <div class="login">
                        <a href="javascript:history.back()">Вернуться к списку мастеров</a>
                        <!-- По факту это 'назад', а не возврат к списку мастеров -->
                        <a href="{{ route('index') }}">Вернуться на главную</a>
                    </div>

                    @include('layouts.auth_user_menu')

                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="master-info">
        <div class="master-sidebar" style="width: 35%;">
            <div>
                <a href="#about_master">О мастере</a>
            </div>
            <div>
                <a href="#services_price">Услуги и цены</a>
            </div>
            <div>
                <a href="#feedbacks">Отзывы</a>
            </div>
        </div>

        <div class="master-content">
            @if (session('success'))
                <div style="height: 60px" class="alert alert-success" style="font-size: 24px; text-align: center">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div style="height: 60px" class="alert alert-danger" style="font-size: 24px; text-align: center">
                    {{ session('error') }}
                </div>
            @endif
            <div class="master-content-right">

                <!-- Имя мастера -->
                <div>
                    <h4><b>{{ $master->name . ' ' . ' ' .  $master->middle_name . ' ' . $master->last_name }}</b></h4>
                </div>

                <!-- Фото мастера -->
                @if (!empty($master->mastersInfo->master_photo))
                    <div>
                        <img loading="lazy" class="img" src="{{ $master->mastersInfo->getPhotoUrl() }}" alt="Мастер">
                    </div>
                @else
                    <div>
                        <img class="img" src="../masters/img/master.jpg" alt="Мастер">
                    </div>
                @endif

                <!-- О себе -->
                <div>
                    <h5 id="about_master">О себе</h5>
                    <p>{{ $master->mastersInfo->description }}</p>
                </div>

                <!-- Опыт -->
                <div>
                    <h5>Опыт</h5>
                    <p>с {{ $master->mastersInfo->experience }} года</p>
                </div>

                <!-- Гарантия -->
                <div>
                    <h5>Гарантия</h5>
                    <p>
                        @if ($master->mastersInfo->guarantee == 0)
                            Без гарантии
                        @else
                            Мастер дает гарантию на работу
                            {{ $master->mastersInfo->guarantee }}
                        @endif
                    </p>
                </div>

                <!-- Фото работ -->
                @if(!empty($images))
                    <div style="padding-right: 40px">
                        <h5 style="margin-right: 0">Фото работ {{ count($images) }}</h5>
                        <div class="photo-slider">
                            <button class="slider-button left" onclick="scrollLeftPhotos()">&#10094;</button>
                            <div class="job-photos">
                                @foreach($images as $index => $photo)
                                    <img loading="lazy" src="{{ asset('storage/' . $photo->image) }}" alt="Фото"
                                         class="image"
                                         data-title="{{ $photo->title }}" data-description="{{ $photo->description }}"
                                         onclick="openModal({{ $index }}, '{{ $photo->title }}', '{{ $photo->description }}')">
                                @endforeach
                            </div>
                            <button class="slider-button right" onclick="scrollRightPhotos()">&#10095;</button>
                        </div>
                    </div>
                @endif

                <!-- Услуги и цены -->
                <div style="padding-right: 40px">
                    <div>
                        <h5 class="table-title" id="services_price">Услуги и цены</h5>
                        <p class="about_price">(цены зависят от размера ванны, выбранного материала и сложности
                            заказа)</p>
                        <table>
                            @foreach($servicePrices as $service=>$price)
                                <tr>
                                    <td class="td-services">{{ $service }}</td>
                                    <td class="column">от {{ $price }} рублей</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>

                <!-- Рейтинг -->
                <div>
                    <h5>Рейтинг мастера</h5>
                    @if($master->mastersInfo->rating)
                        <p>{{ $master->mastersInfo->rating }} из 5</p>
                    @else
                        <p>Оценок пока нет</p>
                    @endif
                </div>

                <!-- Отзывы -->
                @if(count($feedbacks) >= 1)
                    <div class="feedbacks">
                        <h5 style="width: 100%" id="feedbacks">Отзывы {{ count($feedbacks) }}</h5>
                        <table class="table-feedbacks">
                            <tr class="table-tr">
                                <td class="grade-td">
                                    <ul class="stars">
                                        <li><i class="fa-solid fa-star"></i></li>
                                        <li><i class="fa-solid fa-star"></i></li>
                                        <li><i class="fa-solid fa-star"></i></li>
                                        <li><i class="fa-solid fa-star"></i></li>
                                        <li><i class="fa-solid fa-star"></i></li>
                                    </ul>
                                </td>
                                <td>
                                    <div class="progress-bar-container">
                                        <div class="progress-bar"
                                             style="width: {{ ($countOfFiveStarFeedbacks / count($feedbacks)) * 100 }}%;"></div>
                                    </div>
                                </td>
                                <td class="grade-column">{{ $countOfFiveStarFeedbacks }}</td>
                            </tr>
                            <tr class="table-tr">
                                <td class="grade-td">
                                    <ul class="stars">
                                        <li><i class="fa-solid fa-star"></i></li>
                                        <li><i class="fa-solid fa-star"></i></li>
                                        <li><i class="fa-solid fa-star"></i></li>
                                        <li><i class="fa-solid fa-star"></i></li>
                                    </ul>
                                </td>
                                <td>
                                    <div class="progress-bar-container">
                                        <div class="progress-bar"
                                             style="width: {{ ($countOfFourStarFeedbacks / count($feedbacks)) * 100 }}%;"></div>
                                    </div>
                                </td>
                                <td class="grade-column">{{ $countOfFourStarFeedbacks }}</td>
                            </tr>
                            <tr class="table-tr">
                                <td class="grade-td">
                                    <ul class="stars">
                                        <li><i class="fa-solid fa-star"></i></li>
                                        <li><i class="fa-solid fa-star"></i></li>
                                        <li><i class="fa-solid fa-star"></i></li>
                                    </ul>
                                </td>
                                <td>
                                    <div class="progress-bar-container">
                                        <div class="progress-bar"
                                             style="width: {{ ($countOfThreeStarFeedbacks / count($feedbacks)) * 100 }}%;"></div>
                                    </div>
                                </td>
                                <td class="grade-column">{{ $countOfThreeStarFeedbacks }}</td>
                            </tr>
                            <tr class="table-tr">
                                <td class="grade-td">
                                    <ul class="stars">
                                        <li><i class="fa-solid fa-star"></i></li>
                                        <li><i class="fa-solid fa-star"></i></li>
                                    </ul>
                                </td>
                                <td>
                                    <div class="progress-bar-container">
                                        <div class="progress-bar"
                                             style="width: {{ ($countOfTwoStarFeedbacks / count($feedbacks)) * 100 }}%;"></div>
                                    </div>
                                </td>
                                <td class="grade-column">{{ $countOfTwoStarFeedbacks }}</td>
                            </tr>
                            <tr class="table-tr">
                                <td class="grade-td">
                                    <ul class="stars">
                                        <li><i class="fa-solid fa-star"></i></li>
                                    </ul>
                                </td>
                                <td>
                                    <div class="progress-bar-container">
                                        <div class="progress-bar"
                                             style="width: {{ ($countOfOneStarFeedbacks / count($feedbacks)) * 100 }}%;"></div>
                                    </div>
                                </td>
                                <td class="grade-column">{{ $countOfOneStarFeedbacks }}</td>
                            </tr>
                            <tr class="table-tr">
                                <td class="grade-td">
                                    <h5 style="font-weight: bold">Без оценки</h5>
                                </td>
                                <td>
                                    <div class="progress-bar-container">
                                        <div class="progress-bar"
                                             style="width: {{ ($countOfWithoutStarFeedbacks / count($feedbacks)) * 100 }}%;"></div>
                                    </div>
                                </td>
                                <td class="grade-column">{{ $countOfWithoutStarFeedbacks }}</td>
                            </tr>
                        </table>

                        <!-- Сортировка -->
                        <div class="sort">
                            <h5 style="font-weight: bold">Показывать:</h5>
                            <form method="GET" id="sortForm" action="{{ route('master_card', $master->id) }}">
                                <select name="sort" onchange="updateAction(this.value)">
                                    <option value="newest" @if (request('sort') == 'newest')  selected @endif>сначала
                                        новые отзывы
                                    </option>
                                    <option value="oldest" @if (request('sort') == 'oldest')  selected @endif>сначала
                                        старые отзывы
                                    </option>
                                    <option value="with-images" @if (request('sort') == 'with-images')  selected @endif>
                                        сначала отзывы с фотографиями
                                    </option>
                                    <option value="good" @if (request('sort') == 'good')  selected @endif>сначала
                                        хорошие отзывы
                                    </option>
                                    <option value="bad" @if (request('sort') == 'bad')  selected @endif>сначала плохие
                                        отзывы
                                    </option>
                                    <option value="without-grade"
                                            @if (request('sort') == 'without-grade')  selected @endif>сначала отзывы без
                                        оценки
                                    </option>
                                </select>
                            </form>
                        </div>

                        <!--Вывод отзывов -->
                        @foreach($feedbacks as $feedback)
                            <hr class="hr-feedbacks">
                            <div class="feedbacks-content">
                                <table class="feedbacks-content">
                                    <tr>
                                        <td style="width: 200px;  vertical-align: top; ">
                                            @if($feedback->grade >= 1)
                                                <ul class="stars" style="padding-left: 40px">
                                                    @for($i = 1; $i <= $feedback->grade; $i++)
                                                        <li><i class="fa-solid fa-star"></i></li>
                                                    @endfor
                                                </ul>
                                            @else
                                                <p style="padding-left: 40px; font-size: 18px; font-weight: bold">Без
                                                    оценки</p>
                                            @endif
                                        </td>
                                        <td style="padding-left: 20px">
                                            @if(!empty($feedback->user->name))
                                                <h5 style="font-weight: bold">{{ $feedback->user->name }}</h5>
                                            @endif
                                            @if(!empty($feedback->service->name))
                                                <p>Отзыв по услуге: {{ $feedback->service->name }}</p>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="date-center">
                                            {{ Carbon::parse($feedback->created_at)->locale('ru')->translatedFormat('j F Y') }}
                                        </td>
                                        <td style="padding-top: 10px; padding-right: 40px;">
                                            {{ $feedback->content }}
                                            @if (!empty($feedback->images))
                                                <div class="review-slider">
                                                    <div class="review-photos" id="reviewPhotosContainer">
                                                        @foreach($feedback->images as $key => $image)
                                                            <img loading="lazy" src="{{ asset('storage/' . $image) }}" alt="Фото"
                                                                 class="image"
                                                                 onclick="openReviewModal({{ $key }})">
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                            @if(!empty($feedback->answer))
                                                <div class="answer">
                                                    @if(!empty($master->mastersInfo->master_photo))
                                                        <img src="{{ $master->mastersInfo->getPhotoUrl() }}" alt="#"
                                                             class="avatar">
                                                    @else
                                                        <img src="../masters/img/master.jpg" alt="#" class="avatar">
                                                    @endif
                                                    <div class="answer-title">
                                                        <h6 class="profile-name">{{ $master->name . ' ' . $master->last_name }}</h6>
                                                        <p>Ответ мастера</p>
                                                    </div>
                                                </div>
                                                <div>
                                                    {{ $feedback->answer }}
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        @endforeach
                    </div>
                    <div class="feedbacks-table-end"></div>
                @else
                    <div style="padding-right: 40px; border-radius: 10px">
                        <h5 style="width: 100%; border-radius: 10px" id="feedbacks">Отзывов пока нет</h5>
                    </div>
                @endif


                <!-- Ссылки для связи с мастером-->
                <div>
                    <a href="#" class="btn" style="margin-right: 20px" onclick="openMessageWindow()">Написать мастеру</a>
                    <a href="#" class="btn" style="margin-right: 20px" onclick="openOrderWindow()"><span>Отправить заявку</span></a>
                    <a href="#" class="btn" onclick="openFeedbackWindow()"><span>Оставить отзыв</span></a>
                </div>

            </div>

        </div>
    </div>

    <!-- Модальное окно (заказ) -->
    <div id="order-window" class="profile">
        <div class="profile-master-content">
            <span class="profile-close" onclick="closeOrderWindow()">&times;</span>
            <form id="masterForm" role="form" action="{{ route('order') }}" class="get-a-quote" method="post"
                  enctype="multipart/form-data">
                @csrf
                <div class="center-div">
                    <h3>Заявка</h3>
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
                <input type="hidden" name="user_to" value="{{ $master->id }}">
                <div class="group-img" style=" margin-bottom: 20px">
                    <label for="name">Ваше имя*</label>
                    <input id="name" type="text" name="name" required>
                    <label for="phone">Ваш телефон*</label>
                    <input id="phone" type="text" name="phone" required>
                    <label for="content">Сообщение</label>
                    <textarea id="content" style="height: 100px" name="content"></textarea>
                </div>

                @if(Auth::check())
                    <input type="hidden" name="user_from" value="{{ Auth::user()->id }}">
                    <div class="master-form-btn">
                        <button type="submit" class="btn"><span>Отправить</span></button>
                    </div>
                @else
                    <div class="center-div">
                        <div class="auth-info">
                            <h4>Для отправки нужно зарегистрироваться и авторизоваться</h4>
                        </div>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Модальное окно (фотографии примеров работ) -->
    <div id="modal" class="modal">
        <span class="close" onclick="closeModal()">&times;</span>
        <div class="modal-container">
            <img class="modal-content" id="modalImage" alt="Фото">
            <div class="info">
                <div class="profile-user">
                    @if (!empty($master->mastersInfo->master_photo))
                        <img class="avatar" src="{{ $master->mastersInfo->getPhotoUrl() }}" alt="No avatar">
                    @else
                        <img class="avatar" src="../masters/img/master.jpg" alt="No avatar">
                    @endif
                    <h3 class="profile-name">{{ $master->name . ' ' . $master->last_name }}</h3>
                </div>
                <div class="services">
                    <h5>Услуги</h5>
                    <ul>
                        @foreach($servicePrices as $service => $price)
                            <li style="font-size: 18px">{{ $service }}</li>
                        @endforeach
                    </ul>
                </div>
                <h3 id="photoTitle"></h3>
                <p class="photo-description" id="photoDescription"></p>
            </div>
        </div>
        <div class="caption">
            <button class="prev" onclick="changeImage(-1)">&#10094;</button>
            <button class="next" onclick="changeImage(1)">&#10095;</button>
        </div>
    </div>

    <!-- Модальное окно (фотографии отзывы) -->
    <div id="modalReview" class="modal" style="display: none;">
        <span class="close" onclick="closeReviewModal()">&times;</span>
        <div class="modal-container-review">
            <img class="modal-content" id="modalImageReview" alt="Фото">
        </div>
        <div class="caption">
            <button class="prev" onclick="changeReviewImage(-1)">&#10094;</button>
            <button class="next" onclick="changeReviewImage(1)">&#10095;</button>
        </div>
    </div>

    <!-- Модальное окно сообщение мастеру -->
    <div id="message-window" class="profile">
        <div class="profile-master-content">
            <span class="profile-close" onclick="closeMessageWindow()">&times;</span>
            <form id="masterForm" role="form" action="{{ route('message') }}" class="get-a-quote" method="post"
                  enctype="multipart/form-data">
                @csrf
                <div class="center-div">
                    <h3>Сообщение</h3>
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
                <input type="hidden" name="user_to" value="{{ $master->id }}">

                <div class="group-img" style="margin-top: 20px; margin-bottom: 20px">
                    <textarea style="height: 200px" name="content"></textarea>
                </div>
                <div class="group-img">
                    <label for="images" class="file-upload">
                        <input type="file" name="images[]" id="images" accept="image/*" class="form-control"
                               style="display: none;" multiple onchange="displayFileName()">
                        Добавить фото
                        <span id="file-name">Файл не выбран</span>
                    </label>
                </div>

                @if(Auth::check())
                    <input type="hidden" name="user_from" value="{{ Auth::user()->id }}">
                    <div class="master-form-btn">
                        <button type="submit" class="btn"><span>Отправить</span></button>
                    </div>
                @else
                    <div class="center-div">
                        <div class="auth-info">
                            <h4>Для отправки сообщения нужно зарегистрироваться и авторизоваться</h4>
                        </div>
                    </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Модальное окно отзыв -->
    <div id="feedback-window" class="profile">
        <div class="profile-master-content">
            <span class="profile-close" onclick="closeFeedbackWindow()">&times;</span>
            <form id="masterForm" role="form" action="{{ route('feedback') }}" class="get-a-quote" method="post"
                  enctype="multipart/form-data">
                @csrf
                <div class="center-div" style="padding-bottom: 0">
                    <h3>Отзыв</h3>
                </div>
                @if (!Auth::check())
                    <div class="center-div">
                        <p style="padding: 0">(не авторизованные пользователи могут оставить отзыв без оценки)</p>
                    </div>
                @endif
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
                <input type="hidden" name="master_id" value="{{ $master->id }}">

                <div class="group-img" style="margin-top: 20px; margin-bottom: 20px">
                    <textarea style="height: 200px" name="content"></textarea>
                </div>


                @if(Auth::check())
                    <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">

                    <div class="group-img">
                        <label for="experience" class="experience-label">Услуга оказана?</label>
                        <div class="all-check-box">
                            <div class="check-box">
                                <input class="form-check-input" name="service_provided" type="radio" id="yes" value="1"
                                       onclick="toggleServiceSelection()">
                                <label class="form-check-label" for="yes">Да</label>
                            </div>
                            <div class="check-box">
                                <input class="form-check-input" name="service_provided" type="radio" id="no" value="0"
                                       onclick="toggleServiceSelection()">
                                <label class="form-check-label" for="no">Нет</label>
                            </div>
                        </div>
                    </div>

                    <div class="group-img" id="serviceSelection" style="display:none;">
                        <label for="experience" class="experience-label">Выберите услугу</label>
                        <div class="all-check-box">
                            <div class="check-box">
                                <input class="form-check-input" name="service_id" type="radio" id="service1" value="1">
                                <label class="form-check-label" for="service1">Эмалировка ванны</label>
                            </div>
                            <div class="check-box">
                                <input class="form-check-input" name="service_id" type="radio" id="service2" value="2">
                                <label class="form-check-label" for="service2">Реставрация жидким акрилом</label>
                            </div>
                            <div class="check-box">
                                <input class="form-check-input" name="service_id" type="radio" id="service3" value="3">
                                <label class="form-check-label" for="service3">Реставрация акриловым вкладышем</label>
                            </div>
                        </div>
                    </div>

                    <div class="group-img" id="starSelection" style="display:none;">
                        <label for="experience" class="experience-label" style="margin-bottom: -20px">Оцените
                            услугу</label>
                        <div class="center-div" style="padding-bottom: 0">
                            <div class="rating" id="rating">
                                <span class="star" data-value="1" onclick="setRating(1)">★</span>
                                <span class="star" data-value="2" onclick="setRating(2)">★</span>
                                <span class="star" data-value="3" onclick="setRating(3)">★</span>
                                <span class="star" data-value="4" onclick="setRating(4)">★</span>
                                <span class="star" data-value="5" onclick="setRating(5)">★</span>
                            </div>
                        </div>

                        <input type="hidden" name="grade" id="service_rating" value="">
                    </div>

                    <div class="group-img" id="feedback-images" style="display:none;">
                        <label for="feedback-images-data" class="file-upload">
                            <input type="file" name="images[]" id="feedback-images-data" accept="image/*"
                                   class="form-control" style="display: none;" multiple
                                   onchange="displayFeedbackImagesName()">
                            Добавить фото
                            <span id="feedback-images-name">Файл не выбран</span>
                        </label>
                    </div>

                    <div class="master-form-btn">
                        <button type="submit" class="btn"><span>Отправить</span></button>
                    </div>
                @else
                    <div class="master-form-btn">
                        <button type="submit" class="btn"><span>Отправить</span></button>
                    </div>
                @endif
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
<script>
    function toggleServiceSelection() {
        const yesChecked = document.getElementById('yes').checked;
        const serviceSelection = document.getElementById('serviceSelection');
        serviceSelection.style.display = yesChecked ? 'block' : 'none';
        const serviceImagesSelection = document.getElementById('feedback-images');
        serviceImagesSelection.style.display = yesChecked ? 'block' : 'none';
        const starSelection = document.getElementById('starSelection');
        starSelection.style.display = yesChecked ? 'block' : 'none';
    }

    function setRating(value) {
        // Обновляем скрытое поле с оценкой
        document.getElementById('service_rating').value = value;

        // Получаем все звезды
        const stars = document.querySelectorAll('.star');

        // Проходим по каждой звезде и обновляем ее состояние
        stars.forEach((star, index) => {
            if (index < value) {
                star.classList.add('selected'); // Закрашиваем звезду
            } else {
                star.classList.remove('selected'); // Убираем покраску
            }
        });
    }

</script>
