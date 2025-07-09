document.querySelector('.sidebar').addEventListener('click', function (event) {

    if (event.target === this) {
        this.classList.toggle('visible');
    }
});

function openProfileWindow() {
    document.getElementById("profile-window").style.display = "block";
}

function closeProfileWindow() {
    document.getElementById("profile-window").style.display = "none";
    // Очистить контейнер ошибок после закрытия
    document.getElementById('error-container').innerHTML = '';
}

$(document).ready(function () {
    $('#profileForm').on('submit', function (event) {
        event.preventDefault(); // Предотвращаем стандартную отправку формы

        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            success: function (response) {
                // Закрываем модальное окно при успешном сохранении данных
                closeProfileWindow();
                // Здесь можно обновить данные на странице, если нужно
                // Например, обновить элементы профиля на странице:
                // $('#profileName').text(response.name);
                // Итд. в зависимости от структуры вашего ответа
            },
            error: function (xhr) {
                // Выводим ошибки, если они есть
                let errors = xhr.responseJSON.errors;
                $('#error-container').empty().append('<div class="alert alert-danger"><ul></ul></div>');
                $.each(errors, function (key, value) {
                    $('#error-container .alert ul').append('<li>' + value[0] + '</li>');
                });
                // Оставляем модальное окно открытым
            }
        });
    });
});

function openProfileMasterWindow() {
    document.getElementById("profile-master-window").style.display = "block";
}

function closeProfileMasterWindow() {
    document.getElementById("profile-master-window").style.display = "none";
    // Очистить контейнер ошибок после закрытия
    document.getElementById('error-container').innerHTML = '';
}

function dispFileName() {
    const input = document.getElementById('photo');
    document.getElementById('file-name').textContent = input.files[0] ? input.files[0].name : 'Файл не выбран';
}

function displFileName() {
    const input = document.getElementById('files');
    document.getElementById('files-name').textContent = Array.from(input.files).map(file => file.name).join(', ') || 'Файл не выбран';
}

function disFileName() {
    const input = document.getElementById('files-no-read');
    document.getElementById('file-no-read').textContent = Array.from(input.files).map(file => file.name).join(', ') || 'Файл не выбран';
}

function openCitiesWindow() {
    document.getElementById("cities-window").style.display = "block";
}

function closeCitiesWindow() {
    document.getElementById("cities-window").style.display = "none";
    // Очистить контейнер ошибок после закрытия
    document.getElementById('error-container').innerHTML = '';
}

function validateForm() {
    var cityInput = document.getElementById('city').value;
    if (!cityInput) {
        alert('Выберите город');
        return false; // предотвращаем отправку формы
    }
    return true; // разрешаем отправку формы
}

function openServicesWindow() {
    document.getElementById("services-window").style.display = "block";
    $('#services-error-container').empty(); // Очистить ошибки при открытии окна
}

function closeServicesWindow() {
    document.getElementById("services-window").style.display = "none";
    $('#services-error-container').empty(); // очистить ошибки
}

$(document).ready(function () {
    // Инициализация: установка состояния полей в зависимости от чекбоксов
    $('.form-check-input').each(function () {
        const inputId = $(this).attr('id');
        const priceInput = $('input[name="price-' + inputId + '"]');
        priceInput.prop('disabled', !this.checked); // Отключить, если чекбокс не отмечен
    });

    $('#services-save-btn').on('click', function (event) {
        event.preventDefault(); // Предотвращаем стандартную отправку формы

        $('#services-error-container').empty(); // Очищаем контейнер для ошибок
        let hasError = false; // Флаг для ошибок

        // Проверка на заполнение цен
        if ($('#enamel').is(':checked') && !$('input[name="price-enamel"]').val()) hasError = true;
        if ($('#acrylic').is(':checked') && !$('input[name="price-acrylic"]').val()) hasError = true;
        if ($('#liner').is(':checked') && !$('input[name="price-liner"]').val()) hasError = true;

        // Если есть ошибки, выводим одну общую ошибку
        if (hasError) {
            $('#services-error-container').append('<div style="color: red; text-align: center;">Укажите начальную цену!</div>');
            return; // Прекращаем выполнение при наличии ошибок
        }


        // Ошибок нет, отправляем данные через Ajax
        $.ajax({
            url: $("#serviceForm").attr('action'), // Получаем URL из формы
            method: 'POST', // Метод отправки
            data: $("#serviceForm").serialize(), // Сериализуем данные формы
            beforeSend: function () {
                const actionUrl = $("#serviceForm").attr('action'); // Получаем URL
                const serializedData = $("#serviceForm").serialize(); // Сериализуем данные

                console.log("Sending data to URL:", actionUrl); // Логируем URL
                console.log("Serialized data:", serializedData); // Логируем сериализованные данные
            }.bind("#serviceForm"), // Убедитесь, что this привязано к форме
            success: function (response) {
                closeServicesWindow(); // Закрываем модальное окно при успешном ответе
                location.reload(); // Перезагрузка страницы
            },
            error: function (xhr) {
                console.error(xhr); // Логирование ошибок
                $('#services-error-container').empty().append('<div style="color: red; text-align: center;">Произошла ошибка. Попробуйте позже.</div>');
            }
        });
    });

    // Деактивируем соответствующий input, если чекбокс не отмечен
    $('.form-check-input').on('change', function () {
        const inputId = $(this).attr('id');
        const priceInput = $('input[name="price-' + inputId + '"]');
        priceInput.prop('disabled', !this.checked); // Активируем/деактивируем input
        if (!this.checked) priceInput.val(''); // Очищаем поле, если чекбокс не отмечен
    });
});

//Сообщения
function openMessagesWindow() {
    document.getElementById("messages-window").style.display = "block";
    markAsRead()
}

function closeMessagesWindow() {
    document.getElementById("messages-window").style.display = "none";
    // Очистить контейнер ошибок после закрытия
    document.getElementById('error-container').innerHTML = '';
    location.reload();
}

function openNoReadMessagesWindow() {
    document.getElementById("no-read-messages-window").style.display = "block";
    markAsRead()
}

function closeNoReadMessagesWindow() {
    document.getElementById("no-read-messages-window").style.display = "none";
    // Очистить контейнер ошибок после закрытия
    document.getElementById('error-container').innerHTML = '';
    location.reload();
}

function markAsRead() {
    const messageIds = Array.from(document.querySelectorAll('.message-item'))
        .map(item => item.dataset.id);

    // Получаем CSRF токен из формы
    const csrfToken = document.querySelector('#messageForm input[name="_token"]').value;

    fetch('/mark_messages_as_read', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-Token': csrfToken
        },
        body: JSON.stringify({messages: messageIds})
    })
        .then(response => {
            if (response.ok) {
                return response.json();
            }
            throw new Error('Ошибка при отметке сообщений прочитанными');
        })
        .then(data => {
            console.log(data.message);
            // Ваш код открытия модального окна здесь
        })
        .catch(error => {
            console.error('Ошибка:', error);
        });
}

function openFullscreen(elem) {
    var modal = document.getElementById("fullscreenModal");
    var img = document.getElementById("fullscreenImage");

    img.src = elem.src;
    modal.style.display = "flex";
}

function closeFullscreen() {
    var modal = document.getElementById("fullscreenModal");
    modal.style.display = "none";
}

function openInNewWindow(imageUrl) {
    window.open(imageUrl, '_blank'); // Открывает новое окно с изображением
}

//Отзывы
function openFeedbacksWindow() {
    document.getElementById("feedback-window").style.display = "block";
    markFeedbackAsRead();
}

function closeFeedbacksWindow() {
    document.getElementById("feedback-window").style.display = "none";
    location.reload();
}

function markFeedbackAsRead() {
    const feedbackIds = Array.from(document.querySelectorAll('.feedback-item'))
        .map(item => item.dataset.id);

    // Получаем CSRF токен из формы
    const csrfToken = document.querySelector('#feedbackForm input[name="_token"]').value;

    fetch('/mark_feedbacks_as_read', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-Token': csrfToken
        },
        body: JSON.stringify({feedbacks: feedbackIds})
    })
        .then(response => {
            if (response.ok) {
                return response.json();
            }
            throw new Error('Ошибка при отметке сообщений прочитанными');
        })
        .then(data => {
            console.log(data.feedback);
            // Ваш код открытия модального окна здесь
        })
        .catch(error => {
            console.error('Ошибка:', error);
        });
}
