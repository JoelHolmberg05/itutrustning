document.addEventListener('DOMContentLoaded', () => {
    const swiper = new Swiper('.swiper', {
        slidesPerView: 4,
        slidesPerGroup: 4,
        spaceBetween: 20,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        breakpoints: {
            1024: {
                slidesPerView: 4
            },
            768: {
                slidesPerView: 2
            },
            480: {
                slidesPerView: 1
            }
        }
    });
});
