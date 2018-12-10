/**	
 *	Developed by: Lisa DeBona	
 */
jQuery(document).ready(function ($) {
	'use strict';

	/* Upload Media - Image */
	$(document).on("click",".upload-media-image",function(e){
		e.preventDefault();
		var frame;
		var parentRow = $(this).parents('tr.mb_row');
		parentRow.addClass('add-image');
		var image_Input = parentRow.find('input.banner_image');
		var imageDiv = parentRow.find('.upload-image-div');
		if ( frame ) {
			frame.open();
			return;
		}

		 // Create a new media frame
		frame = wp.media({
			title: 'Select Image',
			button: {
			text: 'Use this media'
			},
			multiple: false  // Set to true to allow multiple files to be selected
		});

		// When an image is selected in the media frame...
	    frame.on( 'select', function(e) {
	      
	      // Get media attachment details from the frame state
	      var attachment = frame.state().get('selection').first().toJSON();
	      var imageSrc = attachment.url;
	      var attachmentID = attachment.id;
	      image_Input.val( attachmentID );

	      imageDiv.removeClass('no-image');
	      var backgroundImageCss = 'background-image:url('+imageSrc+')';
	      imageDiv.attr('style',backgroundImageCss);
	      parentRow.removeClass('add-image');

	    });

	    //Finally, open the modal on click
	    frame.open();
	    return false;
	});

	$(document).on("click",".svc_upload",function(e){
		e.preventDefault();
		var frame;

		if( $(this).attr('data-col')!=undefined ) {
			var parentRow = $(this).parents('.intro_col');
			var image_Input = parentRow.find('input.column_info_image');
		} else {
			var parentRow = $(this).parents('.svcItem');
			var image_Input = parentRow.find('input.svc_icon');
		}
		var imageContainer = parentRow.find('.icon-container');
		
		if ( frame ) {
			frame.open();
			return;
		}

		 // Create a new media frame
		frame = wp.media({
			title: 'Select Image',
			button: {
			text: 'Use this media'
			},
			multiple: false  // Set to true to allow multiple files to be selected
		});

		// When an image is selected in the media frame...
	    frame.on( 'select', function() {
	      // Get media attachment details from the frame state
	      var attachment = frame.state().get('selection').first().toJSON();
	      var imageSrc = attachment.url;
	      var attachmentID = attachment.id;
	      image_Input.val( attachmentID );
	      var appendImage = '<img src="'+imageSrc+'" alt="" />'
	      imageContainer.html(appendImage);
	      parentRow.find('.svc_delete').addClass('showdiv');
	      parentRow.find('.svc_upload').addClass('hidediv');
	    });

	    // Finally, open the modal on click
	    frame.open();
	    return false;
	});

	/* Delete Image */
	$(document).on("click",".img-deleted",function(e){
		e.preventDefault();
		var tr_row = $(this).parents('tr.mb_row');
		tr_row.find('.upload-image-div').css('background-image','').addClass('no-image');
		tr_row.find('input.banner_image').val("");
	});

	$(document).on("click",".svc_delete",function(e){
		e.preventDefault();
		if( $(this).attr('data-col')!=undefined ) {
			var parent = $(this).parents('.intro_col');
			parent.find('input.column_info_image').val("");
		} else {
			var parent = $(this).parents('.svcItem');
			parent.find('input.svc_icon').val("");
		}
		parent.find('.icon-container').html("");
		parent.find('.svc_upload').removeClass('hidediv');
		$(this).removeClass('showdiv');
	});

	do_js_select2();
	function do_js_select2() {
		$('.jsselect2').select2({
			placeholder: 'Select...'
		});
	}

	$(document).on("change","input.mb_button_link_type",function(){
		var opt = $(this).val();
		var parent = $(this).parents('tr');
		parent.find('input.mb_button_link_type').removeAttr('checked').prop('checked',false).attr('data-checked','');
		parent.find(".link-field .mb_field_control").removeAttr('name');
		parent.find(".link-field").removeClass('showdiv');
		parent.find(".link-field").find(".mb_field_control").val("");
		parent.find(".link-field." + opt).addClass('showdiv').find(".mb_field_control").focus();
		var input_name = parent.find(".link-field ." + opt).attr('data-name');
		parent.find(".link-field ." + opt).attr('name',input_name);
		$(this).prop('checked',true);
		$(this).attr('checked',true);
		$(this).attr('data-checked','checked');
		do_js_select2();
	});

	$(document).on("click",".mb-action,.addItemBtn",function(e){
		e.preventDefault();
		var type = $(this).data("type");
		var nonce = $("input#mb_nonce").val();
		var parent = '';
		if( $(this).parents('tr.mb_row').length> 0 ) {
			parent = $(this).parents('tr.mb_row');
		}
		if(type=='plus' || type=='new_row') {
			var rows = count_table_row();
			var new_row = (rows>0) ? parseInt(rows) + 1 : 1;
			$.ajax({
				type : "post",
				dataType : "json",
				url : metajs.ajaxurl,
				data : {action: "mb_form_fields", 
						mb_nonce: nonce,
						rownum : new_row
				},
				success: function(response) {
					var the_fields = response.fields;
					if( the_fields ) {

						if(type=='plus') {
							$(the_fields).insertAfter(parent);

							$("tr.mb_row.new").fadeIn('normal',function(){
								$(this).removeClass("new");
								do_js_select2();
							});
							var textarea_id = "banner_caption" + new_row;
							//tinyMCE.execCommand('mceAddControl',false,textarea_id);
	        				//tinyMCE.execCommand('mceAddEditor', false, textarea_id);

						} else {
							var appendToArea = $('table.mb-table tbody');
							appendToArea.append(the_fields);

							$("tr.mb_row.new").fadeIn('normal',function(){
								$(this).removeClass("new");
								do_js_select2();
							});
							var textarea_id = "banner_caption" + new_row;
							//tinyMCE.execCommand('mceAddControl',false,textarea_id);
	        				//tinyMCE.execCommand('mceAddEditor', false, textarea_id);
						}


						load_WYSIWYG(textarea_id);
						
					}
				}
			});   
		} 
		else if(type=='minus') {
			parent.fadeOut('fast',function(){
				$(this).remove();
			});
		}
	});

	$(document).on("click","#addSvcBtn,.addSvcCol",function(e){
		e.preventDefault();
		var type = $(this).data("type");
		var nonce = $("input#mb_nonce").val();
		var parent = '';
		if( $(this).parents('.svcItem').length> 0 ) {
			parent = $(this).parents('.svcItem');
		}
		if(type=='plus' || type=='new_row') {
			var rows = $('.svcItem').length;

			var new_row = (rows>0) ? parseInt(rows) + 1 : 1;
			$.ajax({
				type : "post",
				dataType : "json",
				url : metajs.ajaxurl,
				data : {action: "mb_service_fields", 
						mb_nonce: nonce,
						rownum : new_row
				},
				success: function(response) {
					var the_fields = response.fields;
					if( the_fields ) {

						if(type=='plus') {
							$(the_fields).insertAfter(parent);
							var textarea_id = "svcText" + new_row;
							$(".svcItem.new").fadeIn('normal',function(){
								$(this).removeClass("new");
							});
						} else {
							var appendToArea = $('ul.mb_column_list');
							appendToArea.append(the_fields);

							$(".svcItem.new").fadeIn('normal',function(){
								$(this).removeClass("new");
							});
							var textarea_id = "svcText" + new_row;
						}


						load_WYSIWYG(textarea_id,false);
						
					}
				}
			});   
		} 
		else if(type=='minus') {
			parent.fadeOut('fast',function(){
				$(this).remove();
			});
		}
	});

	$(document).on("click",".deleteSvcCol",function(e){
		e.preventDefault();
		$(this).parents('li.svcItem').fadeOut('normal',function(){
			$(this).remove();
		});
	});

	function count_table_row() {
		var count = $('tr.mb_row').length;
		return count;
	}


	/* DRAGGABLE TABLE ROW */
	var fixHelperModified = function(e, tr) {
    var $originals = tr.children();
    var $helper = tr.clone();
    $helper.children().each(function(index) {
        $(this).width($originals.eq(index).width())
        if( $(this).find('.mb_mceEditor').length>0 ) {
	        var textarea_id = $(this).find('.mb_mceEditor').attr('id');
	        if( textarea_id != undefined ) {
	        	$(this).find('.mb_mceEditor').show();
	        	$(this).find('.mce-container').hide();
	        }
    	}

    	if( $(this).find('.mmSvcEditor').length>0 ) {
    		var textarea_id = $(this).find('.mmSvcEditor').attr('id');
	        if( textarea_id != undefined ) {
	        	$(this).find('.mmSvcEditor').show();
	        	$(this).find('.mce-container').hide();
	        }
    	}

    });
	    return $helper;
	},
    stopAction = function(e, ui) {
    	var My_New_Global_Settings =  tinyMCEPreInit.mceInit.content;  
		$('tr.mb_row', ui.item.parent()).each(function (i) {
        	var x = i + 1;
            var row = $(this);
            var textarea_id = $(this).find('.mb_mceEditor').attr('id');
            tinyMCE.execCommand('mceAddEditor', false, textarea_id);
        });
        
    },
    startAction = function(e, ui) {
		$(ui.item).find('textarea').each(function (i) {
			var x = i + 1;
			var textarea_id = $(this).attr('id');
			var target = $(this);
			tinymce.execCommand( 'mceRemoveEditor', false, textarea_id );
		});
    },
    updateAction = function(e, ui) {
    	tinyMCE.triggerSave();
    	$('tr.mb_row').each(function (i) {
        	var x = i + 1;
            var row = $(this);
            var new_textarea_id = 'banner_caption' + x;
            row.find('input.field_counter').val(x);
            row.find('.counter_text').text(x);
            var button_type_name = 'button_link_type_' + x;
            row.find('input.mb_button_link_type').attr("name",button_type_name);
            row.find('input.mb_button_link_type').each(function(){
            	if( $(this).attr('data-checked') ) {
            		$(this).prop('checked',true);
					$(this).attr('checked',true);
					$(this).attr('data-checked','checked');
            	}
            });
        });
    },
    stopServicesAction = function(e, ui) {
    	var My_New_Global_Settings =  tinyMCEPreInit.mceInit.content;  
		$('.svcItem', ui.item.parent()).each(function (i) {
        	var x = i + 1;
            var row = $(this);
            var textarea_id = $(this).find('.mmSvcEditor').attr('id');
            tinyMCE.execCommand('mceAddEditor', false, textarea_id);
        });
    }

	$(".sortable>tbody").sortable({
	    helper: fixHelperModified,
	    handle: "td.handle-sort",
	    forceHelperSize:		true,
		forcePlaceholderSize:	true,
		scroll:					true,
		start: startAction,
	    stop: stopAction,
	    update:updateAction,
	    placeholder: "ui-state-highlight"
	}).disableSelection();

	$(".mb_column_list").sortable({
	    helper: fixHelperModified,
	    handle: ".field-buttons",
	    forceHelperSize:		true,
		forcePlaceholderSize:	true,
		scroll:					true,
		start: startAction,
		stop: stopServicesAction,
	    placeholder: "ui-state-highlight"
	}).disableSelection();

	$('.mb_mceEditor').each(function(){
		var textarea_id = $(this).attr('id');
		load_WYSIWYG(textarea_id,true,100);
	});

	$('.introEditor').each(function(e){
		var textarea_id = $(this).attr('id');
		load_WYSIWYG(textarea_id,false,200);
	});

	$('.mmSvcEditor').each(function(e){
		var textarea_id = $(this).attr('id');
		load_WYSIWYG(textarea_id,false,100);
	});

	function load_WYSIWYG(textarea_id,hasFormat=true,texareaHeight=100) {
		if(hasFormat) {
			var toolbar_list = "styleselect | forecolor backcolor | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | link removeformat";
			var format_list = [
				{title : 'Heading 1', block : 'h1'},
				{title : 'Heading 2', block : 'h2'},
				{title : 'Heading 3', block : 'h3'},
				{title : 'Heading 4', block : 'h4'},
				{title : 'Heading 5', block : 'h5'},
				{title : 'Heading 6', block : 'h6'},
				{title : 'Paragraph', block : 'p'},
				{title : 'Block', block : 'block'}
			];
		} else {
			var toolbar_list = "forecolor backcolor | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | link removeformat";
			var format_list = false;
		}
	    tinymce.init({
			selector: '#'+textarea_id,
			theme: "modern",
			skin:"lightgray",
			language:"en",
			height: texareaHeight,
			branding:false,
			menubar:false,
			cleanup : true,
			force_br_newlines : true,
			force_p_newlines : true,
			forced_root_block : false,
			remove_linebreaks : false,
			oninit : "setPlainText",
			plugins: [
			    "paste link textcolor"
			],
			toolbar: toolbar_list,
			style_formats : format_list,
			setup: function(editor) {
				editor.on('BeforeSetContent', function (e) {
			      //e.content += 'My custom content!';
			    });
	           	editor.on('change', function(e) {
	                editor.save();
	            });
	        },
	        content_css:[includesURL + 'js/tinymce/skins/wordpress/wp-content.css']
		});
	}


	/* Tabs */
	$(document).on("click",".mb_tab_name",function(e){
		e.preventDefault();
		var panel_id = $(this).attr('href');
		$('.mb_tab_name').removeClass('active');
		$('.mb_tab_panel').removeClass('active');
		$(this).addClass('active');
		$(panel_id).addClass('active');
	});

});