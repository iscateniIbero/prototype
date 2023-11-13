moment.locale('es');
var dataSetEmergencyFund = [];
var labelsEmergencyFund = [];
var datasetsEmergencyFund;
var dataLavelsEmergencyFund;
var numberMonthEmergencyFund;
var myChartEmergencyFund;
emergencyFundData();
function emergencyFundData() {
  var url  = '/general/functions/helpers/emergencyFound.php';
  fetch(url)
      .then( r => r.json() )
      .then( info => showEmergencyFundData(info) )
      .catch( error => console.log(error) );
}
function showEmergencyFundData(info) {
  var emergencyFundData = info.fund;
  var lengthData = 0;
  if (emergencyFundData.length >= 12) {
    lengthData = 12;
  } else {
      lengthData = emergencyFundData.length;
  }
  numberMonth = lengthData -1;
  for(var i = 0; i < lengthData; i++) {
    datasetsEmergencyFund = emergencyFundData[i]['Balance'];
    dataSetEmergencyFund.push(datasetsEmergencyFund);
    if (numberMonth == 0) {
        dataLavelsEmergencyFund = moment().format('MMMM YYYY').charAt(0).toUpperCase() + moment().format('MMMM YYYY').slice(1);
    } else {
        dataLavelsEmergencyFund = moment().subtract(numberMonth , 'months').format('MMMM YYYY').charAt(0).toUpperCase() + moment().subtract(numberMonth, 'months').format('MMMM YYYY').slice(1);
    }
    labelsEmergencyFund.push(dataLavelsEmergencyFund);
    numberMonth --;
  }
  showGraphEmergencyFund(dataSetEmergencyFund, labelsEmergencyFund);
}
function showGraphEmergencyFund(dataSetEmergencyFund, labelsEmergencyFund) {

  console.log(dataSetEmergencyFund);
  console.log(labelsEmergencyFund);

  var $graphEmergencyFund = document.querySelector("#showGraphEmergencyFund");
  var dataEmergencyFund = {
    data: dataSetEmergencyFund,
    backgroundColor: 'rgba(54, 162, 235, 0.5)', // Color de fondo
    borderColor: 'rgba(54, 162, 235)', // Color del borde
    borderWidth: 0,// Ancho del borde
    pointStyle: 'dash',
    pointHoverRadius: 1,
    tension: 0.4,
    fill: 'start'
  };
  if (myChartEmergencyFund) {
    myChartEmergencyFund.destroy();
  }
  myChartEmergencyFund = new Chart($graphEmergencyFund, {
      type: 'line',// Tipo de gráfica
      data: {
          labels: labelsEmergencyFund,
          datasets: [
            dataEmergencyFund,
        ]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            display: false,
            position: 'bottom',
          }
        },
        scales: {
          y: {
            stacked: true
          }
        }
      },
  });
}