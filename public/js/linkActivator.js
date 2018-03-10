$(function(){
    
    var url = window.location.href.substr(window.location.href.lastIndexOf('/') + 1);
    if(url === "")
    {
        url = "treeni";
    }
    $('#nav_'+url).addClass('nav_active');
    
    $('[data-toggle="tooltip"]').tooltip(); 
});


