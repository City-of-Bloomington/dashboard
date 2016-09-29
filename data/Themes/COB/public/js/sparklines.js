"use strict";
DASHBOARD.drawSparklines = function () {
    var cards   = document.querySelectorAll('.card'),
        len     = cards.length,
        i       = 0,
        data    = {},
        date    = '',
        rows    = [],
        card    = {},
        options = {
            axisTitlesPosition: 'none',
            enableInteractivity: false,
            width:120, height:20,
            chartArea: {
                width:120, height:20
            },
            legend: { position: 'none' },
            hAxis: {
                textPosition: 'none',
                gridlines: { count: 0 }
            },
            vAxis:  {
                textPosition: 'none',
                gridlines: { count: 0 }
            }
        };

    DASHBOARD.cards = [];
    for (i=0; i<len; i++) {
        data = JSON.parse(cards[i].getAttribute('data-logEntries'));
        rows = [];
        for (date in data) {
            rows.push([date, data[date]]);
        }

        card = {};

        card.datatable = new google.visualization.DataTable();
        card.datatable.addColumn('string', 'Log Date');
        card.datatable.addColumn('number', 'value'   );
        card.datatable.addRows(rows);

        card.chart = new google.visualization.LineChart(cards[i].querySelector('.chart'));
        card.chart.draw(card.datatable, options);
        DASHBOARD.cards.push(card);
    }
}

google.charts.load('current', {packages: ['corechart']});
google.charts.setOnLoadCallback(DASHBOARD.drawSparklines);
