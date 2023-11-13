var date = new Date();
moment.locale('es');
var myChartCashFlow;
cashAndExpenseData();

function cashAndExpenseData() {
    var url  = '/general/functions/helpers/cashAndExpense.php';
    fetch(url)
        .then( r => r.json() )
        .then( data => showcashAndExpenseData(data) )
        .catch( error => console.log(error) );

}

function showcashAndExpenseData( { expenseFiveMonth, expenseFourMonth, expenseThreeMonth, expenseTwoMonth, expenseLastMonth, expenseMonth, incomeFiveMonth, 
                                   incomeFourMonth, incomeThreeMonth, incomeTwoMonth, incomeLastMonth, incomeMonth} ) {
    var datasets = [
        {
            barThickness: 10,
            borderRadius: 3,    
            label: 'Ingresos',
            backgroundColor: 'rgba(14, 203, 129, 0.5)',
            borderColor: 'rgb(14, 203, 129)',
            borderWidth: 1,
            data: [incomeFiveMonth, incomeFourMonth, incomeThreeMonth, incomeTwoMonth, incomeLastMonth, incomeMonth],
        },
        {
            barThickness: 10,
            borderRadius: 3,
            label: 'Gastos',
            backgroundColor: 'rgba(255, 99, 132, 0.3)',
            borderColor: 'rgba(255, 99, 132)',
            borderWidth: 1,
            data: [ expenseFiveMonth, expenseFourMonth, expenseThreeMonth, expenseTwoMonth, expenseLastMonth, expenseMonth],
        }
    ]
    var labels = [
        moment().subtract(5, 'months').format('MMMM YYYY').charAt(0).toUpperCase() + moment().subtract(5, 'months').format('MMMM YYYY').slice(1),
        moment().subtract(4, 'months').format('MMMM YYYY').charAt(0).toUpperCase() + moment().subtract(4, 'months').format('MMMM YYYY').slice(1),
        moment().subtract(3, 'months').format('MMMM YYYY').charAt(0).toUpperCase() + moment().subtract(3, 'months').format('MMMM YYYY').slice(1),
        moment().subtract(2, 'months').format('MMMM YYYY').charAt(0).toUpperCase() + moment().subtract(2, 'months').format('MMMM YYYY').slice(1),
        moment().subtract(1, 'months').format('MMMM YYYY').charAt(0).toUpperCase() + moment().subtract(1, 'months').format('MMMM YYYY').slice(1),
        moment().format('MMMM YYYY').charAt(0).toUpperCase() + moment().format('MMMM YYYY').slice(1),
    ];
    var data = {
        labels: labels,
        datasets: datasets,
        
    };
    var config = {
        type: 'bar',
        data: data,
        options: {}
    };
    if (myChartCashFlow) {
        myChartCashFlow.destroy();
    }
    myChartCashFlow = new Chart(
        document.querySelector('#showGraphCashFlow').getContext("2d"),
        config
    );
}
