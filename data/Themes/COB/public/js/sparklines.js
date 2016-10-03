"use strict";
DASHBOARD.drawSparklines = function () {
    var cards   = document.querySelectorAll('.card'),
        len     = cards.length,
        i       = 0,
        data    = {},
        target  = 0,
        date    = '',
        rows    = [],
        card    = {},
        options = {
            axisTitlesPosition: 'none',
            enableInteractivity: false,
            width:120, height:20,
            backgroundColor: 'transparent',
            colors: ['white', 'gray'],
            series: [
                {  },
                { lineWidth: 1, lineDashStyle:[4, 2] }
            ],
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
        data   = JSON.parse(cards[i].getAttribute('data-logEntries'));
        target =   parseInt(cards[i].getAttribute('data-target'), 10);
        rows   = [];
        for (date in data) {
            rows.push([date, data[date], target]);
        }

        card = {};

        card.datatable = new google.visualization.DataTable();
        card.datatable.addColumn('string', 'Log Date');
        card.datatable.addColumn('number', 'value'   );
        card.datatable.addColumn('number', 'target'  );
        card.datatable.addRows(rows);

        card.chart = new google.visualization.LineChart(cards[i].querySelector('.chart'));
        card.chart.draw(card.datatable, options);
        DASHBOARD.cards.push(card);
    }
}

google.charts.load('current', {packages: ['corechart']});
google.charts.setOnLoadCallback(DASHBOARD.drawSparklines);
