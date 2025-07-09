$('.owl-carousel').owlCarousel({
    items: 1, // Количество отображаемых элементов
    mouseDrag: true, // Включение перетаскивания мышью
    touchDrag: true, // Включение перетаскивания пальцем
    nav: true, // Включить навигацию
    dots: true, // Включить точки навигации
    // Другие опции по вашему усмотрению
});

document.addEventListener('DOMContentLoaded', function() {
    const dropdownMenu = document.querySelector('.dropdown-menu');
    const dropdownContent = document.querySelector('.dropdown-content');

    dropdownMenu.addEventListener('click', function() {
        dropdownContent.style.display = dropdownContent.style.display === 'block' ? 'none' : 'block';
    });

    // Закрытие меню при клике вне его
    window.addEventListener('click', function(event) {
        if (!dropdownMenu.contains(event.target)) {
            dropdownContent.style.display = 'none';
        }
    });
});
