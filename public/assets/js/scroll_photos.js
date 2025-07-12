document.addEventListener("DOMContentLoaded", function() {
    const scrollAmount = 110; // Количество пикселей для прокрутки
    const jobPhotos = document.querySelector('.job-photos');
    const modal = document.getElementById('modal');
    const modalImage = document.getElementById('modalImage');
    const images = Array.from(document.querySelectorAll('.job-photos img')); // Получаем все изображения
    const headerElement = document.querySelector('.header1');
    const photoTitle = document.getElementById('photoTitle');
    const photoDescription = document.getElementById('photoDescription');
    let currentIndex = 0; // Индекс текущего изображения

    window.scrollLeftPhotos = function() {
        jobPhotos.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
    }

    window.scrollRightPhotos = function() {
        jobPhotos.scrollBy({ left: scrollAmount, behavior: 'smooth' });
    }

    window.openModal = function(index, title, description) {
              currentIndex = index; // Устанавливаем текущий индекс
        modal.style.display = "block"; // Показываем модальное окно
        modalImage.src = images[index].src; // Устанавливаем источник изображения
        headerElement.style.display = 'none'; // Скрыть заголовок
        photoTitle.innerText = title; // Обновляем заголовок
        photoDescription.innerText = description; // Обновляем описание
        document.body.style.overflow = 'hidden';
    }

    window.closeModal = function() {
        modal.style.display = "none"; // Закрываем модальное окно
        headerElement.style.display = ''; // Показываем заголовок
        document.body.style.overflow = '';
    }

    window.changeImage = function(direction) {
        currentIndex += direction; // Увеличиваем или уменьшаем индекс
        if (currentIndex < 0) {
            currentIndex = images.length - 1; // Зацикливаем влево
        } else if (currentIndex >= images.length) {
            currentIndex = 0; // Зацикливаем вправо
        }
        modalImage.src = images[currentIndex].src; // Обновляем изображение

        // Обновляем информацию по текущему изображению
        const currentPhoto = images[currentIndex];
        // Получение данных заголовка и описания из атрибутов data
        const title = currentPhoto.getAttribute('data-title');
        const description = currentPhoto.getAttribute('data-description');

        photoTitle.innerText = title; // Заголовок
        photoDescription.innerText = description; // Описание
    }

    // Закрытие окна только при клике на фон
    modal.addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });

    function updateSliderButtons() {
        const container = document.querySelector('.photo-slider');
        const photos = document.querySelector('.job-photos');
        const leftButton = document.querySelector('.slider-button.left');
        const rightButton = document.querySelector('.slider-button.right');

        const containerWidth = container.offsetWidth;
        const photosWidth = photos.scrollWidth;

        if (photosWidth > containerWidth) {
            leftButton.classList.add('show');
            rightButton.classList.add('show');
        } else {
            leftButton.classList.remove('show');
            rightButton.classList.remove('show');
        }
    }

// Звоните эту функцию после загрузки изображений
    window.onload = updateSliderButtons;
    window.onresize = updateSliderButtons; // Обновляем кнопки при изменении размера окна
});
