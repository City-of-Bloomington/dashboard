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

            DASHBOARD.ajax(card.getAttribute('data-queryUrl'), function (r) {
                var data       = JSON.parse(r.responseText),
                    target     = card.getAttribute('data-target'),
                    comparison = card.getAttribute('data-comparison'),
                    status     = 'fail';

                switch (comparison) {
                    case 'gt' : if (data.value >  target) { status = 'pass'; } break;
                    case 'gte': if (data.value >= target) { status = 'pass'; } break;
                    case 'lt' : if (data.value <  target) { status = 'pass'; } break;
                    case 'lte': if (data.value <= target) { status = 'pass'; } break;
                }

                if (data.value) {
                    document.getElementById(id).setAttribute('class', 'card ' + status);
                    //document.getElementById(id).innerHTML += d.value;
                }
            });
        }
    }
}
document.addEventListener('DOMContentLoaded', CARD_LIST.populate, false);