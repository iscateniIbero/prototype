const cardStocksAssets = document.querySelector('#cardStocksAssets');
const btnMenuTrii = document.querySelector('#v-pills-trii-tab');
const btnMenuStocks = document.querySelector('#pills-stocks-tab');
//Modal
const assetStockModalLabel = document.querySelector('#assetStockModalLabel');
const quantityAssetStock = document.querySelector('.quantityAssetStock');
const valueTotalAssetStock = document.querySelector('.valueTotalAssetStock');
const tableAssetStocks = document.querySelector('#tableAssetStocks');
const imgAssetStock = document.querySelector('.imgAssetStock');
const balanceAssetStock = document.querySelector('.balanceAssetStock');


document.addEventListener('DOMContentLoaded', () => {
  btnMenuStocks.addEventListener('click', function () {
    stocksAssets();
  });
});

function stocksAssets() {
  const params = {platform : 'Trii'};
  const url = '../general/functions/helpers/getStocksInverted.php';

  fetch(url, {
    method: 'POST',
    body: JSON.stringify(params)
  })
  .then( response =>  response.json())
    .then( oResponse => {
      if (oResponse.length > 0) {
        showCardsStocks(oResponse);
      }
    })
    .catch( error => console.log(error))
}
function showCardsStocks(oStocks) {
  let html = '<div class="row justify-content-md-center row-cols-3">';

  oStocks.forEach( stock => {
    const { name, acronym, quantity, currentValue, totalValue } = stock;
    valueCurrent = formatNumber(currentValue);
    value = formatNumber(totalValue);
    html += `<div class="col cardStock" onclick="assetsStocks('${acronym}')">
              <div class="cardBudgetList mb-2 p-2 cardCrypto">
              <div class="row">
                <div class="col-2">
                  <img style="border-radius: 50%" src="/general/public/images/stocks/${acronym}.png" class="img-fluid" alt="${name}">
                </div>
                <div class="col-6">
                  <div class="type" style="color:#fff;">${name}</div>
                  <small>${acronym}</small>
                </div>
                <div class="col-4 text-end">
                  <div class="type" style="color:#fff;">Precio mercado</div>
                  <small style="color: #F0B90B;">$${valueCurrent} COP</small>
                </div>
              </div><hr>
              <div class="row">
                <div class="col-6 text-start">
                  <div class="type" style="color:#fff;">Número de acciones</div>
                  <small>${quantity}</small>
                </div>
                <div class="col-6 text-end">
                  <div class="type" style="color:#fff;">Valor actual de mis acciones</div>
                  <small>$${value} COP</small>
                </div>
              </div>
              </div>
            </div>`;
  });
  html += '</div>';
  cardStocksAssets.innerHTML = html;
}
function assetsStocks(asset) {
  const params = {asset : asset};
  const url = '../general/functions/helpers/getDetailsStocksInverted.php';
  fetch(url, {
    method: 'POST',
    body: JSON.stringify(params)
    })
    .then( response =>  response.json())
    .then( oResponse => showCardsDetailsStocks(oResponse))
    .catch( error => console.log(error))
}
function showCardsDetailsStocks(stock) {
  assetStockModalLabel.innerText = stock.asset[0].name;
  quantityAssetStock.innerHTML = `<small>Número de acciones </small><span style="color: #B7BDC6;">${stock.stocks}</span>`;
  imgAssetStock.innerHTML = `<img style="border-radius: 50%" src="/general/public/images/stocks/${stock.asset[0].acronym}.png" class="img-fluid" alt="${stock.asset[0].name}"></img>`;
  valueTotalAssetStock.innerHTML = `<small>Valor de mis acciones </small><span style="color: #F0B90B;">$${formatNumber(stock.total)}</span>`;
  let htmlTable = '';
  let valueTotal = 0;
  htmlTable += `<thead>
                  <tr>
                    <th scope="col" class="text-start">Tipo</th>
                    <th scope="col" class="text-start">Cantidad</th>
                    <th scope="col" class="text-end">Precio de compra</th>
                    <th scope="col" class="text-end">Importe</th>
                  </tr>
                </thead><tbody>`;
  stock.asset.forEach( asset => {
    const {id, name, acronym, quantity, purchaseValue, currentValue, totalValue, date } = asset;
    let amount = quantity * purchaseValue;
    valueTotal = amount + valueTotal;
    htmlTable += `<tr>
                    <th class="text-start">Compra<br>${date}</th>
                    <th class="text-center align-middle">${quantity}</th>
                    <th class="text-end align-middle">$${purchaseValue}</th>
                    <th class="text-end align-middle">$${formatNumber(amount)}</th>
                  </tr>`;
  });
  htmlTable += `<tr>
                  <td colspan="3" class="text-end align-middle">Inversión total</td>
                  <td class="text-end align-middle positive">$${formatNumber(valueTotal)}</td>
                </tr>`;
  htmlTable += '</tbody>';
  let balanceEnd = 0;
  let balance = stock.total - valueTotal;
  let style = 'positive';
  if (balance < 0 ) {
    style = 'negative'; 
    balanceEnd = formatNumber(balance);
    balanceEnd = balanceEnd.replace('-', '-$');
  } else {
    balanceEnd = `$${formatNumber(balance)}`;
  }
  balanceAssetStock.innerHTML = `<small>Mis rendimientos </small><span class='${style}'">${balanceEnd}</span>`;
  tableAssetStocks.innerHTML = htmlTable;
  $('#detailAssetStock').modal('show');
}