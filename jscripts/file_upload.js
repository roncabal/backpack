<!--
var uploading = false;
$(document).ready(function(){
function $(id) {
	return document.getElementById(id);	
}

function getUrlVars() {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
        vars[key] = value;
    });
    return vars;
}

var pocket = getUrlVars()["open"];
if(pocket == null){
	pocket = 'main';
}

var uploader = new plupload.Uploader({
	runtimes : 'gears,html5,flash,silverlight,browserplus',
	browse_button : 'pickfiles',
	urlstream_upload: true,
	container: 'container',
	max_file_size : '150mb',
	url : '../util/upload.php?pocket=' + pocket,
	resize : {quality : 100},
	flash_swf_url : '../pluploadjs/js/plupload.flash.swf',
	silverlight_xap_url : '../pluploadjs/js/plupload.silverlight.xap',
	filters : [
		{title : "Image files", extensions : "jpg,gif,png,tif"},
		{title : "Video files", extensions: "avi,mp4,mpeg,3gp"},
		{title : "Audio files", extensions: "mp3,wav"},
		{title : "Document files", extensions: "doc,docx,ppt,txt"},
		{title : "Zip/Rar files", extensions : "zip,rar"}
	],
	init : { FileUploaded: function(up, file, info) {
			loadXMLDoc();            
		}
	}
});

uploader.bind('FilesAdded', function(up, files) {
	for (var i in files) {
		$('filelist').innerHTML += '<div id="' + files[i].id + '" class="toBeUploaded">' + files[i].name + ' (' + plupload.formatSize(files[i].size) + ') <b></b><span id="'+files[i].id+'" class="removeFile"> Remove from queue</span></div>';
	}
});

uploader.bind('UploadProgress', function(up, file) {
	$(file.id).getElementsByTagName('b')[0].innerHTML = '<progress value=' + file.percent + ' min="0" max="100" ></progress>';
	if(file.percent == 100){
	$(file.id).getElementsByTagName('b')[0].innerHTML = 'Upload successful!';
	}
	if(uploading = true){
		window.onbeforeunload = function(){
			return 'Hello';
		};
	}
});

uploader.bind('UploadComplete', function(){
	uploading = false;
});

$('uploadfiles').onclick = function() {
	uploading = true;
	uploader.start();
	return false;
};
				
uploader.init();

});

-->
