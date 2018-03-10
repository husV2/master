function Achievement()
{
    this.pop = function(){
        setTimeout(function()
        {
            $('#achievement-container').slideDown("slow", function(){
                setTimeout(function()
                { 
                    $('#achievement-container').slideUp("slow", function(){}); 
                }, 7000);
            });
        }, 500);
    };
    
    this.setData = function(data){
        if(data.hasOwnProperty(0))
        {
            data = data[0];
        }
        var title = data["name"];
        var description = data["description"];
        console.log("achievement controller called!");
        
        $('#achievement-popup .achievement-title').text(title);
        $('#achievement-popup .achievement-description').text(description);
        
        this.pop();
        
    };
}

