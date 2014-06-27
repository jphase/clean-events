jQuery(document).ready(function($) {

	// Accordion sections
	$(document).on('click', '#event-details h4:not(.open)', function() {
		$(this).parent().children('h4.open').removeClass('open').children('.dashicons').removeClass('dashicons-arrow-down').addClass('dashicons-arrow-up');
		$(this).parent().children('.section:visible').stop().slideToggle();
		$(this).addClass('open').children('.dashicons').removeClass('dashicons-arrow-up').addClass('dashicons-arrow-down').parent().next().stop().slideToggle();
	});

	// Add date pickers
	$('.datepicker').datetimepicker(settings.date);

	// Add time pickers
	$('.timepicker').datetimepicker(settings.time);

	// Toggle time pickers on all day event
	$('#ce_all_day').on('click', function() {
		if($(this).is(':checked')) {
			$('.timepicker').fadeOut();
			$('.datepicker').animate({width: '189px'}, 1000);
		} else {
			$('.datepicker').animate({width: '104px'}, 1000);
			$('.timepicker').fadeIn();
		}
	});







get_location();

  
    $("#ce_venue_location").autocomplete({
      //This bit uses the geocoder to fetch address values
      source: function(request, response) {
        geocoder.geocode( {'address': request.term }, function(results, status) {
          response($.map(results, function(item) {
            return {
              label:  item.formatted_address,
              value: item.formatted_address,
              latitude: item.geometry.location.lat(),
              longitude: item.geometry.location.lng()
            }
          }));
        })
      },
      //This bit is executed upon selection of an address
      select: function(event, ui) {
        $("#latitude").val(ui.item.latitude);
        $("#longitude").val(ui.item.longitude);
        var location = new google.maps.LatLng(ui.item.latitude, ui.item.longitude);
        marker.setPosition(location);
        map.setCenter(location);
      }
    });
  

});


var geocoder;
var map;
var marker;
    
function initialize(coords) {

	// Map
	var latlng = new google.maps.LatLng(coords.latitude, coords.longitude);
	var options = {
		zoom: 16,
		center: latlng,
		mapTypeId: google.maps.MapTypeId.SATELLITE
	};

	map = new google.maps.Map(document.getElementById('map'), options);

	// Geo coder
	geocoder = new google.maps.Geocoder();

	// Marker
	marker = new google.maps.Marker({
		map: map,
		draggable: true
	});

}

function get_location() {
	navigator.geolocation.getCurrentPosition(callback);
}

function callback(position) {
	initialize(position.coords);
}