"use strict";


var placeholderText;

$(function(){
    
    var selects = $('.organization_select');
    placeholderText = $('.initial').get(0);
    
    $(selects).change(function(e){
        
        var element = e.target;
        
        $(element).find('.initial').remove();
        var levels = $(element).find('option:selected').val().split("_");
        
        if(levels[0] < 4)
        {
            getNextLevel(levels[1], parseInt(levels[0])+1);
        }
    });
    
    
//    $(".btn_register").click(function(e){
//        e.preventDefault();
//        console.log("hovering");
//        var failed = false;
//        
//        $.each($('.reg2 select').not('.hidden'), function(i, item) {
//            if($(item).find('.initial').length)
//            {
//                failed = $(item).find('.initial').length && $("button[data-page='2']").length;
//                return;
//            }
//        });
//        
//        console.log(failed);
//        
//        if(!failed)
//        {
//            this.click();
//        }
//        else
//        {
//            alert("Valitse organisaatio");
//        }
//    });
    
});

function getNextLevel(parentOrganization, nextLevel)
{
    $.ajax({
        method: "POST",
        url: "get/subdivisions",
        dataType: "json",
        data: {"id": parentOrganization, "level": nextLevel}
        })
        .done(function( msg ) {

            if(msg.hasOwnProperty('error'))
            {
                console.log(msg["error"]);
                return;
            }
            if(msg === 0)
            {
                return;
            }
            makeOptions(nextLevel, msg);
    });
}

function makeOptions(level, arr)
{
    var options = [];
    var index = 1;
    level = level-1;
    var select = $( ".organization_select:eq( "+level+" )" );
    $( ".organization_select:gt( "+level+" )" ).addClass('hidden');
    $(select).empty();
    
    options[0] = placeholderText;
    
    $.each(arr, function(i, division) {
        options[index] = $('<option value='+i+'>'+division+'</option>');
        index++;
    });
    select.append(options);
    select.removeClass("hidden");
    
}



