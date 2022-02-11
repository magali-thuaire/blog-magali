import $ from 'jquery';
import form from '../components/form';

$(document).ready(function () {
    form("#commentForm", [collapseForm]);
});

function collapseForm()
{
    $("#collapseExample").removeClass('show');
}