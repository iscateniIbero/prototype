$('#login-button').click(function(){
  $('#login-button').fadeOut("slow",function(){
   $(".container-login").fadeIn();
   TweenMax.from(".container-login", .4, { scale: 0, ease:Sine.easeInOut});
   TweenMax.to(".container-login", .4, { scale: 1, ease:Sine.easeInOut});
  });
});
$(".close-btn").click(function(){
  TweenMax.from(".container-login", .4, { scale: 1, ease:Sine.easeInOut});
  TweenMax.to(".container-login", .4, { left:"0px", scale: 0, ease:Sine.easeInOut});
  $(".container-login, #forgotten-container-login").fadeOut(800, function(){
   $("#login-button").fadeIn(800);
  });
});
$(document).ready(function() {
   $('#btnLogin').click(function(){
      var user = $('#user').val();
      var password = $('#password').val();
      if (user == "" && password == "") {
         $('.userpass').slideDown('slow');
         setTimeout(function(){
            $('.userpass').slideUp('slow'); 
         },3000);
      } else {
         if (user == "") {
            $('.infoUser').show();
            $('.container-login').height(240);
            setTimeout(function(){
               $('.infoUser').hide();
               $('.container-login').height(220);
            },1500);
         } else {
            if (password == "") {
               $('.infoPass').show();
               $('.container-login').height(240);
               setTimeout(function(){
                  $('.infoPass').hide();
                  $('.container-login').height(220);  
               },1500);   
            } else {
               data=$('#formLogin').serialize();
               $.ajax({
                  type:"POST",
                  dataType:'json',
                  data: data,
                  url:"../general/functions/helpers/login.php",
                  beforeSend: function() {
                     $('.btnLogin').val('Validando...');
                  },
                  success:function(r){
                     if (!r.error) {
                        if (r.session == '1') {
                           location.href = 'passwordChange';   
                        } else {
                           location.href = '/';
                        } 
                     } else {
                        $('.error').slideDown('slow');
                        setTimeout(function(){
                           $('.error').slideUp('slow');  
                        },3000);
                        $('.btnLogin').val('Iniciar Sesión');
                     }
                  }
               });
            }   
         }     
      }
   });    
});