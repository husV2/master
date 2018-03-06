<div class="weekly_activity">
<h4>{{trans('main.weekly_activity') }} <span id="info">&#9432;</span></h4>
<canvas id="weekly_activity" height="300"></canvas>


<!-- Build the canvas -->
<script>
"use strict";
    
$(function(){
   
    weekly_Bar();
    
});

var formatDecimal = function(number)
{
    return parseFloat(Math.round(number * 100) / 100).toFixed(2);
};

function weekly_Bar()
{
    var weekly_stats = <?= $weekly_stats ?>;
    var numbers = [];
    var labels = [];
    var colors = [];
    
    $.each(weekly_stats, function(i, cat) {
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
    
    var ctx = $("#weekly_activity").get(0).getContext("2d");
    
    var weekly_bar = new Chart(ctx,{
    type: 'bar',
    data: data,
    height:300,
    maintainAspectRatio: false,
    options: 
        {
            tooltips: {
                mode: 'label',
                callbacks: {
                    label: function(tooltipItem, data) {
                        return '<?= $text_completed ?> '+ formatDecimal(data['datasets'][0]['data'][tooltipItem['index']]) + '% <?= $text_of_all ?>';
                    }
                }
            },
            scales: 
            {
                xAxes: [{

                            gridLines: {
                                display:false,
                            },
                            categoryPercentage: 0.9
                        }],
                yAxes: [{
                            display: true,
                            ticks: {
                                beginAtZero: true,
                                max: 100
                            }
                        }]
            }
        }
        
    });
    
    
}
</script>
</div>