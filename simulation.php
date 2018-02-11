<!DOCTYPE html>

<html lang="en">

<head>
    <!-- Theme Made By www.w3schools.com - No Copyright -->
    <title>EM simulation alpha - Canvas</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script type="text/javascript" src="scripting.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="styleSheet1.css">
    <script type="text/javascript" src="Cango3D-7v10.js"></script>
    <script src="Mathematical_Module_withClasses.js" type="text/javascript"></script>
    <script>
        $(document).ready(function() {
                    g = new Cango3D("cvsID");
                    NewtonianModule = new NewtonianModule(1);
                    StaticModule = new StaticModule();
                    DynamicModule = new DynamicModule(1, 1);
                    var worldcoordinates = [0, 0, 0]; // -75, -120, 150
                    var lightSource = [0, 0, 0];
                    canvas = new canvas(worldcoordinates, lightSource, "blue", 1, 50, 1, 25);

                    // load the filenames on the HTML file
                    $.post("LoadingList.php",
                        JSON.stringify({
                            username: sessionStorage.getItem("username")
                        }),
                        function(data) {
                            var array = [];
                            $.each(data, function(key, data) {
                                array.push(data.filename);
                                console.log(array);
                            })
                            // starts adding filename fields to the HTML
                            for (var counter = 0; counter != array.length; counter++) {
                                var string = '<option id="' + array[counter] + '" value="' + array[counter] + '">' + array[counter] + '</option>';
                                $("#file-name-list").append(string);
                            }
                            // end of the function

                        }
                    )
                    

                    // start validation functions
                    function validateNumber(variable) {
						// pattern for a float point number
						var patt = /^[+-]?\d+(\.\d+)?$/;
						if (!patt.test(variable)) {
							return false;
						} else {
							return true;
						}
                        
                    };

                        function validateLength(variable) {
                            if (variable.length > 0) {
                                return true;
                            } else {
                                return false;
                            }
                        }

                        function massValidate(array) {
                            var validArray = false;
                            for (var counter = 0; counter != array.length; counter++) {
                                validArray = validateLength(array[counter]);
                                validArray = validateNumber(array[counter]);
                            }
                            return validArray;
                        }

                        // end validation fnc

                        // saving and loading the simulation
                        $("#save-simulation").click(function() {
                            if ($("#filename").val() == "") {
                                alert("insert valid file name"); // add validation
                            } else {
                                canvas.saveFile($("#filename").val());
                                $("#file-name-list").append('<option id="' + $("#filename").val() + '" value="' + $("#filename").val() + '">' + $("#filename").val() + '</option>');
                            }
                        })

                        $("#load-simulation").click(function() {
                            var fileName = $("#file-name-list").val();
                            $.post("LoadingSimulation.php",
                                JSON.stringify({
                                    filename: fileName
                                }),
                                function(data) {
                                    if (data == false) {
                                        alert("The simulation couldn't be loaded try again later.");
                                    } else {
                                        canvas.LoadSimulation(data);
                                        alert("Simulation loaded successfully");
                                    }
                                })
								
							console.log(DynamicModule.FieldArray);
                        })

                        $("#delete-simulation").click(function() {
                            // variable             // type            // purpose
                            // fileName             // string          // stores the value of the fileName
                            var fileName = $("#file-name-list").val();
                            $.post("DeletingSimulation.php",
                                JSON.stringify({
                                    filename: fileName
                                }),
                                function(data) {
                                    if (data == "Record deleted successfully") {
                                        alert("Simulation deleted.");
                                        $("#" + fileName).hide();
                                    } else {
                                        alert(data);
                                    }

                                }
                            )
                        })

                        $("#render-staticFrame").click(function() {
							var validationArray=[];
                            for (var counter = 0; counter != StaticModule.StaticElements.length; counter++) {
                                StaticModule.StaticElements[counter].charge = (10 ** 13) * StaticModule.StaticElements[counter].charge;
                            }
                            // variable             // type            // purpose
                            // minZ                   float            stores the minimum z coordinate for field line population
                            // maxZ                   float            stores the maximum z coordinate for field line population
                            // minX                   float            stores the minimum x coordinate for field line population
                            // minX                   float            stores the maximum x coordinate for field line population
                            // maxY                   float            stores the minimum y coordinate for field line population
                            // minY                   float            stores the maximum y coordinate for field line population

                            var minZ = Number($("#field-LineMinz").val());
                            var maxZ = Number($("#field-LineMaxz").val());

                            var maxX = Number($("#field-LineMaxx").val());
                            var minX = Number($("#field-LineMinx").val());

                            var maxY = Number($("#field-LineMaxy").val());
                            var minY = Number($("#field-LineMiny").val());
                            validationArray.push(maxX, maxY, maxZ, minX, minY, minZ);
                            
                            if (massValidate(validationArray) == true) {
                                if ($("#type-fieldLines").val() == "2D") {
                                    maxZ = 2;
                                    minZ = 1;
                                };
                                canvas.updateGroupWithCharges();
                                canvas.populateCanvasWithFieldlines(maxX, maxY, maxZ, minX, minY, minZ);
                                g.render(canvas.objectGroupList[2]);
                            } else {
                                alert("The field line variables are not valid");
                            }
                            
                            // changes the population of field lines from 2D to 3D


                        })

                        $("#render-magneticFrame").click(function() {
                            // variable             // type            // purpose
                            // minZ                   float            stores the minimum z coordinate for field line population
                            // maxZ                   float            stores the maximum z coordinate for field line population
                            // minX                   float            stores the minimum x coordinate for field line population
                            // minX                   float            stores the maximum x coordinate for field line population
                            // maxY                   float            stores the minimum y coordinate for field line population
                            // minY                   float            stores the maximum y coordinate for field line population
                            var validationArray=[];
                            var minZ = Number($("#field-LineMinz").val());
                            var maxZ = Number($("#field-LineMaxz").val());

                            var maxX = Number($("#field-LineMaxx").val());
                            var minX = Number($("#field-LineMinx").val());

                            var maxY = Number($("#field-LineMaxy").val());
                            var minY = Number($("#field-LineMiny").val());
                            validationArray.push(maxX, maxY, maxZ, minX, minY, minZ);
                            if (massValidate(validationArray) == true) {
                                if ($("#type-fieldLines").val() == "2D") {
                                    maxZ = 2;
                                    minZ = 1;
                                };
                                canvas.updateGroupWithMagnets();
                                canvas.populateCanvasWithFieldlines(maxX, maxY, maxZ, minX, minY, minZ);
                                g.render(canvas.objectGroupList[2]);
                            } else {
                                alert("The field line variables are not valid");
                            }

                        })

                        $("#render-dynamic").click(function() {
                            // variable             // type             // purpose
                            // IntervalID           // session variable // used to stop the interval
                            canvas.updateGroupWithCharges();

                            function play() {
                                canvas.UpdateCanvas();
                                g.renderFrame(canvas.objectGroupList[2]);
                            }
                            sessionStorage.setItem("intervalID", window.setInterval(play, 100));
                        })

                        $("#stop-render-dynamic").click(function() {
                            alert("simulation stopped");
                            window.clearInterval(sessionStorage.getItem("intervalID"));
                        })


                        // creating objects start
                        // creating a charge
                        $("#confirm-charge1").click(function() {
                            // variable name          //type               // purpose                      
                            // mass                    float              stores the mass of the charge
                            // position                array              stores the position vector of the charge
                            // charge                  float              stores the magnitude of the charge
                            // velocity                array              stores the velocity vector
                            // validationArray         array              stores the variables for validation
                            console.log("creating a charge");
                            var mass, position, type, chargeQuantity, velocity, validationArray=[];

                            mass = Number($("#mass-charge1").val()) * (10 ** (-31));
                            validationArray.push(mass);
                            position = [Number($("#positionX-charge1").val()),
                                Number($("#positionY-charge1").val()),
                                Number($("#positionZ-charge1").val())
                            ];

                            validationArray.push($("#positionX-charge1").val());
                            validationArray.push($("#positionY-charge1").val());
                            validationArray.push($("#positionZ-charge1").val());

                            chargeQuantity = Number($("#charge-charge1").val()) * 10 ** -19;
                            validationArray.push(chargeQuantity);
                            if (chargeQuantity < 0) {
                                type = "electron";
                            } else {
                                type = "proton";
                            }
                            velocity = [Number($("#velocityX-charge1").val()),
                                Number($("#velocityY-charge1").val()),
                                Number($("#velocityZ-charge1").val())
                            ];

                            validationArray.push(velocity[0], velocity[1], velocity[2]);

                            if (massValidate(validationArray) == true) {
                                canvas.createCharge(mass, position, type, chargeQuantity, velocity);
                                alert("A charge has been created!");
                            } else {
                                alert("The charge attributes are not valid");
                            }


                        })

                        // creating a field
                        $("#confirm-field1").click(function() {
                            // variable            type                 purpose
                            // strength            float                stores the strength of the field
                            // direction           array                vector indicating direction of the field
                            // type                string               stores the type of the field
                            var strength, direction, type, validationArray=[];
                            
                            strength = $("#strength-field1").val();
                            
                            validationArray.push(strength);
                            direction = [Number($("#directionX-field1").val()), Number($("#directionY-field1").val()), Number($("#directionZ-field1").val())];
                            validationArray.push(direction[0], direction[1], direction[2]);
                            type = $("#type-field1").val().toLowerCase(); // always valid and it comes from a select attribute
                            if (massValidate(validationArray) == true) {
                                if (type = "electric") {
                                    strength = Number(strength)* (10**-30);    
                                } else {
                                    strength = Number(strength); // modification for magnetic
                                }
                                
                                canvas.createField(strength, direction, type);
                                alert("a field was created!");
                            } else {
                                alert("One of the field attributes is not valid");
                            }
                        })

                        // creating a magnet
                        $("#confirm-magnet1").click(function() {
                            // variable            type                 purpose
                            // position            array                position vector
                            // strength            float                stores the strength of the magnet
                            // validationArray     array                stores the variables for validation
                            // rotationArray       array                stores the axies around which the magnet is rotated around
                            var position, strength=0, validationArray=[], flipPoles;
                            position = [$("#positionX-magnet1").val(), $("#positionY-magnet1").val(), $("#positionZ-magnet1").val()];
                            validationArray.push(position[0],position[1],position[2]);
                            strength = $("#strength-magnet1").val();
							validationArray.push(strength);
                            flipPoles = $("#flip-poles").val();
                            if (massValidate(validationArray) == true) {
                                position = [Number(position[0]), Number(position[1]), Number(position[2])];
                                strength = Number(strength);
                                canvas.createMagnet(position, strength, flipPoles);
								alert("A magnet was created!");
                            } else {
                                alert("One of the magnets variables is not correct");
                            }
                        })
                        // creating objects end
                        
                        $("#clean-canvas").click(function() {
                            // clear the canvas and the objects
                            // clears the arrays containing objects
                            canvas.clearDrawingArray();
                            // resets the canvas object groups.
                            canvas.fieldLinesObjects = [];
                            StaticModule.StaticElements = [];
                            DynamicModule.FieldArray = [];
                            canvas.magnets = [];
                            g.clearCanvas();
                            alert("canvas cleared!");
                        });


                    })
    </script>
</head>

<body id="myPage" data-spy="scroll" data-target=".navbar" data-offset="50">
    <!-- Navigation bar -->

    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>                     
      </button>
                <a class="navbar-brand" href="index.php">EM Simulation</a>
            </div>
            <div class="collapse navbar-collapse" id="myNavbar">
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="index.php">HOME PAGE</a></li>
                </ul>
            </div>
        </div>
    </nav>


    <!-- Container - Centred text with the title -->
    <div id="Title-block" class="container text-center">
        <h2>THE SIMULATION</h2>
        <p><em>"All science is either Physics or stamp collecting" - Ernest Rutherford</em></p>
        <p>Welcome to the simulation!</p>
        <br>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-5" id="simulation-objects">
                <h3>Menu</h3>
                <p>The below menu allows you to add objects. After you do use the simulation manipulation menu to render what you want to render</p>
                <div class="vertical-menu">
                    <a class="active">Objects</a>
                    <a>
                        <div id="charge-1">
                            <p>Charge Input menu</p>
                            Charge:
                            <input type="number" id="charge-charge1" class="inputValues"> x10^-19 C Mass:
                            <input type="number" id="mass-charge1" class="inputValues"> x10^-31 kg Position x-coordinate:
                            <input type="number" id="positionX-charge1" class="inputValues"> Position y-coordinate:
                            <input type="number" id="positionY-charge1"> Position z-coordinate:
                            <input type="number" id="positionZ-charge1"> Initial Velocity x-component magnitude:
                            <input type="number" id="velocityX-charge1"> Initial Velocity y-component magnitude:
                            <input type="number" id="velocityY-charge1"> Initial Velocity z-component magnitude:
                            <input type="number" id="velocityZ-charge1">
                            <button type="button" id="confirm-charge1">Make a Charge!</button>
                        </div>
                    </a>
                    <a>
                        <div id="field-001">
                            <p> Field Input menu </p>
                            <p> Please enter a vector <br> pointing the way you <br> would like the field <br> to be directed. </p>
                            <p>Remember x is positive to the right, y is positive up and z is positive out of the screen.</p>
                            Direction x-coordinate:
                            <input type="number" id="directionX-field1"> Direction y-coordinate:
                            <input type="number" id="directionY-field1"> Direction z-coordinate:
                            <input type="number" id="directionZ-field1"> Type:
                            <select id="type-field1">
				<option value="electric">Electric</option>
				<option value="magnetic">Magnetic</option>
			                 </select>
                            Strength:
                            <input type="number" id="strength-field1">
                            <button type="button" id="confirm-field1">Make a Field!</button>
                        </div>
                    </a>
                    <a>
                        <div id="magnet-001">
                            <p> Magnet Input menu</p> Position x-coordinate:
                            <input type="number" id="positionX-magnet1" max=600 min=-200><br> Position y-coordinate: <br>
                            <input type="number" id="positionY-magnet1" max=600 min=-200><br> Position z-coordinate: <br>
                            <input type="number" id="positionZ-magnet1" max=600><br> Strength: <br>
                            <input type="number" id="strength-magnet1" min="0" max="20"><br>
                            <select id="flip-poles">
				<option value=false>No</option>
                <option value=true>Yes</option>
			                 </select>
                            <button type="button" id="confirm-magnet1">Make a Magnet!</button><br>
                        </div>
                    </a>
                </div>
                <div class="vertical-menu">
                    <a>
                        <p> Simulation management</p>
                        <p>Field lines 3D or 2D</p>
            <select id="type-fieldLines">
				<option value="3D">3D</option>
				<option value="2D">2D</option>
			</select>
                        <p>Render Static Frame</p>
                        <button type="button" id="render-staticFrame">Render Static</button>
                        <p>Play Dynamic Simulation</p>
                        <button type="button" id="render-dynamic">Render</button>
                        <p>Stop Dynamic Simulation</p>
                        <button type="button" id="stop-render-dynamic">Stop</button>
                        <p>Clean Canvas</p>
                        <button type="button" id="clean-canvas">Clean</button>
                        <p>Render Magnetic Frame</p>
                        <button type="button" id="render-magneticFrame">Render</button>
                        <p>Save a Simulation:</p>
                        <p>Enter the file name:</p>
                        <input type="text" id="filename" value="savedSimulation1">
                        <button type="button" id="save-simulation">Save</button>
                        <p>Load and Delete Simulation files:</p>
                        <select id="file-name-list">
                <!-- It's filled with file names -->
			</select>
                        <button type="button" id="delete-simulation">Delete</button>
                        <button type="button" id="load-simulation">Load</button>
                    </a>
                    <a>
                        <p>Advanced options</p>

                        <p>Rendering Lines Limit minimum x-coordinate:</p>
                        <input type="number" id="field-LineMinx" min=0 max=600 value=0>
                        <p>Rendering Lines Limit maximum x-coordinate:</p>
                        <input type="number" id="field-LineMaxx" min=0 max=600 value=600>

                        <p>Rendering Lines Limit minimum y-coordinate:</p>
                        <input type="number" id="field-LineMiny" min=0 max=600 value=0>
                        <p>Rendering Lines Limit maximum y-coordinate:</p>
                        <input type="number" id="field-LineMaxy" min=0 max=600 value=600>

                        <p>Rendering Lines Limit minimum z-coordinate:</p>
                        <input type="number" id="field-LineMinz" min=0 max=600 value=0>
                        <p>Rendering Lines Limit maximum z-coordinate:</p>
                        <input type="number" id="field-LineMaxz" min=0 max=600 value=600>
                    </a>
                </div>

            </div>
            <div class="col-md-7" id="canvas-container">
                <p>This is where the canvas is placed. This is where everything will be happening.</p>
                <div>

                    <h3 class="text"> Canvas </h3>
                    <canvas id="cvsID" width="1000" height="1000" style="height: 99%; width: 99%;"></canvas>

                    <!-- style="padding: 5px; width: 80%; height: 80%; margin: 0px;" -->

                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="text-center">

            <div id="notes-block">
                <!-- This container holds notes concerning the event that is being portrayed. This will import the JSON file -->
                <h2>What is going on?</h2>
                <!-- This contains the example of the format that should be included -->
                <div id="notes">
                    <p>The numbers entered are in the base units that would be attributed during your calculations. Velocity is broken down into components to allow you to use the full 3D modelling in the desired 3 dimensions. The canvas has a limited range so be weary with high values for charges and field strength as they cause big acceleration. The values for mass and charge should be chosen carefully when using the dynamic rendering as the values may cause the charges to fly off the screen in less than a frame rendering the simulation impossible to see. The adjustments were used to present the static module in a .</p>
                </div>
            </div>

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

</html>