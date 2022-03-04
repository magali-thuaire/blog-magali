import $ from "jquery";
import 'datatables.net-bs5';
import ajaxModal from '../../components/modal/ajax_modal';

$(document).ready(function () {

    // Table des articles
    $('#post-table').DataTable({
        "order": [[1, "desc"]],
        "keys": true,
        "columnDefs": [
        {
            "targets": 4,
            "sortable": false
        },
        ],
        "language": {
            "sEmptyTable": "Aucune donnée disponible dans le tableau",
            "sInfo": "Affichage de l'élément _START_ à _END_ sur _TOTAL_ éléments",
            "sInfoEmpty": "Affichage de l'élément 0 à 0 sur 0 élément",
            "sInfoFiltered": "(filtré à partir de _MAX_ éléments au total)",
            "sInfoPostFix": "",
            "sInfoThousands": ",",
            "sLengthMenu": "Afficher _MENU_ éléments",
            "sLoadingRecords": "Chargement...",
            "sProcessing": "Traitement...",
            "sSearch": "Rechercher :",
            "sZeroRecords": "Aucun élément correspondant trouvé",
            "oPaginate": {
                "sFirst": "Premier",
                "sLast": "Dernier",
                "sNext": "Suivant",
                "sPrevious": "Précédent"
            },
            "oAria": {
                "sSortAscending": ": activer pour trier la colonne par ordre croissant",
                "sSortDescending": ": activer pour trier la colonne par ordre décroissant"
            },
            "select": {
                "rows": {
                    "_": "%d lignes sélectionnées",
                    "0": "Aucune ligne sélectionnée",
                    "1": "1 ligne sélectionnée"
                }
            }
        }
    });

    $('#post-table tbody').on('click', '.js-post-delete', function () {
        let target = this;
        // Appel AJAX et intégration d'une modale
        ajaxModal(target, 'post-modal');
    });

    $('#post-published').on('click', function () {
        let target = this;
        let isPublished = $(target).is(':checked');

        if (isPublished) {
            $('.post-published.bg-success').removeClass('d-none');
            $('.post-published.bg-warning').addClass('d-none');
        } else {
            $('.post-published.bg-warning').removeClass('d-none');
            $('.post-published.bg-success').addClass('d-none');
        }
    });
});