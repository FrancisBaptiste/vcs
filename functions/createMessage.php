<?php

require("../includes/encryption.php");
require("../includes/setup.php");

$conversation = $_POST['conversation'];
$message = $_POST['message'];

$user = $_POST['user'];
$user = decrypt_id($user);

$statement = $db->prepare("INSERT INTO messages(message, conversation_id, user_id) values(?, ?, ?)");
$statement->bind_param('sii', $message, $conversation, $user);
$statement->execute();


if($statement){
    echo "true";
    #if message is put into database
    #trigger the inbox boolean in the user table, for the user I'm conversing with, not the one attached to the message
    $getCon = $db->query("SELECT * FROM conversations WHERE id=$conversation");
    
    while($con = $getCon->fetch_assoc() ){
	    if( $con['person_a'] == $user ){
	    	$partner = $con['person_b'];
	    }else{
		    $partner = $con['person_a'];
	    }
	    $db->query("UPDATE users SET inbox=1 WHERE id=$partner");
	    $db->query("UPDATE conversations SET inbox=1 WHERE id=$conversation");
    }
    
}else{
    echo "false";
}

?>