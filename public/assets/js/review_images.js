let curIndex = 0; // Индекс текущего изображения
const headerElement = document.querySelector('.header1');
const reviewPhotos = document.querySelector('.review-photos');
const modalReview = document.getElementById('modalReview');
const modalImage = document.getElementById('modalImageReview'); // Используйте правильный идентификатор

// Функция для смены изображения
window.changeReviewImage = function(direction) {
    curIndex += direction; // Изменяем индекс

    // Проверяем границы массива
    if (curIndex < 0) {
        curIndex = 0; // Нельзя уменьшать ниже 0
    } else if (curIndex >= reviewPhotos.children.length) {
        curIndex = reviewPhotos.children.length - 1; // Нельзя увеличивать больше края
    }

    const currentPhoto = reviewPhotos.children[curIndex]; // Получаем текущее изображение
    modalImage.src = currentPhoto.src; // Обновляем изображение в модальном окне
    console.log("Displaying image:", modalImage.src); // Отладка: выводим новое изображение
    updateNavigationButtons(); // Обновляем навигационные кнопки
}

// Функция для открытия модального окна
window.openReviewModal = function(index) {
    console.log("Opening modal for feedback image at index: ", index); // Отладка: выводим индекс

    // Проверяем, что индекс находится в пределах допустимого диапазона
    if (index < 0 || index >= reviewPhotos.children.length) {
        console.error("Invalid index: " + index);
        alert("Image not found.");
        return;
    }

    curIndex = index; // Устанавливаем текущий индекс
    const currentPhoto = reviewPhotos.children[curIndex]; // Получаем текущее изображение
    console.log("Current photo found: ", currentPhoto);

    if (currentPhoto) {
        modalImage.src = currentPhoto.src; // Устанавливаем источник изображения
        console.log("Current photo src: ", modalImage.src); // URL изображения
    } else {
        console.error("Current photo not found!");
        alert("Image not found.");
        return;
    }

    modalReview.style.display = "block"; // Показываем модальное окно
    document.body.style.overflow = 'hidden'; // Блокируем прокрутку страницы
    updateNavigationButtons(); // Обновляем навигационные кнопки
    headerElement.style.display = 'none'; // Скрыть заголовок
}

// Функция для обновления кнопок навигации (предыдущая/следующая)
function updateNavigationButtons() {
    const leftButton = document.querySelector('.modal .prev');
    const rightButton = document.querySelector('.modal .next');

    // Отладка: выводим количество изображений
    console.log("Total images: ", reviewPhotos.children.length);

    // Скрыть кнопки, если изображений меньше или равно 1
    if (reviewPhotos.children.length <= 1) {
        leftButton.style.display = 'none';
        rightButton.style.display = 'none';
        return;
    }

    // Устанавливаем видимость кнопок в зависимости от текущего индекса
    leftButton.style.display = curIndex > 0 ? 'block' : 'none'; // Скрыть кнопку "назад", если текущий индекс 0
    rightButton.style.display = curIndex < reviewPhotos.children.length - 1 ? 'block' : 'none'; // Скрыть кнопку "вперед", если индекс на последнем изображении

    // Отладка: выводим состояния кнопок
    console.log("Left button visible: ", leftButton.style.display === 'block');
    console.log("Right button visible: ", rightButton.style.display === 'block');
}

// Функция закрытия модального окна
window.closeReviewModal = function() {
    console.log("Closing modal"); // Отладка
    modalReview.style.display = "none"; // Закрываем модальное окно
    headerElement.style.display = ''; // Показываем заголовок
    document.body.style.overflow = ''; // Разрешаем прокрутку страницы
    headerElement.style.display = ''; // Показываем заголовок
}

// Закрытие модального окна при клике на фон
modalReview.addEventListener('click', function(e) {
    if (e.target === this) {
        closeReviewModal(); // Закрываем модальное окно, если кликнули на фон
    }
});

// Инициализация прокрутки
document.addEventListener("DOMContentLoaded", function() {
    const scrollAmount = 110; // Количество пикселей для прокрутки

    // Функция для прокрутки влево
    window.scrollLeftPhotos = function() {
        reviewPhotos.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
    }

    // Функция для прокрутки вправо
    window.scrollRightPhotos = function() {
        reviewPhotos.scrollBy({ left: scrollAmount, behavior: 'smooth' });
    }
});
