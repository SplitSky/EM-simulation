<?php
require_once 'config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>

  <title>EM simulation alpha - Canvas</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="online_library\bootstrap.min.css">
  <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="online_library\bootstrap.min.js"></script>
  <link rel="stylesheet" type="text/css" href="styleSheet1.css">
  <script type="text/javascript" src="Cango3D-7v10.js"></script>
  <link rel="stylesheet" type="text/css" href="styleSheet2.css">
   
<script type="text/javascript">

    
    $(document).ready(function() {
		
		function validateUserInput(variable) {
			var expression = /^[a-zA-Z0-9]/;
			if (!expression.test(variable)) {
				return false;
			} else {
				expression = /^\S+$/;
				if (!expression.test(variable)) {
					return false;
				} else {
					return true;
				}
			}
		};
		
        $("#login-submit").click(function(){
            if ($("#username").val() != "" 
             && $("#password1").val() != "" 
              && $("#password1").val() == $("#password2").val() && validateUserInput($("#username").val()) && validateUserInput($("#password1").val())){
                $.post("signup.php", 
                       { username: $("#username").val(), password: $("#password1").val() },
                        function(data){
                    console.log(data);
                            if(data != "success"){
                                alert(data);
                            } else {
                                sessionStorage.setItem("username", $("#username").val());
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
    });
</script>
   
   
  </head>

<body id="accountPage-body" data-spy="scroll" data-target=".navbar" data-offset="50">
<!-- Navigation bar -->

<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>                        
      </button>
      <a class="navbar-brand" href="#myPage">EM Simulation</a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav navbar-right">
        <li><a href="#myPage">HOME</a></li>
        <li><a href="#band">FEATURES</a></li>
        <li><a href="#tour">GO TO SIMULATION</a></li>
        <li><a href="#contact">CONTACT</a></li>
		<li><a href="<?=intialPage?>">LOG OUT</a></li>			<!-- EDIT to move around the pages -->
      </ul>
    </div>
  </div>
</nav>


<!-- Container - Centred text with the title -->
<div class="container">
  <div class="row">
    <div class="col-sm-12" id="account-management-body">
    <form action="">  <!-- FORM PROCESSING INCLUDE THE PHP PAGE -->
	
	Enter the details below <br>
	<input type="text" name="username" class="form-control" placeholder="Username" id="username" maxlength="20"><br>
	<input type="text" name="password" class="form-control" placeholder="Password" id="password1"><br>
	<input type="text" name="password-again" class="form-control" placeholder="Password again" id="password2"><br>
	<input type="button" name="login-submit" id="login-submit" tabindex="4" class="form-control btn btn-login" value="Log In">   <!-- submission button -->
	
	</form>
    </div>
  </div>

</div>  

<!-- Footer -->
<footer class="text-center">

<!-- footer optional -->

</footer>

<script>
$(document).ready(function(){
  // Initialize Tooltip
  $('[data-toggle="tooltip"]').tooltip();
  
  // Add smooth scrolling to all links in navbar + footer link
  $(".navbar a, footer a[href='#myPage']").on('click', function(event) {

    // Make sure this.hash has a value before overriding default behavior
    if (this.hash !== "") {

      // Prevent default anchor click behavior
      event.preventDefault();

      // Store hash
      var hash = this.hash;

      // Using jQuery's animate() method to add smooth page scroll
      // The optional number (900) specifies the number of milliseconds it takes to scroll to the specified area
      $('html, body').animate({
        scrollTop: $(hash).offset().top
      }, 900, function(){
   
        // Add hash (#) to URL when done scrolling (default click behavior)
        window.location.hash = hash;
      });
    } // End if
  });
})
</script>

</body>
</html>
