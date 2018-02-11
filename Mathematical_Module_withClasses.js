 //Newtonian Module start
class NewtonianModule {

    constructor(timeFrame) { //constructor method
        this.timeFrame = timeFrame;
    };

    magnitudeOfVector(columnVector) {
        // finds the magnitude of a vector
        var total = 0.0,
            counter;

        for (counter = 0; counter != 3; counter++) {
            total += columnVector[counter] ** 2;
            total = parseFloat(total).toFixed(3);
            // avoids the rounding errors increasing
            total = Number(total);
            total = total ** 0.5;
        };
        return total;
    };

    addVectors(vector1, vector2) {
		
        //works
        //resolves the vectors
        var finalVector = [0, 0, 0],
            i;
        for (i = 0; i != 3; i++) {
            finalVector[i] = vector1[i] + vector2[i];
        }; //end for

        return finalVector;
    };


    calcVectorFromMagnitudeAndDirection(magnitude, positionVector) {

        // factor has to be positive for the algorithm to work
        // uses the fact that the magnitude of the vector is related to the vector by the same constant

        if (magnitude == 0) {
            return [0, 0, 0];
        }

        var counter, total; 
        var factor = 0.0; //
        var finalVector = [0, 0, 0]; 
        total = this.magnitudeOfVector(positionVector);
        factor = magnitude / total;

        for (counter = 0; counter != 3; counter++) {
            finalVector[counter] = positionVector[counter] * factor;
        }

        return finalVector;

    };

    resolveMagnitude(Vector1, Vector2) {
        var finalVector = ((this.magnitudeOfVector(Vector1)) ** 2 + (NewtonianModule.magnitudeOfVector(Vector2)) ** 2) ** 0.5;
        //use of Pythagoras to resolve two vectors
        return finalVector;
    };


    dotProduct(Vector1, Vector2) {
        var finalVector;
        finalVector = [Vector1[0] * Vector2[0], Vector1[1] * Vector2[1], Vector1[2] * Vector2[2]];
        return finalVector;
    };

    crossProduct(Vector1, Vector2) {
        var finalVector;
        finalVector = [Vector1[1] * Vector2[2] - Vector2[1] * Vector1[2], -1 * (Vector1[0] * Vector2[2] - Vector1[2] * Vector2[0]),
            Vector1[0] * Vector2[1] - Vector1[1] * Vector1[0]
        ];
        return finalVector;
    };

}; //class end
// Newtonian Module end

//DynamicModule start

class DynamicModule {

    constructor() {
        this.FieldArray = []; // stores the array of the fields

    }


    resolveAtAPoint(position) {
        // resolves around static objects - used in render static frame
        var counter;
        var ChargeMagnitude, VectorPosition, finalVectorMagnitude, SumOfVectors = [0, 0, 0];


        for (counter = 0; counter < StaticModule.StaticElements.length; counter++) { // Loop through all objects in the Static Objects array

            ChargeMagnitude = StaticModule.StaticElements[counter].charge;
            VectorPosition = StaticModule.StaticElements[counter].position;
            finalVectorMagnitude = StaticModule.calculateForceBetweenCharges(1, ChargeMagnitude, position, VectorPosition);
           
            var finalVectorDirection = [-StaticModule.StaticElements[counter].position[0] + position[0], -StaticModule.StaticElements[counter].position[1] + position[1], -StaticModule.StaticElements[counter].position[2] + position[2]];
            if (NewtonianModule.magnitudeOfVector(finalVectorDirection) == 0) {
                finalVector = [0, 0, 0];
                // ignores the vector if the position is on a charge as field line would be of infinite length
            } else {
                var finalVector = NewtonianModule.calcVectorFromMagnitudeAndDirection(finalVectorMagnitude, finalVectorDirection);
            }
            SumOfVectors = NewtonianModule.addVectors(finalVector, SumOfVectors);
        }

        
        // field interactions 

        for (counter = 0; counter < this.FieldArray.length; counter++) {
            if (this.FieldArray[counter].type == "electric") {
                SumOfVectors = NewtonianModule.addVectors(SumOfVectors, this.FieldArray[counter].strength);
            }
        }
        // end field 

        return SumOfVectors; // returns the final sum of the forces acting at a test charge at a point indicated in position
    }

    resolveAtAPoint2(Q1, position) {
        // the resolve at a point method with modifications for the dynamic module
        // it resolves around dynamic objects and then returns a vector
        var counter;
        var ChargeMagnitude, VectorPosition, finalVectorMagnitude, SumOfVectors = [0, 0, 0];

        for (counter = 0; counter < StaticModule.StaticElements.length; counter++) { // Loop through all objects in the Static Objects array
            ChargeMagnitude = StaticModule.StaticElements[counter].charge; // get the charge of each element in the array
            VectorPosition = StaticModule.StaticElements[counter].position; // get the position of the charge in the array
            finalVectorMagnitude = StaticModule.calculateForceBetweenCharges(Q1, ChargeMagnitude, position, VectorPosition); // calculate the force vector
           
            var finalVectorDirection = [StaticModule.StaticElements[counter].position[0] - position[0],
                StaticModule.StaticElements[counter].position[1] - position[1],
                StaticModule.StaticElements[counter].position[2] - position[2]
            ];
            if (NewtonianModule.magnitudeOfVector(finalVectorDirection) == 0) {
                var finalVector = [0, 0, 0];
                // ignores the vector if the position is on a charge as field line would be of infinite length
            } else {
                if (Q1 * StaticModule.StaticElements[counter].charge < 0) {
                    finalVectorDirection[0] = -finalVectorDirection[0];
                    finalVectorDirection[1] = -finalVectorDirection[1];
                    finalVectorDirection[2] = -finalVectorDirection[2];
					
                    // inverts the direction vector so that the attraction is possible
                    var finalVector = NewtonianModule.calcVectorFromMagnitudeAndDirection(finalVectorMagnitude, finalVectorDirection);

                }
            }
			
            SumOfVectors = NewtonianModule.addVectors(finalVector, SumOfVectors); // sum the vectors at the point
			
        }

        // field 

        for (counter = 0; counter < this.FieldArray.length; counter++) {
            if (this.FieldArray[counter].type == "electric") {
                SumOfVectors = NewtonianModule.addVectors(SumOfVectors, this.FieldArray[counter].strength);
				
            }
        }
        // end field 

        return SumOfVectors; // returns the final sum of the forces acting at a test charge at a point indicated in position
    }

    addObjectToFieldArray(strength, direction, type, lineWidth) {
        // parameters passed in
        // strength - the intensity of the field E for electric fields and B for magnetic in Newtons per Coulomb and Teslas appropriately
        var fieldObject = Field(strength, direction, "red", lineWidth, this.FieldArray.length, type);
        // strength,direction,colour,lineWidth,fieldID,type
        queueHelper.addObjectToQueue(this.FieldArray, fieldObject)
    }


    findForceFromField(fieldID, charge, velocity) {
        var velocity2 = [
            charge * velocity[0],
            charge * velocity[1],
            charge * velocity[2]
        ];
        var forceVector = NewtonianModule.crossProduct(velocity2, this.FieldArray[fieldID].strength);
      
        return forceVector;
    };
};

//DynamicModule end    
// Static Module start

class StaticModule {
    constructor() {
        this.permittivity = 8.85 * 10 ** (-12);
        this.pie = 3.141592653589793;
        this.massOfAnElectron = 9.1093897 * 10 ** (-31);
        this.massOfAProton = 1.6726231 * 10 ** (-27);
        this.StaticElements = [];
    }

    calculateForceBetweenCharges(Q1, Q2, Q1pos, Q2pos) {
        var r = (Q2pos[0] - Q1pos[0]) ** 2 + (Q2pos[1] - Q1pos[1]) ** 2 + (Q2pos[2] - Q1pos[2]) ** 2 //calculate the distance between and square it
        if (r == 0) {
            return 0;
        }
        var Force = (1 / (4 * this.pie * this.permittivity)) * ((Q1 * Q2) / r) //calculate the force between the charges and return it

        return Force;
    };

};

// Static Module end
// Canvas module start

class canvas {
    constructor(worldPos, lightPos, colourLine, lineWidth, lineDensity, timeInterval, chargeSize) {
        var magnets = g.createGroup3D();
        var field = g.createGroup3D(); // the field object contains the singular field vectors to be drawn.
        var all = g.createGroup3D();
        this.objectGroupList = [magnets, field, all]; // object group lists are only defined for drawing.
        this.colour = colourLine;
        this.pie = 3.141592653589793;
        this.worldPos = worldPos;
        this.lineWidth = lineWidth;
        this.lineDensity = lineDensity;
        this.lineLength = 2;
        this.chargeSize = chargeSize;
        this.timeInterval = timeInterval;
        this.fieldLinesObjects = [];
        this.magnets = [];
        g.setPropertyDefault("backgroundColor", "black");

    };

    UpdateCanvas() {
        // routine designed to refresh the entire canvas after a time step
        // plays the simulation going through all the static and dynamic elements evaluating and updating the variables as it goes.
        for (var counter = 0; counter < StaticModule.StaticElements.length; counter++) {
            
            StaticModule.StaticElements[counter].updateResultantForce();
            StaticModule.StaticElements[counter].interactionWithTheField(); 
          
            StaticModule.StaticElements[counter].updateVelocity(); 
            StaticModule.StaticElements[counter].updatePosition(1); 
        };
    };




    createCharge(mass, position, type, chargeQuantity, velocity) {
        // creates a charge object
        StaticModule.StaticElements.push(new Charge(type, StaticModule.StaticElements.length, [0, 0, 0], position, chargeQuantity, velocity, this.chargeSize, mass));
    }

    updateGroupWithCharges() {
        // updates the drawing group with the charges from the static array
        var counter;
        for (counter = 0; counter != StaticModule.StaticElements.length; counter++) {
            this.objectGroupList[2].addObj(StaticModule.StaticElements[counter].cangoObjectDefinition);

        }
    }


    updateGroupWithMagnets() {
        // updates the drawing group with magnets
        var counter;
        for (counter = 0; counter != this.magnets.length; counter++) {
            this.objectGroupList[2].addObj(this.magnets[counter].drawingGroup);
        }

    }

    createField(strength, direction, type) {
        // creates a field
        DynamicModule.FieldArray.push(new Field(strength, direction, type));
    }

    createMagnet(position, strength,flipPoles) {
        this.magnets.push(new Magnet(position, strength,flipPoles));
    }

    LoadSimulation(contents) {
        // variable         type                purpose
        // contents         array               stores the entire JSON file array
        // defaults         array               stores temporarily the currently inspected object properties to be added
        // counter          integer             counter for the for loop
        // temp             array               stores temporarily the decoded JSON file of the object currently inspected
        var contents = JSON.parse(contents);
        // decoding and assignment canvas
        var defaults = JSON.parse(contents.canvas);
        // assigns the values from the file to the canvas
        this.worldPos = defaults.canvasWorldPos;
        this.lightPos = defaults.canvaslightPos;
        this.lineWidth = defaults.canvasLineWidth;
        this.lineDensity = defaults.canvasLineDensity;
        this.timeInterval = defaults.canvasTimeInterval;
        this.chargeSize = defaults.canvasChargeSize;

        // clears the arrays containing objects
        this.clearDrawingArray();
        // resets the canvas object groups.
        this.fieldLinesObjects = [];
        StaticModule.StaticElements = [];
        DynamicModule.FieldArray = [];
        this.magnets = [];

        // canvas decoding end
        var counter, temp;
        // decoding and assignment for charges
        defaults = JSON.parse(contents.charges); // returns an array of JSON objects
      
        for (counter = 0; counter != defaults.length; counter++) {
            temp = JSON.parse(defaults[counter]);
            this.createCharge(Number(temp.mass), temp.position, temp.type, Number(temp.charge), temp.velocity);
        }
        // decoding and assignment fields
        defaults = JSON.parse(contents.fields);

        for (counter = 0; counter != defaults.length; counter++) {
            temp = JSON.parse(defaults[counter]);			
			
            this.createField(temp.strength, temp.direction, temp.type);
        }

        // decoding and assignment magnets
        defaults = JSON.parse(contents.magnets);

        for (counter = 0; counter != defaults.length; counter++) {
            temp = JSON.parse(defaults[counter]);
            this.createMagnet(temp.position, temp.strength, false);
        }
        // end of decoding
    }

    saveFile(fileName) {
        // variable			type 				purpose
		// canasJSON		JSON object			stores the object containing variables for the canvas
		// object			JSON object			stores the JSON objects and is used in composing the bigger object which is sent
		// json				JSON object			the bigger object which is sent to the php file
		// posting			object				used in posting data to the php file
        var canvasJSON = {
            "canvasWorldPos": this.worldPos, // canvas properties as declared during creation 
            "canvasLightPos": this.lightPos,
            "canvasLineWidth": this.LineWidth,
            "canvasLineDensity": this.lineDensity,
            "canvasTimeInterval": this.timeInterval,
            "canvasChargeSize": this.chargeSize
        };
		
        var object = {
            "canvas": JSON.stringify(canvasJSON),
            "charges": this.composeChargesJSON(),
            "fields": this.composeFieldsJSON(),
            "magnets": this.composeMagnetsJSON()
        };
	
        var json = {
            "username": sessionStorage.getItem("username"),
            "fileName": fileName,
            "content": JSON.stringify(object)
        };
	
        var posting = $.post("SaveSimulation.php",
            JSON.stringify(json),
            function(data) {
                if (data == "success") {
                    alert("File saved successfully");
                } else {
                    alert("Error: File wasn't saved");
                }
            }
        );
 
    }; //	end of method 

    // functions for field line composition start
    composeChargesJSON() {
        // variable             type            purpose
        // obj                  JSON object     stores the content of the JSON file
        // counter              integer         utilized as a counter in for loops
        // array                array           stores the variables to be stringified
        var obj, counter, array = [];
        for (counter = 0; counter != StaticModule.StaticElements.length; counter++) {
			if (StaticModule.StaticElements[counter].type != "pole") {
				obj = {
				"type": StaticModule.StaticElements[counter].type,
				"ID": StaticModule.StaticElements[counter].objectID,
				"resultantForce": StaticModule.StaticElements[counter].resultantForce,
				"position": StaticModule.StaticElements[counter].position,
				"charge": StaticModule.StaticElements[counter].charge,
				"velocity": StaticModule.StaticElements[counter].velocity,
				"mass": StaticModule.StaticElements[counter].mass,
				};
				array.push(JSON.stringify(obj));
			}
        }
        return JSON.stringify(array);
    }

    composeFieldsJSON() {
        // variable             type            purpose
        // obj                  JSON object     stores the content of the JSON file
        // counter              integer         utilized as a counter in for loops
        // array                array           stores the variables to be stringified
        var obj, counter, array = [], tempStrength;
        for (counter = 0; counter != DynamicModule.FieldArray.length; counter++) {
            tempStrength = DynamicModule.FieldArray[counter].strength;
			obj = {
                "direction": DynamicModule.FieldArray[counter].direction,
                "strength": ((tempStrength[0]**2+tempStrength[1]**2+tempStrength[2]**2)**0.5),
                "type": DynamicModule.FieldArray[counter].type
				
            };
	      array.push(JSON.stringify(obj));
        }
        return JSON.stringify(array);
    }

    composeMagnetsJSON() {
        // variable             type            purpose
        // obj                  JSON object     stores the content of the JSON file
        // counter              integer         utilised as a counter in for loops
        // array                array           stores the variables to be stringified
        var obj, counter, array = [];
        for (counter = 0; counter != this.magnets.length; counter++) {
			obj = {
                "position": this.magnets[counter].position,
                "strength": this.magnets[counter].strength
            };
            array.push(JSON.stringify(obj));
        }
        return JSON.stringify(array);
    }

    composeFieldLine(positionOfFieldLine) {
        // create a fieldLine
        // assign the correct value to the magnitude of the field line
        // assign a drawing group to the field line
        // change the drawing group by modifying vectors using the rotations
        // assign the new drawing group to the object
        // return object
        var temp;
        var fieldLine = DynamicModule.resolveAtAPoint(positionOfFieldLine);

        if (NewtonianModule.magnitudeOfVector(fieldLine) > this.lineLength) {
            fieldLine = NewtonianModule.calcVectorFromMagnitudeAndDirection(this.lineLength, fieldLine);
        }

        temp = NewtonianModule.addVectors(positionOfFieldLine, fieldLine);

        // cango object definition
        var fieldLineDrawing = g.compilePath3D(["M", positionOfFieldLine[0], positionOfFieldLine[1], positionOfFieldLine[2],
            "L", positionOfFieldLine[0], positionOfFieldLine[1], positionOfFieldLine[2], temp[0], temp[1], temp[2],
            "Z"], this.colour, this.lineWidth); //compiles the path of the arrow for the simulation        
        // end of object definition
        return new fieldLineObject(positionOfFieldLine, fieldLine, fieldLineDrawing); // return the field line object
    }; // end function


    populateCanvasWithFieldlines(xMax, yMax, zMax, xMin, yMin, zMin) {
       // variable             type            purpose
       // x                     integer         it's a counter used while looping around the x coordinate
       // y                     integer         it's a counter used while looping around the y coordinate
       // z                     integer         it's a coutner used while looping around the z coordinate
        var x, y, z;
        for (x = xMin; x < xMax; x += this.lineDensity) {
            for (y = yMin; y < yMax; y += this.lineDensity) {
                for (z = zMin; z < zMax; z += this.lineDensity) {
                    this.fieldLinesObjects.push(this.composeFieldLine([x, y, z])); // composes a field line object for each arrow.
                }
            }
        }
        // adds the field lines during the static render
        for (x = 0; x != this.fieldLinesObjects.length; x++) {
            this.objectGroupList[2].addObj(this.fieldLinesObjects[x].drawingGroup);
        }
    };


    clearDrawingArray() {
        // resets the drawing group
        this.objectGroupList[2] = "";
        this.objectGroupList[2] = g.createGroup3D();
        g.clearCanvas();
    };

    // functions for field line composition end
};
// Canvas Module end

// class Charge start
class Charge {

    constructor(type, ID, resultantForce, position, charge, velocity, radius, mass) {
        this.mass = mass; // float
        this.radius = radius; // float
        this.position = position; // array
        
        this.type = type; // string
        this.objectID = ID; // integer
        this.charge = charge; // float
        this.velocity = velocity; // array
        this.resultantForce = resultantForce; // array
        if (this.charge < 0) {
            this.colour = "blue";
        } else {
            this.colour = "red";
        }
        this.cangoObjectDefinition = g.objectOfRevolution3D(shapes3D.circle(this.radius), 0, 20, this.colour, this.colour, true);
        this.cangoObjectDefinition.translate(this.position[0], this.position[1], this.position[2]);
        // The definition of the charge as a Cango3D context

    };

    updateVelocity(timeFrame) {
        this.velocity = NewtonianModule.addVectors([this.resultantForce[0] / this.mass, this.resultantForce[1] / this.mass, this.resultantForce[2] / this.mass], this.velocity);
        this.cangoObjectDefinition.transform.translate(this.velocity[0], this.velocity[1], this.velocity[2]);
    };
    updatePosition(timeFrame) {
        this.position = NewtonianModule.addVectors(this.position, [this.velocity[0] / timeFrame, this.velocity[1] / timeFrame, this.velocity[2] / timeFrame]); // adds the velocity increment to its position
        
    };
    updateResultantForce() {
        // variable             type            purpose
        // counter              integer         it's a counter in the for loop
        var counter;
        for (counter = 0; canvas.objectGroupList.length != counter; counter++) {
            this.resultantForce = DynamicModule.resolveAtAPoint2(this.charge, this.position);
        };
    };

    interactionWithTheField() {
        // variable             type            purpose
        // counter              integer         it's a counter in the for loop
        for (var counter = 0; counter < DynamicModule.FieldArray.length; counter++) {
            if (DynamicModule.FieldArray[counter].type == "magnetic") {
                this.resultantForce = NewtonianModule.addVectors(DynamicModule.findForceFromField(counter, this.charge, this.velocity), this.resultantForce); // adds the vectors for each field in the field array
            }
            
        };
    };
    // end function

};

// class charge end
// class fieldLine start

class fieldLineObject {
    constructor(position, fieldVector, drawingGroup) {
        this.position = position;
        this.fieldVector = fieldVector;
        this.drawingGroup = drawingGroup;
    };
};

// class fieldLine end

//class field start

class Field {
    constructor(magnitude, direction, type) {
        this.type = type // "electric" or "magnetic"
        this.strength = NewtonianModule.calcVectorFromMagnitudeAndDirection(magnitude, direction); // strength is also known as a field vector - it gives the direction and magnitude

        this.direction = direction;
        this.objectID = DynamicModule.FieldArray.length;

    };
    // class field end
};
// Object classes End

//Prototype classes

class Magnet {

    constructor(position, strength,flipPoles) {
        this.drawingGroup = g.createGroup3D();
        this.position = position;
        this.strength = strength;
        this.lineWidth = 1;
        this.position = position;
        this.poles = []; // [Northpole, Southpole]
        this.strength = strength;
        this.composeBarMagnet(flipPoles);
    };
    composeCube(constructionArray, colour) {
        var drawingGroup = g.createGroup3D();
        /// construction of the cube
        var side;
        side = g.compileShape3D(constructionArray, colour, colour, this.lineWidth);
        // side 1
        drawingGroup.addObj(side);
        side = g.compileShape3D(constructionArray, colour, colour, this.lineWidth);
        // side 2
        side.rotate(0, 1, 0, 90);
        drawingGroup.addObj(side);
        side = g.compileShape3D(constructionArray, colour, colour, this.lineWidth);
        // side 3
        side.rotate(1, 0, 0, 270);
        drawingGroup.addObj(side);
        side = g.compileShape3D(constructionArray, colour, colour, this.lineWidth);
        // side 4
        side.translate(0, 0, -10);
        drawingGroup.addObj(side);
        // side 5
        side = g.compileShape3D(constructionArray, colour, colour, this.lineWidth);
        side.rotate(1, 0, 0, 270);
        side.translate(0, 50, 0);
        drawingGroup.addObj(side);
        // side 6
        side = g.compileShape3D(constructionArray, colour, colour, this.lineWidth);
        side.rotate(0, 1, 0, 90);
        side.translate(50, 0, 0);
        drawingGroup.addObj(side);
        return drawingGroup;
        /// construction of the cube end
    }

    composeBarMagnet(flipPoles) {
        var temp,constructionArray = ["M", 0, 0, 0, "L", 50, 0, 0, 50, 50, 0, 0, 50, 0, "Z"]; // delares a shape for a wall


		if (flipPoles == true) {        
			this.poles.push(new Charge("pole", 0, [0, 0, 0], [25, 25, -25], (-this.strength / 2) * 10 ** (13), [0, 0, 0], 0.1, 0)); // creates a South pole
			this.poles.push(new Charge("pole", 0, [0, 0, 0], [25, 75, -25], (this.strength / 2) * 10 ** (13), [0, 0, 0], 0.1, 0)); // creates a North pole
			this.drawingGroup.addObj(this.composeCube(constructionArray, "red")); // adds the cube to the drawing group
			temp = this.composeCube(constructionArray, "blue");
			temp.translate(0, 50, -1); // moves the second cube to create a rectangular magnet
			this.drawingGroup.addObj(temp); // adds the cube to the drawing group

		} else {
            // position  [25,25,-25]
            
            // position  [25,75,-25]
			this.poles.push(new Charge("pole", 0, [0, 0, 0], [this.position[0]+25,this.position[1]+25,this.position[2]-25], (this.strength / 2)*(10**-2), [0, 0, 0], 0.1, 0)); //creates a North pole
            StaticModule.StaticElements.push(this.poles[0]);
			this.poles.push(new Charge("pole", 0, [0, 0, 0], [25+this.position[0], 50 + this.position[1], this.position[2]-25], (-this.strength / 2)*(10**-2), [0, 0, 0], 0.1, 0)); // 
            StaticModule.StaticElements.push(this.poles[1]);
            //creates a South pole
			this.drawingGroup.addObj(this.composeCube(constructionArray, "red")); // adds the cube to the drawing group
			temp = this.composeCube(constructionArray, "blue");
			temp.translate(0, 50, -1); // moves the second cube to create a rectangular magnet
			this.drawingGroup.addObj(temp); // adds the cube to the drawing group
  
        }
		this.drawingGroup.translate(this.position[0],this.position[1],this.position[2]);
    };
};