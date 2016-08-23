var CARD_LIST = {
    cards: document.querySelectorAll('article.card'),
    populate: function () {
        var len = CARD_LIST.cards.length,
            i   = 0,
            id  = '',
            card = {};

        for (i=0; i<len; i++) {
            card = CARD_LIST.cards[i];
            id   = card.getAttribute('id');

            (function (x) {
                DASHBOARD.ajax(card.getAttribute('data-queryUrl'), function (r) {
                    var element    = document.getElementById(x),
                        data       = JSON.parse(r.responseText),
                        value      = parseInt(data.value),
                        target     = parseInt(element.getAttribute('data-target')),
                        comparison = element.getAttribute('data-comparison'),
                        status     = 'fail';

                    if (data.value) {
                        switch (comparison) {
                            case 'gt' : if (value >  target) { status = 'pass'; } break;
                            case 'gte': if (value >= target) { status = 'pass'; } break;
                            case 'lt' : if (value <  target) { status = 'pass'; } break;
                            case 'lte': if (value <= target) { status = 'pass'; } break;
                        }
                        element.setAttribute('class', 'card ' + status);
                        element.querySelector('.value').innerHTML = value;
                    }
                });
            })(id);
        }
    }
}
document.addEventListener('DOMContentLoaded', CARD_LIST.populate, false);