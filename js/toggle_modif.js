$( document ).ready(function() {

     $(".champ").hide();

  $(".modif").on("click", function() {
     
     if($(this).next(".champ").attr('style') == 'display: block;'){
          $(this).next(".champ").slideUp(200);
          $(this).parent().attr('disabled', 'disabled');
     } else {
          $(".champ").each(function(){
              $(this).slideUp(200);
              $(this).parent().attr('disabled', 'disabled');

          });
         $(this).next(".champ").slideToggle(200);
          $(this).parent().removeAttr( "disabled" )
     }    
  });
 
});