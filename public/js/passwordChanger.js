var PasswordChanger =
{
    old_pw_confirming: false,
    confirmation: {
    "old_pw_confirmed": false,
    "new_pw_confirmed": false,
    "new_pw_compared": false
    },
    error_messages:
    {
        "new_password": "Salasanan täytyy olla vähintään 6 merkkiä pitkä",
        "confirm_password": "Salasanat eivät vastaa toisiaan"
    },
    
    confirmOldPassword: function()
    {
        var data = $(".tab-content #password input[name='old_password']").val().trim();
        $.ajax({             
            method: "POST",
            url: routes["confirm_old"],
            data: { "old": data },
            dataType: 'json',
            context: this
        })
        .done(function( data ) {
            var input = $(".tab-content #password input[name='old_password']");
            $('.tab-content #password .help-block.old-password strong').text(data["success"]);
            $(".tab-content #password input[name='old_password']").attr("disabled", true);
            setTimeout(function(){PasswordChanger.hideField($(input).parent());}, 1000);
            this.confirmation.old_pw_confirmed = true;
        })
        .fail(function(data){
            console.log(data);
            $('.tab-content #password .help-block.old-password strong').text(data.responseJSON["error"]);
            this.confirmation.old_pw_confirmed = false;
        })
        .always(function(){
            this.old_pw_confirming = false;      
        });
    },
    changePassword: function()
    {
        var data = {
            "password": $(".tab-content #password input[name='new_password']").val().trim(),
            "password_confirmation": $(".tab-content #password input[name='confirm_password']").val().trim(),
            "old": $(".tab-content #password input[name='old_password']").val().trim()
        };
        $.ajax({             
            method: "POST",
            url: routes["change_pw"],
            data: { "data": data },
            dataType: 'json',
            context: this
        })
        .done(function( data ) {
            $('.tab-content #password .success-block').text(data["success"]);
            $(".tab-content #password #update-form").slideUp("slow", function(){
            $('.tab-content #password .success-block').slideDown("fast", function(){});
            });
        })
        .fail(function(data){
            console.log(data);
            $('.tab-content #password .help-block.new-password strong').text(data.responseJSON["error"]);
        });
        
    },
    restoreOldPasswordField: function()
    {
        var field = $(".tab-content #password input[name='old_password']");
        $(field).attr("disabled", false);
        $(field).parent().show();
        $('.tab-content #password .help-block.old-password strong').text("");
    },
    resetTab: function()
    {
        $(".tab-content #password #update-form").show();
        $('.tab-content #password .success-block').hide();
        $('.tab-content #password input[type="password"]').val("");
        this.restoreOldPasswordField();
    },
    compareNewPassword: function()
    {
        var field1 = $(".tab-content #password input[name='new_password']").val();
        var field2 = $(".tab-content #password input[name='confirm_password']").val();
        var identical = (field1 === field2);
        this.confirmation.new_pw_compared = identical;
        return identical;
    },
    validateNewPassword: function()
    {
        var ret = $(".tab-content #password input[name='new_password']").val().length >= 6;
        this.confirmation.new_pw_confirmed = ret;
        return ret;
    },
    hideField: function(field)
    {
        $(field).slideUp("slow", function(){});
    },
    showError: function(type, errormsg = null)
    {
        if(type > 2 || type < 0)
        {
            console.log("ERROR showError(): Given type must be a value between 0 and 2");
            return;
        }
        var keys = Object.keys(PasswordChanger.confirmation);
        this.confirmation[keys[type]] = false;
        var types = $.map($('.tab-content #password .help-block'), function(item, index){
            return $(item).attr('class').split(' ')[1];
        });
        $('.tab-content #password .help-block.'+types[type]+' strong').text(errormsg === null ? "Tarkista tämä kenttä" : errormsg);
    }
};
$(function(){
    
    $(".tab-content #password input[name='old_password']").blur(function(){
        if($(this).val().length > 0 && !PasswordChanger.old_pw_confirming)
        {
            PasswordChanger.old_pw_confirming = true;
            PasswordChanger.confirmOldPassword();
        }    
    });
    $(".tab-content #password input[name='new_password'], .tab-content #password input[name='confirm_password']").blur(function(){
        if($(this).val().length > 0)
        {
            var fields = $(".tab-content #password input[name='new_password'], .tab-content #password input[name='confirm_password']");
            for(var i = 0; i < fields.length; i++)
            {
                if($(fields[i]).val().length > 0 && ((i !== 1 && !PasswordChanger.validateNewPassword()) || (i === 1 && !PasswordChanger.compareNewPassword())))
                {
                    PasswordChanger.showError(i+1, PasswordChanger.error_messages[$(fields[i]).attr("name")]);
                }
                else
                {
                    $(fields[i]).prevAll(".help-block").find("strong").text("");
                }
            }
        }
    });
    $(".tab-content #password .pw-change-button").click(function(){
        var keys = Object.keys(PasswordChanger.confirmation);
        var length = keys.length;

        var cc = true;
        for(var i = 0; i < length; i++)
        {
            if(!PasswordChanger.confirmation[keys[i]])
            {
                PasswordChanger.showError(i);
                cc = false;
            }
        }
        if(!cc)
        {
            return;
        }
        PasswordChanger.changePassword();
    });
    
});