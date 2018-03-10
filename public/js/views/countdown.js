$( document ).ready(function() {
	
	Notification.requestPermission();
	
	/* timer elements */
	var $clock = $('#cd-clock, .cd-clock'); // clock element
	var $cdText = $('#cd-text'); // text above clock
	var textContents = ["Seuraavaan treeniin:", "Treenin aika!"];
	
	/* modal elements */
	var $exContent = $('#ex-content'); // exercise html
	var $exName = $('#ex-name'); // exercise name
	
	/* button elements */
	var $cdSnooze = $('#cd-snooze, .cd-snooze');
	var $cdAccept = $('#cd-accept, .cd-accept');
	var $cdDecline = $('#cd-decline');
	var $exCancel = $('#ex-cancel');
	var $exCompleted = $('#ex-completed');
	var $exCompletedCd = $('#ex-completed-cd');
	
	var exData; // exercise data from get/next ajax call
	
	/* Starts the count down */
	function cdStart(dateInput=null) {
		
		function newCountdown(_dateInput) {
			$cdText.html(textContents[0]);
			$cdSnooze.hide();
			$cdAccept.hide();
			$clock.show();
			$clock.countdown(new Date(_dateInput), function(event) {
				$(this).html(event.strftime('%H:%M:%S'));
			});
		}

		/* when snooze button is clicked */
		if (dateInput) {
			newCountdown(dateInput);
			return;
		}
		
		/* when new exercise is timed */
		$.ajax({
			method: 'GET',
			url: nextUrl,
			dataType: 'json',
			success: function(data) {
				exData = data;
				if (!data['start_date']){ return; }
                                if(data['refetch'])
                                {
                                    $('#calendar').fullCalendar( 'refetchEvents' );
                                }
				newCountdown(data['start_date']);
			},
			error: function (xhr, ajaxOptions, thrownError) {
				console.log(xhr.status);
				console.log(thrownError);
			}
		});
	}
	
	/* Called when exercise is canceled or declined */
	function onExCancel() {
		
		$.ajax({
			method: 'POST',
			url: skipUrl,
			data: {'id': exData['id']},
			success: function(data) {
				cdStart();
			}
		});
		$('#modtest').modal('hide');
	}
	
	$cdAccept.click(function() {
		/* code here when accepted */
		$exContent.html(exData['html']);
		$exName.html(exData['name']);
		$('#modtest').modal({backdrop: 'static', keyboard: false});
		console.log(exData);
		$exCompleted.attr("disabled", true);
		var d = new Date();
		d.setSeconds(d.getSeconds() + exData['duration']);
		$exCompletedCd.countdown(d);
		$exCompletedCd.on('update.countdown', function(event) {
			$(this).html(event.strftime('%H:%M:%S'));
		});
		$exCompletedCd.on('finish.countdown', function() { 
			$(this).html('00:00:00');
			$exCompleted.attr("disabled", false);
			
			if ($('#ex-completed-notification').is(':checked')) {
				var sound = new Audio(exCompletedSoundUrl);
				sound.play();
			}
			
		});
	});
	
	$cdDecline.click(onExCancel);
	$exCancel.click(onExCancel);
	
	$cdSnooze.click(function() {
		/* start the timer with snooze time*/
		
		$.ajax({
			method: 'POST',
			url: snoozeUrl,
			data: {'id': exData['id']},
			success: function(data) {
				cdStart(new Date(data));
			}
		});
	});
	
	$exCompleted.click(function() {
		$.ajax({
			method: 'POST',
			url: completeUrl,
			data: {'id': exData['id']},
			success: function(data) {
                            cdStart();
                            var jsonData = JSON.parse(data);
                            if(jsonData.hasOwnProperty('achievement'))
                            {
                                achievementController.setData(jsonData['achievement']);
                            }
			}
		});
		$('#modtest').modal('hide');
	});
	
	function onCountdownFinished(event) {
		if (Notification.permission === "granted") {
			var notification = new Notification('Ilmoitus MiniTreeniltä', {
			  icon: $("link[rel^='notification icon']").attr('href'),
			  body: "Hei! Treenisi odottaa sinua MiniTreenissä! Klikkaa ilmoitusta jatkaaksesi",
			  requireInteraction: true
			  //sound: soundUrl // not yet supported
			});
			
			var sound = new Audio(soundUrl);
			sound.play();

			notification.onclick = function () {
				parent.focus();
				window.focus();
				this.close();
			};
		}
		
		$cdText.html(textContents[1]);
		$clock.hide();
		$cdAccept.show();
		$cdSnooze.show();
	}
	
	skipCountdown = onCountdownFinished;
	cdStart();
	$clock.on('finish.countdown', onCountdownFinished);
});