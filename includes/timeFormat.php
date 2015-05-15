<?php

$timePassed = time() - $postTime;
if($timePassed < 60){
    $postTime = "just now";
}else if($timePassed < 600){
    $postTime = "a few minutes ago";
}else if($timePassed < 3600){
    $postTime = "less than an hour ago";
}else if($timePassed < 7200){
    $postTime = "about an hour ago";
}else if($timePassed < 10800){
    $postTime = "about two hours ago";
}else if($timePassed < 14400){
    $postTime = "about three hours ago";
}else if($timePassed < 18000){
    $postTime = "about four hours ago";
}else if($timePassed < 21600){
    $postTime = "about five hours ago";
}else if($timePassed < 25200){
    $postTime = "about six hours ago";
}else if($timePassed < 28800){
    $postTime = "about seven hours ago";
}else if($timePassed < 32400){
    $postTime = "about eight hours ago";
}else if($timePassed < 36000){
    $postTime = "about nine hours ago";
}else if($timePassed < 39600){
    $postTime = "about ten hours ago";
}else if($timePassed < 43200){
    $postTime = "about eleven hours ago";
}else if($timePassed < 46800){
    $postTime = "about twelve hours ago";   
}else if($timePassed < 50400){
    $postTime = "about thirteen hours ago";
}else if($timePassed < 86400){
    $postTime = "earlier today";
}else if($timePassed < 172800){
    $postTime = "yesterday";
}else if($timePassed < 259200){
    $postTime = "two days ago";
}else if($timePassed < 345600){
    $postTime = "three days ago";
}else if($timePassed < 345600){
    $postTime = "three days ago";
}else if($timePassed < 604800){
    $postTime = "earlier this week";
}else if($timePassed < 1209600){
    $postTime = "last week";
}else if($timePassed < 1814400){
    $postTime = "two weeks ago";
}else{
    $postTime = $comment->date;
}

?>