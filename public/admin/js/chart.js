Highcharts.chart('container', {
    chart: {
        type: 'spline'
    },
    title: {
        text: 'Snow depth at Vikjafjellet, Norway'
    },
    subtitle: {
        text: 'Irregular time data in Highcharts JS'
    },
    xAxis: {
        type: 'datetime',
        dateTimeLabelFormats: { // don't display the dummy year
            month: '%e. %b',
            year: '%b'
        },
        title: {
            text: 'Date'
        }
    },
    credits: {
        enabled: false
    },
    yAxis: {
        title: {
            text: 'Snow depth (m)'
        },
        min: 0
    },
    tooltip: {
        headerFormat: '<b>{series.name}</b><br>',
        pointFormat: '{point.x:%e. %b}: {point.y:.2f} m'
    },

    plotOptions: {
        spline: {
            marker: {
                enabled: true
            }
        }
    },

    series: [{
        name: 'Winter 2012-2013',
        // Define the data points. All series have a dummy year
        // of 1970/71 in order to be compared on the same x axis. Note
        // that in JavaScript, months start at 0 for January, 1 for February etc.
        data: [
            [Date.UTC(1970, 9, 21), 0],
            [Date.UTC(1970, 10, 4), 0.28],
            [Date.UTC(1970, 10, 9), 0.25],
            [Date.UTC(1970, 10, 27), 0.2],
            [Date.UTC(1970, 11, 2), 0.28],
            [Date.UTC(1970, 11, 26), 0.28],
            [Date.UTC(1970, 11, 29), 0.47],
            [Date.UTC(1971, 0, 11), 0.79],
            [Date.UTC(1971, 0, 26), 0.72],
            [Date.UTC(1971, 1, 3), 1.02],
            [Date.UTC(1971, 1, 11), 1.12],
            [Date.UTC(1971, 1, 25), 1.2],
            [Date.UTC(1971, 2, 11), 1.18],
            [Date.UTC(1971, 3, 11), 1.19],
            [Date.UTC(1971, 4, 1), 1.85],
            [Date.UTC(1971, 4, 5), 2.22],
            [Date.UTC(1971, 4, 19), 1.15],
            [Date.UTC(1971, 5, 3), 0]
        ]
    }, {
        name: 'Winter 2013-2014',
        data: [
            [Date.UTC(1970, 9, 29), 0],
            [Date.UTC(1970, 10, 9), 0.4],
            [Date.UTC(1970, 11, 1), 0.25],
            [Date.UTC(1971, 0, 1), 1.66],
            [Date.UTC(1971, 0, 10), 1.8],
            [Date.UTC(1971, 1, 19), 1.76],
            [Date.UTC(1971, 2, 25), 2.62],
            [Date.UTC(1971, 3, 19), 2.41],
            [Date.UTC(1971, 3, 30), 2.05],
            [Date.UTC(1971, 4, 14), 1.7],
            [Date.UTC(1971, 4, 24), 1.1],
            [Date.UTC(1971, 5, 10), 0]
        ]
    }, {
        name: 'Winter 2014-2015',
        data: [
            [Date.UTC(1970, 10, 25), 0],
            [Date.UTC(1970, 11, 6), 0.25],
            [Date.UTC(1970, 11, 20), 1.41],
            [Date.UTC(1970, 11, 25), 1.64],
            [Date.UTC(1971, 0, 4), 1.6],
            [Date.UTC(1971, 0, 17), 2.55],
            [Date.UTC(1971, 0, 24), 2.62],
            [Date.UTC(1971, 1, 4), 2.5],
            [Date.UTC(1971, 1, 14), 2.42],
            [Date.UTC(1971, 2, 6), 2.74],
            [Date.UTC(1971, 2, 14), 2.62],
            [Date.UTC(1971, 2, 24), 2.6],
            [Date.UTC(1971, 3, 2), 2.81],
            [Date.UTC(1971, 3, 12), 2.63],
            [Date.UTC(1971, 3, 28), 2.77],
            [Date.UTC(1971, 4, 5), 2.68],
            [Date.UTC(1971, 4, 10), 2.56],
            [Date.UTC(1971, 4, 15), 2.39],
            [Date.UTC(1971, 4, 20), 2.3],
            [Date.UTC(1971, 5, 5), 2],
            [Date.UTC(1971, 5, 10), 1.85],
            [Date.UTC(1971, 5, 15), 1.49],
            [Date.UTC(1971, 5, 23), 1.08]
        ]
    }]
});
















//Product Js

Highcharts.chart('product', {
    chart: {
        type: 'spline'
    },
    title: {
        text: 'Snow depth at Vikjafjellet, Norway'
    },
    subtitle: {
        text: 'Irregular time data in Highcharts JS'
    },
    xAxis: {
        type: 'datetime',
        dateTimeLabelFormats: { // don't display the dummy year
            month: '%e. %b',
            year: '%b'
        },
        title: {
            text: 'Date'
        }
    },
    credits: {
        enabled: false
    },
    yAxis: {
        title: {
            text: 'Snow depth (m)'
        },
        min: 0
    },
    tooltip: {
        headerFormat: '<b>{series.name}</b><br>',
        pointFormat: '{point.x:%e. %b}: {point.y:.2f} m'
    },

    plotOptions: {
        spline: {
            marker: {
                enabled: true
            }
        }
    },

    series: [{
        name: 'Winter 2012-2013',
        // Define the data points. All series have a dummy year
        // of 1970/71 in order to be compared on the same x axis. Note
        // that in JavaScript, months start at 0 for January, 1 for February etc.
        data: [
            [Date.UTC(1970, 9, 21), 0],
            [Date.UTC(1970, 10, 4), 0.28],
            [Date.UTC(1970, 10, 9), 0.25],
            [Date.UTC(1970, 10, 27), 0.2],
            [Date.UTC(1970, 11, 2), 0.28],
            [Date.UTC(1970, 11, 26), 0.28],
            [Date.UTC(1970, 11, 29), 0.47],
            [Date.UTC(1971, 0, 11), 0.79],
            [Date.UTC(1971, 0, 26), 0.72],
            [Date.UTC(1971, 1, 3), 1.02],
            [Date.UTC(1971, 1, 11), 1.12],
            [Date.UTC(1971, 1, 25), 1.2],
            [Date.UTC(1971, 2, 11), 1.18],
            [Date.UTC(1971, 3, 11), 1.19],
            [Date.UTC(1971, 4, 1), 1.85],
            [Date.UTC(1971, 4, 5), 2.22],
            [Date.UTC(1971, 4, 19), 1.15],
            [Date.UTC(1971, 5, 3), 0]
        ]
    }, {
        name: 'Winter 2013-2014',
        data: [
            [Date.UTC(1970, 9, 29), 0],
            [Date.UTC(1970, 10, 9), 0.4],
            [Date.UTC(1970, 11, 1), 0.25],
            [Date.UTC(1971, 0, 1), 1.66],
            [Date.UTC(1971, 0, 10), 1.8],
            [Date.UTC(1971, 1, 19), 1.76],
            [Date.UTC(1971, 2, 25), 2.62],
            [Date.UTC(1971, 3, 19), 2.41],
            [Date.UTC(1971, 3, 30), 2.05],
            [Date.UTC(1971, 4, 14), 1.7],
            [Date.UTC(1971, 4, 24), 1.1],
            [Date.UTC(1971, 5, 10), 0]
        ]
    }, {
        name: 'Winter 2014-2015',
        data: [
            [Date.UTC(1970, 10, 25), 0],
            [Date.UTC(1970, 11, 6), 0.25],
            [Date.UTC(1970, 11, 20), 1.41],
            [Date.UTC(1970, 11, 25), 1.64],
            [Date.UTC(1971, 0, 4), 1.6],
            [Date.UTC(1971, 0, 17), 2.55],
            [Date.UTC(1971, 0, 24), 2.62],
            [Date.UTC(1971, 1, 4), 2.5],
            [Date.UTC(1971, 1, 14), 2.42],
            [Date.UTC(1971, 2, 6), 2.74],
            [Date.UTC(1971, 2, 14), 2.62],
            [Date.UTC(1971, 2, 24), 2.6],
            [Date.UTC(1971, 3, 2), 2.81],
            [Date.UTC(1971, 3, 12), 2.63],
            [Date.UTC(1971, 3, 28), 2.77],
            [Date.UTC(1971, 4, 5), 2.68],
            [Date.UTC(1971, 4, 10), 2.56],
            [Date.UTC(1971, 4, 15), 2.39],
            [Date.UTC(1971, 4, 20), 2.3],
            [Date.UTC(1971, 5, 5), 2],
            [Date.UTC(1971, 5, 10), 1.85],
            [Date.UTC(1971, 5, 15), 1.49],
            [Date.UTC(1971, 5, 23), 1.08]
        ]
    }]
});







//Affiliates js


// Create the chart
Highcharts.chart('affiliates', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'Browser market shares. January, 2015 to May, 2015'
    },
    credits: {
        enabled: false
    },
    subtitle: {
        text: 'Click the columns to view versions. Source: <a href="http://netmarketshare.com">netmarketshare.com</a>.'
    },
    xAxis: {
        type: 'category'
    },
    yAxis: {
        title: {
            text: 'Total percent market share'
        }

    },
    legend: {
        enabled: false
    },
    plotOptions: {
        series: {
            borderWidth: 0,
            dataLabels: {
                enabled: true,
                format: '{point.y:.1f}%'
            }
        }
    },

    tooltip: {
        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
        pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}%</b> of total<br/>'
    },

    series: [{
        name: 'Brands',
        colorByPoint: true,
        data: [{
            name: 'Microsoft',
            y: 56.33,
            drilldown: 'Microsoft'
        }, {
            name: 'Chrome',
            y: 24.03,
            drilldown: 'Chrome'
        }, {
            name: 'Firefox',
            y: 10.38,
            drilldown: 'Firefox'
        }, {
            name: 'Safari',
            y: 4.77,
            drilldown: 'Safari'
        }, {
            name: 'Opera',
            y: 0.91,
            drilldown: 'Opera'
        }, {
            name: 'Undetectable',
            y: 0.2,
            drilldown: null
        }]
    }],
    drilldown: {
        series: [{
            name: 'Microsoft Internet Explorer',
            id: 'Microsoft Internet Explorer',
            data: [
                [
                    'v11.0',
                    24.13
                ],
                [
                    'v8.0',
                    17.2
                ],
                [
                    'v9.0',
                    8.11
                ],
                [
                    'v10.0',
                    5.33
                ],
                [
                    'v6.0',
                    1.06
                ],
                [
                    'v7.0',
                    0.5
                ]
            ]
        }, {
            name: 'Chrome',
            id: 'Chrome',
            data: [
                [
                    'v40.0',
                    5
                ],
                [
                    'v41.0',
                    4.32
                ],
                [
                    'v42.0',
                    3.68
                ],
                [
                    'v39.0',
                    2.96
                ],
                [
                    'v36.0',
                    2.53
                ],
                [
                    'v43.0',
                    1.45
                ],
                [
                    'v31.0',
                    1.24
                ],
                [
                    'v35.0',
                    0.85
                ],
                [
                    'v38.0',
                    0.6
                ],
                [
                    'v32.0',
                    0.55
                ],
                [
                    'v37.0',
                    0.38
                ],
                [
                    'v33.0',
                    0.19
                ],
                [
                    'v34.0',
                    0.14
                ],
                [
                    'v30.0',
                    0.14
                ]
            ]
        }, {
            name: 'Firefox',
            id: 'Firefox',
            data: [
                [
                    'v35',
                    2.76
                ],
                [
                    'v36',
                    2.32
                ],
                [
                    'v37',
                    2.31
                ],
                [
                    'v34',
                    1.27
                ],
                [
                    'v38',
                    1.02
                ],
                [
                    'v31',
                    0.33
                ],
                [
                    'v33',
                    0.22
                ],
                [
                    'v32',
                    0.15
                ]
            ]
        }, {
            name: 'Safari',
            id: 'Safari',
            data: [
                [
                    'v8.0',
                    2.56
                ],
                [
                    'v7.1',
                    0.77
                ],
                [
                    'v5.1',
                    0.42
                ],
                [
                    'v5.0',
                    0.3
                ],
                [
                    'v6.1',
                    0.29
                ],
                [
                    'v7.0',
                    0.26
                ],
                [
                    'v6.2',
                    0.17
                ]
            ]
        }, {
            name: 'Opera',
            id: 'Opera',
            data: [
                [
                    'v12.x',
                    0.34
                ],
                [
                    'v28',
                    0.24
                ],
                [
                    'v27',
                    0.17
                ],
                [
                    'v29',
                    0.16
                ]
            ]
        }]
    }
});