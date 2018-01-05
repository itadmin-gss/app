(function($) {
  "use strict"; // Start of use strict
  // Configure tooltips for collapsed side navigation
  $('.navbar-sidenav [data-toggle="tooltip"]').tooltip({
    template: '<div class="tooltip navbar-sidenav-tooltip" role="tooltip"><div class="arrow"></div><div class="tooltip-inner"></div></div>'
  });

  $(".datepicker").datepicker();

  $("#header-quantity-summary").html("<u>("+$("#summary-count-value").val()+") New Requests</u> | ");
  $("#header-quantity-complete").html("<u>("+$("#complete-count-value").val() + ") Complete</u> | ");
  $("#header-quantity-inprocess").html("<u>("+$("#inprocess-count-value").val() + ") In-Process</u> | ");
  $("#header-quantity-rework").html("<u>("+$("#rework-count-value").val() + ") Rework</u> | ");

  $("#gmap-modal").on("shown.bs.modal", function(){
      initMap();
  });

  $(".order-photo-img").on("click", function(){
    var src = $(this).attr("src");
    $("#view_image_div").html("<img src='+src+'>");
    $("#view_image").modal("toggle");
  });

  $("div.dt-toolbar").html($("#page-heading-dt").html());

  // Toggle the side navigation
  $("#sidenavToggler").click(function(e) {
    e.preventDefault();
    $("body").toggleClass("sidenav-toggled");
    $(".navbar-sidenav .nav-link-collapse").addClass("collapsed");
    $(".navbar-sidenav .sidenav-second-level, .navbar-sidenav .sidenav-third-level").removeClass("show");
  });
  // Force the toggled class to be removed when a collapsible nav link is clicked
  $(".navbar-sidenav .nav-link-collapse").click(function(e) {
    e.preventDefault();
    $("body").removeClass("sidenav-toggled");
  });
  // Prevent the content wrapper from scrolling when the fixed side navigation hovered over
  $('body.fixed-nav .navbar-sidenav, body.fixed-nav .sidenav-toggler, body.fixed-nav .navbar-collapse').on('mousewheel DOMMouseScroll', function(e) {
    var e0 = e.originalEvent,
      delta = e0.wheelDelta || -e0.detail;
    this.scrollTop += (delta < 0 ? 1 : -1) * 30;
    e.preventDefault();
  });
  $("#addNewPropertyView").on("click", function(){
    if ($(".add-new-property").hasClass("property-visible")){
      $(".add-new-property").removeClass("property-visible");
      $(".add-new-property").slideUp();
    } else {
      $(".add-new-property").addClass("property-visible");
      $(".add-new-property").slideDown();
    }
  });
  $("#addNewVendorView").on("click", function(){
    if ($(".add-new-vendor").hasClass("vendor-visible")){
      $(".add-new-vendor").removeClass("vendor-visible");
      $(".add-new-vendor").slideUp();
    } else {
      $(".add-new-vendor").addClass("vendor-visible");
      $(".add-new-vendor").slideDown();
    }
  });
  // Scroll to top button appear
  $(document).scroll(function() {
    var scrollDistance = $(this).scrollTop();
    if (scrollDistance > 100) {
      $('.scroll-to-top').fadeIn();
    } else {
      $('.scroll-to-top').fadeOut();
    }
  });
  // Configure tooltips globally
  $('[data-toggle="tooltip"]').tooltip()
  // Smooth scrolling using jQuery easing
  $(document).on('click', 'a.scroll-to-top', function(event) {
    var $anchor = $(this);
    $('html, body').stop().animate({
      scrollTop: ($($anchor.attr('href')).offset().top)
    }, 1000, 'easeInOutExpo');
    event.preventDefault();
  });
})(jQuery); // End of use strict
