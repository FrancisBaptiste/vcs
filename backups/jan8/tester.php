<?php


function checkForLinks($text){
    $words = explode(" ", $text);
    foreach($words as $word){
        if(strtolower(substr($word, 0, 4)) == "http" || strtolower(substr($word, 0, 3)) == "www"){
            if(strlen($text) >= 20){
                return "<a href='$text' target='blank' alt>$text</a>";
            }else{
                return "<a href='$text' target='blank' alt>[Link]</a>";
            }
        }
    }
}


?>