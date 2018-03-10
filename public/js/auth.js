var regpage = 1;
var validator = new Validator();

$(document).ready(function(){
    console.log(window.location.pathname);
        //liitetään salasanatarkistus molempiin kenttiin
        $("#password1, #confirm_password").focusout(checkPassword);
        
        $("#email, #confirm_password").focusout(function() {
            var results = checkEmail();
            if(!results[0]) {
                document.getElementById("checkMessage").innerHTML = results[1];
                $('.btn_register').attr('disabled', true);
            }
            else {
               $('.btn_register').attr('disabled', false);
            }
        });
    
	//$('[data-toggle="tooltip"]').tooltip();
	 
//	var p_pass = $('#password').attr('placeholder'); 
//	var p_user = $('#username').attr('placeholder'); 
	
//	$("#password").prop("type", "text").val(p_pass);
//	$('#username').val(p_user);
//
//	$('#username').focus(function(){
//		if($(this).val() === p_user) {
//			$(this).val('');
//		}
//	});
//	$('#username').blur(function(){
//		if(!$(this).val().length) {
//			$(this).val(p_user);
//		}
//	});
//	
//	$("#password").focus(function() {
//		if($(this).val() === p_pass)
//		{
//			$(this).prop("type", "password").val("");
//		}
//	});
//
//	$("#password").blur(function() {
//		if(!$(this).val().length)
//		{
//			 $(this).prop("type", "text").val(p_pass);
//		}
//	});
	
	
	$('.btn_register').click(function(e){
            
                document.getElementById("checkMessage").innerHTML = "";
                if(!validator.validate())
                {
                    document.getElementById("checkMessage").style.color = "#FF0000";
                    return;
                }
		$('.reg'+regpage).css("display","none");
		regpage++;
		if(regpage > 3) {
                        $('.reg6').css("display","table");
                        $('.btn_register').addClass('hidden');
                        sendFormData();
			console.log("complete");
		}
		else {
			$('.reg'+regpage).css("display","table");
			if(regpage === 4) $('.btn_register').html('Valmis <span id="btn_register_icon" class="glyphicon glyphicon-play-circle" aria-hidden="true"></span>');
		}
                $('.btn_register').attr('data-page', regpage);
	});
        
        $('.btn_back').click(function(e){
            
                f_resetregister();  
	});
        
        
	$('#gridSystemModal').on('hidden.bs.modal', function () {
                f_resetregister();
                console.log("closed");
        });
	
	$('#select-theme > div.colorbox').each(function() {
		var $this = $(this);
		var $themeInput = $('#theme');
		
		$this.click(function() {
			$themeInput.val(this.id);
			console.log($themeInput.val());
		});
	});
        
        $('#select-picture > div.profilebox').each(function() {
                var $this = $(this);
                var $pictureInput = $('#picture');
                
                $this.click(function() {
                        $pictureInput.val(this.id);
                        console.log($pictureInput.val());
                });
        });
});

function f_resetregister() {
        regpage = 1;
        $('.btn_register').removeClass('hidden');
        $('.reg2').css("display","none");
        $('.reg3').css("display","none");
        $('.reg4').css("display","none");
        $('.reg1').css("display","table");
        $('.btn_register').html('Seuraava	<span id="btn_register_icon" class="glyphicon glyphicon-play-circle" aria-hidden="true"></span>');
        $('.register_tab input:required').css('border', 'none');
}


/* tällä funktiolla kerätään rekisteröinti lomakkeiden kenttien data */
function collectData() {
	var collectedFormData = {};
	$('.register_form').each(function(){ 
		var temp = $(this).serializeArray();
		var formData = {};
		for (var i in temp) {
			var t = temp[i];
			formData[t['name']] = t['value'];
                        console.log(temp[i]);
		}
		$.extend( collectedFormData, formData); 
	});
	console.log(collectedFormData);
        console.log(jQuery.isPlainObject(collectedFormData));
        return collectedFormData;
}

// ajax kutsu mikä lähetetään formin lopussa
function sendFormData() {
    var url = window.location.href;
    url =  url.substring(0,url.lastIndexOf('/') +1);
    var formData = collectData();
    $.ajax( {
        type: 'post',
        url : url+'register',
        data: formData,
        success: function(data) {
            location.href = "";
        },
        error: function(data){
        var errors = data.responseJSON;
        $.each(errors, function(key, value) {
            $('.i_reg[name="'+key+'"]').parents('.register_tab').find('.text_block').html('<p class="text-normal" style="color:#d70a78">'+value+'</p>');
        });
        console.log(errors);
        $('.reg4').css("display","table");
        $('.reg6').css("display", "none");
      }
    });
}

//tarkistaa formin salasanat
function checkPassword() {
    var pass1 = $("#password1").val();
    var pass2 = $("#confirm_password").val();

    if (pass1 === pass2) {
        // Salasanat täsmää
        document.getElementById("checkMessage").innerHTML = "Salasanat OK";
        document.getElementById("checkMessage").style.color = "#58FA82";    // vihreä
        $('.btn_register').attr('disabled', false);
    } 
    else if(pass1.length < 6) {
        document.getElementById("checkMessage").innerHTML = "Salasana on liian lyhyt";
        document.getElementById("checkMessage").style.color = "#FF0000";    // punainen
        $('.btn_register').attr('disabled', false);
    }
    else {
        // Salasanat ei täsmää
        document.getElementById("checkMessage").innerHTML = "Salasanat eivät täsmää";
        document.getElementById("checkMessage").style.color = "#FF0000";    // punainen
        $('.btn_register').attr('disabled', true);
    }
}

function Validator() {
    //tsekkaa kentät
    this.validate = function() {
        if(this.checkRequired() && this.checkEmail() && this.checkOrganization()) {
            return true;
        }
        if(!this.checkRequired()) {
            //kentistä ei saa yksikään olla tyhjä
            console.log(document.getElementById("checkMessage"));
            document.getElementById("checkMessage").innerHTML += "Jotkin vaaditut kentät ovat tyhjiä</br>";
        }
        return false;
    };
    
    this.checkEmail = function() {
        //checkkaa että salasana on tarpeeksi pitkä
        if(!$('.reg'+regpage).has('#email').length) {
            return true;
        }
        var email = $("#email").val();
        //katsoo että emailissa on @-merkki
        if(!email.includes("@")) {
            console.log("Vääränmuotoinen email");
            document.getElementById("checkMessage").innerHTML += "Vääränmuotoinen email</br>";
            return false;
        }
        var lastPart = $("#email").val().split('@')[1];
        //tässä tsekkaa että loppu osa on hus.fi
       if(lastPart !== "hus.fi") {
           console.log("Väärä email osoite!");
          document.getElementById("checkMessage").innerHTML += "Vain HUS -emailit ovat sallittuja</br>";
          return false;
       }
       return true;
    };
    
    this.checkRequired = function() {
        var goodToGo = true;
        $('.reg'+regpage+' input:required').each(function(i, item) {

            if(!$(item).val()) {
                $(item).css('border', '1px solid red');
                goodToGo = false;
            }

        });
        
        return goodToGo;
    };
    this.checkOrganization = function() {
        var goodToGo = true;
        if(!$('.reg'+regpage).has('.organization_select').length) {
            return goodToGo;
        }
        //tsekki että kaikki organisaatio valinnat tehdään
        if($('.organization_select[name="group1"] :selected').attr('class') === 'initial') {
            document.getElementById("checkMessage").innerHTML += "Sinun täytyy valita ensimmäinen organisaatio</br>";
            goodToGo = false;
        }
        if($('.organization_select[name="group2"] :selected').attr('class') === 'initial') {
            document.getElementById("checkMessage").innerHTML += "Sinun täytyy valita toinen organisaatio</br>";
            goodToGo = false;
        }
        
        for(var i = 3; i<6; i++) {
            if(!$('.organization_select[name="group'+i+'"]').hasClass('hidden') && $('.organization_select[name="group'+i+'"] :selected').attr('class') === 'initial') {
                document.getElementById("checkMessage").innerHTML += "Organisaation valinta puuttuu</br>";
                goodToGo = false;
            }
        }
        return goodToGo;
    };
}
