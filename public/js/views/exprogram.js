$( function() {
	function formDataToObject(arr) {
		var o = {};
		
		for (var i in arr) {
			var d = arr[i];
			o[d['name']] = d['value'];
		}
		return o;
	}
	
	function removeOnDoubleClick() {
		$(this).remove();
	}
	
	function cloneHelper(event) {
		var $clone = $(this).clone();
		$clone.dblclick(removeOnDoubleClick);
		return $clone;
	}
	
    $( ".sortable" ).sortable({
		revert: true
    });
	
    $( "#ex-list > .draggable" ).draggable({
		connectToSortable: ".sortable",
		helper: cloneHelper,
		revert: "invalid"
    });
	
	$( ".sortable > .draggable" ).draggable({
		connectToSortable: ".sortable",
		revert: "invalid"
    }).dblclick(removeOnDoubleClick);

	
    $( "ul, li" ).disableSelection();
	
	$('#ex-program-form').submit(function(event) {
		var formData = formDataToObject($(this).serializeArray());
		var exData = [];
		
		$('ul.day-ex-list').each(function() {
			$this = $(this);
			var data = [];
			$this.find('li').each(function() {
				data.push($(this).data('id'));
			});
			exData.push(data);
		});
		
		formData['exprogram'] = exData;
		var a = formData['isActive'];
		formData['isActive'] =  (a && a == 'on' ? 1 : 0);
		
		console.log(formData);
		$('<input />').attr('type', 'hidden')
					.attr('name', 'exprogram')
					.attr('value', JSON.stringify(exData))
					.appendTo('#ex-program-form');
		//event.preventDefault();
	});
	
	$('#ex-filter').submit(function(event) {
		event.preventDefault();
		var formData = formDataToObject($(this).serializeArray());
		
		$('#ex-list > li').each(function() {
			var $li = $(this);
			$li.show();
			for (var key in formData) {
				var value = formData[key];
				if (!value) {
					continue;
				}
				var liData = $li.data(key);
				if (liData) {
					var isNumber = $.isNumeric( liData );
					if 	((isNumber && liData != value ) ||
						(!isNumber && liData.toLowerCase().search(value.toLowerCase()) == -1)) {
						$li.hide();
					}
				}
			}
		});
	});
	
	/*$('li').hover(function(){
		clearTimeout($(this).data('timeout'));
		$(this).css("background-color", "red");
	}, function(){
		var $this = $(this)
		var t = setTimeout(function() {
			$this.css("background-color", "pink");
		}, 500);
		$(this).data('timeout', t);
	}); */
} );