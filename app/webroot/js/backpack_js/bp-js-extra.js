var xRetrieve, xMessage, xSendMessage, xGetLast;
var pendingmessage = new Array();
var selectedbagmate = "";
var messagesshown = 0;
var maxmessage = false, submenu = false;

function sendMessage()
{
    var message = $("#userChat").val();
    if(message != "")
    {
        var data = {"messages":[{"message":message, "sender":true, "date_sent":""}]};
        fillChatBox(data, "chatting");
        $("#userChat").val("");

        pendingmessage.push({"message":message, "bagmate":selectedbagmate});
        sendMessageNow(pendingmessage);
    }
   
}

function sendMessageNow(to_send)
{
    if(xSendMessage && xSendMessage.readyState != 4)
    {
        return;
    }

    xSendMessage = $.ajax({
        url:sendmsg_url,
        data:{"messages":to_send},
        dataType:"json",
        type:"POST",
        success:function(data){
            if(data.status == "success")
            {
                messageSent(data.sent_count);
            }
            else if(data.status == "error")
            {

            }
        }
    });
}

function subMenu()
{
    if(submenu)
    {
        $("#subMenuHolder").slideUp("slow");
        submenu = false;
    }
    else
    {
        $("#subMenuHolder").slideDown("slow");
        submenu = true;
    }

}

function messageSent(count)
{
    pendingmessage.splice(0, count);
    if(pendingmessage.length != 0)
    {
        sendMessageNow(pendingmessage);
    }
}

var timestamp = 0, online = 0, offline = 0, allbagmates = 0, used = 0;
function retrieve()
{
    if(xRetrieve && xRetrieve.readyState != 4)
    {
        xRetrieve.abort();
    }

    xRetrieve = $.ajax({
        url:retrieve_url,
        data:{"timestamp":timestamp, "online":online, "offline":offline, "selectedbagmate":selectedbagmate, "allbagmates":allbagmates, "used":used},
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

                if(data.getbagmates == true)
                {
                    online    = data.online_bagmates;
                    offline   = data.offline_bagmates;
                    allbagmates = data.all_bagmates;
                    fillBagmatesPlace(data);
                }

                if(data.new_messages == true)
                {
                    fillChatBox(data, "chatting");
                }
            }
            else if(data.status == "error")
            {

            }
            else if(data.status == "renew")
            {
                xRetrieve.abort();
            }
            retrieve();
        }
    });
}

function fillChatBox(data, method) 
{   
    var bagmatechat = '';
    for(var i=data.messages.length -1;i>=0;i--)
    {
        if(data.messages[i].sender == true)
        {
            bagmatechat += '<div class="user-chat bg-color-blueDark"> <div class="user-message"> <p class="fg-color-white">'+ data.messages[i].message +'</p><h6 class="fg-color-white">'+ data.messages[i].date_sent +'</h6> </div> <div class="user-message-arrow-right"> </div> </div>';
        }
        else
        {
            bagmatechat += '<div class="bagmate-chat bg-color-pink"> <div class="bagmate-message"> <p class="fg-color-white">'+ data.messages[i].message +'</p><h6 class="fg-color-white">'+ data.messages[i].date_sent +'</h6> </div> <div class="bagmate-message-arrow-left"> </div> </div>';
        }
        messagesshown++;
    }


    if(method == "open" || method == "chatting")
    {
        if(method == "open")
        {
            $("#chatHolder .overview").html(bagmatechat);
            if(data.messages.length < 9)
            {
                maxmessage = true;
            }
        }
        else if(method == "chatting")
        {
            $("#chatHolder .overview").append(bagmatechat);
        }

        $("#chatHolder").tinyscrollbar();
        var msgscrollheight = $("#chatHolder .thumb").css("height");
        var bottom = 400 - parseInt(msgscrollheight);
        var messageheight = $("#chatHolder .overview").height();
        if(messageheight > 400)
        {
            $("#chatHolder .thumb").css({"top":bottom +"px"});
            $("#chatHolder .overview").css({"top":400-messageheight +"px"});
        }
    }
    else if(method == "more")
    {
        if(data.messages.length < 9)
        {
            maxmessage = true;
        }
        var oldheight = $("#chatHolder .overview").height();
        var oldscrollheight = $("#chatHolder .thumb").height();
        $("#chatHolder .overview").prepend(bagmatechat);
        $("#chatHolder").tinyscrollbar();
        var newscrollheight = $("#chatHolder .thumb").height();
        var newheight = $("#chatHolder .overview").height();
        if(oldheight > 400)
        {
            $("#chatHolder .thumb").css({"top":newscrollheight * 2 +"px"});
            $("#chatHolder .overview").css({"top":oldheight - newheight +"px"});
        }
    }
    
    
}

function fillBagmatesPlace(data)
{
    var mybagmates = "";
    $(".user-bagmate").die("click");
    for(var i=0;i<data.bagmates.length;i++)
    {
        var color = "darken";
        var bagmatemessage = '';
        if(data.bagmates[i].bagmate_status == true)
        {
            color = "green";
        }

        if(data.bagmates[i].bagmate_message > 0)
        {
            if(data.bagmates[i].bagmate_message > 1)
            {
                bagmatemessage = data.bagmates[i].bagmate_message + " new messages";
            }
            else
            {
                bagmatemessage = data.bagmates[i].bagmate_message + " new message";
            }
        }
        mybagmates += '<div id="b_'+ data.bagmates[i].bagmate_id +'" class="user-bagmate"> <div class="bagmate-image"> <img src="'+ data.bagmates[i].bagmate_image +'" width="60" height="60" /> </div> <div class="bagmate-name"> <h4>'+ data.bagmates[i].bagmate_name +'</h4> </div><div class="bagmate-messages-noti"><h6>'+ bagmatemessage +'</h6></div> <div class="online-stat bg-color-'+ color +'"></div> </div>'; }

    $("#bagmatesPlace").html(mybagmates);
    if(selectedbagmate != "")
    {
        $("#"+selectedbagmate).addClass("bg-color-blue");
    }

    $(".user-bagmate").live("click", function(){
        if(selectedbagmate != "")
        {
            $("#"+selectedbagmate).removeClass("bg-color-blue");
        }
        selectedbagmate = $(this).attr("id");
        $(this).addClass("bg-color-blue");
        openChatBox();
    });
}

function openChatBox()
{
    $("#noSelectedBagmate").remove();
    $("#pocketPlaceHolder").append('<div class="divLoad"><div class="loader-background"></div><div class="loading">' + loader_url + '</div></div>');
    $("#" + selectedbagmate + " .bagmate-messages-noti h6").html("");
    messagesshown = 0;
    maxmessage = false;

    if(xMessage && xMessage.readyState != 4)
    {
        xMessage.abort();
    }

    xMessage = $.ajax({
        url:getmsg_url,
        data:{"selectedbagmate":selectedbagmate, "allmessages":messagesshown},
        dataType:"json",
        type:"POST",
        success:function(data){
            if(data.status == "success")
            {
                $("#chatBagmateHolder").show();
                $("#pocketPlaceHolder .divLoad").remove();
                $("#bagmateName").html(data.bagmate_first + " " + data.bagmate_last + " ("+ data.bagmate_nametag +")");
                $("#bagmateImage").attr("src", data.bagmate_img);
                $("#userImage").attr("src", data.user_img);
                fillChatBox(data, "open");
            }
            else if(data.status == "error")
            {
                showToast(data.error_msg);
            }
            retrieve();
        }
    });
}

$(document).ready(function(){

    $('#bagmatesPlace').tinyscrollbar();
    retrieve();

    window.onload = function(event) {
        
    }

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

    var mousedrag = false;
    $("#bagActivityHolder .thumb").live("mousedown", function(){
        mousedrag = true;
    }).live("mouseup", function(){
        if(mousedrag == true)
        {
            var scrollHeight = $(this).height();
            var top          = parseInt($(this).css('top'));

            if((scrollHeight + top) >= 230)
            {
                if(xActivity && xActivity.readyState != 4)
                {
                    xActivity = $.ajax({
                            url:activity_url,
                            type:"POST",
                            data:{"number" : actpresent},
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
                                            $("#activityPlace").append('<div id="'+ result.activities[i].act_id +'" data-desc="'+ result.activities[i].act_desc +'" class="bag-activity bg-color-'+ result.activities[i].color_code +' tile">' +
                                                '<div class="activity-icon">' +
                                                    '<img src="'+ result.activities[i].img_path +'" width="30" height="30" >' +
                                                '</div>' +
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
                                else(result.status == "error")
                                {
                                    $("#activityPlace").append(result.error_msg);
                                }
                            $('#bagActivityHolder').tinyscrollbar();
                            $(this).css({'top' : top +"px"});
                            mousedrag = false;
                        }
                    });
                }
            }
        }
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

    $("#myActivities").live("mouseover", function(){
        $("#activityHelper").show();
    }).live("mouseout", function(){
        $("#activityHelper").hide();
    });

    $("#userChat").keypress(function(e){
        if(e.which == 13)
        {
            e.preventDefault();
            sendMessage();
        }
    });

    $('body').bind("mouseup", function(){
        if(scrollmessage == true)
        {
            scrollmessage = false;
        }
    }).bind("mousemove", function(){
        if(maxmessage == false && scrollmessage == true)
        {
            var ontop = parseInt($("#chatHolder .thumb").css("top"));
            if(ontop <= 20)
            {
                if(xGetLast && xGetLast.readyState != 4)
                {
                    return;
                }
                $("#chatHolder").append('<div class="divLoad"><div class="loader-background"></div><div class="loading">' + loader_url + '</div></div>');

                xGetLast = $.ajax({
                    url:getmsg_url,
                    data:{"selectedbagmate":selectedbagmate, "allmessages":messagesshown},
                    dataType:"json",
                    type:"POST",
                    success:function(data){
                        if(data.status == "success")
                        {
                            $("#chatBagmateHolder").show();
                            $("#pocketPlaceHolder .divLoad").remove();
                            $("#bagmateName").html(data.bagmate_first + " " + data.bagmate_last + " ("+ data.bagmate_nametag +")");
                            $("#bagmateImage").attr("src", data.bagmate_img);
                            maxmessage = data.max_messages;
                            fillChatBox(data, "more");
                            $("#chatHolder .divLoad").remove();
                        }
                        else if(data.status == "error")
                        {
                            showToast(data.error_msg);
                        }
                        retrieve();
                    }
                });
            }
        }
    });

    var scrollmessage = false;
    $("#chatHolder .thumb").live('mousedown', function(){
        scrollmessage = true;
    });

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
