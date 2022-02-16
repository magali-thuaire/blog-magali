import $ from "jquery";

import { Tooltip } from 'bootstrap';

const tooltipTriggerList = [].slice.call(
    $('[data-bs-toggle="tooltip"]')
)

tooltipTriggerList.map(
    function (tooltipTriggerEl) {
        return new Tooltip(tooltipTriggerEl)
    }
)