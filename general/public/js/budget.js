const percentage = document.querySelector('#deductionPercentage');
const incomeBtn = document.querySelector('#saveIncome');
const nameIncome = document.querySelector('#nameIncome');
const valueIncome = document.querySelector('#valueIncome');
const valueMovement = document.querySelector('#value');
const valuePay = document.querySelector('#valuePay');
const transactionBtn = document.querySelector('#btnAddTransaction');
const closeModalIncome = document.querySelector('#closeModalIncome');
const closeModalTransaction = document.querySelector('#btnCloseTransaction');

eventsListeners();

function eventsListeners() {
    document.addEventListener('DOMContentLoaded', () => {
        $('#tableIncomeSources').load('views/budget/tables/tableIncomeSources.php');
        $('#tableOfExpenses').load('views/budget/tables/tableOfExpenses.php');
        $('#tableIncomeCash').load('views/budget/tables/tableIncomeCash.php');
        $('#tableExpenseCash').load('views/budget/tables/tableExpenseCash.php');
        $('#cashFlow').load('views/budget/cards/cashFlow.php');
        $('#patrimony').load('views/budget/cards/patrimony.php');
        $('#savings').load('views/budget/cards/savings.php');
        $('#investments').load('views/budget/cards/investments.php');
        $('#emergencyFund').load('views/budget/cards/emergencyFund.php');
        $('#graphCashFLow').load('views/budget/graph/graphCashFLow.php');
        $('#graphSaving').load('views/budget/graph/graphSaving.php');
        $('#graphInvestments').load('views/budget/graph/graphInvestments.php');
        $('#graphEmergencyFund').load('views/budget/graph/graphEmergencyFund.php');
        $('#graphPatrimony').load('views/budget/graph/graphPatrimony.php');
        incomeBtn.addEventListener('click', newIncome);
        transactionBtn.addEventListener('click', newTransaction);
        percentage.addEventListener('keyup', validation);
        nameIncome.addEventListener('keyup', validation);
        valueIncome.addEventListener('keyup', validation);
        percentage.addEventListener('keypress', number);
        valueIncome.addEventListener('keypress', number);
        valueMovement.addEventListener('keypress', number);
        valuePay.addEventListener('keypress', number);
        closeModalIncome.addEventListener('click', cleanForm);
        closeModalTransaction.addEventListener('click', cleanForm);
        recargarLista('Income');
        $("#typeIncome").on("click", function(){
            $("#typeEgress").addClass("btn-secondary");		
            $("#typeEgress").removeClass("btn-danger");
            $("#typePayment").addClass("btn-secondary");	
            $("#typePayment").removeClass("btn-primary");
            $("#typeWithdrawal").addClass("btn-secondary");	
            $("#typeWithdrawal").removeClass("btn-warning");
            $("#typeIncome").addClass("btn-success");
            $("#typeTransaction").val("Income");
            $('.groupEgress').hide();
            $('#tCredit').hide();
            $('#tfees').hide();
            $('#typeFrequency').hide();
            $('#movimient').show();
            $('#Payment').hide();
            $('.payments').hide();
            $('.withdrawal').hide();
            recargarLista('Income');	
        });
        $("#typeEgress").on("click", function(){
            $("#typeIncome").addClass("btn-secondary");		
            $("#typeIncome").removeClass("btn-success");
            $("#typePayment").addClass("btn-secondary");	
            $("#typePayment").removeClass("btn-primary");
            $("#typeWithdrawal").addClass("btn-secondary");	
            $("#typeWithdrawal").removeClass("btn-warning");
            $("#typeEgress").addClass("btn-danger");
            $("#typeTransaction").val("Egress");			
            $('#movimient').show();	
            $('.groupEgress').show();
            $('#Payment').hide();
            $('.payments').hide();	
            $('.withdrawal').hide();	
            recargarLista('Expense');
        });
        $("#typePayment").on("click", function(){
            $("#typeIncome").addClass("btn-secondary");		
            $("#typeIncome").removeClass("btn-success");
            $("#typeEgress").addClass("btn-secondary");		
            $("#typeEgress").removeClass("btn-danger");
            $("#typeWithdrawal").addClass("btn-secondary");	
            $("#typeWithdrawal").removeClass("btn-warning");
            $("#typePayment").removeClass("btn-secondary");
            $("#typePayment").addClass("btn-primary");
            $("#typeTransaction").val("Payment");
            $('.groupEgress').hide();			
            $('#movimient').hide();	
            $('#typeFrequency').hide();	
            $('#tCredit').hide();
            $('#tfees').hide();
            $('#Payment').show();
            $('.payments').show();	
            $('.withdrawal').hide();	
            recargarListaPay('Expense');
        });
        $("#typeWithdrawal").on("click", function(){
            $("#typeIncome").addClass("btn-secondary");		
            $("#typeIncome").removeClass("btn-success");
            $("#typeEgress").addClass("btn-secondary");		
            $("#typeEgress").removeClass("btn-danger");
            $("#typePayment").addClass("btn-secondary");
            $("#typePayment").removeClass("btn-primary");
            $("#typeWithdrawal").removeClass("btn-secondary");
            $("#typeWithdrawal").addClass("btn-warning");
            $("#typeTransaction").val("Withdrawal");
            $('.groupEgress').hide();			
            $('#movimient').hide();			
            $('#Payment').hide();	
            $('#typeFrequency').hide();	
            $('#tCredit').hide();
            $('#tfees').hide();
            $('.payments').hide();
            $('#Withdrawal').show();			
            $('.withdrawal').show();			
        });
        $("#type").on("change" ,function(){
            var valueSelect =  $("#type").val();
            if (valueSelect == "Eventual" ) {
                $('#typeFrequency').hide();	
                $('.tCredit').hide();
            } else {
                if (valueSelect == "Variable") {
                    $('#typeFrequency').show();	
                    $('.tCredit').show();
                } else {
                    if (valueSelect == "Fijo") {
                        $('#typeFrequency').show();	
                        $('.tCredit').hide();
                    }
                }
            }
        });
        $("#paymentMethod").on("change" ,function(){
            var valueMethodPayment =  $("#paymentMethod").val();
            if (valueMethodPayment == "Credito") {
                $('#tCredit').show();	
                $('#tfees').show();
            } else {
                $('#tCredit').hide();
                $('#tfees').hide();
            }
        });
    });  
    incomeBtn.disabled = true;
    removeValidator();
}
function validation(e) {
    if (e.target.name === "nameIncome" || e.target.name === "valueIncome" || e.target.name === "deductionPercentage") {
        if (e.target.value === "") {
            e.target.classList.add('is-invalid');
            e.target.classList.remove('is-valid');
        } else {
            e.target.classList.remove('is-invalid');
            e.target.classList.add('is-valid');
        }
    }
    if (e.target.name === "valueIncome") {
        if (e.target.value === "" || e.target.value == "0") {
            e.target.classList.add('is-invalid');
            e.target.classList.remove('is-valid');
        } else {
            e.target.classList.remove('is-invalid');
            e.target.classList.add('is-valid');
            e.target.value = formatNumber(e.target.value);
        }
    }
    if (e.target.name === "deductionPercentage") {
        if (e.target.value > 100 || e.target.value === "") {
            e.target.classList.add('is-invalid');
            e.target.classList.remove('is-valid');
        } else {
            e.target.classList.remove('is-invalid');
            e.target.classList.add('is-valid');
        }
    }
    if ( nameIncome.value !== '' && valueIncome.value !== '' && percentage.value !== '' && percentage.value < 100 ) {
        incomeBtn.disabled = false;
    } else {
        incomeBtn.disabled = true;
    }

}
function number(e) {
    var key = window.event ? e.which : e.keyCode;
    if (key < 48 || key > 57) {
        e.preventDefault();
    }
}
function formatNumber (e) {
	e = String(e).replace(/\D/g, "");
  return e === '' ? e : Number(e).toLocaleString();
}
function removeValidator() {
    percentage.classList.remove('is-invalid');
    percentage.classList.remove('is-valid');
    valueIncome.classList.remove('is-invalid');
    valueIncome.classList.remove('is-valid');
    nameIncome.classList.remove('is-invalid');
    nameIncome.classList.remove('is-valid');
}
function cleanForm(e) {
    let footer = e.target.parentElement;
    let body = footer.previousElementSibling;
    let form = body.children[0];
    form.reset();
    eventsListeners();
}
// calling assistant
function newIncome() {
    const name = $('#nameIncome').val();
    const value = $('#valueIncome').val();
    const deduction = $('#deductionPercentage').val();
    if ( name === "" && value === "" && deduction === "" ) {
        percentage.classList.add('is-invalid');
        nameIncome.classList.add('is-invalid');
        valueIncome.classList.add('is-invalid');	
    } else if ( name === "" ) {
        nameIncome.classList.add('is-invalid');
    } else if ( value === "" ) {
        valueIncome.classList.add('is-invalid');
    } else if ( deduction === "" ) {
        percentage.classList.add('is-invalid');
    } else {
        data=$('#formAddSourceIncome').serialize();
        $.ajax({
            type:"POST",
		    data:data,
		    url:"/tique/functions/helpers/addIncomeSource.php",	
            success:function(r){
                if (r == 1) {
                    $('#tableIncomeSources').load('views/budget/tables/tableIncomeSources.php');
                    $('#formAddSourceIncome')[0].reset();
                    removeValidator();
                    $('#addIncome').modal('hide');
                    alertify.success("Agregado exitosamente");
                } else {
                    alertify.error("Error, no se agrego la nueva fuente de ingresos");
                }	
            } 
        });
    }
}
function cancelIncome(id) {
	alertify.confirm('<i class="fa-solid fa-ban fa-beat-fade"></i>Cancelar el ingreso', '¿Seguro de cancelar este ingreso?', function(){
        $.ajax({
            type: "POST",
            data: "id=" + id,
            url: "/tique/functions/helpers/cancelIncomeSource.php",
            success:function(r){
                if (r==1) {
                    $('#tableIncomeSources').load('views/budget/tables/tableIncomeSources.php');
                    alertify.success("Ingreso cancelado con exito");
                } else {
                    alertify.error("No se pudo cancelar el ingreso");
                }
            }
        });
    }, function(){
    }).set('labels', {ok:'Si', cancel:'Cerrar'});
}
function cancelVariableCost(id) {
    alertify.confirm('<i class="fa-solid fa-ban fa-beat-fade"></i>Cancelar este gasto', '¿Seguro de cancelar este este gasto?', function(){
        $.ajax({
            type: "POST",
            data: "id=" + id,
            url: "/tique/functions/helpers/cancelVariableCost.php",
            success:function(r){
                if (r==1) {
                    $('#tableOfExpenses').load('views/budget/tables/tableOfExpenses.php');
                    alertify.success("Gasto cancelado con exito");
                } else {
                    alertify.error("No se pudo cancelar el Este gasto");
                }
            }
        });
    }, function(){
    }).set('labels', {ok:'Si', cancel:'Cerrar'});	
}
function newTransaction() {
    let success = "";
    var typeTransaction = $('#typeTransaction').val();
    var detail = $('#detail').val();
    var value = $('#value').val();
    var category = $('#category').val();
    var type = $('#type').val();
    var frequency = $('#frequency').val();
    var targetCredit = $('#targetCredit').val();
    var fees = $('#fees').val();
    var detailPay = $('#detailPay').val();
    var valuePay = $('#valuePay').val();
    var detailWithdrawal = $('#detailWithdrawal').val();
    var valueWithdrawal = $('#valueWithdrawal').val();
    if (typeTransaction == "") {
        alertify.error("Debes agregar todos los datos");
        success = "false";
    }
    if (typeTransaction == "Income") {
        if (detail == "" || value =="" || typeTransaction == "" || category == "") {
            alertify.error("Debes agregar todos los datos");
            success = "false";
        }
    }
    if (typeTransaction == "Egress") {
        if (typeTransaction == "Egress" && type == "Fijo") {
            if (frequency == "" || paymentMethod == "" || type == "") {
                success = "false";
                alertify.error("Debes agregar todos los datos");
            }
        }
        if (typeTransaction == "Egress" && type == "Variable") {
            if (frequency == "" || paymentMethod == "" || type == "") {
                success = "false";
                alertify.error("Debes agregar todos los datos");
            }
            if (paymentMethod == "Credito") {
                if (targetCredit == "" || fees == "") {
                    success = "false";
                    alertify.error("Debes agregar todos los datos");
                }
            }
        }
        if (detail == "" || value =="" || typeTransaction == "" || category == "") {
            alertify.error("Debes agregar todos los datos");
            success = "false";
        }
    }
    if (typeTransaction == "Payment") {
        console.log(success);
        if (detailPay == "" || valuePay =="" || typeTransaction == "" || category == "") {
            alertify.error("Debes agregar todos los datos");
            success = "false";
        }
    }
    if (typeTransaction == "Withdrawal") {
        console.log(success);
        if (detailWithdrawal == "" || valueWithdrawal =="" || typeTransaction == "" ) {
            alertify.error("Debes agregar todos los datos");
            success = "false";
        }
    }
    if ( success == "" ) {
        data=$('#registerTransaction').serialize();
        $.ajax({
            type:"POST",
            data:data,
            url:"/tique/functions/helpers/addNewTransaction.php", 
            success:function(r){
                if (r == 1) {
                    $('#graphPatrimony').load('views/budget/graph/graphPatrimony.php');
                    $('#graphCashFLow').load('views/budget/graph/graphCashFLow.php');
                    $('#graphSaving').load('views/budget/graph/graphSaving.php');
                    $('#graphEmergencyFund').load('views/budget/graph/graphEmergencyFund.php');
                    $('#patrimony').load('views/budget/cards/patrimony.php');
                    $('#cashFlow').load('views/budget/cards/cashFlow.php');  
                    $('#tableIncomeCash').load('views/budget/tables/tableIncomeCash.php');
                    $('#savings').load('views/budget/cards/savings.php');
                    $('#investments').load('views/budget/cards/investments.php');
                    $('#registerTransaction')[0].reset();
                    $('#transactionLog').modal('hide');
                    alertify.success("Transacción agregada exitosamente");
                } 
                if (r == 2) {
                    $('#graphPatrimony').load('views/budget/graph/graphPatrimony.php');
                    $('#graphCashFLow').load('views/budget/graph/graphCashFLow.php');
                    $('#graphSaving').load('views/budget/graph/graphSaving.php');
                    $('#graphEmergencyFund').load('views/budget/graph/graphEmergencyFund.php');
                    $('#patrimony').load('views/budget/cards/patrimony.php');
                    $('#cashFlow').load('views/budget/cards/cashFlow.php');  
                    $('#tableOfExpenses').load('views/budget/tables/tableOfExpenses.php');
                    $('#tableExpenseCash').load('views/budget/tables/tableExpenseCash.php');
                    $('#savings').load('views/budget/cards/savings.php');
                    $('#emergencyFund').load('views/budget/cards/emergencyFund.php');
                    $('#registerTransaction')[0].reset();
                    $('#transactionLog').modal('hide');
                    alertify.success("Transacción agregada exitosamente");
                }
            }
        });
    }
}
function recargarLista(type) {
    $.ajax({
        type:"POST",
        data:"type=" + type,
        url:"/general/functions/helpers/typeCategory.php",
        success:function(r){
            $('#selectCategory').html(r);	
        }
    });
}
function recargarListaPay(type) {
    $.ajax({
        type:"POST",
        data:"type=" + type,
        url:"/general/functions/helpers/typeCategory.php",
        success:function(r){
            $('#selectCategoryPay').html(r);	
        }
    });
}