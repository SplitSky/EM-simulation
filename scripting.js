$(document).ready( function(){
	// stores the main scripting for the page
    
     // when a button is clicked   
     $("#login-submit-btn2").click(function(){

        if ($("#username").val() != "" 
         && $("#password").val() != "" 
         ){
            console.log("It's doing something");
            $.post("login.php", 
                   { username: $("#username").val(), password: $("#password").val() },
                    function(data){
                        if(data != "success"){
                            alert(data);
                        } else {
                            sessionStorage.setItem("userID",$("#username").val()) // assigns the userID for future storage
                            window.location.assign("simulation.php");
                        }								
                    }						   
            )
            .done(function( msg ) {

            });

        }else{
            alert("Please fill all fields with valid data!");
        }
    });  
    
    $("#btn-makeAnAccount").click(function(){
        window.location.assign("accountPage.php");
    });
    
    
    // input validation functions
    //function validateNumericalValue(inputVariable,min, max) {
    //    var variable = inputVariable;
    //    if (variable != "") {
    //        if (Number(variable) != NaN) {
    //            return true;
    //        }
    //    }
        
    //} else {
      //  alert("Please enter valid data.");
    //}
    
    

    
    
});