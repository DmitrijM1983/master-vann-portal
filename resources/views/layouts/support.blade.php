<div class="support-icon" id="supportIcon" onclick="openSupportModalWindow()">
    <svg width="1em" height="1em" viewBox="0 0 22 22" xmlns="http://www.w3.org/2000/svg">
        <path d="M17.39 17.3c-.19.03-.4.04-.6.05v2.66a.81.81 0 0 1-1.35.6l-3.52-3.22H5.45a3.95 3.95 0 0 1-3.95-3.96V7.45A3.95 3.95 0 0 1 5.45 3.5h11.1a3.95 3.95 0 0 1 3.95 3.95v5.98a3.96 3.96 0 0 1-3.08 3.86h-.03Z" fill="currentColor"></path>
    </svg>
    <div class="tooltip-label">Обратиться в поддержку</div>
</div>

<div class="profile" id="supportModal">
    <div class="profile-master-content">
        <span class="profile-close" onclick="closeSupportModalWindow()">&times;</span>
        <div class="center-div" style="margin-top: 20px">
            <h3>Обращение</h3>
        </div>
        <form action="{{ route('support') }}" id="supportForm" class="get-a-quote" style="margin-top: 0" method="post" enctype="multipart/form-data">
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
            <div class="group-img" style="margin-top: 20px; margin-bottom: 20px">
                <label for="description" class="experience-label">Ваше сообщение</label>
                <textarea style="height: 180px" name="content"></textarea>
            </div>
            @if (!Auth::check())
                <div class="group-img" style="margin-top: 20px; margin-bottom: 20px">
                    <label for="email" class="experience-label">Укажите электронную почту</label>
                    <input type="email" name="email" id="email" class="form-control" required>
                </div>
            @else
                <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
            @endif
            <div class="group-img">
                <h5 style="margin-bottom: 10px">Фотография</h5>
                <label for="support-photo" class="file-upload">
                    <input type="file" name="photo" id="support-photo" accept="image/*" class="form-control" style="display: none;" onchange="printFileName()">
                    Прикрепить фотографию
                    <span id="support-file-name">Файл не выбран</span>
                </label>
            </div>
            <div class="master-form-btn">
                <button type="submit" class="btn"><span>Отправить</span></button>
            </div>
        </form>
    </div>
</div>
