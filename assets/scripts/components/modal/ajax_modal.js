import $ from "jquery";

import empty_modal from "./empty_modal";

// Appel AJAX et intégration d'une modale
export default function (target, modal) {
    // Récupère l'url depuis la propriété "data-href" de la balise html a
    let url = $(target).data('href');
    // Appel ajax vers l'action symfony qui nous renvoie la vue
    return $.ajax({
        method: 'GET',
        url: url
    }).done(function (data) {
        // Injecte le html dans la modale
        $('#' + modal).html(data);
        // Récupère l'id de la modale
        let modal_id = $('.modal').attr('id');
        // Ouvre la modale
        let postModal = new bootstrap.Modal($('#' + modal_id));
        postModal.show();
        empty_modal(modal);
    })
}