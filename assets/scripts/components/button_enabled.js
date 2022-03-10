import $ from "jquery";

export default function () {
    $('button[type=submit]').removeAttr('disabled');
}