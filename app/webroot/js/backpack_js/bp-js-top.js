var xRetrieve;
var submenu = false;

var timestamp = 0;
var used = "";
function retrieve()
{
	if(xRetrieve && xRetrieve.readyState != 4)
	{
		xRetrieve.abort();
	}

	xRetrieve = $.ajax({
		url:retrieve_url,
		data:{"timestamp":timestamp, "used":used},
		dataType:"json",
		success:function(data){
			if(data.status == "success")
            {
                timestamp = data.last_visit;
                if(data.new_space == true)
                {  
                    used = data.s_used;
                    $("#totalSpace h5").html("Total Space : " + data.t_space);
                    $("#spaceUsed h5").html("Space Used : " + data.s_used);
                    $("#percentage").css({"width":data.percent + "%"});
                }

                if(data.group_noti == true)
                {
                    $("#myGroupReqNotification").show();
                    $("#myGroupReqNotification h5").html(data.group_notifications);
                }

                if(data.bagmate_noti == true)
                {
                    $('#myMessageNotification').show();
                    $('#myMessageNotification h5').html(data.bagmate_notifications);
                }
                
            }
            else if(data.status == "renew")
            {
                xRetrieve.abort();
            }
            retrieve();
		}
	});
}

function subMenu()
{
    if(submenu)
    {
        $("#subMenuHolder2").slideUp("slow");
        submenu = false;
    }
    else
    {
        $("#subMenuHolder2").slideDown("slow");
        submenu = true;
    }

}


$(document).ready(function(){
    $("#topcontent").tinyscrollbar();
    $("#decaycontent").tinyscrollbar();
    $("#actPanel").tinyscrollbar();
	window.onload = function(event) {
        retrieve();
    }

    $("#myActivities").live("mouseover", function(){
        $("#activityHelper").show();
    }).live("mouseout", function(){
        $("#activityHelper").hide();
    });

    $("#myMessages").live("mouseover", function(){
        $("#messageHelper").show();
    }).live("mouseout", function(){
        $("#messageHelper").hide();
    });

    $("#myGroupReq").live("mouseover", function(){
        $("#memberHelper").show();
    }).live("mouseout", function(){
        $("#memberHelper").hide();
    });
});

