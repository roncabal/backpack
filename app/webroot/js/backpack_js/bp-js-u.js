var rightpanel = false, optiondrop = false;
var xActivity;
var actpresent = 0;

function dropOptions()
{
	if(optiondrop == false){
		$("#userOptionHolder").css({ "display": 'inline-block', 'z-index': 3000});
		optiondrop = true;
	}
	else
	{
		$("#userOptionHolder").hide().css({ 'z-index': 0});
		optiondrop = false;
	}
}

function toggleRightPanel()
{
     if(rightpanel == false){
        $("#rightPanelHolder").css({ 'right': '0px', 'z-index': 5000});
        $("#rightPanelToggler").css({'right' : "300px"});
        $("#rightPanelTogglerArrow").css({'border-right': '0px', 'border-left' : "5px solid #777777"});

        if(xActivity && xActivity.readyState != 4)
        {
            xActivity.abort();
        }
        $("#bagActivityHolder").append('<div class="divLoad"><div class="loader-background"></div><div class="loading">' + loader_url + '</div></div>');

        xActivity = $.ajax({
            url:activity_url,
            type:"POST",
            data:{"number" : 0},
            dataType : "json",
            success : function(result)
            {
                if(result.status == "success")
                {
                    for(var i=result.activities.length - 1;i>=0;i--)
                    {
                        if(document.getElementById(result.activities[i].act_id))
                        {
                            $("#"+ result.activities[i].act_id +" .user-activity").html('<h4>'+ result.activities[i].act_title +'</h4>');
                            $("#"+ result.activities[i].act_id +" .activity-time").html('<h6>'+ result.activities[i].act_time +'</h6>');
                        }
                        else
                        {
                            actpresent++;
                            $("#activityPlace").prepend('<div id="'+ result.activities[i].act_id +'" data-desc="'+ result.activities[i].act_desc +'" class="bag-activity bg-color-'+ result.activities[i].color_code +' tile">' +
                                '<div class="user-activity">' +
                                    '<h4>'+ result.activities[i].act_title +'</h4>' +
                                '</div>' +
                                '<div class="activity-time">' +
                                    '<h6>'+ result.activities[i].act_time +'</h6>' +
                                '</div>' +
                            '</div>');
                        }
                    }
                }
                else if(result.status == "error")
                {
                    $("#activityPlace").append('<div style="position:absolute;top:100px;left:0px;right:0px;margin:auto;height:20px; width:120px;">' +result.error_msg + '</div>');
                }
                $('#bagActivityHolder').tinyscrollbar();
                $('#bagActivityHolder .divLoad').remove();
            }
        });
        rightpanel = true;
    }
    else
    {
        $("#rightPanelHolder").css({ 'right' : '-9999px', 'z-index': 0});
        $("#rightPanelToggler").css({'right' : "0px"});
        $("#rightPanelTogglerArrow").css({'border-left': '0px', 'border-right' : "5px solid #777777"});
        rightpanel = false;
    }
}

$(document).ready(function(){

    $('#bagActivityHolder').tinyscrollbar();
    $('#chatBoxHolder').tinyscrollbar();

	$("#optionTrigger").bind("mouseover", function(){
        $("#optionTrigger").css({"background":"#EEEEEE"});
        $("#optionArrow").css({"border-top":"5px solid #000000"});
    }).bind("mouseout", function(){
        if(optiondrop == false){
            $("#optionTrigger").css({"background":"transparent"});
            $("#optionArrow").css({"border-top":"5px solid #BBBBBB"});
        }
    }).bind("click", function(){
        if(optiondrop == true)
        {
            $("#optionTrigger").css({"border":"1px solid #BBBBBB", "border-bottom":"0px"});
        }
        else if(optiondrop == false)
        {
            $("#optionTrigger").css({"border":"0px"});
        }
        
    });

    $(window).resize(function(){
        $('#bagActivityHolder').tinyscrollbar();
        $('#chatBoxHolder').tinyscrollbar();
    });

      /*Placeholder Options*/
     $('.bp-element').live("focus", function() 
        {
            placeholder = $('label[for="'+$(this).attr('id')+'"]');
            element = $(this).attr('id');
            placeholder.hide();
            $(".placeholderPlace").css({'z-index':'-1'});
        }).live("focusout", function() {
            oldplaceholder = placeholder;
            if($(this).val() == ''){
                oldplaceholder.show();
                $(".placeholderPlace").css({'z-index':'0'});
            }
        });
    /*Placeholder Options End*/

    /*Body Context*/
    $('body').live('contextmenu', function(e)
    {
        closeContexts();
        var window_width = $(window).width();
        var window_height = $(window).height();
        var menu_width = $("#bodyContext").width();
        var menu_height = 200;
        var display_x = e.pageX + 'px';
        var display_y = e.pageY + 'px';
        if(e.pageX > window_width - menu_width)
        {
            display_x = (e.pageX - menu_width) +'px';
        }
        
        if(e.pageY > window_height - menu_height)
        {
            display_y = (e.pageY - menu_height) + 'px';
        }
        
        $('#bodyContext').css({
            top: display_y,
            left: display_x,
            display: 'inline'
        });
        return false;
    }).bind('click', function()
    {
        closeContexts();
    });
    /*Body Context End*/

});

function closeContexts()
{
     $('#bodyContext').css({
        display: 'none'
    });

    $('#fileContext').css({
        display: 'none'
    });

    $('#fileFieldContext').css({
        display: 'none'
    });
}

var toast;
function showToast(message)
{
    if(toast)
    {
        clearTimeout(toast);
    }

    $("#ajaxMessage").html(message).css({"display":"inline-block"});
    $(".ajax-message").fadeIn();
    toast = setTimeout(function(){
        $(".ajax-message").fadeOut();
    }, 10000);
}

function ucFirst(string) {
    return string.substring(0, 1).toUpperCase() + string.substring(1).toLowerCase();
}

function cutstring(string, length)
{
    if(string.length > length)
    {
        return string.substr(0, length) + '...';
    }
    
    return string;
}