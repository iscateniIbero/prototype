moment.locale('es');
var dataSet = [];
var labelsSaving = [];
var datasets;
var dataLavels;
var numberMonth;
var myChartSaving;
savingData();
function savingData() {
    var url  = '../general/functions/helpers/saving.php';
    fetch(url)
        .then( r => r.json() )
        .then( info => showSavingData(info) )
        .catch( error => console.log(error) );
}
function showSavingData(info) { 
    let savingData = info.Saving;
    let lengthData = 0;
    if (savingData.length >= 12) {
        lengthData = 12;
    } else {
        lengthData = savingData.length;
    }
    numberMonth = lengthData -1;
    for(let i = 0; i < lengthData; i++) {
        datasets = savingData[i]['Balance'];
        dataSet.push(datasets);
        if (numberMonth == 0) {
            dataLavels = moment().format('MMMM YYYY').charAt(0).toUpperCase() + moment().format('MMMM YYYY').slice(1);
        } else {
            dataLavels = moment().subtract(numberMonth , 'months').format('MMMM YYYY').charAt(0).toUpperCase() + moment().subtract(numberMonth, 'months').format('MMMM YYYY').slice(1);
        }
        labelsSaving.push(dataLavels);
        numberMonth --;
    }
    showGraph(dataSet, labelsSaving);
}

function showGraph(dataSet, labelsSaving) {
    const graphSaving = document.querySelector("#showGraphSaving");
    let dataSaving = {
        data: dataSet,
        backgroundColor: 'rgba(14, 203, 129, 0.2)', // Color de fondo
        borderColor: 'rgb(14, 203, 129)', // Color del borde
        borderWidth: 1,// Ancho del borde
        spanGaps: false,
        tension: 0.4,
        pointStyle: 'circle',
        pointRadius: 5,
        pointHoverRadius: 10,
        fill: 'start',
    };
    if (myChartSaving) {
        myChartSaving.destroy();
    }
    myChartSaving = new Chart(graphSaving, {
        type: 'line',// Tipo de gráfica
        data: {
            labels: labelsSaving,
            datasets: [
                dataSaving,
            ]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                }
                
            },
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                display: false,
                }
            }
        }
    });
}
