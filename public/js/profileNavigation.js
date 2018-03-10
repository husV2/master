$(function(){
    
    $('.setting_box #workhours').text($('.setting_box #workhours').text()+" h");
    $('.setting_box #event_interval').text($('.setting_box #event_interval').text()+" min");

    $('#nav-tabs a').click(function (e) {
        e.preventDefault();
        var previous = $('.tab-pane.active');
        $(this).tab('show');
        $(this).removeClass("new-messages");
        var title = $('#tab-title');
        $(title).text($(this).find("img").attr("title"));
        if(previous.attr("id") === "password"){ PasswordChanger.resetTab(); }
        $('html, body').animate({
            scrollTop: $(title).offset().top
        }, 500);
      });
      
    $('#profile .settings-change, #profile .password-change').click(function(){   
        $(this).tab('show');
        $("#nav-tabs .active").removeClass("active");
    });
    
});