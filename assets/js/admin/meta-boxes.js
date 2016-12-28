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
	$('#add_video_row').click(function(event) {
		var tpl   = $('#tmpl-video-row').html();
		var index = 0;
		$('#vessel_video_list tr').each(function(y, el) {
			var i = parseInt($(el).data('index'));
			if( i > index ) index = i;
		});
		index++;
		var search = '__index__';
		tpl = tpl.replace(new RegExp(search, 'g'), index);
		$('#vessel_video_list').append(tpl);
		return false;
	});
	$('#vessel_video_list').on('click', '.remove_video_row', function(event) {
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

	// Location map
	var input = $('input#LocationLastKnownPoint');

	$('<div class="clearfix">').insertAfter(input);

	var container = input.parent();
	var locationCoords = null;
	var map;
	var geocoder;
	var mapContainer = $('<div id="last-location-map">').css({width: '430px','height': '430px','margin-top': '30px'}).appendTo(container).hide();


	var apiLoaded = false;
	var wT = null;
	var marker = null;
	function showLastLocationMap() {

		if (wT) {
			clearTimeout(wT);
			wT = null;
		}
		if (!input.is(':visible')) {
			wT = setTimeout(showLastLocationMap, 1000);
			return;
		}
		if (!apiLoaded) {
			wT = setTimeout(showLastLocationMap, 300);
			return;
		}

		var coords_str = input.val();
		var coords = coords_str.match(/([\-\d\.]+)[\,\s]+([\-\d\.]+)/);

		if (coords.length>1 && coords_str !== locationCoords) {

			locationCoords = coords_str;

			mapContainer.show();

			var latlng = {
				lat: parseFloat(coords[1]),
				lng: parseFloat(coords[2])
			};

			if (!map) {
				map = new google.maps.Map(document.getElementById('last-location-map'), {
					center: latlng,
					zoom: 8
				});
			} else {
				map.setCenter(latlng);
			}
			if (!marker) {
				marker = new google.maps.Marker({
					position: latlng,
					map: map,
					title: '',
					draggable: true,
				});
			} else {
				marker.setPosition(latlng);
			}

		}

	}
	initLastLocationMap = function()
	{
		apiLoaded = true;
		geocoder = new google.maps.Geocoder();
	}
	$(document).ready(function(){
		if (googleOptions.mapsKey) {
			$('<script>').prop('defer', true).prop('async',true).attr('src', 'https://maps.googleapis.com/maps/api/js?key=' + googleOptions.mapsKey + '&callback=initLastLocationMap').prependTo('body');
		}
		if (input.length) {


			if (input.val()) {
				showLastLocationMap();
			}
			input.change(function(){
				showLastLocationMap();
			});
			var t = null;
			input.keydown(function(){
				if (t) {
					clearTimeout(t);
				}
				t = setTimeout(showLastLocationMap, 2000);
			});


			// make it placeholder
			input.val('26.082055, -80.126648');
			showLastLocationMap();
			$('input#LocationLastKnownDate').val('AIS Date May 10, 2016');

		}


	});

});
