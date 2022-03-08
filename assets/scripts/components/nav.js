import $ from "jquery";

$(document).ready(function () {

    let url = new URL(window.location.href);
    let $navbarNav = $('.navbar-nav');
    let $navLinks = $navbarNav.find('.nav-link');
    $navLinks.first().removeClass('active');
    let $dropdown = $navbarNav.find('.nav-link.dropdown-toggle');
    let navLinksWithoutAnchor = [];

    let excludedPages =  [
        '/post',
        '/login',
        '/register',
        '/forgot-password',
        '/dashboard',
        '/post-update',
        '/post-new',
        '/admin',
    ];

    // Find all anchors
    $('nav').find('a.nav-link[href]').each(function (i,a) {

        let $a = $(a);
        let href = $a.attr('href');

        // vérifie que le href contient une ancre
        if (href.includes('#')) {
            // recupère l'ancre
            href = href.substr(href.indexOf('#'));
            // créer un attribut data-bs-target avec l'ancre
            $a.attr('data-bs-target',href);
        } else {
            navLinksWithoutAnchor.push($a);
            // ajout une ancre vide
            $a.attr('href',href + '#');
            $a.attr('data-bs-target','#');
        }
    });

    // Initialisation du scrollspy
    if (!excludedPages.some((el) => url.href.includes(el))) {
        new bootstrap.ScrollSpy(document.body, {
            target: '#navbarNav'
        });
    } else {
        excludedPages.forEach((element) => {
            $navLinks.attr('href', function (k,v) {
                if (v.includes(element) && url.href.includes(element)) {
                    $(this).addClass('active');
                }
            });
        });
    }
    // supprimer les ancres vides
    navLinksWithoutAnchor.forEach((element) => {
        let deleteAnchor = element.attr('href').replace('#', '');
        element.attr('href', deleteAnchor);
    });

    // mettre la navbar en inactive dans l'admin
    if (url.href.includes('admin')) {
        $navLinks.removeClass('active');
    }

    // mettre le menu utilisateur en actif
    if (url.href.includes('login') || url.href.includes('register') || url.href.includes('forgot-password') || url.href.includes('admin')) {
        $dropdown.addClass('active');
    }
});