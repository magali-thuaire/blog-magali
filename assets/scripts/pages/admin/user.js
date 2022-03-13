import $ from "jquery";
import 'datatables.net-bs5';
import ajaxModal from '../../components/modal/ajax_modal';
import check from '../../components/check';

$(document).ready(function () {

    // Table des utilisateurs
    $('#user-table').DataTable({
        "order": [[4, "asc"]],
        "keys": true,
        "columnDefs": [
        {
            "targets": 5,
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

    $('#user-table tbody').on('click', '.js-user-validate, .js-user-delete', function () {
        let target = this;
        // Appel AJAX et intégration d'une modale
        ajaxModal(target, 'user-modal');
    });

    check('user-validated');

});