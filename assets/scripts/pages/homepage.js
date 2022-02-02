import $ from 'jquery';
import alert from '../components/alert';

$(document).ready(function() {
    //Formulaire de contact : transmission des données
    $("#contactForm").submit(function (e) {

        e.preventDefault();
        let target = this;
        let url = $(target).data('href');
        let formData = $(target).serialize();

        $.ajax({
            method: "POST",
            url: url,
            data: formData,
            dataType: 'json',
            success: function (result) {

                let messageTarget = '.js-form-message';
                let button = 'button[type=submit]';
                alert(messageTarget, true);

                if(result.form.error) {
                    $(target)
                        .find(messageTarget)
                        .addClass('alert-danger')
                        .removeClass('d-none alert-success')
                        .html(result.form.error);
                } else {

                    $(target)
                        .find(messageTarget)
                        .addClass('alert-success')
                        .removeClass('d-none alert-danger')
                        .html(result.form.success);

                    $(button).attr('disabled','disabled');

                    function buttonEnabled() {
                        $(button).removeAttr('disabled');
                    }

                    $(target)[0].reset();
                    alert(messageTarget, false, buttonEnabled);
                }
            }
        });
    });
});