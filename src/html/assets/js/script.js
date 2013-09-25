$(document).ready(function() {
  $('.product-card').mf_EqualHeight();
  $('.product-card .col').mf_EqualHeight();

  $('.slider').flexslider({
    // animation: "fade",
    animation: "slide",
    animationSpeed: 1000,
    slideshowSpeed: 7000,
    pauseOnHover: false,
  });
});