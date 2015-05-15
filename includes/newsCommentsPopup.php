<?php

$commentQ = $m->query("SELECT * from news_comments WHERE story_id=$storyID AND response_id=0 ORDER BY id DESC");
while($cRow = $commentQ->fetch_assoc()){
    $commentID = $cRow['id'];
    $userID = $cRow['user_id'];
    $thisName = $cRow['guest_name'];
    if($userID != 0){
        $nameQ = $m->query("SELECT name, image FROM users WHERE id=$userID");
        while($nameRow = $nameQ->fetch_assoc()){
            $thisName = $nameRow['name'];
            $thisImage = $nameRow['image'];
            if($thisImage == ""){$thisImage = "http://vancitysocial.ca/images/noProfile.jpg";}
        }
    }else{
        $thisImage = "http://vancitysocial.ca/images/noProfile.jpg";
    }
    
    //set out comments time
    $thisTime = createTimeVal($cRow['date']);
    
    echo "<div class='comment' comment_id='$commentID' thread_count='0' user_id='$userID'>";
    echo "<div class='picMask'><img src='".$thisImage."'/></div>";
    echo "<p><strong>".$thisName."</strong> <span class='mainText'>". stripslashes($cRow['text']) . "</span> <em>".$thisTime."</em></p>";
    echo "<div class='replyLine'>";
    if($userID != 0){
        echo "<span class='messageUser'>Send Message to User</span>";
    }
    echo "<span class='respondComment'>Respond to Comment</span>";
    echo "</div>"; #end of reply line
    responseBlock($decodedID); #used to be $id
    echo "</div>"; #end of comment div
    checkForResponse($commentID, $m, $decodedID); #used to be $id
}



function responseBlock($id){
    echo '<div class="responseBlock">';
    if($id == null){
        echo '<br/>Guest Name:<br/><input type="text" class="responseGuestName"/><br/>';
    }
    echo '<textarea name="comment" class="response" cols="25" rows="5"></textarea>';
    echo '<div class="responseButton">Comment</div>';
    echo '</div>';
}

function checkForResponse($CID, $mysqliO, $id){
    $m = $mysqliO;
    $thisCID = $CID;
    $q2 = $m->query("SELECT * FROM news_comments WHERE response_id=$thisCID");
    while($resRow = $q2->fetch_assoc()){
            $thisID = $resRow['id'];
            $userID = $resRow['user_id'];
            $thisName = $resRow['guest_name'];
            $thisImage = "";
            if($userID != 0){
                $nameQ = $m->query("SELECT name, image FROM users WHERE id=$userID");
                while($nameRow = $nameQ->fetch_assoc()){
                    $thisName = $nameRow['name'];
                    $thisImage = $nameRow['image'];
                    if($thisImage == ""){$thisImage = "http://vancitysocial.ca/images/noProfile.jpg";}
                }
            }else{
                $thisImage = "http://vancitysocial.ca/images/noProfile.jpg";
            }
            
            $thisTime = createTimeVal($resRow['date']);
            
            $tcount = $resRow['thread_count'];
            if($tcount > 10){
                $tcount = 10;
            }
            $tcountLabel = "indent" . $tcount;
            
            echo "<div class='comment $tcountLabel threaded' comment_id='$thisID' thread_count='$tcount' user_id='$userID'>";
            echo "<div class='picMask'><img src='".$thisImage."'/></div>";
            echo "<p><strong>".$thisName."</strong> <span class='mainText'>". stripslashes($resRow['text']) ."</span> <em>".$thisTime."</em></p>";
            echo "<div class='replyLine'>";
            if($userID != 0){
                echo "<span class='messageUser'>Send Message to User</span>";
            }
            echo "<span class='respondComment'>Respond to Comment</span>";
            echo "</div>"; #end of reply line
            responseBlock($id);
            echo "</div>";
            
                                
                                
            #echo "<div class='comment $tcountLabel threaded'>$spaces" . $resRow['text'] . "</div>";
            checkForResponse($thisID, $m, $id);
    }
}


function createTimeVal($timestamp){
    $now = Date('U');
    $commentTime = strtotime($timestamp);
    $secondsInDay = 86400;
    $secondsInHour = 3600;
    $timeDifference = $now - $commentTime;
    if($timeDifference < $secondsInDay && $timeDifference > $secondsInHour){
        $timeVal = round($timeDifference/$secondsInHour);
        $thisTime = "Posted $timeVal hours ago";
    }else if($timeDifference > $secondsInDay && $timeDifference > $secondsInHour){
        $timeVal = round($timeDifference/$secondsInDay);
        $thisTime = "Posted $timeVal days ago";
    }else if($timeDifference < $secondsInHour){
        $timeVal = round($timeDifference/60);
        $thisTime = "Posted $timeVal minutes ago";
    }else if($timeDifference < 60){
        $thisTime = "Posted $timeVal seconds ago";
    }
    
    return $thisTime;
}

?>

