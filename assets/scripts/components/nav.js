import $ from "jquery";

$(document).ready(function() {

    let url = new URL(window.location.href);
    let nav = ['post'];
    let navLinks = $('.navbar-nav').find('.nav-link');
    navLinks.first().removeClass('active');
    let navLinksWithoutAnchor = [];

    nav.forEach(function(value) {
        if (url.href.includes(value)) {
            navLinks.removeClass('active').attr('href', function (i, val) {
                if (val.includes(value)) {
                    $(this).addClass('active');
                }
            });
        } else {
            navLinks.first().addClass('active');
        }
    });

    // Find all anchors
    $('nav').find('a.nav-link[href]').each(function(i,a){

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
    if(!url.href.includes('p=post')) {
        new bootstrap.ScrollSpy(document.body, {
                target: '#navbarNav'
        });
        // supprimer les ancres vides
        navLinksWithoutAnchor.forEach((element) => {
                let deleteAnchor = element.attr('href').replace('#', '');
                element.attr('href', deleteAnchor);
            })
    }
});