<?php
#$c = mysql_connect("localhost", "pixelbor_vcs", "golightly");
#$db = mysql_selectdb("pixelbor_VanCitySocial");

require("../includes/encryption.php");
require("../includes/setup.php");

$uid = $_POST['user'];
$uid = decrypt_id($uid);

$post = $_POST['post'];
$image = $_POST['image'];
$iid = $_POST['interest'];

#if(isset($_POST['image'])){
    #$q = mysql_query("INSERT INTO posts(uid, iid, text, image) values($uid, $iid, '$post', '$image')");
    #$statement = $db->prepare("INSERT INTO posts(uid, iid, text) values(?,?,?)");
    #$statement->bind_param("iis", $uid, $iid, $post);
    #$statement->execute();
#}else{
    #$q = mysql_query("INSERT INTO posts(uid, iid, text) values($uid, $iid, '$post')");
    $statement = $db->prepare("INSERT INTO posts(uid, iid, text) values(?,?,?)");
    $statement->bind_param("iis", $uid, $iid, $post);
    $statement->execute();
#}

#NOTE: There are no image posts anymore

if($statement){
    echo "true";
}else{
    echo "false";
}


?>