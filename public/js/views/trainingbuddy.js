$(document).ready(function() {

	var indexInfo = 0;
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
	
	updateInfoText();
});


