export var swiper = new Swiper(".mySwiper", {
    loop: true,
    spaceBetween: 0,
    slidesPerView: 3,
    watchSlidesProgress: true,
  
    autoplay: {
    delay: 2500,
    },
  
  slidesPerView: 1,
  centeredSlides: false,
  slidesPerGroupSkip: 1,
  grabCursor: true,
  keyboard: {
  enabled: true,
  },
  breakpoints: {
  769: {
    slidesPerView: 4,
    slidesPerGroup: 1,
  },
  },
  /*
  scrollbar: {
  el: ".swiper-scrollbar",
  },*/
  
  navigation: {
  nextEl: ".swiper-button-next",
  prevEl: ".swiper-button-prev",
  },
  pagination: {
  el: ".swiper-pagination",
  clickable: true,
  },
  });
  