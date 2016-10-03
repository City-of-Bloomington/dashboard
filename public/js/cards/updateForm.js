"use strict";
var CARD_FORM = {
    service: null,
    loadMethods: function (e) {
        var select      = e.target,
            options     = '',
            service_id  = select.options[select.selectedIndex].value,
            services    = null,
            method      = document.getElementById('method'),
            m           = '';

        DASHBOARD.ajax(DASHBOARD.BASE_URI + '/services/' + service_id + '?format=json', function (r) {
            CARD_FORM.service = JSON.parse(r.responseText);

            for (m in CARD_FORM.service.methods) { options += '<option>' + m + '</option>'; }
            method.innerHTML     = options;
            method.selectedIndex = 1;

            document.getElementById('responseKey').innerHTML = '';
        });
    },
    loadParameters: function (e) {
        var methodDropdown  = e.target,
            serviceDropdown = document.getElementById('service_id'),
            service_id      = serviceDropdown.options[serviceDropdown.selectedIndex].value,
            method          = methodDropdown .options[methodDropdown .selectedIndex].value,
            url             = document.location,
            search          = document.location.search,
            tmpNode         = document.createElement('div'),
            keyDropdown     = document.getElementById('responseKey'),
            options         = '',
            key             = '';

        // Repopulate the responseKey drop down
        for (key in CARD_FORM.service.methods[method].response) { options += '<option>' + key + '</option>'; }
        keyDropdown.innerHTML     = options;
        keyDropdown.selectedIndex = 0;

        // Ask the server to render the form fields for the chosen method
        url += url.search
            ? ';partial=cards/updateForm.inc'
            : '?service_id=' + service_id + ';method=' + method + ';partial=cards/updateForm.inc';

        DASHBOARD.ajax(url, function (r) {
            tmpNode.innerHTML = r.responseText;
            document.getElementById('parameters').innerHTML = tmpNode.querySelector('#parameters').innerHTML;
        });

    }
};
document.getElementById('service_id').addEventListener('change', CARD_FORM.loadMethods,    false);
document.getElementById('method')    .addEventListener('change', CARD_FORM.loadParameters, false);
