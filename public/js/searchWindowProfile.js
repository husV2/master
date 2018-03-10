$(function()
{
    $('.find_friends').keyup(function(){
        
//        if(shouldBeOpen())
//        {
            //$('.grid').addClass('open');
            sort();
//        }
//        else
//        {
//            $('.grid').removeClass('open');
//        }
        
    });
//    $('.find_friends').focus(function(){
//        
//        if(shouldBeOpen())
//        {
//            $('.grid').addClass('open');
//        }
//        else
//        {
//            $('.grid').removeClass('open');
//        }
//        
//    });
//    $(document).mouseup(function(){
//        if(!shouldBeOpen())
//        {
//            $('.grid').removeClass('open'); 
//        }
//    });
    
    $('.friendReq_btn').click(function(){
        
        $.ajax({
            method: "POST",
            url: "send/friendRequest",
            dataType: "json",
            data: {"friend_id": $(this).attr("id")}
            })
            .done(function( msg ) {
                if(msg.hasOwnProperty('id'))
                {
                    $("#friendModal").modal('show');
                    
                    setTimeout(function(){ $("#friendModal").modal('hide');}, 1500);
                    var item = $('.friendReq_btn[id="'+msg["id"]+'"]').closest('.grid-item');
                    $(item).fadeOut(function(){ $(item).remove(); });
                }
                console.log(msg);
                if(msg.hasOwnProperty('achievement'))
                {
                    console.log(msg["achievement"]);
                    achievementController.setData(msg["achievement"]);
                }
        });
        
    });
});
function shouldBeOpen()
{
    var input = $('.find_friends');
    var inputNotEmpty = input.length ? input.val().length > 0 : false;
    var inputFocused = input.is(":focus");
    
    return inputNotEmpty && inputFocused;
}

function sort()
{
    $('.grid #result_message').text("");
    var val = $('.find_friends').val().toLowerCase();
    
    var all = $('.grid .grid-item');
    var method = '^';
    var fields = ["name", "f", "l", "email"];
    var element;
    if(!val.length)
    {
        showAll(all);
        return;
    }
    hideAll(all);
    for(var i = 0; i< fields.length; i++)
    {
        element = $('.grid .grid-item[data-'+fields[i]+method+'="'+val+'"]');
        if(element.length)
        {
            element.removeClass("hidden");
            break;
        }
    }
    
    if(!element.length)
    {
        hideAll(all);
        $('.grid #result_message').text("Hakuasi vastaavaa tulosta ei löytynyt");
    }
    
    
}
function showAll(hidden)
{
    hidden.each(function(i, item){
           $(item).removeClass("hidden");
       });
}
function hideAll(hidden)
{
    hidden.each(function(i, item){
           $(item).addClass("hidden");
       });
}

