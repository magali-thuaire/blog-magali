import $ from "jquery";

$(document).ready(function() {

    // Scroll top
    // var target = $('#header').offset().top;
    var target = 100;

    $('#scroll-top').hide();

    $(window).scroll(function() {
        var scrollTop = $(window).scrollTop();
        if (scrollTop > target) {
            $('#scroll-top').fadeIn();
        } else {
            $('#scroll-top').fadeOut();
        }
    });

});