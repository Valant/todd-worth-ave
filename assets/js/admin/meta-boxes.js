jQuery( function( $ ) {

	// Vessel gallery file uploads.
	var vessel_gallery_frame;
	var $image_gallery_ids = $( '#vessel_image_gallery' );
	var $vessel_images    = $( '#vessel_images_container' ).find( 'ul.vessel_images' );

	$( '.add_vessel_images' ).on( 'click', 'a', function( event ) {
		var $el = $( this );

		event.preventDefault();

		// If the media frame already exists, reopen it.
		if ( vessel_gallery_frame ) {
			vessel_gallery_frame.open();
			return;
		}

		// Create the media frame.
		vessel_gallery_frame = wp.media.frames.vessel_gallery = wp.media({
			// Set the title of the modal.
			title: $el.data( 'choose' ),
			button: {
				text: $el.data( 'update' )
			},
			states: [
				new wp.media.controller.Library({
					title: $el.data( 'choose' ),
					filterable: 'all',
					multiple: true
				})
			]
		});

		// When an image is selected, run a callback.
		vessel_gallery_frame.on( 'select', function() {
			var selection = vessel_gallery_frame.state().get( 'selection' );
			var attachment_ids = $image_gallery_ids.val();

			selection.map( function( attachment ) {
				attachment = attachment.toJSON();

				if ( attachment.id ) {
					attachment_ids   = attachment_ids ? attachment_ids + ',' + attachment.id : attachment.id;
					var attachment_image = attachment.sizes && attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;

					$vessel_images.append( '<li class="image" data-attachment_id="' + attachment.id + '"><img src="' + attachment_image + '" /><ul class="actions"><li><a href="#" class="delete" title="' + $el.data('delete') + '">' + $el.data('text') + '</a></li></ul></li>' );
				}
			});

			$image_gallery_ids.val( attachment_ids );
		});

		// Finally, open the modal.
		vessel_gallery_frame.open();
	});

	// Image ordering.
	$vessel_images.sortable({
		items: 'li.image',
		cursor: 'move',
		scrollSensitivity: 40,
		forcePlaceholderSize: true,
		forceHelperSize: false,
		helper: 'clone',
		opacity: 0.65,
		placeholder: 'ya-metabox-sortable-placeholder',
		start: function( event, ui ) {
			ui.item.css( 'background-color', '#f6f6f6' );
		},
		stop: function( event, ui ) {
			ui.item.removeAttr( 'style' );
		},
		update: function() {
			var attachment_ids = '';

			$( '#vessel_images_container' ).find( 'ul li.image' ).css( 'cursor', 'default' ).each( function() {
				var attachment_id = $( this ).attr( 'data-attachment_id' );
				attachment_ids = attachment_ids + attachment_id + ',';
			});

			$image_gallery_ids.val( attachment_ids );
		}
	});

	// Remove images.
	$( '#vessel_images_container' ).on( 'click', 'a.delete', function() {
		$( this ).closest( 'li.image' ).remove();

		var attachment_ids = '';

		$( '#vessel_images_container' ).find( 'ul li.image' ).css( 'cursor', 'default' ).each( function() {
			var attachment_id = $( this ).attr( 'data-attachment_id' );
			attachment_ids = attachment_ids + attachment_id + ',';
		});

		$image_gallery_ids.val( attachment_ids );

		// Remove any lingering tooltips.
		$( '#tiptip_holder' ).removeAttr( 'style' );
		$( '#tiptip_arrow' ).removeAttr( 'style' );

		return false;
	});

	// Add video row
	$('.ya-repeater-add-row').click(function(event) {
		var tpl_id = $(this).data('tmpl');
		var tpl    = $('#'+tpl_id).html();
		var index  = 0;
		var $tbody = $(this).closest('.ya-repeater-table').find('.ya-repeater-tbody')
		$tbody.find('tr').each(function(y, el) {
			var i = parseInt($(el).data('index'));
			if( i > index ) index = i;
		});
		index++;
		var search = '__index__';
		tpl = tpl.replace(new RegExp(search, 'g'), index);
		$tbody.append(tpl);
		$( document.body ).trigger( 'ya-repeater-row-added' );
		return false;
	});
	$('.ya-repeater-table').on('click', '.ya-repeater-remove-row', function(event) {
		var $row = $(this).closest('tr');
			$row.remove();
		return false;
	});

	// Unit Conversion
	$( document.body ).on( 'ya-init-unit-conversion', function() {
		$('select.unit-select').each(function(index, el) {
			var unit    = $(this).val();
			$(this).data('prevunit', unit );
		});
		$('.options_group').on('change', 'select.unit-select', function(event) {
			var $unit_v = $(this).closest('.form-field-unit').find('.unit-value');
			var unit    = $(this).val();
			var value   = $unit_v.val();
			if( value != ''){
				var prevunit = $(this).data('prevunit');
				var new_v = ya_convert_measurement(value, prevunit, unit);
				if( new_v ){
					$unit_v.val(new_v);
				}
			}
			$(this).data('prevunit', unit);
		});
	}).trigger( 'ya-init-unit-conversion' );

	// Tabbed Panels
	$( document.body ).on( 'ya-init-tabbed-panels', function() {
		$( 'ul.ya-tabs' ).show();
		$( 'ul.ya-tabs a' ).click( function( e ) {
			e.preventDefault();
			var panel_wrap = $( this ).closest( 'div.panel-wrap' );
			$( 'ul.ya-tabs li', panel_wrap ).removeClass( 'active' );
			$( this ).parent().addClass( 'active' );
			$( 'div.panel', panel_wrap ).hide();
			$( $( this ).attr( 'href' ) ).show();
		});
		$( 'div.panel-wrap' ).each( function() {
			$( this ).find( 'ul.ya-tabs li' ).eq( 0 ).find( 'a' ).click();
		});
	}).trigger( 'ya-init-tabbed-panels' );

	

});
