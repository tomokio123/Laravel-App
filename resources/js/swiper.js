  // import Swiper JS
  //webpackmix.jsに読み込ませる
  import Swiper from 'swiper';
  // import Swiper styles
  import 'swiper/swiper-bundle.css';
 
 // core version + navigation, pagination modules:
 import SwiperCore, { Navigation, Pagination } from 'swiper/core';
 
 // configure Swiper to use modules
 SwiperCore.use([Navigation, Pagination]);
 
 // init Swiper:
 const swiper = new Swiper('.swiper-container', {
   // Optional parameters
   // direction: 'vertical',今回はいらん
   loop: true,
 
   // If we need pagination
   pagination: {
     el: '.swiper-pagination',
   },
 
   // Navigation arrows
   navigation: {
     nextEl: '.swiper-button-next',
     prevEl: '.swiper-button-prev',
   },
 
   // And if we need scrollbar
   scrollbar: {
     el: '.swiper-scrollbar',
   },
 });