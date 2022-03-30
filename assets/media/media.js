var FILEMANAGER_PAGE = 1;
var ACTIVE_EDITOR;
$(function(){
	$(document).on('hidden.bs.modal', '#media-modal', function(){
		if($(".modal.show").length > 0){
			$("body").addClass('modal-open');
		}
	});

	$(document).on('click', ".trigger-upload-image", function(e){
		e.preventDefault();
		//set data-hash to filemanager modal
		hash = $(this).closest(".input-image-holder").attr('data-hash');
		$("#media-modal").attr('data-hash', hash);
		$("#media-modal [data-hash]").attr('data-hash', hash);
		$("#media-modal").modal('show');
		loadFileManager(1, true);
	});

	$(document).on('click', '.input-image-holder .remove-image', function(e){
		e.preventDefault();
		par = $(this).closest('.input-image-holder');
		par.find('.listen-image-upload').val('');

		mi = par.find('img.media-item');
		mi.attr('src', mi.attr('data-fallback'));
	});

	$(document).on('click', '.square-image.add', function(e){
		e.preventDefault();
		container = $(this).closest('.media-multiple-holder');

		mainhash = container.attr('container-hash');
		html = $("#media-multiple-single-item-" + mainhash).html();
		container.find('.multi-media-container').append(html);

		container.find('.multi-media-container .square-image:last').attr('data-hash', makerandomstringhash(30));
		container.find('.multi-media-container .square-image:last-child .trigger-upload-image').trigger('click');
		// feather.replace();	
	});

	$(document).on('click', '.multi-closer', function(e){
		e.preventDefault();
		tgt = $(this).closest('.square-image');
		tgt.fadeOut(300);
		setTimeout(function(){
			tgt.remove();
		}, 310);
	});

});





Dropzone.autoDiscover = false;
function initImageDropzone(){
	$(".mydropzone").each(function(){
		var ajaxurl = $(this).data("target");
		var dropzonehash = $(this).attr('data-hash');
		var maxsize = $(this).attr('upload-limit');
		if(maxsize.length == 0){
			maxsize = 2;
		}

		if($(this).find('.dz-default').length == 0){
			$(this).dropzone({
				url : ajaxurl,
				acceptedFiles : 'image/*',
				maxFilesize : maxsize,
				sending : function(file, xhr, formData){
					formData.append("_token", window.CSRF_TOKEN);
					disableAllButtons();
				},
				init : function(){
					this.on("success", function(file, data){
						data = window.JSON.parse(file.xhr.responseText);
						this.removeFile(file);
						enableAllButtons();
					});

					this.on("queuecomplete", function(){
						this.removeAllFiles();
						enableAllButtons();
						afterFinishUpload();
					});
					this.on("error", function(file, err, xhr){
						this.removeAllFiles();
						enableAllButtons();
					});
				}
			});		
		}
	});		
}
$(function(){
	$(document).on('click', ".trigger-upload-tab", function(e){
		e.preventDefault();
		gotoUpload();
	});
	$(document).on('click', ".trigger-filemanager", function(e){
		e.preventDefault();
		gotoFilemanager();
	});

	$(document).on('click', '.filemanager-content .pagination a.page-link', function(e){
		e.preventDefault();
		page = parseInt($(this).attr('data-page'));
		loadFileManager(page);
	});

	// trigger utk menjalankan refresh file manager
	$(document).on('click', '.btn.sort-desc', function(){
		$(this).addClass('active');
		$(".btn.sort-asc").removeClass('active');
		loadFileManager();
	});
	$(document).on('click', '.btn.sort-asc', function(){
		$(this).addClass('active');
		$(".btn.sort-desc").removeClass('active');
		loadFileManager();
	});
	$(document).on('submit', '.media-search', function(e){
		e.preventDefault();
		loadFileManager();
	});
	$(document).on('click', '.refresh-button', function(){
		loadFileManager();
	});

	$(document).on('click', '.filemanager-detail .closer', function(e){
		e.preventDefault();
		hideImageDetail();
	});

	if( 
		$(".mydropzone").length || 
		$(".mydropzone-multiple").length
	){
		initImageDropzone();
	}

	$(document).on('click', '.media-image-thumb', function(e){
		e.preventDefault();
		loadImageDetail($(this));
	});

	$(document).on('click', '.filemanager-select-final', function(e){
		e.preventDefault();
		response = {};
		response.thumb = $(".filemanager-thumb-selection").val();
		response.id = $(".filemanager-thumb-selection").attr('data-id');
		response.path = $(".filemanager-thumb-selection").attr('data-path');

		//output format : JSON stringify & url path
		string_response = window.JSON.stringify(response);
		hash_target = $("#media-modal").attr('data-hash');

		//output format : string path utk wysiwyg
		if(window.ACTIVE_EDITOR){
			//for tinymce input : get thumb final URL from ajax
			$.ajax({
				url : window.AUTOCRUD_BASE_URL + '/media/get-image-url',
				type : 'GET',
				dataType : 'html',
				data : response,
				success : function(resp){
					window.ACTIVE_EDITOR.insertContent(resp);
					window.ACTIVE_EDITOR = null;
					resetModalFilemanager();
				},
				error : function(resp){
					alert('Sorry, some error occured when select the image');
					hideAutocrudLoading();
				}
			});
		}
		else if($(".input-image-holder[data-hash='"+hash_target+"']").length){
			input_target = $(".input-image-holder[data-hash='"+hash_target+"']");
			input_target.find('.listen-image-upload').val(string_response);
			input_target.find('.media-item').attr('src', $(".holder-image").attr('src'));
			resetModalFilemanager();
		}

	});

	$(document).on('click', '.delete-permanently', function(e){
		cf = confirm('Are you sure you want to delete this data? This action cannot be undone.');
		if(cf){
			showAutocrudLoading();
			media_id = $(this).closest('.filemanager-detail').find('select').attr('data-id');
			$.ajax({
				url : window.AUTOCRUD_REMOVE_MEDIA_URL + '/' + media_id,
				type : 'POST',
				dataType : 'json',
				data : {
					_token : window.CSRF_TOKEN
				},
				success : function(resp){
					loadFileManager(window.FILEMANAGER_PAGE);
				},
				error : function(resp){
					hideAutocrudLoading();
				}
			});
		}
	});
});

function resetModalFilemanager(){
	$("#media-modal").modal('hide');
	$("#media-modal").find('.opened').removeClass('opened');
	$("#media-modal").find('.selected').removeClass('selected');
}

function gotoUpload(){
	$("#media-modal .card-header").fadeOut();
	$(".filemanager-content").slideUp();
	$(".filemanager-upload").slideDown();
	$(".filemanager-upload .custom-dropzone").trigger('click');
}

function gotoFilemanager(reload){
	reload = reload || true;
	$(".filemanager-content").slideDown();
	$(".filemanager-upload").slideUp();
	$("#media-modal .card-header").fadeIn();

	if(reload){
		loadFileManager();
	}
	// feather.replace();
}

function afterFinishUpload(){
	gotoFilemanager(true);
}

function loadFileManager(page, ignore_loading){
	page = page || 1;
	ignore_loading = ignore_loading || false;

	if(!ignore_loading){
		showAutocrudLoading();
	}

	keyword = $("#media-search-keyword").val();
	sort_dir = $(".btn.sort-asc").hasClass('active') ? 'asc' : 'desc';
	$.ajax({
		url : window.AUTOCRUD_FILE_MANAGER_URL,
		type : 'POST',
		dataType : 'html',
		data : {
			keyword : keyword,
			sort_dir : sort_dir,
			page : page
		},
		success : function(resp){
			$(".filemanager-content").html(resp);
			// feather.replace();
			hideAutocrudLoading();
		},
		error : function(resp){
			toastr['error']('Sorry, we cannot process your request right now');
			hideAutocrudLoading();
		}
	});
}

function loadImageDetail(click_instance){
	$(".media-image-container .selected").removeClass('selected');
	click_instance.addClass('selected');
	thumb_src = click_instance.attr('data-src');
	media_id = click_instance.attr('data-media-id');
	filename = click_instance.attr('data-filename');
	path = click_instance.attr('data-origin');
	media_url = window.AUTOCRUD_STORAGE_URL + path;

	$(".filemanager-detail img").attr('src', thumb_src);
	$(".filemanager-detail .holder-title").html(filename);
	$(".filemanager-detail .holder-url").attr('href', media_url).html(media_url);
	$(".filemanager-detail .filemanager-thumb-selection").attr('data-id', media_id);
	$(".filemanager-detail .filemanager-thumb-selection").attr('data-path', path);
	$(".filemanager-content, .media-image-container, .filemanager-detail").addClass('opened');
	// feather.replace();	
}

function hideImageDetail(){
	$(".filemanager-content, .media-image-container, .filemanager-detail").removeClass('opened');
	$(".media-image-container .selected").removeClass('selected');
}



function openTinyMceMedia(target){
	window.ACTIVE_EDITOR = target;
	$("#media-modal").modal('show');
	loadFileManager(1, true);
}	

function makerandomstringhash(length){
    var result           = '';
    var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    var charactersLength = characters.length;
    for ( var i = 0; i < length; i++ ) {
      result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
   return result    
}