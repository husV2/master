var limit = 255;
var interval = 10000;
var updater;
var tab;
var tab_img;
$(function(){
        
    tab = $('a[href="#messages"]');
    tab_img = $('a[href="#messages"]').find("img");
    updater = new UpdateController();
    setTimeout(function(){updater.start(interval);}, interval);
    
    $('.messages .list-group-item .remove-message-icon').click(function(){
        updater.removeMessage($(this).closest('.list-group-item').attr('data-id'));
    });
    $('.writing-area textarea').keyup(function(event){
        this.value = this.value.slice(0, limit);
    });
    $('.writing-area textarea').keydown(function(event){
        if(event.key === 'Backspace' || this.value.length <= limit)
        {
            $('.writing-area span').html(limit - this.value.length);
        }
    });

    $('.msg-board-btn').click(function(event){

        var message = $('#chat-msg').val();

        if(message.length < 1)
        {
            $('#chat-msg').attr('placeholder', warning_message);
        }
        else
        {
            updater.sendMessage( message );
        }
    });

});
function addNewMessage(data)
{
    var rmbutton = 'rm-'+data["id"];
    var isOwner = parseInt(data["isOwner"]) === 1;
    //var encoded = data["message"].replace(/[\u00A0-\u9999<>\&]/gim, function(i) { return '&#'+i.charCodeAt(0)+';'; });
    var newMessage = '<div class="list-group-item clearfix new" data-id="'+data["id"]+'" style="display:none">';
    newMessage += '<div class="message-board-avatar">';
    newMessage += '<a href="'+data["url"]+'">';
    newMessage += '<img title="'+data["username"]+'" src="'+data["avatar"]+'" class="img-responsive" /></a></div>';
    newMessage += isOwner ? '<span class="remove-message-icon '+rmbutton+'">╳</span>' : '';
    newMessage += '<small class="list-group-item-heading">'+data["created_at"]+'</small>';
    newMessage += '<p class="list-group-item-text">'+data["message"]+'</p></div>';

    if($('.messages .list-group-item').length > 0)
    {
        var lastID = parseInt(data["id"]) - 1;
        var lastElement = $('.messages .list-group-item[data-id="'+lastID+'"');
        while(!$(lastElement).length)
        {
            lastID--;
            lastElement = $('.messages .list-group-item[data-id="'+lastID+'"');
        }

        $(lastElement).after( newMessage );
    }
    else
    {
        $('.messages').append( newMessage );
    }

    animateTab();
    $('.messages .list-group-item[data-id="'+data["id"]+'"').slideDown( "slow", function() {
        if(isOwner)
        {
            $('.'+rmbutton).click(function(){
                updater.removeMessage($(this).closest('.list-group-item').attr('data-id'));
            });
            $('.'+rmbutton).removeClass(rmbutton);
        }
    });
}
function removeOldMessages()
{
    var messages = $('.messages').find('.list-group-item');
    var first = $(messages).first();

    if($(messages).length > 5)
    {
        $(first).slideUp( "slow", function() {
            $(first).remove();
            removeOldMessages();
        });
    }
}
function clearWritingArea()
{
    $('#chat-msg').val("");
    $('.writing-area span').html(limit);
}
function animateTab()
{
    if(tab_img.length)
    {
        $(tab_img).addClass("animated-tab");
        $('li:not(.active) a[href="#messages"]').addClass("new-messages");
        setTimeout(function(){$(tab_img).removeClass("animated-tab");}, 1000);
    }
}

//// Object that controllers the chat updates ////
function UpdateController()
{
    this.timer;
    this.start = function(interval)
    {
        this.timer = setInterval(this.checkMissedMessages, interval);
    };
    this.stop = function()
    {
        clearInterval(this.timer);
    }.bind(this);

    this.updateChat = function(ids)
    {
        console.log("updateChat called");
        $.ajax({             
            method: "GET",
            url: routes["update_chat"],
            data: { "owner": user, "ids": ids },
            dataType: 'json',
            context: this
        })
        .done(function( data ) {        
            $.each(data["content"], function(key, value){
                addNewMessage(value);
                console.log(data.content);
            });
            removeOldMessages();
            animateTab();
        })
        .fail(function(){
            console.log("Update failed");
        });
    }.bind(this);

    this.checkMissedMessages = function()
    {
        var ids = [];
        $('.messages .list-group-item').each(function(key, item){
            ids.push($(item).attr("data-id"));
        });
        $.ajax({             
            method: "GET",
            url: routes["check_missed"],
            data: { "owner": user, "ids": ids },
            datatype: 'json',
            context: this
        })
        .done(function( data ) {
            var data1 = JSON.parse(data); /* <--- WTF */
            console.log("Update check done");
            if(parseInt(data1.status) === 0)
            {
                console.log("Invalid owner id given for ajax call: getLastMessageID()");
                return;
            }
            if(data1.ids.length > 0)
            {
                this.updateChat(data1.ids);
            }

        });
    }.bind(this);
    this.sendMessage = function(message)
    {
        $.ajax({
                method: "POST",
                url: routes["send_message"],
                data: { user_wall: user, message: message },
                dataType: 'json',
                timeout: 60000
            })
            .done(function( data ) {
                updater.currentLastID = parseInt(data["id"]);
                if(!data.hasOwnProperty("error"))
                {
                    addNewMessage(data);
                    removeOldMessages();
                    clearWritingArea();
                }
            })
            .fail(function(e){
                console.log("Ajax call failed.");
                if(e.textStatus === 'timeout')
                {     
                    console.log('Failed from timeout'); 
                }
            });
    };
    this.removeMessage = function(id)
    {
        if(isNaN(id)){ return; }
        
        var message = $('.messages .list-group-item[data-id="'+id+'"]');
        $(message).slideUp( "slow", function() {});
        
        $.ajax({
            method: "DELETE",
            url: routes["remove_message"],
            data: { id: id },
            dataType: 'json',
            timeout: 60000
        })
        .done(function(data) {
            console.log(data);
            setTimeout(function(){$(message).remove();}, 10000);
        })
        .fail(function(e){
            console.log(e);
            $(message).html("Error");
            $(message).slideDown( "slow", function() {});
        });
    };
   

}