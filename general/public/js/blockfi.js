const cardCryptoAssetsBlockFi = document.querySelector('#cardCryptoAssetsBlockFi');
const btnMenuBlockFi = document.querySelector('#v-pills-blockfi-tab');

eventsListeners();

function eventsListeners() {
  document.addEventListener('DOMContentLoaded', () => {
    btnMenuBlockFi.addEventListener('click', function() {
      cryptoAssetsBlockFI();
      setInterval(() => {
        cryptoAssetsBlockFI();
      }, 30000);
    } );
  });
}
function cryptoAssetsBlockFI() {
  const params = {platform : 'BlockFi'};
  const url = '../general/functions/helpers/getCryptoCurrencyInverted.php';

  fetch(url, {
    method: 'POST',
    body: JSON.stringify(params)
    })
    .then( response =>  response.json())
    .then( oResponse => {
      if (oResponse.length > 0) {
        showCardsBlockFi(oResponse);
      }
    })
    .catch( error => console.log(error))
}
function showCardsBlockFi(oCryptos) {
  let html = '<div class="row">';

  oCryptos.forEach( cryto => {
    const { id, name, value, available, btcTotal, icon, price, symbol, priceUSD, interestPaid} = cryto;
    val = formatNumber(value);
    html += `<div class="col-6">
               <div class="cardBudgetList mb-2 p-2 cardCrypto">
                <div class="row">
                  <div class="col">
                    <img style="border-radius: 50%" src="/general/public/images/cryptocurrency/${icon}" class="img-fluid" alt="${name}">
                  </div>
                  <div class="col-6">
                    <div class="type" style="color:#fff;">${name} <span style="color:#B7BDC6; font-weight: 500;">${symbol}</span></div>
                    <small>$${priceUSD} USD</small>
                  </div>
                  <div class="col-5">
                    <div class="type" style="color:#fff;">Interes pagado</div>
                    <small>${interestPaid} ${symbol}</small>
                    <div class="type" style="color:#fff;">Rendimiento anual</div>
                    <small style="font-weight: 500;">3.50%</small>
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
  cardCryptoAssetsBlockFi.innerHTML = html;
}
