var date = new Date();
moment.locale('es');
var myChartPatrimony;
PatrimonyData();

function PatrimonyData() {
    var url  = '../general/functions/helpers/patrimony.php';
    fetch(url)
        .then( r => r.json() )
        .then( data => showPatrimonyData(data) )
        .catch( error => console.log(error) );
}

function showPatrimonyData(data) {
    var datasets = [
        {  
          backgroundColor: [
            'rgba(75, 192, 192, 0.3)',
            'rgba(255, 99, 132, 0.3',
            'rgba(14, 203, 129, 0.2)',
          ],
          borderColor: [
            'rgba(75, 192, 192)',
            'rgba(255, 99, 132',
            'rgba(14, 203, 129)',
          ],
          borderWidth: 1,
          data: [data.assets, data.liabilities, data.equity],
        }
    ]
    var labels = ['Activos', 'Pasivos', 'Patrimonio'];
    var data = {
        labels: labels,
        datasets: datasets,
      };
    var config = {
        type: 'polarArea',
        data: data,
        options: {
          responsive: true,
          plugins: {
            legend: {
              display: false,
            },
          }
        }
    };
    if (myChartPatrimony) {
        myChartPatrimony.destroy();
    }
    myChartPatrimony = new Chart(
        document.querySelector('#showGraphPatrimony').getContext("2d"),
        config
    );
}
