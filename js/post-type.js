jQuery(document).ready(function($) {

	// Accordion sections
	$(document).on('click', '#event-details h4:not(.open)', function() {
		$(this).parent().children('h4.open').removeClass('open').children('.dashicons').removeClass('dashicons-arrow-down').addClass('dashicons-arrow-up');
		$(this).parent().children('.section:visible').stop().slideToggle();
		$(this).addClass('open').children('.dashicons').removeClass('dashicons-arrow-up').addClass('dashicons-arrow-down').parent().next().stop().slideToggle();
	});

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

});