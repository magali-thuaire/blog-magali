import $ from 'jquery';
import form from '../components/form';
import collapse from "../components/collapse";

$(document).ready(function () {
    form("#commentForm", [collapse("#collapseExample")]);
});