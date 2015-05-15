<?php

require("../includes/encryption.php");
require("../includes/setup.php");

#$db = new mysqli("localhost", "vancitys_vcs", "golightly", "vancitys_vancitysocial");


$uid = $_POST['user'];
$uid = decrypt_id($uid);

$iid = $_POST['topic'];

#$q = mysql_query("INSERT INTO user_interests(uid, iid) values($uid, $iid)");
# "DELETE FROM Persons WHERE LastName='Griffin'");

$q = $db->query("DELETE FROM user_interests WHERE uid=$uid AND iid=$iid");

if($q){
    echo "TRUE";
}else{
    echo "FALSE";
}

?>