moment.locale('es');
var dataSetInvestments = [];
var myChartInvestments;
investmentsData();
function investmentsData() {
    var url  = '../general/functions/helpers/investments.php';
    fetch(url)
        .then( r => r.json() )
        .then( info => showInvesmentData(info) )
        .catch( error => console.log(error) );
}
function showInvesmentData(info) { 
    let investmentsData = info.investments;
    let totalCrypto = investmentsData.totalCrypto;
    let totalStock = investmentsData.totalStock;
    let totalFund = investmentsData.totalFund;
    //let total = investmentsData.totalInvested;
    //percentCryto = (totalCrypto / total) * 100;
    //percentStock = (totalStock / total) * 100;
    let dataSetInvestments = [totalCrypto, totalStock, totalFund];
    labelsInvestments = investmentsData.type;
    showGraphInvestments(dataSetInvestments, labelsInvestments);
}

function showGraphInvestments(dataSetInvestments, labelsInvestments) {
    var graphInvestments = document.querySelector("#showGraphInvestments");
    var dataInvestments = {
        data: dataSetInvestments,
        backgroundColor: ['rgba(247, 147, 25, 0.5)', 'rgba(14, 62, 134, 0.5)', 'rgba(75, 192, 192, 0.5)'],// Color de fondo
        borderColor: ['rgb(247, 147, 25)', 'rgb(14, 62, 134)', 'rgba(75, 192, 192)'],// Color de fondo
        borderWidth: 1,// Ancho del borde
        
    };
    if (myChartInvestments) {
        myChartInvestments.destroy();
    }
    myChartInvestments = new Chart(graphInvestments, {
    type: 'doughnut',// Tipo de gráfica
    data: {
        labels: labelsInvestments,
        datasets: [
            dataInvestments,
        ]
    },
    options: {
        cutout: 80,
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
