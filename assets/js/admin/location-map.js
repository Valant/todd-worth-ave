jQuery( function( $ ) {
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
