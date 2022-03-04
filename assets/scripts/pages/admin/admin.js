import $ from "jquery";

$(document).ready(function () {

    $(".js-alert").fadeTo(4000, 0, function () {
        $(".js-alert").addClass('d-none');
    });

    // Sidebar toggle behavior
    $('#adminSidebarCollapse').on('click', function () {
        $('#adminSidebar, #adminContent').toggleClass('active');
    });

    let url = new URL(window.location.href);
    let $navLinks = $('#adminSidebar').find('.nav-link');
    let excludedPages =  [
        'p=dashboard',
        'p=post-update',
        'p=post-new',
        'p=comment',
        'p=user',
    ];
    excludedPages.forEach((element) => {
        $navLinks.attr('href', function (k,v) {
            if (v.includes(element) && url.href.includes(element)) {
                $(this).addClass('active');
            }
            if (url.href.includes('p=post-update')) {
                let test = $('.nav-link[href*="p=dashboard"]');
                test.addClass('active');
            }
        });
    });
});