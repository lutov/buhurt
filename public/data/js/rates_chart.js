var randomColorFactor = function() {
    return Math.round(Math.random() * 255);
};
var randomColor = function() {
    return 'rgba(' + randomColorFactor() + ',' + randomColorFactor() + ',' + randomColorFactor() + ',.7)';
};

var barChartData = {

    labels: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
    datasets: [{
        label: 'Выставлено оценок',
        backgroundColor: randomColor(),
        data: chart_rates
    }]

};

window.onload = function() {
    var ctx = $("#chart_rates");
    window.myBar = new Chart(ctx, {
        type: 'bar',
        data: barChartData,
        options: {
            // Elements options apply to all of the options unless overridden in a dataset
            // In this case, we are setting the border of each bar to be 2px wide and green
            elements: {
                rectangle: {
                    borderWidth: 2,
                    borderColor: randomColor(),
                    borderSkipped: 'bottom'
                }
            },
            responsive: true,
            legend: {
                position: 'top'
            },
            title: {
                display: false
            }
        }
    });

};