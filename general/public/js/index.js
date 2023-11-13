const spinnerIndex = document.querySelector('#spinner');
const content = document.querySelector('#content');
document.addEventListener('DOMContentLoaded', () => {
  setTimeout(() => {
    spinnerIndex.style.display = 'none';
    spinnerIndex.style.opacity = '0';
    content.classList.add('d-flex');
    content.classList.add('align-items-start');
  }, 400);
  $("a .boton").on("click", function(){
    $("a").find(".activeTique").removeClass("activeTique");
    $(this).addClass("activeTique");
  });
});