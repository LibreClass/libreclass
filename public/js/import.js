$(function() {
	'use strict';

	// UPLOAD CLASS DEFINITION
	// ======================

	$(".btn-upload").click(function() {
		$("#block-add").hide();
		$(".spinupload").show();
		$(".progress-bar").addClass("progress-bar-striped active");
	});

	//    var dropZone = document.getElementById('drop-zone');
	//    var uploadForm = document.getElementById('js-upload-form');
	//
	//    var startUpload = function(files) {
	//        alert(files);
	//        console.log(files);
	//    }
	//
	//    uploadForm.addEventListener('submit', function(e) {
	//        var uploadFiles = document.getElementById('js-upload-files').files;
	//        e.preventDefault()
	//
	//        startUpload(uploadFiles)
	//    })
	//
	//    dropZone.ondrop = function(e) {
	//        e.preventDefault();
	//        this.className = 'upload-drop-zone';
	//
	//        startUpload(e.dataTransfer.files)
	//    }
	//
	//    dropZone.ondragover = function() {
	//        this.className = 'upload-drop-zone drop';
	//        return false;
	//    }
	//
	//    dropZone.ondragleave = function() {
	//        this.className = 'upload-drop-zone';
	//        return false;
	//    }
});
