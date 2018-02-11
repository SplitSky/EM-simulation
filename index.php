<?php
require_once 'config.php';
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <title>EM simulation - Homepage</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <link rel="stylesheet" type="text/css" href="styleSheet1.css">
        <script>
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
				
				
                $("#login-submit-btn2").click(function() {

                    if ($("#username").val() != "" &&
                        $("#password").val() != "" && validateUserInput($("#password").val()) && validateUserInput($("#username").val()))
                     {
                        $.post("login.php", {
                                    username: $("#username").val(),
                                    password: $("#password").val()
                                },
                                function(data) {
                                    console.log(data);
                                    if (data != "success") {
                                        alert(data);
                                    } else {
                                        sessionStorage.setItem("username", $("#username").val());
                                        window.location.assign("simulation.php");
                                    }
                                }
                            )
                            .done(function(msg) {

                            });

                    } else {
                        alert("Please fill all fields with valid data!");
                    }
                });

                $("#btn-makeAnAccount").click(function() {
                    window.location.assign("accountPage.php");
                });
            })
            // stores the main scripting for the page

            // when a button is clicked
        </script>
    </head>

    <body id="myPage" data-spy="scroll" data-target=".navbar" data-offset="50">

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
                    </ul>
                </div>
            </div>
        </nav>

        <div id="myCarousel" class="carousel slide" data-ride="carousel">
            <!-- Indicators -->
            <ol class="carousel-indicators">
                <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                <li data-target="#myCarousel" data-slide-to="1"></li>
                <li data-target="#myCarousel" data-slide-to="2"></li>
            </ol>

            <!-- Wrapper for slides -->
            <div class="carousel-inner" role="listbox">
                <div class="item active">
                    <img src="img\magnet.png" alt="Picture of an Electron missing" width="1200" height="600">
                    <div class="carousel-caption">
                        <h3>Magnetism</h3>
                        <p>The simulation for a magnetic field</p>
                    </div>
                </div>

                <div class="item">
                    <img src="img\electron.png" alt="Point charges" width="1200" height="600">
                    <div class="carousel-caption">
                        <h3>Point charges</h3>
                        <p>Simulating paticle-like charges in space.</p>
                    </div>
                </div>

                <div class="item">
                    <img src="img\field.png" alt="Coils are missing. Who took them?" width="1200" height="600">
                    <div class="carousel-caption">
                        <h3>Coils</h3>
                        <p>Fields acting on coils</p>
                    </div>
                </div>
            </div>

            <!-- Left and right controls -->
            <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
      <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
      <span class="sr-only">Previous</span>
    </a>
            <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
      <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
      <span class="sr-only">Next</span>
    </a>
        </div>

        <!-- Container (The Band Section) -->
        <div id="band" class="container text-center">
            <h3>THE SIMULATION</h3>
            <p><em>"All science is either Physics or stamp collecting" - Ernest Rutherford</em></p>
            <p>The purpose of the simulation is to give you some insight into the innerworkings of the phenomena described below.</p>
            <br>
            <div class="row">
                <div class="col-md-4">
                    <p class="text-center"><strong><b>Magnetism</b></strong></p><br>
                    <a href="#Magnetism-description" data-toggle="collapse">
        <button class="button" >Press to learn more </button>
      </a>
                    <div id="Magnetism-description" class="collapse">
                        <p>It shows the vector lines of the field.</p>
                        <p>It allows you to observe how the field changes around objects.</p>
                        <p>The field distributions can then be saved and used in a point charge simulation.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <p class="text-center"><strong><b>Coils</b></strong></p><br>
                    <a href="#Coils-description" data-toggle="collapse">
        <button class="button" >Press to learn more </button>
      </a>
                    <div id="Coils-description" class="collapse">
                        <p>It allows you to see the coils in action.</p>
                        <p>The coils cause a change in the field what allows you to observe the effect on the acceleration of the charges as they pass through it.</p>
                        <p></p>
                    </div>
                </div>
                <div class="col-md-4">
                    <p class="text-center"><strong><b>Point charges</b></strong></p><br>
                    <a href="#demo3" data-toggle="collapse">
        <button class="button" >Press to learn more </button>
      </a>
                    <div id="demo3" class="collapse">
                        <p>A point charge is a hypothetical charge located at a single point in space.</p>
                        <p>This module allows you to play around with them and see Coulomb's law in action.</p>

                    </div>
                </div>
            </div>
        </div>

        <!-- Container (Simulation loading section) -->
        <div id="tour" class="bg-1">
            <div class="container">
                <h3 class="text-center">Simulation Modes</h3>
                <p class="text-center">Select which one you would like to look at and enjoy.<br></p>

                <div class="row text-center">
                    <div class="col-md-4">
                        <div class="thumbnail">
                            <img src="img\magnet-background.jpg" alt="Magnetism" height="300">
                            <p><strong>Magnetism</strong></p>
                            <p>They can be a bit pushy.</p>
                            <button class="btn" data-toggle="modal" data-target="#myModal">Take me to the simulation!</button>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="thumbnail">
                            <img src="img\coil_background.png" alt="Coils Background" height="300">
                            <p><strong>Coils</strong></p>
                            <p>Faraday would be proud.</p>
                            <button class="btn" data-toggle="modal" data-target="#myModal">Take me to the simulation!</button>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="thumbnail">
                            <img src="img\point-charges-background.png" alt="Point Charges" height="300">
                            <p><strong>Point charges</strong></p>
                            <p>Hypothetical but let's assume they exist.</p>
                            <button class="btn" data-toggle="modal" data-target="#myModal">Take me to the simulation!</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="myModal" role="dialog">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">×</button>
                            <h4><span class="glyphicon glyphicon-flash"></span> Before You Start</h4>
                        </div>
                        <div class="modal-body">
                            <form role="form">
                                <div class="form-group">
                                    <label for="psw"><span class="glyphicon glyphicon-plus"></span> Enter your username</label>
                                    <input type="text" class="form-control" id="username" name="username" placeholder="Username">
                                    <!-- Stores the value of the username -->
                                </div>
                                <div class="form-group">
                                    <label for="usrname"><span class="glyphicon glyphicon-magnet"></span> Password</label>
                                    <input type="text" class="form-control" id="password" name="password" placeholder="Password">
                                    <!-- stores the value of the password -->
                                </div>
                                <button type="submit" id="login-submit-btn2" class="btn btn-block">Proceed 
                <span class="glyphicon glyphicon-ok"></span>
              </button>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-danger btn-default pull-left" data-dismiss="modal">
            <span class="glyphicon glyphicon-remove"></span> Cancel
          </button>
                            <p>Don't have an account? Make one! <button type="button" id="btn-makeAnAccount" class="btn btn-info">Click me!</button></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Container (Contact Section) -->
        <div id="contact" class="container">
            <h3 class="text-center">Contact</h3>
            <p class="text-center"><em>Contact me about anything</em></p>

            <div class="row">
                <div class="col-md-4">
                    <p>Need some help?</p>
                    <p><span class="glyphicon glyphicon-map-marker"></span>Chicago, US</p>
                    <p><span class="glyphicon glyphicon-phone"></span>Phone: +00 1515151515</p>
                    <p><span class="glyphicon glyphicon-envelope"></span>Email: mail@mail.com</p>
                </div>
            </div>
            <br>
        </div>
        </div>



        <!-- Footer -->
        <footer class="text-center">
            <a class="up-arrow" href="#myPage" data-toggle="tooltip" title="TO TOP">
    <span class="glyphicon glyphicon-chevron-up"></span>
  </a><br><br>

        </footer>

        <script>
            $(document).ready(function() {
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
                        }, 900, function() {

                            // Add hash (#) to URL when done scrolling (default click behavior)
                            window.location.hash = hash;
                        });
                    } // End if
                });
            })
        </script>

    </body>

    </html>