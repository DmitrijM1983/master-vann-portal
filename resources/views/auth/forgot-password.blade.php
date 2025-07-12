@extends('../layouts/base')
@section('title', 'Восстановление пароля')

@section('content')
<div class="centered-form">
    <div class="col-lg-5">
        <form role="form" action="{{ route('password.email') }}" class="get-a-quote" method="post">
            @csrf
            <div class="center-div">
                <h3>Восстановление пароля</h3>
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
            @if (session('status'))
                <div class="alert alert-success" style="font-size: 24px; text-align: center">
                    {{ session('status') }}
                </div>
            @endif
            <div class="group-img">
                <i><svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_22_25)">
                            <path d="M17.0769 3H0.923098C0.415371 3 0 3.41537 0 3.92306V14.0769C0 14.5846 0.415371 15 0.923098 15H17.0769C17.5846 15 18 14.5847 18 14.0769V3.92306C18 3.41537 17.5846 3 17.0769 3ZM16.7305 3.69226L9.531 9.09233C9.40156 9.19084 9.20285 9.25247 8.99996 9.25155C8.79711 9.25247 8.59845 9.19084 8.46896 9.09233L1.26946 3.69226H16.7305ZM12.8848 9.44864L16.8079 14.2948C16.8118 14.2997 16.8166 14.3034 16.8208 14.3078H1.17921C1.18336 14.3032 1.18821 14.2997 1.19215 14.2948L5.11523 9.44864C5.23543 9.30003 5.21265 9.08217 5.06377 8.96169C4.91516 8.84149 4.6973 8.86427 4.57706 9.01291L0.692297 13.8118V4.12496L8.0538 9.64611C8.33052 9.8522 8.66718 9.9429 8.99993 9.94382C9.33223 9.94311 9.66916 9.85241 9.94605 9.64611L17.3076 4.12496V13.8117L13.4229 9.01291C13.3027 8.86431 13.0846 8.84146 12.9362 8.96169C12.7873 9.08189 12.7645 9.30003 12.8848 9.44864Z" fill="black"/>
                        </g>
                    </svg>
                </i>
                <input type="text" name="email" placeholder="Введите почту" value="{{ old('email') }}" required>
            </div>

            <div class="login">
                <button type="submit" class="btn"><span>Отправить ссылку</span></button>
                <a class="hover-link" href="{{ route('index') }}">Назад</a>
            </div>
        </form>
    </div>
</div>
@endsection

