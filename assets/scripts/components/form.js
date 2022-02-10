import $ from "jquery";
import alert from "./alert";

export default function(target, callbackForm = []) {
    //Formulaire de contact : transmission des données
    $(target).submit(function (e) {

        e.preventDefault();
        let url = $(target).data('href');
        let formData = $(target).serialize();
        let messageTarget = '.js-form-message';
        let button = 'button[type=submit]';
        let alertCallback = [buttonEnabled];

        if (callbackForm.length !== 0) {
            alertCallback = alertCallback.concat(callbackForm);
        }

        function buttonEnabled() {
            $(button).removeAttr('disabled');
        }

        $.ajax({
            method: "POST",
            url: url,
            data: formData,
            dataType: 'json',
            success: function (result) {

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

                    $(target)[0].reset();
                    alert(messageTarget, false, alertCallback);
                }
            }
        });
    });
}