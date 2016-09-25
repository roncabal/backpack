var pocketdrop = false, fileupload = false, createfolder = false, deletefiles = false, renameitem = false, showfolder = false, showopenfolder = false, showfoldersopened = false, sharetype = false, groupshare = false, showimage = false, sharelink = false, backpackorganizer = false, submenu = false;
var selectedfiles = new Array(), showopenfolders = new Array(), viewimages = new Array(), uploadedfiles = new Array();
var xreloadfilelist, xLazy, xAlive, xRetrieve, xGetGroups, xShareToGroup, xOpenImages, xShareLinks, xGetSuggestions, xAcceptOrganize, xTutorial;
var pocket = "main";

function closeThisModal(toclose)
{
    if(toclose == "fileupload" && fileupload == true)
    {
        $("#modalBackground").hide();
        $("#fileUploadHolder").hide();
        fileupload = false;
    }

    if(toclose == "createfolder" && createfolder == true)
    {
        $("#modalBackground").hide();
        $("#createFolderHolder").hide();
        createfolder = false;
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
        renameitem = false;
    }

    if(toclose == "showfolder" && showfolder == true)
    {
        $("#modalBackground").hide();
        $(".select-folder").die("click");
        $(".select-folder").die("dblclick");
        $('.show-open-folder').die("click");
        $('.show-open-folder').die("dblclick");
        $("#doAction").unbind("click");
        $("#chosenFolder h3").html("");
        $("#folderContainer").html('<div class="showOpenFoldersTitle"><h4>Choose folder destination:</h4></div> <div id="main" data-fullname="Main" data-fullpath="main" class="select-folder folder-list cursor-pointer"><h4>  Main  </h4></div> ');
        showopenfolders.splice(0, showopenfolders.length);
        $("#showFolderHolder").hide();
        showfolder = false;
    }

    if(toclose == "sharetype" && sharetype == true)
    {
        $("#modalBackground").hide();
        $("#shareTypeHolder").hide();
        sharetype = false;
    }

    if(toclose == "groupshare" && groupshare == true)
    {
        $("#modalBackground").hide();
        $("#groupShareHolder").hide();
        $(".group-share-button").die("click");
        $("#groupSharePlace .overview").html("");
        groupshare = false;
    }

    if(toclose == "showimage" && showimage == true)
    {
        $("#blackModalBackground").hide();
        $("#showImageHolder").hide();
        $("#previousImageToggle").die("click");
        $("#nextImageToggle").die("click");
        $("#imageDownload").die("click");
        showimage = false;
    }

    if(toclose == "sharelink" && sharelink == true)
    {
        $("#modalBackground").hide();
        $("#shareLinkHolder").hide();
        $("#shareUrl").val("");
        $("#claimFile").val("");
        $("#shareUrlPlaceholder").show();
        $("#claimFilePlaceholder").show();
        sharelink = false;
    }

    if(toclose == "backpackorganizer" && backpackorganizer == true)
    {
        $(".remove-organize-file").die("click");
        $(".organize-accept-button").die("click");
        $("#blackModalBackground").hide();
        $("#backpackOrganizerHolder").hide();

        backpackorganizer = false;
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

function shareToGroup()
{
    closeThisModal("sharetype");
    $("#modalBackground").show();
    $("#groupShareHolder").show();
    groupshare = true;

    $("#groupShareHolder").append('<div class="divLoad"><div class="loader-background"></div><div class="loading">' + loader_url + '</div></div>');

    if(xGetGroups && xGetGroups.readyState != 4)
    {
        xGetGroups.abort();
    }

    xGetGroups = $.ajax({
        url:getgroups_url,
        dataType:"json",
        success:function(data){
            if(data.status == "success")
            {
                var groupstoshare = '';
                for(var i=0;i<data.group_share.length;i++)
                {
                    groupstoshare += '<div class="group-share"> <h4>'+ data.group_share[i].group_name +'</h4> <div class="group-share-button-holder center-div-v"> <button id="g_'+ data.group_share[i].group_id +'" class="group-share-button bg-color-blueDark fg-color-white">Share</button> </div> </div>';
                }

                $("#groupSharePlace .overview").html(groupstoshare);
            }
            else if(data.status == "error")
            {

            }

            $("#groupSharePlace").tinyscrollbar();
            $("#groupShareHolder .divLoad").remove();
        }
    });
}

var timestamp = 0;
var used = "";
var filespresent = 0;
function retrieve()
{
    if(xRetrieve && xRetrieve.readyState != 4)
    {
        xRetrieve.abort();
    }

    xRetrieve = $.ajax({
        url:retrieve_url,
        data:{"timestamp":timestamp, "used":used, "filespresent":filespresent, "pocket":full_path, "organizer":organizer},
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

                if(data.org_changed == true)
                {
                    organizer = (data.org_status) ? "1" : "0";
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

function chooseShareType()
{
    if(sharetype == false)
    {
        $("#modalBackground").show();
        $("#shareTypeHolder").show();
        sharetype = true;
    }
}

function aliveFiles()
{
    $("#spotlightPlace").append('<div class="divLoad"><div class="loader-background"></div><div class="loading">' + loader_url + '</div></div>');
    if(xAlive && xAlive.readyState != 4)
    {
        xAlive.abort();
    }
    xAlive = $.ajax({
        url:alive_url,
        dataType:"json",
        success:function(data){
            if(data.status == "success")
            {
                var decayitems = '';
                var aliveitems = '';
                for(var i=0;i<data.alive.length;i++)
                {
                     aliveitems += '<div class="tile top-used bg-color-blue"> ' +
                                    '<div class="tile-content">' +
                                        '<div class="file-name">' +
                                            '<h3>'+ cutstring(data.alive[i].file_name, 15) +'</h3><br/>' +
                                        '</div>' +
                                        '<div class="spotlight-actions">' +
                                            '<h5>'+ data.alive[i].file_download +' Downloads : '+ data.alive[i].file_share +' Shares</h5>' +
                                        '</div>' +
                                        '<div class="file-type">' +
                                            '<h5>'+ data.alive[i].file_ext +'</h5>' +
                                        '</div>' +
                                    '</div>' +
                                '</div>';
                }

                for(var i=0;i<data.decaying.length;i++)
                {
                     decayitems += '<div class="tile top-used bg-color-red"> ' +
                                    '<div class="tile-content">' +
                                        '<div class="file-name">' +
                                            '<h3>'+ data.decaying[i].file_name +'</h3>' +
                                        '</div>' +
                                        '<div class="file-type">' +
                                            '<h5>'+ data.decaying[i].file_ext +'</h5>' +
                                        '</div>' +
                                        '<div class="spotlight-actions">' +
                                            '<h5 class="delete-file">Delete</h5> : <h5 class="regen-file">Regenerate</h5>' +
                                        '</div>' +
                                    '</div>' +
                                '</div>';
                }

                $(".delete-file").live('click', function(){
                    
                })

                $("#geneticsSpotlight").html(aliveitems);
                $("#decaySpotlight").html(decayitems);
                $('#spotlightPlace').tinyscrollbar();
                $("#spotlightPlace .divLoad").remove();
            }
        }
    });
}

function openFileUpload()
{
    if(fileupload == false)
    {
        $("#modalBackground").show();
        $("#fileUploadHolder").show();
        $("#fileUploadHolder").tinyscrollbar();
        fileupload = true;
    }
}

function createFolder()
{
    if(createfolder == false)
    {
        $("#modalBackground").show();
        $("#createFolderHolder").show();
        $('#createFolderName').val('');
        createfolder = true;
    }
}

function confirmFileDelete()
{
    $("#modalBackground").show();
    $("#deleteFilesHolder").show();
    deletefiles = true;
}

function deleteSelectedFiles()
{
    closeThisModal('deletefiles');
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
        showToast("Please select files to delete.");
    }
}

function renameItemSelected()
{
    if(selectedfiles.length == 1)
        {
        $("#renameItemName").val('');
        $("#modalBackground").show();
        $("#renameItemHolder").show();
        renameitem = true;
    }
    else
    {
        showToast('Please select only 1 item to rename.');
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
                $("#renameItemHolder").hide();
                $("#renameItemHolder .divLoad").remove();
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

function createFolderNow()
{
    $("#createFolderContent").append('<div class="divLoad"><div class="loader-background"></div><div class="loading">' + loader_url + '</div></div>');
    var folder_name = $("#createFolderName").val();
    if(folder_name != "")
    {
        $.ajax({
            url: folder_url,
            type: "GET",
            data: {"folder_name":folder_name},
            dataType: "json",
            success:function(result)
            {
                showToast(result.result.message);
                $("#modalBackground").hide();
                $("#createFolderHolder").hide();
                createfolder = false;
                $(".divLoad").remove();
                reloadFileList();
            }
        });
    }
    else
    {
        showToast('Folder name must not be blank.');
        $(".divLoad").remove();
    }
}

function copyItems()
{
    if(selectedfiles.length >= 1)
    {
       showFoldersAvailable("copy");
    }
}

function moveItems()
{
    if(selectedfiles.length >= 1)
    {
        showFoldersAvailable("move");
    }
}

function showFoldersAvailable(method)
{
    var selectedfolder = null;
    var selectedopenfolder = null;
    var xShowFolder;
    $("#modalBackground").show();
    showfolder = true;
    $("#showFolderHolder").show();
    $("#showFolderTitle").html(ucFirst(method) + "item(s) to:");
    $("#doAction").html( ucFirst(method));

    $(".select-folder").live("click", function(){
        if(selectedfolder != null)
        {
            $('#' + selectedfolder).css({"background" : "transparent"});
        }
        selectedfolder = $(this).attr("id");
        $("#chosenFolder h3").html($("#" + selectedfolder).attr('data-fullname'));
        $(this).css({"background" : "#2d89ef"});
    });

    $(".select-folder").live("dblclick", function(){
        var open_name = $(this).attr('data-fullname');
        var open_fullpath = encodeURIComponent($(this).attr('data-fullpath'));
        showopenfolders.push({"name" : open_name, "path" : $(this).attr('data-fullpath')});
        $("#folderContainer").append('<div class="divLoad"><div class="loader-background"></div><div class="loading">' + loader_url + '</div></div>');
        if(xShowFolder && xShowFolder.readyState != 4)
        {
            xShowFolder.abort();
        }
        xShowFolder = $.ajax({
            url:showfolders_url,
            type:"POST",
            data:{"open_folder" : open_name, "open_path" : open_fullpath},
            dataType:"json",
            success:function(result)
            {
                var folders = '<div class="showOpenFoldersTitle"><h4>Choose folder destination:</h4></div>';
                for(var i=0; i<result.files.length;i++)
                {
                    folders += '<div id="' + result.files[i].id + '"  data-fullname="'+ result.files[i].name +'" data-fullpath="'+ result.files[i].path +'" class="select-folder folder-list cursor-pointer"><h4>' + cutstring(result.files[i].name, 20) + '</h4></div>'
                }
                $("#folderContainer").html(folders);
                $(".divLoad").remove();
            }
        });
    });

    $('.show-open-folder').live("click", function(){
        if(selectedopenfolder != null)
        {
            $('#' + selectedopenfolder).css({"background" : "transparent"});
        }
        selectedopenfolder = $(this).attr("id");
        $(this).css({"background" : "#2d89ef"});
    });

    $('.show-open-folder').live("dblclick", function(){
        var open_id   = $(this).attr('id');
        var open_name = $(this).attr('data-fullname');
        var open_fullpath = encodeURIComponent($(this).attr('data-fullpath'));
        selectedfolder = open_id;

        var showopenfolders_index = parseInt(selectedfolder.substring(selectedfolder.length - 1, selectedfolder.length)) + 1;
        selectedfolder = selectedfolder.substring(0, selectedfolder.length - 2); 
        showopenfolders.splice(showopenfolders_index, showopenfolders.length - showopenfolders_index);

        $("#folderContainer").append('<div class="divLoad"><div class="loader-background"></div><div class="loading">' + loader_url + '</div></div>');
        if(xShowFolder && xShowFolder.readyState != 4)
        {
            xShowFolder.abort();
        }
        xShowFolder = $.ajax({
            url:showfolders_url,
            type:"POST",
            data:{"open_folder" : open_name, "open_path" : open_fullpath},
            dataType:"json",
            success:function(result)
            {
                var folders = '<div class="showOpenFoldersTitle"><h4>Choose folder destination:</h4></div>';
                for(var i=0; i<result.files.length;i++)
                {
                    folders += '<div id="' + result.files[i].id + '"  data-fullname="'+ result.files[i].name +'" data-fullpath="'+ result.files[i].path +'" class="select-folder folder-list cursor-pointer"><h4>' + cutstring(result.files[i].name, 20) + '</h4></div>'
                }
                $("#folderContainer").html(folders);
                $(".divLoad").remove();
                $("#chosenFolder h3").html(open_name);
                $("#showOpenFolders").hide();
                showopenfolder = false;
            }
        });
    });

    

    $("#doAction").bind("click", function(){
        if(selectedfolder != null)
        {
            var invalidaction = false;
            var destination = $("#" + selectedfolder).attr("data-fullpath");
            var selectedfiles_name = new Array();

            for(var i=0;i<selectedfiles.length;i++)
            {
                selectedfiles_name.push($("#" + selectedfiles[i]).attr("data-fullname"));
            }

            for(var i=0;i<selectedfiles_name.length;i++)
            {
                var folder = destination.substring(destination.lastIndexOf("/") + 1, destination.length);
                if(folder == selectedfiles_name[i])
                {
                    invalidaction = true;
                }

            }

            if(invalidaction == false)
            {
                if(method == 'copy')
                {
                    $("#showFolderContent").append('<div class="divLoad"><div class="loader-background"></div><div class="loading">' + loader_url + '</div></div>');
                    $.ajax({
                        url:copy_url,
                        type:"POST",
                        data:{"items" : selectedfiles_name, "destination" : destination, "from" : full_path},
                        dataType:"json",
                        success:function(result)
                        {
                           if(result.status = 'success')
                            {
                                showToast(result.success_msg);
                            }
                            else if(result.status = 'error')
                            {
                                showToast(result.err_msg);
                            }
                            $('#' + selectedopenfolder).css({"background" : "transparent"});
                            reloadFileList();
                            closeThisModal("showfolder");
                            $("#showFolderContent .divLoad").remove();
                            
                        }
                    });
                }
                else if(method == 'move')
                {
                    $("#showFolderContent").append('<div class="divLoad"><div class="loader-background"></div><div class="loading">' + loader_url + '</div></div>');
                    $.ajax({
                        url:move_url,
                        type:"POST",
                        data:{"items" : selectedfiles_name, "destination" : destination, "from" : full_path},
                        dataType:"json",
                        success:function(result)
                        {
                            if(result.status = 'success')
                            {
                                showToast(result.success_msg);
                            }
                            else if(result.status = 'error')
                            {
                                showToast(result.err_msg);
                            }
                            $('#' + selectedopenfolder).css({"background" : "transparent"});
                            reloadFileList();
                            closeThisModal("showfolder");
                            $("#showFolderContent .divLoad").remove();
                        }
                    });                
                }
                else
                {
                    showToast("Method is not selected");
                    closeThisModal("showfolder");
                    $("#showFolderContent .divLoad").remove();
                }

            }
            else
            {
                showToast("Cannot " + method + " folder inside itself.");
            }
        }
        else
        {
            showToast("Please select a folder first.");
        }
    });
}

function dropOpenFolders()
{
    if(showopenfolder == false)
    {
        $("#showOpenFoldersTrigger").css({"background" : "#AAAAAA"});
        var o_folders = '<div class="showOpenFoldersTitle"><h4>Opened folders:</h4></div>';
        for(var i=0;i<showopenfolders.length;i++)
        {
            var div_id = showopenfolders[i].name + '_' + i;
            o_folders += '<div id="' + div_id + '" data-fullname="'+ showopenfolders[i].name +'" data-fullpath="'+ showopenfolders[i].path +'" class="show-open-folder select-disable cursor-pointer"><h4>' + showopenfolders[i].name + '</h4></div>';
        }
        $("#showOpenFolders").html(o_folders);
        $("#showOpenFolders").show();
        showopenfolder = true;
    }
    else
    {
        $("#showOpenFoldersTrigger").css({"background" : "transparent"});
        $("#showOpenFolders").hide();
        showopenfolder = false;
    }
    
}

function pasteItems()
{
    $.ajax({
        url:paste_url,
        type:"POST",
        dataType:"json",
        success:function(result){
            showToast(result.result.message);
        }
    });
}

var isimage = new Array('png', 'jpg', 'jpeg', 'bmp', 'gif');

var imageexists = false;
var imagenumber = 0;
function openItem()
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
                if(item_type.toLowerCase() == isimage[i])
                {
                    image = true;
                    break;
                }
            }

            if(image == true)
            {
                for(var i=0;i<viewimages.length;i++)
                {
                    if(item_name == viewimages[i])
                    {
                        imagenumber = i;
                        imageexists = true;
                        break;
                    }
                }

                if(imageexists == true)
                {
                    $("#blackModalBackground").show();
                    $("#showImageHolder").show();
                    showimage = true;
                    updateViewImage(item_name, imagenumber + 1);

                    $("#previousImageToggle").live("click", function(){
                        imagenumber = (imagenumber == 0) ? viewimages.length - 1 : imagenumber -1;
                        updateViewImage(viewimages[imagenumber], imagenumber + 1);
                    });

                    $("#nextImageToggle").live("click", function(){
                        imagenumber = (imagenumber + 1 == viewimages.length) ? 0 : imagenumber + 1;
                        updateViewImage(viewimages[imagenumber], imagenumber + 1);
                    });

                    $("#imageDownload").live("click", function(){
                        window.location = download_url + "?full_path=" + full_path + "&file=" + viewimages[imagenumber];
                    });

                }
                
            }
            else
            {
                window.location = download_url + "?full_path=" + full_path + "&file=" + item_name;
            }
            
        }
    }
    else
    {
        showToast("Please select only 1 item to open.");
    }
}

function updateViewImage(item_name, imagenumber)
{
    $("#imageName").html(cutstring(item_name, 30));
    $("#image").attr('alt', item_name);
    $("#image").attr('src', view_path + item_name);
    $("#imageCount").html(imagenumber +' of '+ viewimages.length +' images');
}

function downloadNow()
{
    if(selectedfiles.length == 1)
    {
        var selected_id = selectedfiles[0];
        var item_name = $("#" + selected_id).attr("data-fullname");
        window.location = download_url + "?full_path=" + full_path + "&file=" + item_name;           
    }
    else
    {
        var dfiles = '';
        for(var i=0;i<=selectedfiles.length - 1;i++)
        {
            dfiles += $("#" + selectedfiles[i]).attr("data-fullname") + "/";
        }
        window.location = downloadm_url + "?full_path=" + full_path + "&files=" + dfiles;
    }
}

function shareLinkFile()
{
    if(selectedfiles.length == 1)
    {
        if($("#" + selectedfiles[0]).attr("data-itemtype") != "folder")
        {
            closeThisModal('sharetype');
            $("#shareLinkHolder").append('<div class="divLoad"><div class="loader-background"></div><div class="loading">' + loader_url + '</div></div>');

            $("#modalBackground").show();
            $("#shareLinkHolder").show();
            sharelink = true;

            if(xShareLinks && xShareLinks.readyState != 4)
            {
                xShareLinks.abort();
            }

            xShareLinks = $.ajax({
                url:sharelink_url,
                data : {"file": $("#" + selectedfiles[0]).attr("data-fullname"), "fullpath":full_path},
                type:"POST",
                dataType:"json",
                success:function(data)
                {
                    if(data.status == "success")
                    {
                        $("#shareUrl").val(data.share_url);
                        $("#claimFile").val(data.claim_code);
                        $("#shareUrlPlaceholder").hide();
                        $("#claimFilePlaceholder").hide();
                        $("#shareUrl").bind("click", function(){
                            $(this).focus();
                            $(this).select();
                        });

                        $("#claimFile").bind("click", function(){
                            $(this).focus();
                            $(this).select();
                        });
                    }
                    else if(data.status == "error")
                    {
                        showToast(data.error_msg);
                        closeThisModal('sharelink');
                    }
                    $("#shareLinkHolder .divLoad").remove();
                }
            });
        }
        else
        {
            showToast("Folders cannot be shared.");
        }
    }
    else if(selectedfiles.length > 1)
    {
        showToast("Share link applies for 1 file only.");
    }
    else
    {
        showToast("Please select a file first.");
    }
}

$(document).ready(function(){

	$('#spotlightPlace').tinyscrollbar();
    $('#selectedFiles').tinyscrollbar();
    retrieve();

    $('#createFolderName').val('');
    $('#renameItemName').val('');

    window.onload = function(event) {
        reloadFileList();
    }

    $(window).resize(function(){
        $('#selectedFiles').tinyscrollbar();
    });

    $("#uploadFile").live("mouseover", function(){
        $("#uploadHelper").show();
    }).live("mouseout", function(){
        $("#uploadHelper").hide();
    });

    $("#addNewFolder").live("mouseover", function(){
        $("#addFolderHelper").show();
    }).live("mouseout", function(){
        $("#addFolderHelper").hide();
    });

    $("#recoverBin").live("mouseover", function(){
        $("#recoverHelper").show();
    }).live("mouseout", function(){
        $("#recoverHelper").hide();
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

    $(".group-share-button").live("click", function(){
        var share_id = $(this).attr("id");
        $("#groupShareHolder").append('<div class="divLoad"><div class="loader-background"></div><div class="loading">' + loader_url + '</div></div>');
        
        if(xShareToGroup && xShareToGroup.readyState != 4)
        {
            xShareToGroup.abort()
        }

        var selectednames = new Array();
        for(var i=0;i<selectedfiles.length;i++)
        {
            selectednames.push($("#"+selectedfiles[i]).attr("data-fullname"));
        }

        xShareToGroup = $.ajax({
            url:groupshare_url,
            data:{"share_id":share_id, "files_selected":selectednames, "path":full_path},
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
                $("#groupShareHolder .divLoad").remove();
            }
        });
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