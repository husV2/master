<?php

namespace App\Helpers;


/**
 * 
 * Contains the functions that handle the creation and manipulation of exercises
 * 
 */

class ExerciseHelper
{
    public static function createContentHtml($text, $video, $audio)
    {
        $youtube = "";
        $audioplayer = "";
        $returned = "";
        $video = preg_replace('/\s+/', '', $video);
        $audio["file"] = preg_replace('/\s+/', '', $audio["file"]);
        if(!empty($video) && $video !== "")
        {
            $youtube = '<iframe width="560" height="315" src="'.$video.'" frameborder="0" allowfullscreen></iframe>';
        }
        
        if(!empty($audio["file"]) && $audio["file"] !== "" )
        {
        $audioplayer = '<audio controls><source src="../storage/exercises/audios/".'.$audio["file"].'" type="'.$audio["type"].'">Your browser does not support the audio element.</audio>';
        }
        
        $returned .= "<div class='row'><div class='col-md-12'>".$youtube.$audioplayer."</div></div><div class='row'><div class='col-md-12'><p>".$text."</p></div></div>";
        
        return $returned;
        
    }
}
