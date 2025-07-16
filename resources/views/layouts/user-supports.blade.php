<!-- Модальное окно Мои обращения -->
@if (Auth::check())
<div id="user-supports-window" class="profile">
    <div class="profile-master-content">
        <span class="profile-close" onclick="closeUserSupportsWindow()">&times;</span>

        @if ( count($supports) > 0)
            <div class="center-div" style="padding-bottom: 20px">
                <h3 style="margin-top: 20px">Обращения</h3>
            </div>
            @foreach ($supports as $support)
                <form style="margin-top: 0 !important;" id="masterForm" role="form" action="" class="get-a-quote-message">
                    @csrf

                    <div class="message-cont">
                        <div class="message-content" style="margin-top: 20px; margin-bottom: 20px">
                            <h5 style="margin-bottom: 20px">Обращение создано в {{ $support->created_at->format('H:i') . ' ' . $support->created_at->format('d.m.Y') }}</h5>

                            <textarea style="overflow:hidden; resize:none;" id="textarea" readonly>@if(!empty($support->content)){{ $support->content }}@endif</textarea>
                        </div>

                        @if (!empty($support->photo))
                            <div class="message-image">
                                <img src="{{ asset('storage/' . $support->photo) }}" onclick="openInNewWindow('{{ asset('storage/' . $support->photo) }}')" alt="Не загружено" class="clickable-image" style="cursor: pointer;">
                            </div>
                        @endif
                    </div>

                    <div class="answer-content">
                        @if (empty($support->answer))
                            <div class="message-content" style="margin-top: 20px; margin-bottom: 20px">
                                <h3 style="margin-bottom: 20px">Ответа пока нет</h3>
                            </div>
                        @else
                            <div class="message-content" style="margin-top: 20px; margin-bottom: 20px">
                                <h3 style="margin-bottom: 20px">Ответ поддержки</h3>
                                <textarea style="overflow:hidden; resize:none;" id="textarea" readonly name="content">{{ $support->answer }}</textarea>
                            </div>
                        @endif
                    </div>
                </form>


            @endforeach
        @else
            <div class="center-div">
                <h3>Обращений нет</h3>
            </div>
        @endif
    </div>
    <div id="fullscreenModal" class="fullscreen-modal" onclick="closeFullscreen()">
        <button class="close-button" onclick="closeFullscreen();">✖</button>
        <img id="fullscreenImage" class="fullscreen-image">
    </div>
</div>
@endif
