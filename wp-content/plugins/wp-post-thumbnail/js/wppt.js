jQuery(document).ready( function($) {

	var pluginpath = '../wp-content/plugins/wp-post-thumbnail';
	var adminAjaxPath = '../wp-admin/admin-ajax.php';
	var thumbWidth, thumbHeight, srcWidth, srcHeight, scaleRatio, jcrop_api;
	var x = 0, y = 0, x2 = 0, y2 = 0, w = 0, h = 0;
	var thumbID, postID = $('#post_ID').val();

	var spinner = new Image();
	$(spinner).attr({ id:"wppt-loading-spinner", src: pluginpath + "/images/loader-medium.gif" })
		.css({ "vertical-align":"text-bottom" }).load();

	var spinnerSmall = new Image();
	$(spinnerSmall).attr({ id:"wppt-loading-spinner-small", src: pluginpath + "/images/loader-small.gif" })
		.css({ "vertical-align":"text-bottom" }).load();

	$.ajaxSetup({ type:'POST', url:adminAjaxPath, cache:false, timeout: (30 * 1000) });

	$('#wppt_save_thumbnail_button').attr({ 'disabled':'disabled' }); /* Disable "Save Thumbnail" btn */
	buildThumbTabs();
	loadLibThumbs();


	/* --------------------------------------------------------------------------------------------------- */


	jQuery.fn.hoverCursorFadeIn = function( options ) {
		var defaults = { fadeOutOpacity:1 };
		var settings = jQuery.extend(defaults, options);

		return this.each( function() {
				jQuery(this).hover(function() {
					jQuery(this).stop().animate({ opacity: 1.0 }, 128).css({ 'cursor':'pointer' });
				}, function () {
					jQuery(this).stop().animate({ opacity: settings.fadeOutOpacity }, 128).css({ 'cursor':'' });
				});
		});
	};


	/* --------------------------------------------------------------------------------------------------- */


	(function ($) { $.fn.filterRegExp = function(options) {
		var defaults = { /*min:'0', max:'100',*/ regex:'', if_match:function(){}, if_not_match:function(){} };
		var settings = $.extend(defaults, options);
		var pasteEventName = ($.browser.msie ? 'paste' : 'input');

		function check($objField) {
			var val = $objField.val();
			if ( /*val >= settings.min && val <= settings.max &&*/ settings.regex.test( val ) ) {
				$objField.css({ "background":"#fff" });
				settings.if_match.call($objField);
			} else {
				$objField.css({ "background":"#ff2828" });
				settings.if_not_match.call($objField);
			}
		}

		return this.each(function() {
			$(this).keyup(function(e) { check($(this)); });
			$(this).bind(pasteEventName, function() { check($(this)); });
		});
	}; }) (jQuery);


	/* --------------------------------------------------------------------------------------------------- */


	jQuery.fn.ignoreReturnKey = function() {
		return this.each( function() {
			jQuery(this).keypress( function(e) {
				var charCode = ( e.which ) ? e.which : event.keyCode;
				if ( charCode == 13 ) { return false; }
				return true;
			});
		});
	};


	/* --------------------------------------------------------------------------------------------------- */


	function getSelectedThumb() {
		/* If a selected thumbnail is still loading, exit function */
		if ( $('.wppt_selected_thumbnail > .loading').is(':visible') ) { return false; }

		/* Append loading spinner next to selected thumbnail */
		$('.wppt_selected_thumbnail').append('<span class="loading">&nbsp;&nbsp;</span>')
			.children('.loading').append(spinner);

		$('.wppt-delete-thumbnail-link').hide(); /* Hide "Delete Thumbnail" link */

		/* Disable "Save Thumbnail" button */
		$('#wppt_save_thumbnail_button').attr({ 'disabled':'disabled' });

		/* Slightly fade thumbnail preview image */
		$('.wppt-thumbnail-preview-image > img').animate({ opacity: 0.38 }, 368);

		$.ajax({
			data: { action: 'getSelectedThumb', 'srcAttachID': $('#wppt-source-image').data('id'),
				'thumbID': thumbID, 'postID': postID, 'cookie': encodeURIComponent(document.cookie) },
			success: function( response ) {
				var thumb = JSON.parse( response );
				thumbWidth = thumb['wppt_preset_width'];
				thumbHeight = thumb['wppt_preset_height'];

				var str_metaDesc = thumbWidth + 'px, ' + thumbHeight + 'px';
				if ( thumb['wppt_preset_meta_key'] != null ) {
					str_metaDesc = str_metaDesc + " | Custom Key: "
									+ thumb['wppt_preset_meta_key'];
				}
				$('.wppt-thumbnail-preview-meta').empty().append( str_metaDesc );

				if ( typeof( thumb['guid'] ) != "undefined" ) { /* thumbnail exists */
					var img = new Image();
					$(img).attr({ id:"wppt-thumbnail-image", src:thumb['guid'] + '?rand=' + thumb['random_str'] }).load( function() {
						$('.wppt-thumbnail-preview-image').fadeOut(128, function() {
							$('.wppt-thumbnail-preview-image').empty().append( img ).fadeIn( 128, function() {
								$('.wppt-delete-thumbnail-link').show();
								$('.wppt_selected_thumbnail > .loading').remove();

								/* (Re)enable "Save thumbnail" button if a source image is loaded */
								if ( jcrop_api != null ) {
									$('#wppt_save_thumbnail_button').removeAttr('disabled');
								}
							});
						});
					});

					for (key in thumb) {
						if ( (key == "filename") || (key == "alt") || (key == "title") ) {
							var divID = '#wppt-thumbnail-' + key;

							$(divID + ' .wppt-optional-field-display-data').empty();

							if ( thumb[key].length > 1 ) {
								$(divID + ' .wppt-optional-field-display-empty-msg').hide();
								$(divID + ' .wppt-optional-field-display-data').append( thumb[key] );
							} else { $(divID + ' .wppt-optional-field-display-empty-msg').show(); }
						}
					}

					$('input[name=wppt_default_placement_group]')[thumb['placementID']].checked = true;

				} else { /* thumbnail does NOT exists */
					var div_notFound = "<div class=\"wppt-thumbnail-not-found\" style=\"width:" +
										thumbWidth + "px; height:" + thumbHeight + "px;\" ></div>";

					$('.wppt-thumbnail-preview-image').fadeOut( 128, function() {
						$(this).empty().append(div_notFound).show();

						$('.wppt-optional-field-display-data').empty();
						$('.wppt-optional-field-display-empty-msg').show();
						$('.wppt_selected_thumbnail > .loading').remove();

						/* (Re)enable "Save thumbnail" button if a source image is loaded */
						if ( jcrop_api != null ) {
							$('#wppt_save_thumbnail_button').removeAttr('disabled');
						}
					});
				}

				if ( ( thumbWidth >= srcWidth ) || ( thumbHeight >= srcHeight ) ) {
					alert( thumb['smallSourceWarningMsg'] );
				}

				if ( jcrop_api != null ) {
					jcrop_api.setOptions({ aspectRatio: thumbWidth/thumbHeight });
					jcrop_api.setOptions(
						( ( thumbWidth >= srcWidth ) || ( thumbHeight >= srcHeight ) ) ?
						{ minSize: [ 0, 0 ] } : { minSize: [ thumbWidth, thumbHeight ] } );
					jcrop_api.setSelect( ( typeof( thumb['x'] ) != "undefined" ) ?
						[ thumb['x'], thumb['y'], thumb['x'] + thumb['w'], thumb['y'] + thumb['h'] ] :
						[ 0, 0, thumbWidth, thumbHeight ] );
					jcrop_api.focus();
				}
			} /* success */
		}); /* $.ajax */
	} /* getSelectedThumb */


	/* --------------------------------------------------------------------------------------------------- */


	function buildThumbTabs() {
		$.ajax({
			data: { action:'buildThumbTabs', 'cookie':encodeURIComponent(document.cookie) },
			success: function(res) {
				$('.wppt_thumbnail_preview').before(res);

				var $thumbnailTabs = $('.wppt_thumbnail_tabs_ul > li');
				var $firstThumbnailTab = $thumbnailTabs.eq(0);

				$thumbnailTabs.hoverCursorFadeIn().click( function() {
					if ( $(".wppt_selected_thumbnail > .loading").is(":visible") ) {
						return false;
					}
					thumbID = $(this).attr('id');
					thumbID = thumbID.charAt(thumbID.length-1);
	
					$thumbnailTabs.removeClass('wppt_selected_thumbnail');
					$(this).addClass('wppt_selected_thumbnail');
					getSelectedThumb();
				});

				/* First thumbnail is selected by default when writing/editing posts */
				$firstThumbnailTab.addClass('wppt_selected_thumbnail');
				thumbID = $firstThumbnailTab.attr('id');
				thumbID = thumbID.charAt(thumbID.length-1);
				getSelectedThumb();
			} /* success */
		}); /* $.ajax */
	} /* buildThumbTabs */


	/* --------------------------------------------------------------------------------------------------- */


	function loadImageBindJcrop(data) {
		/* Image attachment source is not found */
		if ( typeof( data['error'] ) != "undefined" ) {
			/* Remove current source image and divs. Hide source image loading message. */
			$("#wppt-source-image-area > .wppt-source-image-loading").hide(); /* Hide loading message */

			if ( jcrop_api != null ) {
				var coords = jcrop_api.tellSelect();
				jcrop_api.setSelect( coords.x, coords.y, coords.x2, coords.y2 );
				jcrop_api.enable();
			}

			if ( data['attachID'] == $('#wppt-source-image').data('id') ) {
				$("#wppt-source-image-area").hide().
					children('img, div').not(".wppt-source-image-loading").remove();
			}

			/* (Re)enable "Save Thumbnail" button */
			if ( $("#wppt-source-image").length ) { $('#wppt_save_thumbnail_button').removeAttr('disabled'); }

			alert( data['error'] );
			return false;
		}

		/* Load the source image for cropping */
		srcWidth = data[1];
		srcHeight = data[2];
		var img = new Image();
		$(img).attr({ id:"wppt-source-image", src:data['resizedImgUrl'] } )
			.data( "abspathToSrc", data['abspath'] ).data( "id", data['attachID'] )
			.load( function() {
				/* Remove current source image and divs */
				$("#wppt-source-image-area").children('img, div').not(".wppt-source-image-loading").remove();
				$("#wppt-source-image-area").prepend(this); /* Prepend new source image */
				$("#wppt-source-image-area > .wppt-source-image-loading").hide(); /* Hide loading message */

				if ( ( thumbWidth >= srcWidth ) || ( thumbHeight >= srcHeight ) ) {
					alert( data['warning_smallSrcImg'] );
				}

				if ( jcrop_api != null ) { jcrop_api.destroy(); }
				jcrop_api = $.Jcrop(this);
				jcrop_api.setOptions({ aspectRatio: thumbWidth/thumbHeight, bgOpacity:0.38,
					allowSelect:false, trueSize:[ srcWidth, srcHeight ], bgColor:'#fff' });
				jcrop_api.setOptions( ( ( thumbWidth >= srcWidth ) || ( thumbHeight >= srcHeight ) ) ?
					{ minSize: [ 0, 0 ] } : { minSize: [ thumbWidth, thumbHeight ] } );
				jcrop_api.setSelect( ( typeof( data['x'] ) != "undefined" ) ?
					[ data['x'], data['y'], data['x'] + data['w'], data['y'] + data['h'] ] :
					[ 0, 0, thumbWidth, thumbHeight ] );
				jcrop_api.focus();

				/* (Re)enable "Save Thumbnail" button */
				$('#wppt_save_thumbnail_button').removeAttr('disabled');
			}); /* $(img) chaining */
	} /* loadImageBindJcrop() */


	/* --------------------------------------------------------------------------------------------------- */


	function libThumbsBindClick() {
		/* for each image library thumbnails */
		$('ul.wppt-image-library-thumbnails').find('img')
			.hoverCursorFadeIn({ fadeOutOpacity: 0.38 })
			.unbind('click').bind('click', function(e) {

				/* Check for source image loading gif to see if a source image is currently loading */
				if ( $("#wppt-source-image-area > .wppt-source-image-loading")
					.is(":visible") ) { return false; } /* If yes, cancel click event action. */

				/* Disable "Save Thumbnail" button */
				$('#wppt_save_thumbnail_button').attr({ 'disabled':'disabled' });

				var attachID = $(this).attr("alt"); /* ID of source image to crop */

				/* Animate thumbnail click effect, something fancy */
				$(this).animate({ opacity: 0.3 }, 68, function() {
					if ( jcrop_api != null ) { jcrop_api.release(); }

					$(this).animate({ opacity: 1.0 }, 68, function() {
						var containerWidth = $("#wp-post-thumbnail > .inside").width();

						$("#wppt-source-image-area").width(containerWidth).show();
						$("#wppt-source-image-area > div.wppt-source-image-loading").show();

						/* load full image of clicked thumbnail via ajax */
						$.ajax({ data: { action: 'get_src_image_attachment',
								'cookie': encodeURIComponent(document.cookie),
								'attachID': attachID, 'postID': postID,
								'thumbID': thumbID, 'containerWidth': containerWidth },
							success: function( response ) {
								loadImageBindJcrop( JSON.parse( response ) );
							},
							error: function (XMLHttpRequest, textStatus, errorThrown) {
								$("#wppt-source-image-area > .wppt-source-image-loading").hide(); /* Hide loading message */
								
								if ( $("#wppt-source-image").length ) /* (Re)enable "Save Thumbnail" button */
									$('#wppt_save_thumbnail_button').removeAttr('disabled');

							},
							complete: function() {  }
						});

					}); /* $(this).animate() */
				}); /* $(this).animate() */

			}); /* .click() */

		/* $('.image-library-thumbnails').find('img') chaining end */

	} /* libThumbsBindClick() */


	/* ---------------------------------------------------------------- get image library thumbnails ----- */


	/* get image library thumbnails */
	function loadLibThumbs() {
		$('#wppt-image-library-thumbnails-area > .footer > .msg').hide();
		$('#wppt-image-library-thumbnails-area > .footer > .loading-msg').show();
	
		$.ajax({ data: { action:'list_thumbs', 'postID':postID,
				'cookie':encodeURIComponent(document.cookie) },
			success: function( response ) {
				$('#wppt-image-library-thumbnails-area > .footer > .loading-msg').hide();
				$('#wppt-image-library-thumbnails-area > .footer > .msg').show();
				$('#wppt-image-library-thumbnails-area > .no-image-attachments-found-msg').remove();
				$('#wppt-image-library-thumbnails-area > ul').remove();
				$('#wppt-image-library-thumbnails-area').prepend( response );
				libThumbsBindClick();
			}
		}); /* $.ajax */
	}

	$('#wppt-reload-image-library').click( function(e) { loadLibThumbs(); });


	/* ------------------------------------------------------------------------------- Settings Page ----- */


	$('#wp-post-thumbnail-save-options').click( function(e) {
		$(this).hide().siblings('.wppt-loading-msg').show();

		$.ajax({ data: { action:'save_options',
				'cookie':encodeURIComponent(document.cookie),
				'default_jpeg_quality': $('#wppt-jpeg-quality-option option:selected').val(),
				'default_thumbnail': $('#wppt-default-thumbnail option:selected').val() },
			success: function(res) {
				$('#wp-post-thumbnail-save-options').siblings('.wppt-loading-msg').hide();
				$('#wp-post-thumbnail-save-options').show();
			}
		});
	});


	/* --------------------------------------------------------- "Save Thumbnail" button click event ----- */


	$('#wppt_save_thumbnail_button').click( function(e) {
		var coords = jcrop_api.tellSelect();
		var placementID = $('[name=wppt_default_placement_group]:checked').val();
		var thumbFilenameKeywords = $('#wppt-thumbnail-filename .wppt-optional-field-display-data').text();
		var thumbAlt = $('#wppt-thumbnail-alt .wppt-optional-field-display-data').text();
		var thumbTitle = $('#wppt-thumbnail-title .wppt-optional-field-display-data').text();

		$('.wppt-delete-thumbnail-link').hide();

		$('#wppt_save_button_area').fadeOut(228, function() {
			$('#wppt_save_button_area_loading').show();
		});

		$.ajax({ data: { action: 'saveThumbnail',
				'cookie': encodeURIComponent(document.cookie),
				'x': coords.x, 'y': coords.y,
				'x2': coords.x2, 'y2': coords.y2,
				'postID': postID,
				'thumbID': thumbID,
				'filenameKeywords': thumbFilenameKeywords,
				'attachID': $('#wppt-source-image').data('id'),
				'placementID': placementID,
				'alt': thumbAlt,
				'title': thumbTitle },
			success: function( response ) {
				getSelectedThumb();
				$('#wppt_save_button_area_loading').hide();
				$('#wppt_save_button_area').show();
			}
		});
	});


	/* ----------------------------------------------------------------- Delete thumbnail link clicked ----- */


	$('.wppt-delete-thumbnail-link').click( function(e) {
		e.preventDefault();
		if ( confirm('Delete this thumbnail. Are you sure?') ) {
			$.ajax({ data: { action: 'fullDeleteThumbnail',
					'cookie': encodeURIComponent(document.cookie),
					'postID': postID, 'thumbID': thumbID },
				success: function( response ) { getSelectedThumb(); }
			});
		}
		return false;
	});


	/* --------------------------------------------------------- Show/hide "optional thumbnail fields" ----- */


	$('.wppt_optional_fields_toggle').toggle(
		function() { $('.wppt_optional_fields').slideDown(); },
		function() { $('.wppt_optional_fields').slideUp(); }
	);


	/* --------------------------------------- Optional thumbnail fields common hover and clicks event ----- */


	$('.wppt-optional-field-display').hover(
		function() { $(this).css({ 'background-color':'#fffec9' }); },
		function() { $(this).css({ 'background-color':'transparent' });
	}).click( function(e) {
		var $currentValue = $(this).find('.wppt-optional-field-display-data').text();

		$('.wppt-loading-msg:hidden')
			.siblings('.wppt-optional-field-edit').hide().end()
			.siblings('.wppt-optional-field-display').show();

		$(this).hide().css({ 'background-color':'transparent' });
		$(this).parent().find('.wppt-optional-field-edit').show().end()
			.find('.wppt-optional-text-field').val($currentValue).focus().ignoreReturnKey();
	});

	$('.wppt-optional-field-cancel-button').click( function(e) {
		$('.wppt-optional-field-edit').hide();
		$('.wppt-optional-field-display').show();
	});

	$('.wppt-optional-field-save-button').click( function(e) {
		var $thisParent = $(this).parent();
		var $thisParentParent = $(this).parent().parent();
		var fieldValue = $(this).siblings('.wppt-optional-text-field').val();
		var php_function_name;

		function restoreUI() {
			$thisParentParent.find('.wppt-loading-msg').hide();

			if ( fieldValue.length <= 0 ) {
				$thisParentParent.find('.wppt-optional-field-display-empty-msg').show();
			} else { $thisParentParent.find('.wppt-optional-field-display-empty-msg').hide(); }

			$thisParentParent.find('.wppt-optional-field-display').show();
		}

		$thisParent.hide();
		$thisParentParent.find('.wppt-loading-msg').show().end()
			.find('.wppt-optional-field-display-data').text(fieldValue);

		if ( $('#wppt-thumbnail-image').length ) {
			switch ( $thisParentParent.attr('id') ) {
				case "wppt-thumbnail-filename": php_function_name = "saveThumbnailFilename"; break;
				case "wppt-thumbnail-alt": php_function_name = "saveThumbnailAlt"; break;
				case "wppt-thumbnail-title": php_function_name = "saveThumbnailTitle";
			}

			$.ajax({ data: { action: php_function_name, 'fieldValue': fieldValue,
					'cookie': encodeURIComponent(document.cookie),
					'postID': postID, 'thumbID': thumbID },
				success: function( response ) { restoreUI(); },
			});
		} else { restoreUI(); }
	});

	$("input[name='wppt_default_placement_group']").change( function() {
		if ( $('#wppt-thumbnail-image').length <= 0 ) { return false; }

		var $elems_LabelsAndInputs = $(this).parent().find('label, input');
		var $div_LoadingMsg = $(this).parent().find('.wppt-loading-msg');

		$elems_LabelsAndInputs.hide();
		$div_LoadingMsg.show();

		$.ajax({ data: { action: 'saveThumbnailPlacement', 'thumbID': thumbID,
				'cookie': encodeURIComponent(document.cookie), 'postID': postID,
				'fieldValue': $('[name=wppt_default_placement_group]:checked').val() },
			success: function(res) {
				$div_LoadingMsg.fadeOut(268, function() {
					$elems_LabelsAndInputs.fadeIn(268);
				});
			}
		});
	});


	/* -------------------------------------------------------------------- Options Page - Set default thumbnail --- */


	$('ul.wppt_options_thumbnail_tabs_ul > li').hoverCursorFadeIn().click( function(e) {
		var $clickedID = $(this).attr('id');
			$clickedID = $clickedID.charAt( $clickedID.length-1 );

		$(this).siblings().removeClass('wppt-options-selected-thumbnail').end()
			.addClass('wppt-options-selected-thumbnail');

		$('ul.wppt_options_thumbnail_ul > li').hide();
		$('ul.wppt_options_thumbnail_ul > li:eq(' + $clickedID + ')').show();
	});


	/* ---------------------------------------------------------- Options Page - Set Thumbnail "Custom Key" name --- */


	$("input[name='wppt-custom-thumbnail-meta-key-name']")
		.filterRegExp({ regex:/^[A-Za-z0-9\-\_]+$/,
			if_match: function() { $(this).siblings(".wppt-custom-thumbnail-meta-key-name-set").removeAttr('disabled'); },
			if_not_match: function() { $(this).siblings(".wppt-custom-thumbnail-meta-key-name-set").attr({ 'disabled':'disabled' }); }
		});

	$(".wppt-custom-thumbnail-meta-key-name-set").click( function(e) {
		var $this = $(this); /* clicked "Assign It" button */

		/* Get custom key name entered by the user */
		var $custom_key = $this.siblings( "input[name='wppt-custom-thumbnail-meta-key-name']" ).val();
		if ( $custom_key.length < 1 ) {
			e.preventDefault();
			return false;
		}

		/* Get clicked thumbnail's id in which to assign it to */
		var $clicked_thumbnail_id = $this.parents("table")
			.find( "input[name='wppt-custom-thumbnail-id']" ).val();

		/* Hide "Assign it" button, show loading message called via AJAX
		function "saveThumbCustomKey()" in "wppt.php". */
		$this.hide().siblings(".wppt-loading-msg").show();

		$.ajax({ data: { action: "saveThumbCustomKey",
				"cookie": encodeURIComponent(document.cookie),
				"custom_key": $custom_key,
				"clicked_thumbnail_id": $clicked_thumbnail_id  },
			success: function( res ) {
				$this.show().siblings(".wppt-loading-msg").hide();
			}
		});
	});


	/* -------------------------------------------------------- Options Page - Resize thumbnail width and height --- */


	/* Numeric only. If not, disable "Resize" button. */
	$("input[name='wppt-custom-thumbnail-width'], input[name='wppt-custom-thumbnail-height']")
		.filterRegExp({ regex:/^[0-9]+$/,
			if_match: function() { $(this).siblings(".wppt-custom-thumbnail-resize").removeAttr('disabled'); },
			if_not_match: function() { $(this).siblings(".wppt-custom-thumbnail-resize").attr({ 'disabled':'disabled' }); }
		});

	$(".wppt-custom-thumbnail-resize").click( function(e) { /* When "Resize" button is clicked */
		var $this = $(this); /* get the clicked "Resize" button */

		/* Get width and height entered by the user. Check again if not valid numbers, return false. */
		var w = $this.parents("table").find( "input[name='wppt-custom-thumbnail-width']" ).val();
		var h = $this.siblings( "input[name='wppt-custom-thumbnail-height']" ).val();
		if ( !( /^[0-9]+$/.test(w) && /^[0-9]+$/.test(h) ) ) return false;

		/* Get clicked thumbnail's ID so that we know which thumbnail to resize. */
		var clicked_thumbnail_id = $this.parents("table").find( "input[name='wppt-custom-thumbnail-id']" ).val();

		$this.hide().siblings(".wppt-loading-msg").show(); /* Hide "Resize" button, show loading message */

		/* Call function "saveThumbSize()" in "wppt.php" via AJAX */
		$.ajax({ data: { action: 'saveThumbSize', 'cookie': encodeURIComponent(document.cookie),
				'width': w, 'height': h, 'clicked_thumbnail_id': clicked_thumbnail_id  },
			success: function( response ) {
				$this.show().siblings(".wppt-loading-msg").hide(); /* Hide loading message. Show "Resize" button. */
				alert( response );
			}
		});
	});


	/* -------------------------------------- Prevent use of WP Post Thumbnail if post has not been "Save Draft" --- */


	if ( postID < 1 ) {
		$("#wp-post-thumbnail div.inside").children("div, br").not(".wppt-save-draft-before-using-msg").remove();
		$("#wp-post-thumbnail div.inside").find(".wppt-save-draft-before-using-msg").show();
	}

});