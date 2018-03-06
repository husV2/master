<div class="gamification_stats">
    <canvas data-toggle="tooltip" title="{{ trans('main.buddy_health') }}: <?= $stats["HP"] ?>/<?=$stats["maxHP"] ?>" style="background: #eee; max-height: 70px; width: 100%;" id="progression"></canvas>
</div>
<script>
"use strict";
    
$(function(){
   
    progress_Bar();
    
});

function progress_Bar()
{
    var percentage = <?= $stats["HP"] ?>/<?= $stats["maxHP"] ?>;
    var canvas = document.getElementById("progression");
    var ctx = canvas.getContext("2d");

    ctx.fillStyle = "#abc549";
    ctx.fillRect(0,0,canvas.width*percentage,canvas.height);
       
}
</script>

