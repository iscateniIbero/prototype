const updatePagesFacebook = document.querySelector('#updatePagesFacebook');
const btnToDoList = document.querySelector('.btnToDoList');
const date = document.querySelector('#dateToDoList');
const list = document.querySelector('#list');
const addTask = document.querySelector('#addTask');
const btnEnter = document.querySelector('#enter');
const toDoList = document.querySelector('.toDoList');
const titleTask = document.querySelector('#titleTask');
const check = 'fa-check-circle';
const unCheck = 'fa-circle';
const lineThrough = 'line-through';
let id;
let aList;
const btnWishes = document.querySelector('#btnWishes');
const formWishes = document.querySelector('#formWishes');
const addItemWhishes = document.querySelector('#addItemWhishes');
const nameWishes = document.querySelector('#nameWishes');
const priceWishes = document.querySelector('#priceWishes');
const budgetWishes = document.querySelector('#budgetWishes');
const linkWishes = document.querySelector('#linkWishes');
const captureWishes = document.querySelector('#captureWishes');
const descriptionWishes = document.querySelector('#descriptionWishes');
const wishesList = document.querySelector('#wishesList');
let idWishes;
let aWishes;

document.addEventListener('DOMContentLoaded', () => {
    showTime();
    updateTime();
    setInterval(updateTime, 1000);
    $(".option").click(function(){
        $(".option").removeClass("active");
        $(this).addClass("active");
        
    });
});
updatePagesFacebook.addEventListener('mouseover', () => {
    updatePagesFacebook.className += ' fa-spin';
});
updatePagesFacebook.addEventListener('mouseout', () => {
    updatePagesFacebook.className = 'fas fa-sync-alt';
});
function showTime() {
	myDate = new Date();
	hours = myDate.getHours();
	minutes = myDate.getMinutes();
	seconds = myDate.getSeconds();	
	var sufijo = ' AM';
	if(hours > 12) {
	  hours = hours - 12;
	  sufijo = ' PM';
	}
	if (hours < 10) hours = 0 + hours;
	if (minutes < 10) minutes = "0" + minutes;
	$("#time").text(hours+ ":" +minutes+ sufijo);
	setTimeout("showTime()", 1000);
}
var updateTime = function() {
	let currentDate = new Date(),
    day = currentDate.getDate(), 
    month = currentDate.getMonth(), 
    year = currentDate.getFullYear();
    const weekDays = [
        'Domingo',
        'Lunes',
        'Martes',
        'Miércoles',
        'Jueves',
        'Viernes',
        'Sabado'
    ];
    document.getElementById('weekDay').textContent = weekDays[weekDay];
    document.getElementById('day').textContent = day;
    const months = [
        'Enero',
        'Febrero',
        'Marzo',
        'Abril',
        'Mayo',
        'Junio',
        'Julio',
        'Agosto',
        'Septiembre',
        'Octubre',
        'Noviembre',
        'Diciembre'
    ];
    document.getElementById('month').textContent = months[month];
    document.getElementById('year').textContent = year; 
};
btnToDoList.addEventListener('click', () => {
    const dateToDoList = new Date();
    date.innerHTML = dateToDoList.toLocaleDateString('es-CO', {weekday:'long', month:'long', day:'numeric'});
    let data = localStorage.getItem('ToDoList');
    if (data) {
        aList = JSON.parse(data);
        id = aList.length;
        loadList(aList); 
    } else {
        aList = [];
        id = 0;
    }
    function loadList(data) {
        data.forEach(i => {
            addTaskToDoList(i.name, i.id, i.realized, i.deleted);
        });
    }
});
function addTaskToDoList(task, id, realized, deleted) {
    if (deleted) {return} 
    const validation = realized ? check : unCheck;
    const line = realized ? lineThrough : '';

    const element = `<li>
                    <i class="far ${validation}" data="realized" id="${id}"></i>
                    <p class="txt ${line}">${task}</p>
                    <i class="fas fa-trash de" data="deleted" id="${id}"></i>
                    </li>`
    list.insertAdjacentHTML("beforeend", element);
}
function taskRealized(element) {
    element.classList.toggle(check);
    element.classList.toggle(unCheck);
    element.parentNode.querySelector('.txt').classList.toggle(lineThrough);
    aList[element.id].realized = aList[element.id].realized ? false : true;
}
function taskDeleted(element) {
    element.parentNode.parentNode.removeChild(element.parentNode);
    aList[element.id].deleted = true;
}
btnEnter.addEventListener('click', () => {
    const task = addTask.value;
    if (task) {
        addTaskToDoList(task, id, false, false);
        aList.push({
            name: task,
            id: id,
            realized : false,
            deleted : false
        });
    }
    localStorage.setItem('ToDoList', JSON.stringify(aList));
    addTask.value = '';
    id++;
});
toDoList.addEventListener('keyup', (e) => {
    if (e.key == 'Enter') {
        const task = addTask.value;
        if (task) {
            addTaskToDoList(task, id, false, false);
            aList.push({
                name: task,
                id: id,
                realized : false,
                deleted : false
            });
        }
        localStorage.setItem('ToDoList', JSON.stringify(aList));
        addTask.value = '';
        id++;
    }
});
list.addEventListener('click', (e) =>{
    e.stopPropagation();
    const element = e.target;
    const elementData = element.attributes.data.value;

    if (elementData === 'realized') {
        taskRealized(element);
    } else if (elementData === 'deleted') {
        taskDeleted(element);
    }
    localStorage.setItem('ToDoList', JSON.stringify(aList));
});
/* whishes */
function addItemsWhishes(name, price, budget, link, capture, description, buy, id) {
    if (buy) {return}
    const validation = buy ? check : unCheck;
    const element = `<div class="col mb-4">
                        <div class="card cardItemWishes">
                            <div class="row g-0">
                                <div class="col-md-4 align-self-center imgWishes">
                                    <img src="${capture}" class="img-fluid rounded-start" alt="${name}">
                                </div>
                                <div class="col-md-8">
                                    <div class="card-body">
                                        <h5 class="card-title">${name}</h5>
                                        <i class="far ${validation} buyWishes" data="buy" id="${id}"></i>
                                        <p class="card-text">${description.substr(0,186)}</p>
                                        <div class="row justify-content-end">
                                            <div class="col-8 linkItemWishes">
                                                <i class="fa-sharp fa-solid fa-link"></i><a href="${link}" target="_blank">${link.substr(0,50)}</a>
                                            </div>
                                            <div class="col-2 budgetItemWishes">${budget}</div>
                                            <div class="col-2 priceItemWishes">${price}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>`;
    wishesList.insertAdjacentHTML("beforeend", element);
}
function buyItemsWhishes(element) {
    element.classList.toggle(check);
    element.parentNode.parentNode.parentNode.parentNode.parentNode.remove(element.parentNode);
    aWishes[element.id].buy = true;
}
btnWishes.addEventListener('click', () =>{
    url =  `/general/functions/helpers/getWishes.php`;
    fetch(url)
        .then(response => response.json())
        .then(oData => {
            if (oData.length > 0) {
                let data = oData;
                if (data) {
                    aWishes = JSON.parse(data);
                    idWishes = aWishes.length;
                    loadListWishes(aWishes); 
                } else {
                    aWishes = [];
                    idWishes = 0;
                }
                function loadListWishes(data) {
                    data.forEach(i => {
                        addItemsWhishes(i.name, i.price, i.budget, i.link, i.capture, i.description, i.buy, i.id);
                    });
                }
            } else {
                aWishes = [];
                idWishes = 0;
            }
        })
        .catch( error => console.log(error))
});
addItemWhishes.addEventListener('click', () =>{
    const name = nameWishes.value;
    const price = priceWishes.value;
    const budget = budgetWishes.value;
    const link = linkWishes.value;
    const capture = captureWishes.value;
    const description = descriptionWishes.value;
    if (name != "" && price != "" && budget != "" && link != "" && capture != "" && description != "" ) {
        aWishes.push({
            name: name,
            price: price,
            budget: budget,
            link: link,
            capture: capture,
            description: description,
            id: idWishes,
            buy : false
        });

        url =  `/general/functions/helpers/addWishes.php`;

        fetch(url, {
                method: 'POST',
                body: JSON.stringify(aWishes)
            })
            .then( response =>  response.json())
            .then( oResponse => {
                if ( oResponse ) {
                    addItemsWhishes(name, price, budget, link, capture, description, false, idWishes);
                    formWishes.reset();
                    idWishes++;
                }
            })
           .catch( error => console.log(error))
    } else {
        if ( description.length < 87 ) {
            alertify.error("Agrega una descripción más larga");
        } else if (name == "" || price  == "" || budget  == "" || link  == "" || capture  == "" || description == "") {
            alertify.error("Todos los campos son necesarios");
        } 
    }
});
wishesList.addEventListener('click', (e) =>{
    e.stopPropagation();
    const element = e.target;
    const elementData = element.attributes.data.value;
    if (elementData === 'buy') {
        buyItemsWhishes(element);
    }
    url =  `/general/functions/helpers/addWishes.php`;

    fetch(url, {
            method: 'POST',
            body: JSON.stringify(aWishes)
        })
        .then( response =>  response.json())
        .then( oResponse => {
            if ( oResponse ) {true}
        })
        .catch( error => console.log(error))
});
budgetWishes.addEventListener('focus', (e) => {
    var number = e.target.value.replace('$', '');
    number = number.replace(/\./g, '');
    budgetWishes.value = number.replace(',00', '');
});
budgetWishes.addEventListener('keypress', (e) => {
    if (!onlyNumbers(e)){
        e.preventDefault();
    }
});
budgetWishes.addEventListener('keyup', (e) =>{
    if (e.key == 'Enter') {
        if (e.target.value == '' || e.target.value == '0') {
            budgetWishes.value = '$0,00'; 
        } else {
            budgetWishes.value = `$${formatMoney(e.target.value)}`;
        }
    }
});
budgetWishes.addEventListener('blur', (e) =>{
    if (e.target.value == '' || e.target.value == '0') {
        budgetWishes.value = '$0,00'; 
    } else if (!budgetWishes.value.startsWith('$')) {
        budgetWishes.value = `$${formatMoney(e.target.value)}`;
    }
});
priceWishes.addEventListener('focus', (e) => {
    var number = e.target.value.replace('$', '');
    number = number.replace(/\./g, '');
    priceWishes.value = number.replace(',00', '');
});
priceWishes.addEventListener('keypress', (e) => {
    if (!onlyNumbers(e)){
        e.preventDefault();
    }
});
priceWishes.addEventListener('keyup', (e) =>{
    if (e.key == 'Enter') {
        if (e.target.value == '' || e.target.value == '0') {
            priceWishes.value = '$0,00'; 
        } else {
            priceWishes.value = `$${formatMoney(e.target.value)}`;
        }
    }
});
priceWishes.addEventListener('blur', (e) =>{
    if (e.target.value == '' || e.target.value == '0') {
        priceWishes.value = '$0,00'; 
    } else if (!priceWishes.value.startsWith('$')) {
        priceWishes.value = `$${formatMoney(e.target.value)}`;
    }
});
function onlyNumbers(e) {
    var key = e.charCode;
    return key >= 48 && key <= 57;
}
function formatMoney (floatValue = 0, decimals = 0, multiplier = 1) {
    let floatMultiplied = floatValue * multiplier;
    let stringFloat = floatMultiplied + "";
    let arraySplitFloat = stringFloat.split(".");
    let decimalsValue = "0";
    if (arraySplitFloat.length > 1) {
       decimalsValue = arraySplitFloat[1].slice(0, decimals);
    }
    let integerValue = arraySplitFloat[0];
    let arrayFullStringValue = [integerValue, decimalsValue];
    let FullStringValue = arrayFullStringValue.join(".")
    let floatFullValue = parseFloat(FullStringValue) + "";
    let formatFloatFullValue = new Intl.NumberFormat('es-CO', { minimumFractionDigits: decimals }).format(floatFullValue);
    return formatFloatFullValue;
}