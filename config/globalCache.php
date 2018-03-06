<?php

/*******************************
 * 
 * 
 *  All the global cache values. If the value has an underscore behind it, a value needs to be added.
 *  For example: departmentRanking_level_1
 *  
 * 
 *****************************/
    

return [
        //"departmentRanking" => "departmentRanking_level_",
         /* Entried under this comment use format: completed_{level}_{group id} */
         /* The values are stored in array with a timestamp: array("value" => $val, "timestamp" => $timestamp) */
        "completed" => "completed_",
        "skipped" => "skipped_",
        "usedTime" => "usedTime_",
];