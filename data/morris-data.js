$(function() {

    Morris.Area({
        element: 'morris-area-chart',
        data: [{
            period: '2010 Q1',
            SOLICITUDES: 2666,
            RESUELTOS: null,
            PENDIENTES: 2647
        }, {
            period: '2010 Q2',
            SOLICITUDES: 2778,
            RESUELTOS: 2294,
            PENDIENTES: 2441
        }, {
            period: '2010 Q3',
            SOLICITUDES: 4912,
            RESUELTOS: 1969,
            PENDIENTES: 2501
        }, {
            period: '2010 Q4',
            SOLICITUDES: 3767,
            RESUELTOS: 3597,
            PENDIENTES: 5689
        }, {
            period: '2011 Q1',
            SOLICITUDES: 6810,
            RESUELTOS: 1914,
            PENDIENTES: 2293
        }, {
            period: '2011 Q2',
            SOLICITUDES: 5670,
            RESUELTOS: 4293,
            PENDIENTES: 1881
        }, {
            period: '2011 Q3',
            SOLICITUDES: 4820,
            RESUELTOS: 3795,
            PENDIENTES: 1588
        }, {
            period: '2011 Q4',
            SOLICITUDES: 15073,
            RESUELTOS: 5967,
            PENDIENTES: 5175
        }, {
            period: '2012 Q1',
            SOLICITUDES: 10687,
            RESUELTOS: 4460,
            PENDIENTES: 2028
        }, {
            period: '2012 Q2',
            SOLICITUDES: 8432,
            RESUELTOS: 5713,
            PENDIENTES: 1791
        }],
        xkey: 'period',
        ykeys: ['SOLICITUDES', 'RESUELTOS', 'PENDIENTES'],
        labels: ['SOLICITUDES', 'RESUELTOS', 'PENDIENTES'],
        pointSize: 2,
        hideHover: 'auto',
        resize: true
    });

    Morris.Donut({
        element: 'morris-donut-chart',
        data: [{
            label: "Download Sales",
            value: 12
        }, {
            label: "In-Store Sales",
            value: 30
        }, {
            label: "Mail-Order Sales",
            value: 20
        }],
        resize: true
    });

    Morris.Bar({
        element: 'morris-bar-chart',
        data: [{
            y: '2006',
            a: 100,
            b: 90
        }, {
            y: '2007',
            a: 75,
            b: 65
        }, {
            y: '2008',
            a: 50,
            b: 40
        }, {
            y: '2009',
            a: 75,
            b: 65
        }, {
            y: '2010',
            a: 50,
            b: 40
        }, {
            y: '2011',
            a: 75,
            b: 65
        }, {
            y: '2012',
            a: 100,
            b: 90
        }],
        xkey: 'y',
        ykeys: ['a', 'b'],
        labels: ['Series A', 'Series B'],
        hideHover: 'auto',
        resize: true
    });
    
});
