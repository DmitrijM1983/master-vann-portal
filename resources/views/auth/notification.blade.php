@extends('../layouts/base')
@section('title', 'Подтверждение почты')

@section('content')
<div class="centered-form">
    <div class="col-lg-5">
        <div  class="get-a-quote">
            <div class="center-div">
            </div>
            <div class="center-div">
                <h4 style="text-align: center">
                    Мы отправили вам письмо с подтверждающей ссылкой.
                </h4>
            </div>
            <div class="center-div">
                <h4 style="text-align: center">
                    Перейдите по ней, чтобы активировать ваш аккаунт. Если письмо не пришло, проверьте папку «Спам» или запросите повторную отправку.
                </h4>
            </div>
            <hr>

            <div class="center-div">
                <h4>Письмо не пришло?</h4>
            </div>
            <div class="center-div">
                    <form action="{{ route('verification.send') }}" method="post">
                        @csrf
                        <button class="btn"> Отправить повторно</button>
                    </form>
            </div>
        </div>
    </div>
</div>
@endsection

