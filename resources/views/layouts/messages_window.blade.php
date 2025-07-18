<div id="mess-window" class="profile">
    <div class="profile-master-content">
        <span class="profile-close" onclick="closeMessWindow()">&times;</span>

        @if (!empty($messages) && count($messages) > 0)

            @if (!empty($noReadAnswer))
                <form id="answerForm" action="{{ 'mark_answer_as_read' }}">
                    @foreach ($noReadAnswer as $answer)
                        @csrf
                        <ul id="answerList">
                            <li data-id="{{ $answer->id }}" class="answer-item"></li>
                        </ul>
                </form>
                @endforeach
            @endif
                <div class="center-div">
                    <h3 style="margin-top: 20px">Сообщения</h3>
                </div>
            @foreach($messages as $message)

                <form style="margin-top: 0 !important;" id="masterForm" role="form" action="{{ route('answer') }}" class="get-a-quote-message" method="post" enctype="multipart/form-data">
                    @csrf

                    <div class="message-cont">
                        <div class="message-content" style="margin-top: 20px; margin-bottom: 20px">
                            <h3 style="margin-bottom: 20px">Получатель: {{ $message->userTo->name . ' ' . $message->userTo->last_name}}</h3>
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
                                <h3 style="margin-bottom: 20px">Ответа пока нет.</h3>
                            </div>
                        @else
                            <div class="message-content" style="margin-top: 20px; margin-bottom: 20px">
                                <h3 style="margin-bottom: 20px">Ответ мастера:</h3>
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
