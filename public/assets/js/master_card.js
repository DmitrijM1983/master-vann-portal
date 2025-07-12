/*
Фиксирует background-color при нажатии на ссылку  в блоке sidebar
*/
const sidebarItems = document.querySelectorAll('.master-sidebar > div');

sidebarItems.forEach(item => {
    item.addEventListener('click', () => {
        // Удаляем класс active у всех элементов
        sidebarItems.forEach(i => i.classList.remove('active'));

        // Добавляем класс active к текущему элементу
        item.classList.add('active');
    });
});

function updateAction(sortValue) {
    document.getElementById('sortForm').submit(); // Сортировка отзывов
}

function openMessageWindow() {
    document.getElementById("message-window").style.display = "block";
    document.getElementById("stickyHeader").style.display = "none";
    document.body.style.overflow = 'hidden';
}

function closeMessageWindow() {
    document.getElementById("message-window").style.display = "none";
    // Очистить контейнер ошибок после закрытия
    document.getElementById('error-container').innerHTML = '';
    document.getElementById("stickyHeader").style.display = "";
    document.body.style.overflow = '';
}

function displayFileName() {
    const input = document.getElementById('images');
    const fileNameDisplay = document.getElementById('file-name');

    if (input.files.length > 0) {
        const fileNames = Array.from(input.files).map(file => file.name);
        fileNameDisplay.textContent = fileNames.join(', ');
    } else {
        fileNameDisplay.textContent = 'Файл не выбран';
    }
}

$(document).ready(function() {
    $('#profileForm').on('submit', function(event) {
        event.preventDefault(); // Предотвращаем стандартную отправку формы

        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                // Закрываем модальное окно при успешном сохранении данных
                closeProfileWindow();
                // Здесь можно обновить данные на странице, если нужно
                // Например, обновить элементы профиля на странице:
                // $('#profileName').text(response.name);
                // Итд. в зависимости от структуры вашего ответа
            },
            error: function(xhr) {
                // Выводим ошибки, если они есть
                let errors = xhr.responseJSON.errors;
                $('#error-container').empty().append('<div class="alert alert-danger"><ul></ul></div>');
                $.each(errors, function(key, value) {
                    $('#error-container .alert ul').append('<li>' + value[0] + '</li>');
                });
                // Оставляем модальное окно открытым
            }
        });
    });
});

//Сообщения
function openMessWindow() {
    document.getElementById("mess-window").style.display = "block";

    const answerIds = Array.from(document.querySelectorAll('.answer-item'))
        .map(item => item.dataset.id);

    // Получаем CSRF токен из формы
    const csrfToken = document.querySelector('#answerForm input[name="_token"]').value;

    fetch('/mark_answer_as_read', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-Token': csrfToken
        },
        body: JSON.stringify({ answers: answerIds })
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

function closeMessWindow() {
    document.getElementById("mess-window").style.display = "none";
    location.reload();
}
//Отправить заявку
function openOrderWindow() {
    document.getElementById("order-window").style.display = "block";
    document.getElementById("stickyHeader").style.display = "none";
    document.body.style.overflow = 'hidden';
}

function closeOrderWindow() {
    document.getElementById("order-window").style.display = "none";
    document.getElementById("stickyHeader").style.display = "";
    document.body.style.overflow = '';
}
//Отзыв
function openFeedbackWindow() {
    document.getElementById("feedback-window").style.display = "block";
    document.getElementById("stickyHeader").style.display = "none";
    document.body.style.overflow = 'hidden';
}

function closeFeedbackWindow() {
    document.getElementById("feedback-window").style.display = "none";
    document.getElementById("stickyHeader").style.display = "";
    document.body.style.overflow = '';
}
//Фото отзыва
function displayFeedbackImagesName() {
    const input = document.getElementById('feedback-images-data');
    const fileNameDisplay = document.getElementById('feedback-images-name');

    if (input.files.length > 0) {
        const fileNames = Array.from(input.files).map(file => file.name);
        fileNameDisplay.textContent = fileNames.join(', ');
    } else {
        fileNameDisplay.textContent = 'Файл не выбран';
    }
}
