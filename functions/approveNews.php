<?php
$c = mysql_connect("localhost", "vancitys_vcs", "golightly");
$db = mysql_selectdb("vancitys_vancitysocial");

$title = $_POST["title"];
$source = $_POST["source"];
$excerpt = $_POST["excerpt"];
$link = $_POST["link"];
$image = $_POST["image"];
$photoCred = $_POST['photographer'];
$writerCred = $_POST['writer'];

$rightNow = date('U');

$tempImageName = "../news_images_temp/" . $rightNow . ".jpg";
$ch = curl_init($image);
$fp = fopen($tempImageName, 'wb');
curl_setopt($ch, CURLOPT_FILE, $fp);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_exec($ch);
curl_close($ch);
fclose($fp);

include('simpleImage.php');
$thisImage = new SimpleImage();
$thisImage->load($tempImageName);
$thisImage->resizeToWidth(220);
$thisImageName = "../news_images/" . $rightNow . ".jpg";
$saveImage = $thisImage->save($thisImageName);

unlink($tempImageName);

$image = $rightNow . ".jpg";

$excerpt = htmlentities($excerpt);

$excerpt = str_replace("'", "&rsquo;", $excerpt);
$title = str_replace("'", "&rsquo;", $title);

$db = new mysqli("localhost", "vancitys_vcs", "golightly", "vancitys_vancitysocial");
$statement = $db->prepare("INSERT INTO news(title, source, link, excerpt, image, photo_credit, writer_credit) values(?, ?, ?, ?, ?, ?, ?)");
$statement->bind_param('sssssss', $title, $source, $link, $excerpt, $image, $photoCred, $writerCred);
$statement->execute();

if($statement){
    echo "TRUE";
}else{
    echo "FALSE";
}



?>