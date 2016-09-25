var creategroup = false, groupsettings = false, joingroup = false, groupinfo = false, grouprequests = false, maxmessage = false, groupmembers = false, groupremove = false, addmember = false, leavegroup = false, unsharefiles = false ,submenu = false;
var groupopen = null, lastuserchat = null, selectedfile = null;
var userreply = 0, messagepresent = 0;
var groupspresent = new Array(), pendingmessages = new Array();
var xGroupMembers, xGroupCreate, xRetrieve, xOpenGroup, xSendMessage, xJoinGroup, xGetGroupInfo, xGroupRequests, xAcceptRequest, xPreviousMessage, xLeaveGroup, xAddMember, xDenyRequest, xUnshareFile;

function closeThisModal(toclose)
{
    if(toclose == "creategroup" && creategroup == true)
    {
        $("#modalBackground").hide();
        $("#createGroupHolder").hide();
        $("#addGroup").val("");
        creategroup = false;
    }

    if(toclose == "joingroup" && joingroup == true)
    {
        $("#modalBackground").hide();
        $("#joinGroupHolder").hide();
        joingroup = false;
    }  

    if(toclose == "groupinfo" && groupinfo == true)
    {
        $("#modalBackground").hide();
        $("#groupInfoHolder").hide();
        if(xGetGroupInfo && xGetGroupInfo.readyState != 4)
        {
            xGetGroupInfo.abort();
        }
        groupinfo = false;
    }  

    if(toclose == "grouprequests" && grouprequests == true)
    {
        $("#modalBackground").hide();
        $("#groupRequestsHolder").hide();
        $("#groupRequestsPlace .overview").html("");
        grouprequests = false;
    }

    if(toclose == "groupmembers" && groupmembers == true)
    {
        $("#modalBackground").hide();
        $("#groupMemberHolder").hide();
        $("#groupMemberHolder .overview").html("");
        $(".remove-member").die("click");
        groupmembers = false;
    }

    if(toclose == "groupremove" && groupremove == true)
    {
        $("#modalBackground").hide();
        $("#groupRemoveHolder").hide();
        groupremove = false;
    }

    if(toclose == "addmember" && addmember == true)
    {
        $("#modalBackground").hide();
        $("#groupAddHolder").hide();
        addmember = false;
    }

    if(toclose == "leavegroup" && leavegroup == true)
    {
        $("#modalBackground").hide();
        $("#leaveGroupHolder").hide();
        leavegroup = false;
    }

    if(toclose == "unsharefiles" && unsharefiles == true)
    {
        $("#modalBackground").hide();
        $("#unshareFilesHolder").hide();
        unsharefiles = false;
    }
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


function leaveCheck()
{
    if(groupopen != null)
    {
        $("#modalBackground").show();
        $("#leaveGroupHolder").show();
        leavegroup = true;
    }
    else
    {
        showToast("Please open a group first.");
    }
}

function addGroupMember()
{
    $("#groupAddHolder").append('<div class="divLoad"><div class="loader-background"></div><div class="loading">' + loader_url + '</div></div>');
    if($("#addNametag").val() != "")
    {
        var nametag = $("#addNametag").val();
        if(xAddMember && xAddMember.readyState != 4)
        {
            xAddMember.abort();
        }

        xAddMember = $.ajax({
            url:addmember_url,
            data:{"nametag":nametag, "selectedgroup":groupopen},
            dataType:"json",
            type:"POST",
            success:function(data){
                if(data.status == "success")
                {
                    showToast(data.success_msg);
                    closeThisModal("addmember");
                }
                else if(data.status == "error")
                {
                    showToast(data.error_msg);
                }
                $("#groupAddHolder .divLoad").remove();
            }
        });
    }
}

function showAddMember()
{
    if(groupopen != null)
    {
        closeThisModal("groupmembers");
        $("#modalBackground").show();
        $("#groupAddHolder").show();
        addmember = true;
    }
    else
    {
        showToast("Please open a group first.");
    }
}

function removeMember(removeid)
{
    $("#groupRemoveHolder").show();
    groupremove = true;
    $("#removeNow").bind("click", function(){
        removeFromGroup("remove", removeid);
        closeThisModal("groupremove");
    });

    $("#cancelRemove").bind("click", function(){
         closeThisModal("groupremove");
         getGroupMembers();
    });
}

function getGroupMembers()
{
    if(groupopen != null)
    {
        $("#modalBackground").show();
        $("#groupMemberHolder").show();
        groupmembers = true;
        $("#groupMemberHolder").append('<div class="divLoad"><div class="loader-background"></div><div class="loading">' + loader_url + '</div></div>');
        if(xGroupMembers && xGroupMembers.readyState != 4)
        {
            xGroupMembers.abort();
        }

        xGroupMembers = $.ajax({
            url:groupmembers_url,
            data:{"chosengroup":groupopen},
            dataType:"json",
            type:"POST",
            success:function(data){
                if(data.status == "success")
                {
                    $("#groupMemberHolder .divLoad").remove();
                    var groupmembers = "";
                    for(var i=0;i<data.members.length;i++)
                    {
                        groupmembers += '<div class="member-place"> <div class="member-image"> <img src="'+ data.members[i].img_path +'" width="60" height="60" /> </div> <div class="member-name-holder"> <a href="'+ data.members[i].bagmate_nametag +'"><p class="member-name">'+ cutstring(data.members[i].bagmate_fname + " " + data.members[i].bagmate_lname, 30) +'</p></a> </div>';

                        if(data.group_owner == true)
                        {
                            groupmembers += '<div id="r_'+ data.members[i].bagmate_id +'"  class="remove-member"><button class="bg-color-red fg-color-white">Remove</button> </div>';
                        }

                        groupmembers += '</div>';
                    }

                    $("#groupMemberHolder .overview").html(groupmembers);
                    $("#groupMemberHolder").tinyscrollbar();
                    $(".remove-member").live("click", function(){
                        $("#groupMemberHolder").append('<div class="divLoad"><div class="loader-background"></div><div class="loading">' + loader_url + '</div></div>');
                        var removeid = $(this).attr("id");

                        removeMember(removeid);
                        
                    });
                }
                else if(data.status == "error")
                {
                    closeThisModal("groupmembers");
                    showToast(data.error_msg);
                }
            }
        });
    }
    else
    {
        showToast("Please open a group first.");
    }
}

function getRequestsNow()
{
    if(xGroupRequests && xGroupRequests.readyState != 4)
    {
        xGroupRequests.abort();
    }

    $(".accept-button").die("click");
    $(".deny-button").die("click");
    $('#groupRequestsNotification').hide();
    $('#groupRequestsNotification h5').html("");

    xGroupRequests = $.ajax({
        url:grouprequests_url,
        dataType:"json",
        success:function(data){
            if(data.status == "success")
            {
                var requests = '';
                for(var i=0;i<data.requests.length;i++)
                {
                    if(data.requests[i].request_type == "join")
                    {
                        requests += '<div class="group-requests"> <div class="group-request"> <h5>'+ data.requests[i].nametag +' wishes to join your group '+ data.requests[i].group_name +'</h5> </div> <div class="accept-request center-div-v"> <button id="accept_'+ data.requests[i].request_id +'" class="accept-button request-button bg-color-pink fg-color-white">Accept</button> </div> <div class="deny-request center-div-v"> <button id="deny_'+ data.requests[i].request_id +'" class="deny-button request-button">Deny</button> </div> </div>'; 
                    }
                    else if(data.requests[i].request_type == "add")
                    {
                        requests += '<div class="group-requests"> <div class="group-request"> <h5>'+ data.requests[i].owner_name +' has invited you to join his group '+ data.requests[i].group_name +'</h5> </div> <div class="accept-request center-div-v"> <button id="accept_'+ data.requests[i].request_id +'" class="accept-button request-button bg-color-pink fg-color-white">Accept</button> </div> <div class="deny-request center-div-v"> <button id="deny_'+ data.requests[i].request_id +'" class="deny-button request-button">Deny</button> </div> </div>'; 
                    }
                }

                $("#groupRequestsPlace .overview").html(requests);
            }

            $("#groupRequestsHolder").tinyscrollbar();
            $("#groupRequestsHolder .divLoad").remove();
        }
    });

    $(".accept-button").live("click", function(){
        var request_id = $(this).attr("id");
        $("#groupRequestsHolder").append('<div class="divLoad"><div class="loader-background"></div><div class="loading">' + loader_url + '</div></div>');

        if(xAcceptRequest && xAcceptRequest.readyState != 4)
        {
            xAcceptRequest.abort();
        }

        xAcceptRequest = $.ajax({
            url:acceptreq_url,
            data:{"request_id":request_id},
            dataType:"json",
            type:"POST",
            success:function(data){
                if(data.status == "success")
                {
                    showToast(data.success_msg);
                    retrieve();
                    getRequestsNow();
                }
                else if(data.status == "error")
                {
                    showToast(data.error_msg);
                    closeThisModal("grouprequests");
                }  

            }
        });

    });

    $(".deny-button").live("click", function(){
        var request_id = $(this).attr("id");
        $("#groupRequestsHolder").append('<div class="divLoad"><div class="loader-background"></div><div class="loading">' + loader_url + '</div></div>');

        if(xDenyRequest && xDenyRequest.readyState != 4)
        {
            xDenyRequest.abort();
        }

        xDenyRequest = $.ajax({
            url:denyrequest_url,
            data:{"request_id":request_id},
            dataType:"json",
            type:"POST",
            success:function(data){
                if(data.status == "success")
                {
                    showToast(data.success_msg);
                    getRequestsNow();
                }
                else if(data.status == "error")
                {
                    showToast(data.error_msg);
                    closeThisModal("grouprequests");
                }  
            }
        });
    });
}

function getGroupRequests()
{
    $("#modalBackground").show();
    $("#groupRequestsHolder").show()
    grouprequests = true;
    $("#groupRequestsHolder").append('<div class="divLoad"><div class="loader-background"></div><div class="loading">' + loader_url + '</div></div>');

    getRequestsNow();
}

function createGroupNow()
{
    $("#createGroupHolder").append('<div class="divLoad"><div class="loader-background"></div><div class="loading">' + loader_url + '</div></div>');
    if(xGroupCreate && xGroupCreate.readyState != 4)
    {
        xGroupCreate.abort();
    }

    var group_name = $("#addGroup").val();
    xGroupCreate = $.ajax({
        url: creategroup_url,
        data: {"groupname":group_name},
        type:"POST",
        dataType:"json",
        success:function(data){
            if(data.status == "success")
            {
                retrieve();
                showToast(data.success_msg);
            }
            else if(data.status == "error")
            {
                showToast(data.error_msg);
            }
            
            $(".divLoad").remove();
            closeThisModal('creategroup');
        }
    });
}

var timestamp = 0, groupcount = 0, used = "";
function retrieve()
{
    if(xRetrieve && xRetrieve.readyState != 4)
    {
        xRetrieve.abort();
    }

    xRetrieve = $.ajax({
        url: retrieve_url,
        data: {"last_visit":timestamp, "group_open":groupopen, "group_count":groupcount, "used":used},
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

                if(data.group_requests == true)
                {
                    $('#groupRequestsNotification').show();
                    $('#groupRequestsNotification h5').html(data.request_notifications);
                }

                if(data.group == true)
                {
                    showGroups(data);
                    groupcount = data.group_count;
                }

                if(data.chat == true)
                {
                    fillGroupChat(data, "chatting");
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

function showGroups(data)
{
    $("#groupPlace").append('<div class="divLoad"><div class="loader-background"></div><div class="loading">' + loader_url + '</div></div>');
    if(data.group_count > 0)
    {
        var usergroups = "";
        for(var i=0;i<data.groups.length;i++)
        {

            usergroups += '<div id="' + data.groups[i].group_id + '" class="user-groups"> <div class="group-name"> <h4>' + data.groups[i].group_name + '</h4> </div> <div class="messages-holder"> <div class="group-messages"> <img src="'+ image_url +'icons/messages_darken.gif" width="25" height="25"> </div>';
            if(data.groups[i].msg_noti > 0 && data.groups[i].group_id != groupopen)
            {
                usergroups += '<div class="messages-notification-holder"> <div class="messages-notification bg-color-red"> <h6 class="fg-color-white">' + data.groups[i].msg_noti + '<h6> </div> </div>';
            }

            usergroups += '</div> <div class="new-share-holder"> <div class="new-share-icon"> <img src="'+ image_url +'icons/share_darken.gif" width="25" height="25"> </div>';
             if(data.groups[i].share_noti > 0 && data.groups[i].group_id != groupopen)
            {
                usergroups += '<div class="new-share-notification-holder"> <div class="new-share-notification bg-color-red"> <h6 class="fg-color-white">' + data.groups[i].share_noti + '</h6> </div> </div>';
            }

            usergroups += '</div></div>';

        }
        killLiveGroups();
        $("#groupsPlace").html(usergroups);
        $("#" + groupopen).css({"background": "#2b5797"});
        liveUserGroups();        
    }
    else
    {
        $("#groupsPlace").html('<div id="noGroupHolder"> <h3>You have no groups yet.</h3> </div>'); 
    }
    $("#groupPlace").tinyscrollbar();
    $(".divLoad").remove();
}

function openGroup()
{
    $("#groupInformationAndChat").append('<div class="divLoad"><div class="loader-background"></div><div class="loading">' + loader_url + '</div></div>');
    $("#sharedFilePlace").append('<div class="divLoad"><div class="loader-background"></div><div class="loading">' + loader_url + '</div></div>');
    $("#groupChatHolder").html('');
    $("#"+ groupopen + " .messages-notification-holder").remove();
    $("#"+ groupopen + " .new-share-notification-holder").remove();
    if(xOpenGroup && xOpenGroup.readyState != 4)
    {
        xOpenGroup.abort()
    }

    if(xSendMessage && xSendMessage.readyState != 4)
    {
        xSendMessage.abort();
    }

    if(xRetrieve && xRetrieve != 4)
    {
        xRetrieve.abort();
    }

    maxmessage = false;
    messagepresent = 0;

    xOpenGroup = $.ajax({
        url: opengroup_url,
        data:{'group_id':groupopen},
        type:"POST",
        dataType:"json",
        success:function(data){
            if(data.status == "success")
            {
                $("#groupOwner").html('<h5 class="fg-color-darken">Owner : '+ data.owner_name +'</h5>');
                if(data.is_owner == true)
                {
                    $("#ownerPanel").html('<div id="unshareFile" class="owner-panel"> <img src="'+ image_url +'icons/unshare.gif" width="40" height="40" /> <div class="owner-panel-title center-div-v center-div-h"><h5 class="fg-color-white">Unshare</h5></div></div><div id="downloadSharedFiles" onclick="downloadNow()" class="owner-panel"> <img src="'+ image_url +'icons/download.gif" width="40" height="40" /> <div class="owner-panel-title center-div-v center-div-h"> <h5 class="fg-color-white">Download</h5> </div> </div>');
                    unshareFiles();
                }
                else if(data.is_owner == false)
                {
                    $("#ownerPanel").html('<div id="downloadSharedFiles" onclick="downloadNow()" class="owner-panel"> <img src="'+ image_url +'icons/download.gif" width="40" height="40" /> <div class="owner-panel-title center-div-v center-div-h"> <h5 class="fg-color-white">Download</h5> </div> </div>');
                }

                fillGroupChat(data, "open");
                fillSharedFiles(data);
                retrieve();
            }
        }
    });
}

function downloadNow()
{
    if(selectedfile != null)
    {
        window.location = download_url + encodeURIComponent(selectedfile);           
    }
}

function unshareFiles()
{
    $("#unshareFile").live("click", function(){
        $("#unshareFilesHolder").show();
        $("#modalBackground").show();
        unsharefiles = true;
    });
   
}

function unshareFilesNow()
{
    if(xUnshareFile && xUnshareFile != 4)
    {
        xUnshareFile.abort();
    }

    if(selectedfile != null)
    {
        xUnshareFile = $.ajax({
            url:unsharefile_url,
            data:{"file":selectedfile},
            dataType:"json",
            type:"POST",
            success:function(data){
                if(data.status == "success")
                {
                    showToast(data.success_msg);
                }
                else if(data.status == "error")
                {
                    showToast(data.error_msg);
                }
                selectedfile = null;
                closeThisModal('unsharefiles');
            }
        });
    }
    else
    {
        showToast("Please select files to unshare.");
    }
}

function fillGroupChat(data, method)
{
    $("#groupInformationAndChat .divLoad").remove();
    var messages = '';
    for(var i=data.group_chat.length-1;i>=0;i--)
    {
        messagepresent++;
        messages += '<div class="group-replies"> <div class="sender"> <h5 class="fg-color-darken">'+ escapeHtml(data.group_chat[i].sender_nametag) +'</h5> </div> <div class="send-time"> <h6 class="fg-color-darken">'+ data.group_chat[i].date_sent +'</h6> </div> <p class="fg-color-darken">'+ escapeHtml(data.group_chat[i].message) +'</p> </div>';
    }

    if(method == "chatting" || method == "open")
    {
        if(method == "chatting")
        {
            $("#groupChatHolder").append(messages);
        }
        else if(method == "open")
        {
            if(data.group_chat.length < 10)
            {
                maxmessage = true;
            }
            $("#groupChatHolder").html(messages);
        }

        $("#groupChatHolderPlace").tinyscrollbar();
        var msgscrollheight = $("#groupChatHolderPlace .thumb").css("height");
        var bottom = 399 - parseInt(msgscrollheight);
        var groupmessageheight = $("#groupChatHolder").height();
        if(groupmessageheight > 399)
        {
            $("#groupChatHolderPlace .thumb").css({"top":bottom +"px"});
            $("#groupChatHolderPlace .overview").css({"top":399-groupmessageheight +"px"});
        }
    }
    else if(method == "more")
    {
        if(data.group_chat.length < 10)
        {
            maxmessage = true;
        }

        var oldheight = $("#groupChatHolderPlace .overview").height();
        var oldscrollheight = $("#groupChatHolderPlace .thumb").height();
        $("#groupChatHolder").prepend(messages);
        $("#groupChatHolderPlace").tinyscrollbar();
        var newscrollheight = $("#groupChatHolderPlace .thumb").height();
        var newheight = $("#groupChatHolderPlace .overview").height();
        if(oldheight > 400)
        {
            $("#groupChatHolderPlace .thumb").css({"top":oldscrollheight - newscrollheight +"px"});
            $("#groupChatHolderPlace .overview").css({"top":oldheight - newheight +"px"});
        }
    }
    
}

function fillSharedFiles(data)
{
    $("#sharedFilePlace .divLoad").remove();
    $("#pocket").css({"width":"500px"});
    selectedfile = null;
    $("#ownerPanel").hide();
    var sharedfiles = '';
    var loop = 1;
    $(".shared-file").die("click").die("contextmenu");
    for(var i=0;i<data.files_shared.length;i++)
    {
        if(loop == 1)
        {
            sharedfiles += '<div class="shared-files-holder">';
        }

        sharedfiles += '<div id="'+ data.files_shared[i].share_id +'" data-fullname="'+ data.files_shared[i].file_name +'" class="shared-file tile double bg-color-'+ data.files_shared[i].color +'"> <div class="tile-content"> <div class="shared-file-name"> <h3>'+ cutstring(data.files_shared[i].file_name, 20) +'</h3> </div> <div class="shared-file-size"> <h5>'+ data.files_shared[i].file_size +'</h5> </div> <div class="shared-file-type"> <h5>'+ ucFirst(data.files_shared[i].file_type) +'</h5> </div> <div class="shared-file-date"> <h5>Shared on : '+ data.files_shared[i].date_shared +'</h5> </div> </div> </div>';

        if(loop == 5 || i == data.files_shared.length )
        {
            sharedfiles += '</div>';
            loop = 0;
        }
        loop++;
    }

    var pocketwidth = Math.floor(data.files_shared.length / 5);
    if(pocketwidth > 0 && data.files_shared.length > 5)
    {
        pocketwidth = parseInt($("#pocket").css("width")) + ((pocketwidth * 330) - 170);
        $("#pocket").css({"width":pocketwidth +"px"});
    }
    $("#sharedFilePlace").html(sharedfiles);

    $(".shared-file").live("click", function(){
        if($(this).hasClass("selected"))
        {
            $(this).removeClass("selected");
            selectedfile = null;
            $("#ownerPanel").hide();
        }
        else
        {
            $("#"+selectedfile).removeClass("selected");
            $(this).addClass("selected");
            selectedfile = $(this).attr("id");
            if(selectedfile != null)
            {
                $("#ownerPanel").show();
            }
        }
    }).live("mouseover", function(e){
        $("#hoverHelper").css({
            "top" : (e.pageY + 5) + 'px',
            "left" : (e.pageX + 5) + 'px'
        }).show().delay(3000).fadeOut("slow");
        $("#hoverHelperTitle").html($(this).attr('data-fullname'));
    });
}

function sendGroupMessage()
{
    var to_send = $("#groupMessage").val();
    if(to_send != "")
    {
        $("#groupMessage").val("");
        var message = '<div class="group-replies"> <div class="sender"> <h5 class="fg-color-darken">'+ escapeHtml(nametag) +'</h5> </div> <div class="send-time"> <h6 class="fg-color-darken"></h6> </div> <p class="fg-color-darken">'+ escapeHtml(to_send) +'</p> </div>';
        $("#groupChatHolder").append(message);
        $("#groupChatHolderPlace").tinyscrollbar();
        var msgscrollheight = $("#groupChatHolderPlace .thumb").css("height");
        var bottom = 399 - parseInt(msgscrollheight);
        var groupmessageheight = $("#groupChatHolder").height();
        if(groupmessageheight > 399)
        {
            $("#groupChatHolderPlace .thumb").css({"top":bottom +"px"});
            $("#groupChatHolderPlace .overview").css({"top":399-groupmessageheight +"px"});
        }

        pendingmessages.push({"message":to_send, "sendtogroup":groupopen});
        sendGroupMessageNow(pendingmessages);
    }
}

function sendGroupMessageNow(to_send)
{
    if(xSendMessage && xSendMessage.readyState != 4)
    {
        return;
    }

    xSendMessage = $.ajax({
        url:groupmsg_url,
        data:{"user_messages":to_send},
        type:"POST",    
        dataType:"json",
        success:function(data){
            if(data.status == "success")
            {
                sendMessage(data.count); 
            }
            else if(data.status == "error")
            {
                showToast(data.error_msg);
            }
            
        }
    });

}

function sendMessage(count)
{
    pendingmessages.splice(0, count);
    if(pendingmessages.length != 0)
    {
        sendGroupMessageNow(pendingmessages);
    }
}

function joinGroupNow()
{
    $("#joinGroupHolder").append('<div class="divLoad"><div class="loader-background"></div><div class="loading">' + loader_url + '</div></div>');
    var owner   = $("#ownerNametag").val();
    var ziplock = $("#groupZiplock").val();

    if(owner == "" || ziplock == "")
    {
        showToast("Please complete the form.")
        $("#joinGroupHolder .divLoad").remove();
        return;
    }

    if(xJoinGroup && xJoinGroup.readyState != 4)
    {
        xJoinGroup.abort();
    }

    xJoinGroup = $.ajax({
        url:joingroup_url,
        data:{"owner":owner, "ziplock":ziplock},
        type:"POST",
        dataType:"json",
        success:function(data){
            if(data.status == "success")
            {
                showToast(data.success_msg);
                closeThisModal("joingroup");
            }
            else if(data.status == "error")
            {
                showToast(data.error_msg);
            }

            retrieve();
            $("#joinGroupHolder .divLoad").remove();
        }
    });
}

function removeFromGroup(method, id)
{
    if(xLeaveGroup && xLeaveGroup.readyState != 4)
    {
        xLeaveGroup.abort();
    }

    if(method == "leave")
    {
        $("#groupInformationAndChat").append('<div class="divLoad"><div class="loader-background"></div><div class="loading">' + loader_url + '</div></div>');
        $("#sharedFilePlace").append('<div class="divLoad"><div class="loader-background"></div><div class="loading">' + loader_url + '</div></div>');
    }

    xLeaveGroup = $.ajax({
        url:leavegroup_url,
        data:{"method":method, "id":id, "groupid":groupopen},
        dataType:"json",
        type:"POST",
        success:function(data){
            if(data.status == "success")
            {
                showToast(data.success_msg);
                if(method == "remove")
                {
                    getGroupMembers();
                }
                else if(method == "leave")
                {
                    retrieve();
                    groupopen = null;
                    $("#groupInformationAndChat").hide();
                    $("#sharedFilePlace").hide();
                    $("#groupOwner h5").html("No Group Selected");
                    $("#groupChatHolder").html("");
                    $("#sharedFilePlace").html("");
                    $("#groupSettingsTrigger").css({'background':"transparent"});
                    $("#groupSettings").hide();  
                    closeThisModal("leavegroup");
                    groupsettings = false 
                }

            }
            else if(data.status == "error")
            {
                showToast(data.error_msg);
                closeThisModal("groupmembers");
            }
        }
    });
}

function createGroup()
{
    $("#createGroupHolder").show();
    $("#modalBackground").show();
    creategroup = true;
}

function joinGroup()
{
    $("#joinGroupHolder").show();
    $("#modalBackground").show();
    joingroup = true;
}

$(document).ready(function(){

    $('#bagmatesPlace').tinyscrollbar();
    $("#groupPlace").tinyscrollbar();
    retrieve();

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

    $("#myMessages").bind("mouseover", function(){
        $("#messageHelper").show();
    }).bind("mouseout", function(){
        $("#messageHelper").hide();
    });

    $("#myGroupReq").bind("mouseover", function(){
        $("#memberHelper").show();
    }).bind("mouseout", function(){
        $("#memberHelper").hide();
    });

    $("#myActivities").bind("mouseover", function(){
        $("#activityHelper").show();
    }).bind("mouseout", function(){
        $("#activityHelper").hide();
    });

    $("#createGroup").bind("mouseover", function(){
        $("#createGroupHelper").show();
    }).bind("mouseout", function(){
        $("#createGroupHelper").hide();
    });

    $("#joinGroup").bind("mouseover", function(){
        $("#joinHelper").show();
    }).bind("mouseout", function(){
        $("#joinHelper").hide();
    });

    $("#groupRequests").bind("mouseover", function(){
        $("#groupRequestsHelper").show();
    }).bind("mouseout", function(){
        $("#groupRequestsHelper").hide();
    });

    var chatscrolldrag = false;
    $("#groupChatHolderPlace .track").bind("mouseover", function(){
        $("#groupChatHolderPlace .scrollbar").css({"width":"15px"});
        $("#groupChatHolderPlace .track").css({"width":"15px"});
        $("#groupChatHolderPlace .thumb").css({"width":"15px"});
        $("#groupChatHolderPlace .thumb .end").css({"width":"15px"});
    }).bind("mouseout", function(){
        if(chatscrolldrag == false)
        {
            $("#groupChatHolderPlace .scrollbar").css({"width":"8px"});
            $("#groupChatHolderPlace .track").css({"width":"8px"});
            $("#groupChatHolderPlace .thumb").css({"width":"8px"});
            $("#groupChatHolderPlace .thumb .end").css({"width":"8px"});
        }
    }).bind("mousedown", function(){
        chatscrolldrag = true;
    });
    
    $('body').bind("mouseup", function(){
        if(chatscrolldrag == true)
        {
            $("#groupChatHolderPlace .scrollbar").css({"width":"8px"});
            $("#groupChatHolderPlace .track").css({"width":"8px"});
            $("#groupChatHolderPlace .thumb").css({"width":"8px"});
            $("#groupChatHolderPlace .thumb .end").css({"width":"8px"});
            chatscrolldrag = false;
        }
    }).bind("mousemove", function(){
        if(maxmessage == false && chatscrolldrag == true)
        {
            var top = parseInt($("#groupChatHolderPlace .thumb").css("top"));
            if(top <= 25)
            {
                if(xPreviousMessage && xPreviousMessage.readyState != 4)
                {
                    return;
                }
                $("#groupChatHolder").append('<div class="divLoad"><div class="loader-background"></div><div class="loading">' + loader_url + '</div></div>');

                xPreviousMessage = $.ajax({
                    url:groupretrieve_url,
                    data:{"selectedgroup":groupopen, "messagepresent":messagepresent},
                    dataType:"json",
                    type:"POST",
                    success:function(data){
                        if(data.status == "success")
                        {
                            $("#groupChatHolder .divLoad").remove();
                            fillGroupChat(data, "more");
                        }
                        else if(data.status == "error")
                        {
                            showToast(data.error_msg);
                        }
                    }
                });
            }
        }
    });

    $("#groupSettingsTrigger").bind("click", function(){
        if(groupsettings == false && groupopen != null)
        {
            $("#groupSettingsTrigger").css({'background':"#603cba"});
            $("#groupSettings").show();    
            groupsettings = true;    
        }
        else
        {
            $("#groupSettingsTrigger").css({'background':"transparent"});
            $("#groupSettings").hide();  
            groupsettings = false 
        }
    });

    $('#groupMessage').keypress(function (e) {
        if (e.which == 13) {
            e.preventDefault();
            sendGroupMessage();
        }
    });

    $("#ownerNametag").keypress(function(e){
        if (e.which == 13) {
            e.preventDefault();
            joinGroupNow();
        }
    });

    $("#groupZiplock").keypress(function(e){
        if (e.which == 13) {
            e.preventDefault();
            joinGroupNow();
        }
    });

    $("#addNametag").keypress(function(e){
        if (e.which == 13) {
            e.preventDefault();
            addGroupMember();
        }
    });

});

function killLiveGroups()
{
    $(".user-groups").die("click");
}

function liveUserGroups()
{
    $(".user-groups").live("click", function(){
        if(groupopen == null)
        {
            groupopen = $(this).attr("id");
            $(this).css({"background": "#2b5797"});
            $("#noGroupSelected").remove();
            $("#groupInformationAndChat").show();
            $("#sharedFilePlace").show();
            $('#groupChatHolderPlace').tinyscrollbar();
            openGroup();
        }
        else
        {
            $("#" + groupopen).css({"background":"transparent"});
            groupopen = $(this).attr("id");
            $(this).css({"background": "#2b5797"});
            openGroup();
        }
    });
}

function escapeHtml(unsafe) {
  return unsafe
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#039;");
}

function openGroupInfo()
{
    if(groupopen != null)
    {
        $("#groupInfoHolder").show();
        $("#modalBackground").show();
        groupinfo = true;   

        $("#groupInfoHolder").append('<div class="divLoad"><div class="loader-background"></div><div class="loading">' + loader_url + '</div></div>');

        if(xGetGroupInfo && xGetGroupInfo.readyState != 4)
        {
            xGetGroupInfo.abort();
        }

        xGetGroupInfo = $.ajax({
            url:groupinfo_url,
            data:{"groupopen":groupopen},
            type:"POST",
            dataType:"json",
            success:function(data){
                $("#groupInfoName h3").html("Group Name : " + data.group_name);
                $("#groupInfoOwner h3").html("Group Owner : " + data.group_owner);
                $("#groupInfoZiplock h3").html("Group Ziplock : " + data.group_ziplock);
                $("#groupInfoHolder .divLoad").remove();
            }
        });
    }
    else
    {
        showToast("Please open a group first.");
    }
}