import $ from "jquery";

$(document).ready(function () {
    // Sidebar toggle behavior
    $('#adminSidebarCollapse').on('click', function () {
        $('#adminSidebar, #adminContent').toggleClass('active');
    });
});