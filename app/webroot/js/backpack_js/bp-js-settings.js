var changename = false, changelockcode = false, changebottomlockcode = false, changeorganizer = false, changeoverwrite = false; changeavatar = false;
var xChangeName, xChangeLockcode, xRetrieve, xBottomLockcode, xOrganizer, xOverwrite;

function closeThisModal(toclose)
{
    if(toclose == "changename" && changename == true)
    {
        $("#modalBackground").hide();
        $("#changeNameHolder").hide();
        $("#firstName").val("");
        $("#lastName").val("");
        changename = false;
    }

    if(toclose == "changelockcode" && changelockcode == true)
    {
        $("#modalBackground").hide();
        $("#changeLockcodeHolder").hide();
        $("#oldLockcode").val("");
        $("#newLockcode").val("");
        changelockcode = false;
    }

    if(toclose == "changebottomlockcode" && changebottomlockcode == true)
    {
        $("#modalBackground").hide();
        $("#changeBottomLockcodeHolder").hide();
        $("#oldBottomLockcode").val("");
        $("#newBottomLockcode").val("");
        changebottomlockcode = false;
    }

    if(toclose == "changeorganizer" && changeorganizer == true)
    {
        $("#modalBackground").hide();
        $("#changeOrganizerHolder").hide();
        changeorganizer = false;
    }

    if(toclose == "changeoverwrite" && changeoverwrite == true)
    {
        $("#modalBackground").hide();
        $("#changeOverwriteHolder").hide();
        changeoverwrite = false;
    }

    if(toclose == "changeavatar" && changeavatar == true)
    {
        $("#modalBackground").hide();
        $("#changeAvatarHolder").hide();
        changeavatar = false;
    }
}

function showChangeName()
{
    $("#modalBackground").show();
    $("#changeNameHolder").show();
    changename = true;
}

function changeNameNow()
{
    if(xChangeName && xChangeName.readyState != 4)
    {
        xChangeName.abort();
    }

    var firstname = $("#firstName").val();
    var lastname  = $("#lastName").val();
    $("#changeNameHolder").append('<div class="divLoad"><div class="loader-background"></div><div class="loading">' + loader_url + '</div></div>');
    xChangeName = $.ajax({
        url:changename_url,
        data:{"firstname":firstname, "lastname":lastname},
        dataType:"json",
        type:"POST",
        success:function(data){
            if(data.status == "success")
            {
                showToast(data.success_msg);
                closeThisModal("changename");
                $("#userName").html("Name : " +  data.first_name + " " + data.last_name);
                $("#changeNameHolder .divLoad").remove();
            }
            else if(data.status == "error")
            {
                showToast(data.error_msg);
                $("#changeNameHolder .divLoad").remove();
            }
        }
    });
}

function showChangeLockcode()
{
    $("#modalBackground").show();
    $("#changeLockcodeHolder").show();
    changelockcode = true;
}

function changeLockcodeNow()
{
    if(xChangeLockcode && xChangeLockcode.readyState != 4)
    {
        xChangeLockcode.abort();
    }

    var oldlockcode = $("#oldLockcode").val();
    var newlockcode = $("#newLockcode").val();

    $("#changeLockcodeHolder").append('<div class="divLoad"><div class="loader-background"></div><div class="loading">' + loader_url + '</div></div>');
    xChangeLockcode = $.ajax({
        url:changelockcode_url,
        data:{"oldlockcode":oldlockcode, "newlockcode":newlockcode},
        dataType:"json",
        type:"POST",
        success:function(data){
            if(data.status == "success")
            {
                showToast(data.success_msg);
                closeThisModal('changelockcode');
            }
            else if(data.status == "error")
            {
                showToast(data.error_msg);
            }
            $("#changeLockcodeHolder .divLoad").remove();
        }
    });
}

function changeBottomLockcodeNow()
{
    $("#changeBottomLockcodeHolder").append('<div class="divLoad"><div class="loader-background"></div><div class="loading">' + loader_url + '</div></div>');

    if(xBottomLockcode && xBottomLockcode.readyState != 4) 
    {
        xBottomLockcode.abort();
    }

    var oldbottomcode = $("#oldBottomLockcode").val();
    var newbottomcode = $("#newBottomLockcode").val();
    xBottomLockcode = $.ajax({
        url:changebottom_url,
        data:{"oldbottomcode":oldbottomcode, "newbottomcode":newbottomcode},
        dataType:"json",
        type:"POST",
        success:function(data){
            if(data.status == "success")
            {
                showToast(data.success_msg);
                closeThisModal('changebottomlockcode');
            }
            else if(data.status == "error")
            {
                if(data.error_code == 101)
                {
                    window.location = "./log_out";
                }

                showToast(data.error_msg);
            }

            $("#changeBottomLockcodeHolder .divLoad").remove();
        }
    });
}

var timestamp = 0, usedspace = "";
function retrieve()
{
    if(xRetrieve && xRetrieve.readyState != 4)
    {
        xRetrieve.abort();
    }

    xRetrieve = $.ajax({
        url:retrieve_url,
        data:{"timestamp":timestamp, "usedspace":usedspace},
        dataType:"json",
        success:function(data){
            if(data.status == "success")
            {
                if(data.new_space == true)
                {
                    usedspace = data.s_used;
                    $("#totalSpace h5").html("Total Space: " + data.t_space);
                    $("#spaceUsed h5").html("Space Used: " + data.s_used);
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
            else if(data.status == "error")
            {
                timestamp = 0;
                usedspace = "";
            }
            retrieve();
        }
    });
}

$(document).ready(function(){
    retrieve();
    
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

    $("#myActivities").live("mouseover", function(){
        $("#activityHelper").show();
    }).live("mouseout", function(){
        $("#activityHelper").hide();
    });

    $("#bottomLockcode").bind('click', function(){
        $("#modalBackground").show();
        $("#changeBottomLockcodeHolder").show();
        changebottomlockcode = true;
    });

    $("#buttonChangeBottomLockcode").bind("click", function(){
        changeBottomLockcodeNow();
    });

    $("#oldBottomLockcode").keypress(function(e){
        if(e.which == 13)
        {
            changeBottomLockcodeNow();
        }
    });

    $("#newBottomLockcode").keypress(function(e){
        if(e.which == 13)
        {
            changeBottomLockcodeNow();
        }
    });

    $("#organizerCheck").bind("click", function(){
        $("#modalBackground").show();
        if(org_status == "On")
        {
            $("#organizerTitle").html("<h4>Turn off organizer?</h4>");
            $("#organizerButton").html("Turn off");
        }
        else if(org_status == "Off")
        {
            $("#organizerTitle").html("<h4>Turn on organizer?</h4>");
            $("#organizerButton").html("Turn on");
        }
        $("#changeOrganizerHolder").show();
        changeorganizer = true;
        return false;
    });

    $("#organizerButton").bind("click", function(){
        $("#changeOrganizerHolder").append('<div class="divLoad"><div class="loader-background"></div><div class="loading">' + loader_url + '</div></div>');

        if(xOrganizer && xOrganizer.readyState != 4)
        {
            xOrganizer.abort();
        }

        xOrganizer = $.ajax({
            url:changeorg_url,
            dataType:"json",
            success:function(data){
                if(data.status == "success")
                {
                    showToast(data.success_msg);
                    org_status = data.org_status;
                    if(data.org_status == "On")
                    {
                        $("#organizerCheck").attr("checked", "checked");
                        $("#orgStatus").html("On");
                    }
                    else if(data.org_status == "Off")
                    {
                        $("#organizerCheck").removeAttr("checked"); 
                        $("#orgStatus").html("Off");  
                    }
                    closeThisModal('changeorganizer');
                }
                else if(data.status == "error")
                {
                    showToast(data.error_msg);
                }
                $("#changeOrganizerHolder .divLoad").remove();
            }
        });
    });

    $("#overwriteCheck").bind("click", function(){
        $("#modalBackground").show();
        if(ovr_status == "On")
        {
            $("#overwriteTitle").html("<h4>Turn off overwrite?</h4>");
            $("#overwriteButton").html("Turn off");
        }
        else if(ovr_status == "Off")
        {
            $("#overwriteTitle").html("<h4>Turn on overwrite?</h4>");
            $("#overwriteButton").html("Turn on");
        }
        $("#changeOverwriteHolder").show();
        changeoverwrite = true;
        return false;
    });

    $("#overwriteButton").bind("click", function(){
        $("#changeOverwriteHolder").append('<div class="divLoad"><div class="loader-background"></div><div class="loading">' + loader_url + '</div></div>');

        if(xOverwrite && xOverwrite.readyState != 4)
        {
            xOverwrite.abort();
        }

        xOverwrite = $.ajax({
            url:changeovr_url,
            dataType:"json",
            success:function(data){
                if(data.status == "success")
                {
                    showToast(data.success_msg);
                    if(data.ovr_status == "On")
                    {
                        $("#overwriteCheck").attr("checked", "checked");
                        $("#ovrStatus").html("On");
                    }
                    else if(data.ovr_status == "Off")
                    {
                        $("#overwriteCheck").removeAttr("checked"); 
                        $("#ovrStatus").html("Off");  
                    }
                    closeThisModal('changeoverwrite');
                }
                else if(data.status == "error")
                {
                    showToast(data.error_msg);
                }
                $("#changeOverwriteHolder .divLoad").remove();
            }
        });
    });

    $("#changeAvatar").bind("click", function(){
        $("#modalBackground").show();
        $("#changeAvatarHolder").show();
        changeavatar = true;
    });
});