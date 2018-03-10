$( document ).ready(function() {
	var $modal = $('#survey-modal');
	var $vitalityDescription = $('#vitalityDescription');
	var $saveSurvey = $('#saveSurvey');
	var $vitalityButtons = $('.vitalities div');
	var $selectedButton;
	var $survey = $('p.survey');
	
	function getValue() {
		return $selectedButton.attr('data-value');
	}
	
	var vitalityDescriptions = [
		"Valintasi tarkoittaa, että olet jatkuvasti väsynyt ja sinun on vaikea suorittaa tehtäviä jotka vaativat keskittymistä. Väsymystä voi aiheuttaa lisäksi huono kunto, huono unen laatu, epäsäännöllinen syöminen, liiallinen tai liian vähäinen työ, sairaudet tai lääkkeet.",
		"Valintasi tarkoittaa, että koet usein väsymystä ja sinulla on vaikeuksia suorittaa keskittymistä vaativia tehtäviä. Väsymystä voi aiheuttaa lisäksi huono kunto, huono unenlaatu, epäsäännällinen syöminen, liiallinen tai liian vähäinen työ, sairaudet tai lääkkeet",
		"Valintasi tarkoittaa, että koet väsymystä vain toisinaan, mutta kuitenkin säännöllisesti. Näinä hetkinä sinun on vaikea suorittaa keskittymistä vaativia tehtäviä.",
		"Valintasi tarkoittaa, että olet erittäin virkeä, etkä koe väsymystä työssä käytännössä koskaan.",
		"Valintasi tarkoittaa, että olet virkeä ja energinen lähes aina ja jaksat keskittyä tehtäviisi hyvin."
	];
	
	$survey.click(function(){
		if ($survey.hasClass('sent')) return;
		$modal.modal();
	});
	
	$saveSurvey.click(function() {
		if (!$selectedButton) return;
		var value = getValue();
		$.ajax({
			method: 'POST',
			url: submitSurveyUrl,
			dataType: 'json',
			data: {'vitalityValue': value },
			success: function(data) {
				alert(data['success']);
				$modal.modal('hide');
				$survey.addClass('sent');
				$survey.click(function(){});
			},
			error: function (xhr, ajaxOptions, thrownError) {
				alert(xhr.status);
				alert(thrownError);
			}
		});
	});
	
	$vitalityButtons.click(function() {
		if ($selectedButton) $selectedButton.removeClass('active');
		$selectedButton = $(this);
		$selectedButton.addClass('active');
		$vitalityDescription.html(vitalityDescriptions[getValue()-1]);
	})
});