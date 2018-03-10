var achievementController;

$(document).ready(function() {
    achievementController = new Achievement();
    var d;
    var today = new Date();
    d = today;
    d.setDate(d.getDate() - 3);

    $('#calendar').fullCalendar({
                    // options and callbacks
                    aspectRatio : 1.35,
                    header: false,
                    displayEventEnd : true,
                    theme: false,
                    editable: false, // Don't allow editing of events
                    handleWindowResize: true,
                    timeZone: 'local',
                    scrollTime: today.getHours()+":"+today.getMinutes()+":00",
                    slotEventOverlap: false,
                    defaultView : "agendaWeek",
					allDaySlot: false,
					columnFormat: 'dddd DD.MM.YYYY',
					nowIndicator: true,
					firstDay: 1,
					
                    events: function(start, end, timezone, callback) {
						$.getJSON( 'get/events', function(data){
							var events = [];
							var duration = '30';
							if(parseInt(data[0]['interval']) < 30)
							{
								duration = data[0]['interval'];
							}
							$.each(data, function(key){
								events.push({
									title: data[key]['name'],
									start: data[key]['start_date'],
									end: moment(data[key]['start_date']).add(duration,'minutes').format('YYYY-MM-DD HH:mm:ss'),
                                                                        color: data[key]['color'],
                                                                        description: data[key]['description'],
                                                                        category: data[key]['category']
								});
							});
							callback(events);

						}).done(function(){ $('.spinner').addClass("hidden"); });
					},
                    eventClick: function(calEvent) {
                        
                        $("#eventModal .modal-title").text(calEvent.title);
                        var date = new Date(calEvent.start);
                        date.setHours(date.getHours() - 2);
                        var content = '<p>'+calEvent.category+'</p><p>'+calEvent.description+'</p>';

                        $("#eventModal .modal-body").html(content);
                        
                        $('#eventModal').modal('show');
                        
                    },
                    timeFormat: 'H(:mm)'
    });

	$('#btn_prev').click(function() {
        $('.spinner').removeClass("hidden");
		$('#calendar').fullCalendar('prev');
	});
	$('#btn_next').click(function() {
        $('.spinner').removeClass("hidden");
		$('#calendar').fullCalendar('next');
	});
	
	/*var indexInfo = 0;
	var indexNot = 0;
	
	var $infoText = $('#infoText');
	var $notificationText = $('#notificationText');
	
	var updatingText = false;
	
	function updateInfoText() {
		if(updatingText) return;
		updatingText = true;
		var maxIndexInfo = infoBubbleTexts.length-1;
		$infoText.html(infoBubbleTexts[indexInfo].message);
		indexInfo++;
		if (indexInfo > maxIndexInfo) indexInfo = 0;
		
		$notificationText.html(adminNotifications[indexNot].message);
		var maxIndexNot = adminNotifications.length-1;
		indexNot++;
		if (indexNot > maxIndexNot) indexNot = 0;
		setTimeout(function(){updatingText=false}, 10000);
	}
	
	$('.speech-bubble').hover(function(){}, function(){
		updateInfoText();
	});
	
	updateInfoText();*/
});

function calendar_modify()
{
    $('#settings_modal').modal('show');
}
function show_profilepic_modal()
{
    $('#profilepic-modal').modal('show');
}

