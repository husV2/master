<div class="personal_accomplishments">
<h4><?= $title ?></h4>
<canvas id="acc"></canvas>
<div id="legend"></div>


<!-- Build the canvas -->
<script>
"use strict";

function accPie()
{
    
    var stats = <?= $personal_stats ?>;
//    var mainColor = "#269b9e";
//    var lightness = 0;
    var colors = [];
    var numbers = [];
    var labels = [];
    
    $.each(stats, function(i, cat) {
        labels.push(i);
        numbers.push(cat["score"]);
        colors.push(cat["color"]);
//        mainColor += 50;
//        colors.push(ColorLuminance(mainColor, lightness));
//        lightness -= 0.15;
    });
    var data = {
    labels: labels,
    datasets: [
        {
            data: numbers,
            backgroundColor: colors,
            hoverBackgroundColor: colors
        }]
    };
    
    var ctx = $("#acc").get(0).getContext("2d");
    
    var pie = new Chart(ctx,{
    type: 'pie',
    data: data,
    options:{
        legend: {
                display: false
            }
    }
    });
    return pie;
    
}
$(function(){
    
    var pie = accPie();
    document.getElementById('legend').innerHTML = pie.generateLegend();
    
});
</script>
<style>
#legend li span{
    display: inline-block;
    width: 16px;
    height: 16px;
    margin-right: 5px;
}
#legend ul
{
    list-style: none;
}
</style>
</div>
