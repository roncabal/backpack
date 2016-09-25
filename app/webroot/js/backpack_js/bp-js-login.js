var drop = false, claimfile = false;

function closeThisModal(toclose)
{
	if(toclose == "claimfile" && claimfile == true)
	{
		$("#modalBackground").hide();
		$("#claimModalHolder").hide();
		$("#claimCode").val("");
		claimfile = false;
	}
}

$(document).ready(function()
{
	$("#loginHolder").hide();
	checkTextbox("nametag");
	checkTextbox("lockcode");

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
	

	$("#loginDropdown").live("click", function()
	{
		if (drop == false)
		{
			$("#loginHolder").css({
				display:"block"
			}).hide().slideToggle("slow");
			drop = true;
			/*$("#nametag").focus();*/
		}
		else
		{
			$("#loginHolder").slideToggle("slow");
			drop = false;
		}
	});

	$(".close").click(function(){
		$("#regSuccess").hide();
	});

	$("#claimFileHolder").bind("click", function(){
		$("#modalBackground").show();
		$("#claimModalHolder").show();
		claimfile = true;
	});

	$("#claimFileButton").bind("click", function(){
		window.location = download_url + $("#claimCode").val();
	});

});

function checkTextbox(textbox)
{
	if($("#" + textbox).val() != "")
	{
		$("#"+textbox+"Placeholder").hide();
	}
}