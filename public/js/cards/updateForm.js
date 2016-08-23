"use strict";
var CARD_FORM = {
    services: null,
    loadMethods: function (e) {
        var select   = e.target,
            options  = '',
            service  = select.options[select.selectedIndex].value,
            services = null,
            method   = document.getElementById('method'),
            m        = '';

        DASHBOARD.ajax(DASHBOARD.BASE_URI + '/services?format=json', function (r) {
            CARD_FORM.services = JSON.parse(r.responseText);
            for (m in CARD_FORM.services[service].methods) {
                options += '<option>' + m + '</option>';
            }
            method.innerHTML = options;
            method.selectedIndex = 1;
        });
    },
    loadParameters: function (e) {
        var methodDropdown  = e.target,
            serviceDropdown = document.getElementById('service'),
            service         = serviceDropdown.options[serviceDropdown.selectedIndex].value,
            method          = methodDropdown .options[methodDropdown .selectedIndex].value,
            url             = document.location,
            search          = document.location.search,
            tmpNode         = document.createElement('div'),
            param           = '';

        url += url.search
            ? ';partial=cards/updateForm.inc'
            : '?service=' + service + ';method=' + method + ';partial=cards/updateForm.inc';

        DASHBOARD.ajax(url, function (r) {
            tmpNode.innerHTML = r.responseText;
            document.getElementById('parameters').innerHTML = tmpNode.querySelector('#parameters').innerHTML;
        });

    }
};
document.getElementById('service').addEventListener('change', CARD_FORM.loadMethods,    false);
document.getElementById('method') .addEventListener('change', CARD_FORM.loadParameters, false);
