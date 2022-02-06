import $ from "jquery";

$(document).ready(function() {
    let url = new URL(window.location.href);
    let test = ['post', 'contact'];
    let navLinks = $('.navbar-nav').find('.nav-link');
    navLinks.first().addClass('active');

    test.forEach(function(value) {
        if (url.href.includes(value)) {
            navLinks.removeClass('active').attr('href', function (i, val) {
                if (val.includes(value)) {
                    $(this).addClass('active');
                }
            });
        }
    });

    // Find all anchors
    $('nav').find('a.nav-link[href]').each(function(i,a){
        let $a = $(a);
        let href = $a.attr('href');

        // check is anchor href starts with page's URI
        if (href.includes('#')) {
            // remove URI from href
            href = href.substr(href.indexOf('#'));
            // update anchors HREF with new one
            $a.attr('data-bs-target',href);
        }
        // Now refresh scrollspy
        $('[data-spy="scroll"]').each(function (i,spy) {
            $(spy).scrollspy('refresh');
        });
    });

});