jQuery(document).ready(function($) {

	$('.mini-clndr').clndr({
		daysOfTheWeek: ['S', 'M', 'T', 'W', 'T', 'F', 'S'],
		numberOfRows: 5,
		days: [
		  {
		    day: '1',
		    classes: 'day today event',
		    id: 'calendar-day-2014-06-30',
		    events: [ ],
		    date: moment('2014-06-30')
		  }
		],
		month: 'June',
		year: '2014',
		eventsThisMonth: [ ],
		extras: { },
		template: settings.clndr.template
	});

});

function process_events(events) {

	var days = [];

	$.each(events, function() {

	});

	return days;
}