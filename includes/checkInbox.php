<?php

$inbox = $decoded->inbox;

$messageNotifications;

if( $inbox == 1 ){
	
	$messageNotifications .= '<div id="messageNotifications"><h3 class="sectionHeader">You Have New Messages!</h3>';

    $conQ = $db->query("SELECT * FROM conversations WHERE inbox=1 AND (person_a='$decodedID' OR person_b='$decodedID')");
    while($cons = $conQ->fetch_assoc() ){
        $conID = $cons['id'];
        
        $messageQ = $db->query("SELECT * FROM messages WHERE conversation_id=$conID AND message_read=0");
        while($mess = $messageQ->fetch_assoc() ){
	        $messageUser = $mess['user_id'];
	        
	        if( $messageUser != $id ){
	        	
	        	$userQ = $db->query("SELECT name FROM users WHERE id=$messageUser");
	        	while($thisUserName = $userQ->fetch_assoc() ){
		        	
		        	$messageNotUser = $thisUserName['name'];
		        	$messageNotifications .= '<div class="messNot"><a href="inbox.php?conversation='.$conID.'">You have a message from <span>'.$messageNotUser.'</span>.</a></div>';
		        	break(2);
		        	#this will break out of the while loop after a single row
	        	}
	        }
        }
        
    }
    
    
    
    $messageNotifications .= "</div>";
    
}else{

}



?>