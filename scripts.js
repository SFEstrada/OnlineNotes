$(function () {

   $(".changeForm").on("click", function () {
       $("#signUpForm").toggle();
       $("#logInForm").toggle();
   });


   /*
   Text area contents
    */
   let oldValue = "";
   $("#notes-area").on("change keyup paste", function () {
      let currentValue = $(this).val();
      if (currentValue == oldValue){
         return;
      }
      oldValue = currentValue;
      //alert("detected change: " + currentValue);


      $.ajax({
         method: "POST",
         url: "updatedatabase.php",
         data: {content: currentValue}
      }).done(function( msg ) {

          }).fail(function () {
            alert("There was an error while saving")
      });
   });

});