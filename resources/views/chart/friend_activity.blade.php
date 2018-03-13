<div class="friend_activity">
<h4>{{trans('main.friend_activity') }} <span class="info_sign" aria-hidden="true" data-toggle="tooltip" data-placement="right" title="{{trans('main.friendActivity_help')}}">&#x24d8;</span></h4>
<canvas id="friend_activity"></canvas>


<!-- Build the canvas -->
<script>
"use strict";
    
$(function(){
   
    friend_Bar();
    
});

function friend_Bar()
{
    var friend_stats = <?= $friend_stats ?>;
    var numbers = [];
    var labels = [];
    var colors = [];
    
    $.each(friend_stats, function(i, cat) {
        labels.push(i);
        numbers.push(cat);
        colors.push('#a1a2a5');
    });
    
    var data = {
    labels: labels,
    datasets: [
        {
            data: numbers,
            label: "<?=$title?>",
            backgroundColor: colors,
            borderColor: colors,
            borderWidth: 1
        }]
    };
    
    var ctx = $("#friend_activity").get(0).getContext("2d");
    
    var friend_bar = new Chart(ctx,{
    type: 'horizontalBar',
    data: data,
    options: 
        {
            scales: 
            {
                xAxes: [{
                            display: false,
                            gridLines: {
                                display:false
                            }
                        }],
                yAxes: [{
                            gridLines: {
                                display:false
                            }   
                        }]
            }
        }
        
    });
    
    
}
</script>
</div>
