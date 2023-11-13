const cardCryptoAssets = document.querySelector('#cardCryptoAssets');
const btnMenuBinance = document.querySelector('#v-pills-binance-tab');
const btnMenuCripto = document.querySelector('#pills-cryptocurrencies-tab');

eventsListeners();

function eventsListeners() {
  document.addEventListener('DOMContentLoaded', () => {
    btnMenuCripto.addEventListener('click', function() {
      cryptoAssets();
      setInterval(() => {
        balance();
      }, 30000);
    } );
    btnMenuBinance.addEventListener('click', function() {
      cryptoAssets();
      setInterval(() => {
        balance();
      }, 30000);
    } );
  });
}
function balance() {
  const url = '../general/functions/scripts/binance/balance.php';
  fetch(url)
    .then(response => response.json())
    .then(data => {
      if (data == '1') {
        cryptoAssets();
      }
    })
    .catch( error => console.log(error))
}
function cryptoAssets() {
  const params = {platform : 'Binance'};
  const url = '../general/functions/helpers/getCryptoCurrencyInverted.php';

  fetch(url, {
    method: 'POST',
    body: JSON.stringify(params)
    })
    .then( response =>  response.json())
    .then( oResponse => {
      if (oResponse.length > 0) {
        showCards(oResponse);
      }
    })
    .catch( error => console.log(error))
}
function showCards(oCryptos) {
  let html = '<div class="row justify-content-md-center row-cols-6">';

  oCryptos.forEach( cryto => {
    const { id, name, value, available, btcTotal, icon, price, symbol, priceUSD, interestPaid } = cryto;
    val = formatNumber(value);
    html += `<div class="col">
               <div class="cardBudgetList mb-2 p-2 cardCrypto">
                <div class="row">
                  <div class="col-2">
                    <img style="border-radius: 50%" src="/general/public/images/cryptocurrency/${icon}" class="img-fluid" alt="${name}">
                  </div>
                  <div class="col-10">
                    <div class="type" style="color:#fff;">${name}</div>
                    <small>$${priceUSD} USD</small>
                  </div>
                </div><hr>
                <div class="row">
                  <div class="col-7">
                    <div class="type" style="color:#fff;">Mi balance</div>
                    <small style="color: #F0B90B;">$${available} ${symbol}</small>
                  </div>
                  <div class="col-5 text-end">
                    <div class="type" style="color:#fff;">Valor</div>
                    <small>$${val} COP</small>
                  </div>
                </div>
               </div>
             </div>`;
  });
  html += '</div>';
  cardCryptoAssets.innerHTML = html;
}
function formatNumber(floatValue = 0, decimals = 0, multiplier = 1) {
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