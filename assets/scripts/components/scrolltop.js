import $ from "jquery";

$(document).ready(function () {

    // Scroll top
    let target = 100;

    $('#scroll-top').hide();

    $(window).scroll(function () {
        let scrollTop = $(window).scrollTop();
        if (scrollTop > target) {
            $('#scroll-top').fadeIn();
        } else {
            $('#scroll-top').fadeOut();
        }
    });

});