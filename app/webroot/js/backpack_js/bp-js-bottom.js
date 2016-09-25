var pocketdrop = false, fileupload = false, bottomlockcode = true, deletefiles = false, renameitem = false, submenu = false;
var selectedfiles = new Array();
var xreloadfilelist, xLazy, xRetrieve, xCheckLockcode;
var pocket = "bottom";

function openFileUpload()
{
    if(fileupload == false)
    {
        $("#modalBackground").show();
        $("#fileUploadHolder").show();
        $("#fileUploadContent").tinyscrollbar();
        fileupload = true;
    }
}

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

function confirmFileDelete()
{
    $("#modalBackground").show();
    $("#deleteFilesHolder").show();
    deletefiles = true;
}

function deleteSelectedFiles()
{
    $("#modalBackground").hide();
    $("#deleteFilesHolder").remove();
    createfolder = false;
    if(selectedfiles.length > 0)
    {
        var selectedfiles_name = new Array();
        for(var i=0;i<selectedfiles.length;i++)
        {
            selectedfiles_name.push($("#" + selectedfiles[i]).attr("data-fullname"));
        }

        $("body").append('<div class="divLoad"><div class="loader-background"></div><div class="loading">' + loader_url + '</div></div>');
        var itemstodelete = {"items":selectedfiles_name, "full_path" : full_path};
        $.ajax({
            url:delete_url,
            type:"POST",
            data:itemstodelete,
            dataType: "json",
            success:function(result)
            {
                if(result.status == "success")
                {
                    selectedfiles.splice(0, selectedfiles.length - 1);
                    $("#fileOptions").slideUp("fast").css({'left':'-9999px'});
                    showToast(result.success_msg);
                }
                else
                {
                    showToast(result.error_msg);
                }
                $(".divLoad").remove();
                reloadFileList();
            }
        });
    }
    else
    {
        alert("Please select files to delete.");
    }
}

function renameItemSelected()
{
    if(selectedfiles.length == 1)
    {
        $('#renameItemHolder').show();
        $("#modalBackground").show();
        renameitem = true;
    }
    else
    {
        showToast('Please select only 1 item to rename.');
    }
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

function renameItemNow()
{
    var newname = $("#renameItemName").val();
    var oldname = $("#" + selectedfiles[0]).attr('data-fullname');
    var file_names = {"name":oldname, "newname":newname, "full_path":full_path};
    $("#renameItemHolder").append('<div class="divLoad"><div class="loader-background"></div><div class="loading">' + loader_url + '</div></div>');
    if(newname != "")
    {
        $.ajax({
            url:rename_url,
            type:"POST",
            data:file_names,
            dataType:"json",
            success:function(result)
            {
                if(result.status == "success")
                {
                    showToast(result.success_msg);
                }
                else
                {
                    showToast(result.error_msg);
                }
                $("#modalBackground").hide();
                $("#renameItemHolder").remove();
                renameitem = false;
                reloadFileList();
            }
        });
    }
    else
    {
        showToast("Please enter your desired name");
    }
}

function closeThisModal(toclose)
{
    if(toclose == "fileupload" && fileupload == true)
    {
        $("#modalBackground").hide();
        $("#fileUploadHolder").hide();
        fileupload = false;
    }

    if(toclose == "bottomlockcode" && bottomlockcode == true)
    {
        $("#modalBackground").hide();
        $("#enterBottomLockcodeHolder").hide();
        bottomlockcode = false;
    }

    if(toclose == "deletefiles" && deletefiles == true)
    {
        $("#modalBackground").hide();
        $("#deleteFilesHolder").hide();
        deletefiles = false;
    }

    if(toclose == "renameitem" && renameitem == true)
    {
        $("#modalBackground").hide();
        $("#renameItemHolder").hide();
        $('#renameItemName').val('');
        renameitem = false;
    }

}

var isimage = new Array('png', 'jpg', 'jpeg', 'bmp', 'gif');

function openItem()
{
    if(xLazy.readyState == 4)
    {
        if(selectedfiles.length == 1)
        {
            var selected_id = selectedfiles[0];
            var item_type = $("#" + selected_id).attr("data-itemtype");
            var item_name = $("#" + selected_id).attr("data-fullname");
            var image       = false;
            if(item_type == "folder")
            {
                var name = $("#" + selected_id).attr("data-fullname");
                var basepath = document.URL.replace("#", "");
                if(basepath.length == basepath.lastIndexOf("/") + 1)
                {
                     window.location = basepath + name;
                }
                else
                {
                    window.location = basepath + '/' + name;
                }
            }
            else
            {
                for(var i=0;i<isimage.length;i++)
                {
                    if(item_type == isimage[i])
                    {
                        image = true;
                        break;
                    }
                }

                if(image == true)
                {

                }
                else
                {
                    window.location = download_url + encodeURIComponent(item_name);
                }
                
            }
           /* $.ajax({
                url:open_url,
                type:"POST",
                data:{"item":selectedfiles},
                dataType:"json",
                success:function(result)
                {
                    if(result.result.item_sort == 'image')
                    {
                        $("#ajaxMessage").html(result.result.item_path);
                        $(".ajax-message").fadeIn().delay(1000).fadeOut("slow");
                    } 
                    else if(result.result.item_sort == 'folder')
                    {
                        window.location = result.result.item_path;
                    }
                }
            });*/
        }
        else
        {
            showToast("Please select only 1 item to open.");
        }
    }
}

function downloadNow()
{
    if(selectedfiles.length == 1)
    {
        var selected_id = selectedfiles[0];
        var item_name = $("#" + selected_id).attr("data-fullname");
        window.location = download_url + encodeURIComponent(item_name);           
    }
    else
    {

    }  
}

$(document).ready(function(){

    $('#createFolderName').val('');
    $('#renameItemName').val('');
    retrieve();
    $("#modalBackground").show();

    $("#bottomLockcodeSubmit").bind("click", function(){
        $("body").append('<div class="divLoad"><div class="loader-background"></div><div class="loading">' + loader_url + '</div></div>');
        var lockcode = $("#bottomLockcode").val();

        if(xCheckLockcode && xCheckLockcode.readyState != 4)
        {
            xCheckLockcode.abort();
        }

        xCheckLockcode = $.ajax({
            url:check_url,
            data:{"bottomcode":lockcode},
            dataType:"json",
            type:"POST",
            success:function(data){
                if(data.status == "success")
                {
                    $("#enterBottomLockcodeHolder").hide();
                    $("#modalBackground").hide();
                    bottomlockcode = false;
                    reloadFileList();
                    $("#bottomLockcode").val('');
                }
                else if(data.status == "error")
                {
                    showToast(data.error_msg);
                }
                $("body .divLoad").remove();
            }
        });
    });

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

    $("#uploadFile").live("mouseover", function(){
        $("#uploadHelper").show();
    }).live("mouseout", function(){
        $("#uploadHelper").hide();
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

});

function unselectItems()
{
    selectedfiles.splice(0, selectedfiles.length);
    $("#fileOptions").css({'left':'-9999px'});
}

function uncheckAll()
{
    for (var i=0; i<selectedfiles.length;i++)
    {
        $('#' + selectedfiles[i]).removeClass("selected");
    }
}

function deselectItems()
{
    uncheckAll();
    unselectItems();
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

function killItemLive()
{
    $(".select-item").die("click").die("mouseover").die("dblclick").die("contextmenu");
    $("#backFolder").die("dblclick");
}

function itemLive()
{
    $('.select-item').live("click", function ()
    {
        if($(this).hasClass("selected"))
        {
            var removeid = "";
            $(this).removeClass("selected");
            for(var i=0;i<selectedfiles.length;i++)
            {
                if($(this).attr("id") == selectedfiles[i])
                {
                    removeid = i;
                    break;
                }
            }
            selectedfiles.splice(removeid,1);
            //alert(selectedfiles);
            if(selectedfiles.length == 0)
            {
                $("#fileOptions").slideUp("fast").css({'left':'-9999px'});
            }
        }
        else
        {
            $(this).addClass("selected");
            $("#fileOptions").css({'left':'0px'}).slideDown("fast");
            selectedfiles.push($(this).attr("id"));  
            //alert(selectedfiles);     
        }
    }).live('contextmenu', function(e)
    {
        var item_id = $(this).attr("id");
        var selected = false;
        for(var i=0; i<selectedfiles.length;i++)
        {
            if(item_id == selectedfiles[i]){ selected = true; break;}
        }

        if(selected == false)
        {
            $(this).addClass("selected");
            $("#fileOptions").css({'left':'0px'}).slideDown("fast");
            selectedfiles.push($(this).attr("id")); 
        }
        
        closeContexts();
        var window_width = $(window).width();
        var window_height = $(window).height();
        var menu_width = $("#fileContext").width();
        var menu_height = 295;
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
        
        $('#fileContext').css({
            top: display_y,
            left: display_x,
            display: 'inline'
        });

        return false;
    }).live("mouseover", function(e){
        $("#hoverHelper").css({
            "top" : (e.pageY + 5) + 'px',
            "left" : (e.pageX + 5) + 'px'
        }).show().delay(3000).fadeOut("slow");
        $("#hoverHelperTitle").html($(this).attr('data-fullname'));
    }).live("dblclick", function(){
        if($(this).hasClass("selected"))
        {
            openItem();
        }
        else
        {
            $(this).addClass("selected");
            $("#fileOptions").css({'left':'0px'}).slideDown("fast");
            selectedfiles.push($(this).attr('id')); 
            openItem();
        }
    });

    $("#backFolder").live("dblclick", function(){
        window.location = $(this).attr("data-url");
    });
}