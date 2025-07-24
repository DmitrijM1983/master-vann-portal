@if (Auth::check())
    <div style="margin-right: 80px" class="drop-div">
        <div class="dropdown-menu">
            <span><h3>{{ Auth::user()->name }}</h3></span>
            <span class="arrow"></span>
            <div class="dropdown-content">
                <a href="#messages" onclick="openMessWindow()">
                    @if($noReadAnswerCount > 0)
                        <span class="mark-no-read"></span>
                    @endif
                    Мои сообщения
                    <span class="message-count">{{ count($messages) }}</span>
                </a>
                <a href="#orders" onclick="openUserOrdersWindow()">Мои заказы
                    <span class="message-count">{{ count($orders) }}</span>
                </a>
                <a href="#supports" onclick="openUserSupportsWindow()">Мои обращения
                    <span class="message-count">{{ count($supports) }}</span>
                </a>
                <a href="{{ route('logout') }}">Выйти</a>
            </div>
        </div>
    </div>
@else
    <div class="login">
        <a href="{{ route('login_form') }}" class="btn">Войти</a>
        <a href="{{ route('register_form') }}" class="btn"><span>Зарегистрироваться</span></a>
    </div>
@endif


