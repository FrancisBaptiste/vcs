<?php
#$c = mysql_connect("localhost", "vancitys_vcs", "golightly");
#$db = mysql_selectdb("vancitys_vancitysocial");

require("../includes/encryption.php");
require("../includes/setup.php");

$uid = $_POST['user'];
$uid = decrypt_id($uid);

$iid = $_POST['topic'];

#$q = mysql_query("INSERT INTO user_interests(uid, iid) values($uid, $iid)");

$q = $db->query("INSERT INTO user_interests(uid, iid) values($uid, $iid)");

if($q){
    echo "TRUE";
}else{
    echo "FALSE";
}

?>