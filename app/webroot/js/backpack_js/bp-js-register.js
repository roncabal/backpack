var oldplaceholder, placeholder, element = "";
var	c_nametag = false, c_lockcode = false , c_firstname = false, c_lastname = false, c_email = false;
var xNametag, xEmail;

var delay = (function()
{
  var timer = 0;
  return function(callback, ms)
  {
    clearTimeout (timer);
    timer = setTimeout(callback, ms);
  };
})();

$(document).ready(function()
{

	/*On Load Validation*/
	if($('#regNametag').val() != '' && $('#regEmail').val() != '')
	{
		$('#regNametagPlaceholder').hide();
		var rnt = $('#regNametag').val();
		validateNametag(rnt);

		$('#regEmailPlaceholder').hide();
		var re = $('#regEmail').val();
		validateEmail(re);
	}
	else if($('#regNametag').val() != '')
	{
		$('#regNametagPlaceholder').hide();
		var rnt = $('#regNametag').val();
		validateNametag(rnt);
	}
	else if($('#regEmail').val() != '')
	{
		$('#regEmailPlaceholder').hide();
		var re = $('#regEmail').val();
		validateEmail(re);
	}

	if($('#regLockcode').val() != '')
	{
		$('#regLockcodePlaceholder').hide();
		var rlc = $('#regLockcode').val();
		lockcodeCheck(rlc);
	}

	if($('#regFirstName').val() != '')
	{
		$('#regFirstNamePlaceholder').hide();
		var rfn = $('#regFirstName').val();
		firstnameCheck(rfn);
	}

	if($('#regLastName').val() != '')
	{
		$('#regLastNamePlaceholder').hide();
		var rln = $('#regLastName').val();
		lastnameCheck(rln);
	}
	/*On Load Validation End*/

	/*Nametag Textbox*/
	$('#regNametag').keyup(function(e)
	{
		if(e.which > 45 || e.which == 8 || (e.which == 9 && $(this).val() != "")){
			$('#vNametag').html("Checking...").css({ color : '#FFFFFF'});
			delay(function()
			{
				var inputvalue = $('#regNametag').val();
				validateNametag(inputvalue);
			}, 1000);
		}
	}).focusout(function(e)
	{
		var inputvalue = $(this).val();
		if($(this).val() != ""){
			validateNametag(inputvalue);
		}
	}).click(function()
	{
		$(this).css({
			background:'white'
		});
	});
	/*Nametag Textbox End*/

	/*Lockcode Textbox*/
	$('#regLockcode').keyup(function(e)
	{
		var inputvalue = $(this).val();
		if(e.which > 45 || e.which == 8 || (e.which == 9 && $(this).val() != "")){
			lockcodeCheck(inputvalue);
		}
	}).focusout(function(e)
	{
		var inputvalue = $(this).val();
		if($(this).val() != ""){
			lockcodeCheck(inputvalue);
		}
	}).click(function()
	{
		$(this).css({
			background:'white'
		});
	});
	/*Lockcode Textbox End*/

	/*FirstName Textbox*/
	$('#regFirstName').keyup(function(e)
	{
		var inputvalue = $(this).val();
		if(e.which > 45 || e.which == 8 || (e.which == 9 && $(this).val() != "")){
			firstnameCheck(inputvalue);
		}
	}).focusout(function(e)
	{
		var inputvalue = $(this).val();
		if($(this).val() != ""){
			firstnameCheck(inputvalue);
		}
	}).click(function()
	{
		$(this).css({
			background:'white'
		});
	});
	/*FirstName Textbox End*/

	/*LastName Textbox*/
	$('#regLastName').keyup(function(e)
	{
		var inputvalue = $(this).val();
		if(e.which > 45 || e.which == 8 || (e.which == 9 && $(this).val() != "")){
			lastnameCheck(inputvalue);
		}
	}).focusout(function(e)
	{
		var inputvalue = $(this).val();
		if($(this).val() != ""){
			lastnameCheck(inputvalue);
		}
	}).click(function()
	{
		$(this).css({
			background:'white'
		});
	});
	/*LastName Textbox End*/

	/*Email Textbox*/
	$('#regEmail').keyup(function(e)
	{
		if(e.which > 45 || e.which == 8 || (e.which == 9 && $(this).val() != "")){
			$('#vEmail').html("Checking...").css({ color : '#FFFFFF'});
			delay(function(){
				var inputvalue = $('#regEmail').val();
				validateEmail(inputvalue);
			}, 1000);
		}
	}).focusout(function(e)
	{
		if($(this).val() != ""){
			var inputvalue = $(this).val();
			validateEmail(inputvalue);
		}
	}).click(function()
	{
		$(this).css({
			background:'white'
		});
	});
	/*Email Textbox End*/

	/*Submit Form*/
	$('#getBackpack').click(function(event)
	{
		var checked = document.getElementById('iAgree').checked == true ? 1 : 0;

		if(c_nametag == false)
		{
			$('#regNametag').css({background :'#F5CCCC'});
		}

		if(c_lockcode == false)
		{
			$('#regLockcode').css({background :'#F5CCCC'});
		}

		if(c_firstname == false)
		{
			$('#regFirstName').css({background :'#F5CCCC'});
		}

		if(c_lastname == false)
		{
			$('#regLastName').css({background :'#F5CCCC'});
		}

		if(c_email == false)
		{
			$('#regEmail').css({background :'#F5CCCC'});
		}

		if(checked == 0)
		{
			$('#agreeHolder').css({background :'#F5CCCC'});
		}

		if(checked != 1 || c_nametag != true || c_lockcode != true || c_firstname != true || c_lastname != true || c_email != true)
		{
			return false;
		}
		else
		{
			return true;
		}

	});
	/*Submit Form End*/

	/*Right Click Disabler*/
	$('body').bind('contextmenu', function(e)
	{
		$('#oContext').css({
			top: e.pageY+'px',
			left: e.pageX+'px',
			display: 'inline'
		});
		return false;
	}).bind('click', function()
	{
		$('#oContext').css({
			display: 'none'
		});
	});
	/*Right Click Disabler End*/


	/*Placeholder Options*/
	$('.bp-element').focus(function() 
	{
		placeholder = $('label[for="'+$(this).attr('id')+'"]');
		element = $(this).attr('id');
		placeholder.hide();
	}).focusout(function() {
		oldplaceholder = placeholder;
		if($(this).val() == ''){
			oldplaceholder.show();
		}
	});
	/*Placeholder Options End*/

});

/*Validate Nametag*/
function validateNametag(tocheck)
{
	if(xNametag && xNametag.readyState != 4)
	{
		xNametag.abort();
	}

	xNametag = $.ajax({
		url: nametag_url,
		type:"POST",
		dataType:"json",
		data: "check_nametag="+tocheck,
		success: function (result){
			nametagResult(result.r_title, result.r_desc, result.r_valid, result.r_response);
		}
	});
}
/*Validate Nametag End*/

/*Validate Nametag*/
function validateEmail(tocheck)
{

	if(xEmail && xEmail.readyState != 4)
	{
		xEmail.abort();
	}

	xEmail = $.ajax({
		url: email_url,
		type:"POST",
		dataType:"json",
		data: "check_email="+tocheck,
		success: function (result){
			emailResult(result.r_title, result.r_desc, result.r_valid, result.r_response);
		}
	});
}
/*Validate Nametag End*/

/*Nametag Result*/
function nametagResult(title, desc, valid, response)
{
	if(valid == true)
	{
		$("#vNametag").html(response).css({ color :'#007700'});
		c_nametag = true;
	}
	else
	{
		$("#vNametag").html(response).css({ color :'#AA0000'});
		c_nametag = false;
	}
	
}
/*Nametag Result End*/

/*Email Result*/
function emailResult(title, desc, valid, response)
{
	if(valid == true)
	{
		$("#vEmail").html(response).css({ color :'#007700'});
		c_email = true;
	}
	else
	{
		$("#vEmail").html(response).css({ color :'#AA0000'});
		c_email = false;
	}
}
/*Email Result End*/

/*Lockcode Check*/
function lockcodeCheck(inputvalue)
{
	$('#vLockcode').html("Checking...").css({ color : '#FFFFFF'});
		if(inputvalue.length <= 8)
		{
			$('#vLockcode').html("Must be more than 8 characters").css({ color :'#AA0000'});
			c_lockcode = false;
		}
		else
		{
			$('#vLockcode').html("Lock code is filled up correctly.").css({ color :'#007700'});
			c_lockcode = true;
		}
}
/*Lockcode Check*/

/*Firstname Check*/
function firstnameCheck(inputvalue)
{
	$('#vFirstName').html("Checking...").css({ color : '#FFFFFF'});
		if(inputvalue.length <= 0)
		{
			$('#vFirstName').html("Must not be blank").css({ color :'#AA0000'});
			c_firstname = false
		}
		else if(inputvalue.match(/[^A-Za-z ]+/))
		{
			$('#vFirstName').html("Last name must contain letters and spaces only.").css({color : '#AA0000'});
			c_firstname = false;
		}
		else
		{
			$('#vFirstName').html("First name is filled up correctly.").css({ color :'#007700'});
			c_firstname = true;
		}
}
/*Firstname Check End*/

/*Lastname Check*/
function lastnameCheck(inputvalue)
{
	$('#vLastName').html("Checking...").css({ color : '#FFFFFF'});
		if(inputvalue.length <= 0)
		{
			$('#vLastName').html("Must not be blank").css({ color :'#AA0000'});
			c_lastname = false;
		}
		else if(inputvalue.match(/[^A-Za-z ]+/))
		{
			$('#vLastName').html("Last name must contain letters and spaces only.").css({color : '#AA0000'})
			c_lastname = false;
		}
		else
		{
			$('#vLastName').html("Last name is filled out correctly.").css({ color :'#007700'});
			c_lastname = true;
		}
}
/*Lastname Check End*/