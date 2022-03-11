import $ from "jquery";

export default function (target) {

    $('#' + target).on('click', function () {
        let that = this;
        let checked = $(that).is(':checked');
        if (checked) {
            $('.' + target + '.bg-success').removeClass('d-none');
            $('.' + target + '.bg-warning').addClass('d-none');
        } else {
            $('.' + target + '.bg-warning').removeClass('d-none');
            $('.' + target + '.bg-success').addClass('d-none');
        }
    });
}

