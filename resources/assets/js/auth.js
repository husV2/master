$(document).ready(function(){
	$('[data-toggle="tooltip"]').tooltip();
	 
	var p_pass = $('#password').attr('placeholder'); 
	var p_user = $('#username').attr('placeholder'); 
	
	$("#password").prop("type", "text").val(p_pass);
	$('#username').val(p_user);

	$('#username').focus(function(){
		if($(this).val() === p_user) {
			$(this).val('');
		}
	});
	$('#username').blur(function(){
		if(!$(this).val().length) {
			$(this).val(p_user);
		}
	});
	
	$("#password").focus(function() {
		if($(this).val() === p_pass)
		{
			$(this).prop("type", "password").val("");
		}
	});

	$("#password").blur(function() {
		if(!$(this).val().length)
		{
			 $(this).prop("type", "text").val(p_pass);
		}
	});
	
});