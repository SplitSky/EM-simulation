$(document).ready(function(){
    // declaring objects

// rendering

    $("#render-staticFrame").onclick(function() {
        alert("happen");
        var maxZ;

        if ($("#type-fieldLines").val() == "2D") {
            maxZ = 2;
        } else {
            maxZ = 100;
        }
        canvas.updateGroupWithCharges();
        canvas.populateCanvasWithFieldlines(100, 100, maxZ);
        g.render(canvas.objectGroupList[2]);
    })

    $("#render-magneticFrame").click(function() {
        canvas.updateGroupWithMagnets();
        canvas.populateCanvasWithFieldlines(maxX, maxY, maxZ);
        g.render(canvas.objectGroupList[2]);
    })

    $("#render-dynamic").click(function(){
        canvas.updateGroupWithCharges();
        function play() {
            canvas.UpdateCanvas();
        }
        setInterval(play, 100);
    })

    // creating objects
    // creating a charge
    $("#confirm-charge1").click(function(){
        console.log("creating a charge");
        var mass, position, type, chargeQuantity, velocity

        mass = $("#mass-charge1").val();
        position = [$("#positionX-charge1").val(),$("#positionY-charge1").val(),$("#positionZ-charge1").val()];
        chargeQuantity = $("#charge-charge1").val();
        if (chargeQuantity < 0) {
            type = "electron";
        } else {
            type = "proton";
        }
        velocity = [$("#velocityX-charge1").val(),$("#velocityY-charge1").val(),$("#velocityZ-charge1").val()];
        canvas.createCharge(mass, position, type, chargeQuantity, velocity);

    })

    // creating a field
    $("#confirm-field1").on("click", function() {
        console.log("creating a field");
        var strength, direction, type;
        strength = $("#strength-field1").val();
        direction = [$("#directionX-field1").val(),$("#directionY-field1").val(),$("#directionZ-field1").val()];
        type = $("#type-field1").val().toLowerCase();
        canvas.createField(strength, direction, type);
    })

    // creating magnet
    $("#confirm-magnet1").on("click", function() {
        console.log("creating magnet");
        var position, strength;
        position = [$("#positionZ-magnet1").val(),$("#positionY-magnet1").val(),$("#positionX-magnet1").val()];
        strength = $("#strength-magnet1").val();
        canvas.createMagnet(position, strength);
    })

// refresh functions
})