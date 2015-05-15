<?php
$c = mysql_connect("localhost", "vancitys_vcs", "golightly");
$db = mysql_selectdb("vancitys_vancitysocial");

$today = date("l, M d, Y");

if(isset($_POST['image']) && isset($_POST['title']) && isset($_POST['link'])){
    $image = $_POST['image'];
    $title = $_POST['title'];
    $link = $_POST['link'];
    $q = mysql_query("INSERT INTO news (image, title, link, date) VALUES('$image', '$title', '$link', '$today')");
    if($q){
        echo "added to database:<br/>$image<br/>$title<br/>$link<br/>$today";
    }
}

?>

<form action="index.php" method="post">
    Image:<br/>
    <input type="text" name="image"/>
    <br/>
    
    Title:<br/>
    <input type="text" name="title"/>
    <br/>
    
    Link:<br/>
    <input type="text" name="link"/>
    <br/>
    
    <input type="submit" value="submit">
</form>