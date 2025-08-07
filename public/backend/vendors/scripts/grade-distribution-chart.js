// Grade Distribution Chart for Report Card
document.addEventListener('DOMContentLoaded', function() {
    // Check if the chart element exists
    if (document.querySelector("#chart5")) {
        // Define the specific values for the grade distribution
        var gradeDistributionData = [
            {
                name: 'Outstanding (90-100)',
                data: [4] // Number of subjects with this grade range
            },
            {
                name: 'Very Satisfactory (85-89)',
                data: [2] // Number of subjects with this grade range
            },
            {
                name: 'Satisfactory (80-84)',
                data: [1] // Number of subjects with this grade range
            },
            {
                name: 'Fairly Satisfactory (75-79)',
                data: [1] // Number of subjects with this grade range
            },
            {
                name: 'Did Not Meet Expectations (<75)',
                data: [0] // Number of subjects with this grade range
            }
        ];

        // Chart options
        var options = {
            series: gradeDistributionData,
            chart: {
                type: 'bar',
                height: 200,
                stacked: false,
                toolbar: {
                    show: false
                }
            },
            plotOptions: {
                bar: {
                    horizontal: true,
                    columnWidth: '55%',
                    borderRadius: 5,
                    dataLabels: {
                        position: 'top'
                    }
                }
            },
            dataLabels: {
                enabled: true,
                formatter: function(val) {
                    return val;
                },
                offsetX: 20,
                style: {
                    fontSize: '12px',
                    colors: ['#304758']
                }
            },
            stroke: {
                width: 1,
                colors: ['#fff']
            },
            grid: {
                show: false
            },
            colors: ['#1b00ff', '#00e396', '#feb019', '#ff4560', '#775dd0'],
            xaxis: {
                categories: ['Subjects'],
                labels: {
                    show: false
                },
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                }
            },
            yaxis: {
                labels: {
                    show: false
                }
            },
            tooltip: {
                shared: false,
                y: {
                    formatter: function(val) {
                        return val + " subjects";
                    }
                }
            },
            legend: {
                position: 'right',
                offsetY: 40
            },
            fill: {
                opacity: 1
            }
        };

        // Initialize the chart
        var chart = new ApexCharts(document.querySelector("#chart5"), options);
        chart.render();
    }
});