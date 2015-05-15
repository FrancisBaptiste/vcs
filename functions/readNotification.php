<?php
#$c = mysql_connect("localhost", "vancitys_vcs", "golightly");
#$db = mysql_selectdb("vancitys_vancitysocial");

require("../includes/setup.php");
#$db = new mysqli("localhost", "vancitys_vcs", "golightly", "vancitys_vancitysocial");


$nid = $_POST['nid'];
$new = 1;

$update = $db->query("UPDATE notifications SET notification_read=1 WHERE id=$nid");

if($update){
    echo "TRUE";
}else{
    echo "FALSE";
}

?>