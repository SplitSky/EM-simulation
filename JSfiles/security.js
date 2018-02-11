
$document.ready({
	$("#login-submit").click(function(){
		console.log("username: " + $("#username").val());
		console.log("password: " + $("#password").val());
		
		if ($("#username").val() != "" && $("#password").val() != "" && validateEmail($("#username".val())) {
			$.post("<?=loginfile?>",{username: $("#username").val(),
			password: $("#password").val() },
			function(data) {
				if data != "logged in") {
					alert(data);
				} else {
					window.location = "<?=welcomePage?>";
				}
			}
			.done(function( msg) {	
			});
		} else {
			alert("Please fill all fields with valid data!")
		}
		
		
	}
	
	
	
	
	
	
}