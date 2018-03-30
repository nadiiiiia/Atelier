/*
Please consider that the JS part isn't production ready at all, I just code it to show the concept of merging filters and titles together !
*/
$(document).ready(function(){
	                $.validator.setDefaults({
        errorClass:'help-block',
        highlight: function(element){
            $(element)
                .closest('.md-form')
                .addClass('alert-danger')
        },
        unhighlight: function(element){
            $(element)
                .closest('.form-control')
                .removeClass('alert-danger')
        }

    });

$("#eventForm").validate({
      rules: {
    
	 titre:"required",

		  },
   messages: {
     titre: "Veuillez tapper votre nom",

	 
   }
});
});