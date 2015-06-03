<?php
#$c = mysql_connect("localhost", "vancitys_vcs", "golightly");
#$db = mysql_selectdb("vancitys_vancitysocial");

$db = new mysqli("localhost", "vancitys_vcs", "golightly", "vancitys_vancitysocial");


$limit = 30;

if(isset($_GET['postIndex'])){
    $postIndex = $_GET['postIndex'];
}

if(isset($_GET['interest'])){
    $interest = $_GET['interest'];
    if($interest == 0){
        if(isset($_GET['postIndex'])){
            $q = $db->query("SELECT * FROM posts WHERE id < $postIndex ORDER BY id DESC LIMIT $limit");
        }else{
            $q = $db->query("SELECT * FROM posts ORDER BY id DESC LIMIT $limit");
        }
    }else{
        if(isset($_GET['postIndex'])){
            $q = $db->query("SELECT * FROM posts WHERE iid=$interest AND id < $postIndex ORDER BY id DESC LIMIT $limit");
        }else{
            $q = $db->query("SELECT * FROM posts WHERE iid=$interest ORDER BY id DESC LIMIT $limit");
        }
    }
}else if(isset($_GET['friendlist'])){
	$thisID = $_GET['friendlist'];
	$friendArray = "";
	$friendQ = $db->query("SELECT friendlist FROM users WHERE id=$thisID");
	while($row = $friendQ->fetch_assoc() ){
		$friendArray = $row['friendlist'];
	}

	if(isset($_GET['postIndex'])){
        $q = $db->query("SELECT * FROM posts WHERE id < $postIndex AND uid IN(" . $friendArray . ") ORDER BY id DESC LIMIT $limit");
    }else{
        $q = $db->query("SELECT * FROM posts WHERE uid IN(" . $friendArray . ") ORDER BY id DESC LIMIT $limit");
    }
}else{
    if(isset($_GET['postIndex'])){
        $q = $db->query("SELECT * FROM posts WHERE id < $postIndex ORDER BY id DESC LIMIT $limit");
    }else{
        $q = $db->query("SELECT * FROM posts ORDER BY id DESC LIMIT $limit");
    }
}


if(isset($_GET['pid'])){
    $pid = $_GET['pid'];
    $q = $db->query("SELECT * FROM posts WHERE id=$pid");
}


$allPosts = array();


while($row = $q->fetch_assoc() ){

    $allComments = array();

    $uid = $row['uid'];
    $pid = $row['id'];

    $author = $db->query("SELECT id, name, image, about FROM users WHERE id=$uid");
    while($aRow = $author->fetch_assoc() ){
        $aId = $aRow['id'];
        $aName = $aRow['name'];
        $aImage = $aRow['image'];
        $aAbout = $aRow['about'];
        if( $aImage == "" ){
	        $aImage = 'http://vancitysocial.ca/images/noProfile.jpg';
        }

    }

    $thisText = $row['text'];

    #if it's a comment on a news story, create special text
    /*
    if($row['iid'] == 41){
    	$newsID = $row['news_id'];
    	$getTitle = $db->query("SELECT title FROM news WHERE id=$newsID");
    	while($newsTitle = $getTitle->fetch_assoc() ){
	    	$thisTitle = $newsTitle['title'];
    	}
    	#$theLink = "http://vancitysocial.ca/news.php?story=" . $row['news_id'];
	    $thisText = "<span class='newsPost'>commented on the story <a href='" . "http://vancitysocial.ca/news.php?story=" . $row['news_id'] . "'>$thisTitle</a>...</span> $thisText";
    }
    */

    $post = array(
        "id" => $pid,
        "text" => $thisText,
        "post_image" => $row['image'],
        "name" => $aName,
        "user_image" => $aImage,
        "user_about" => $aAbout,
        "user_id" => $aId,
        "date" => $row['time'],
        "interest" => $row['iid'],
        "news_id" => $row['news_id']
    );

    ### beginning of comments section

    $c = $db->query("SELECT * FROM comments WHERE pid=$pid");

    while($cRow=$c->fetch_assoc() ){
        $cuid = $cRow['uid'];
        $comment = $cRow['text'];
        $date = $cRow['time'];
        $commentorQ = $db->query("SELECT name, image, about FROM users WHERE id=$cuid");
        while($ccRow = $commentorQ->fetch_assoc() ){
            $commentator = $ccRow['name'];
            $aboutCommentator = $ccRow['about'];
            $cImage = $ccRow['image'];
            if($cImage == ""){
	            $cImage = 'http://vancitysocial.ca/images/noProfile.jpg';
            }

            $comment = array(
                "text" => $comment,
                "user_name" => $commentator,
                "user_id" => $cuid,
                "user_about" => $aboutCommentator,
                "image" => $cImage,
                "date" => $date
            );

            array_push($allComments, $comment);
        }
        //echo " --- $commentator said ... $comment at $date <br/>";

    }

    $post['comments'] = $allComments;
    array_push($allPosts, $post);

}

header('Content-Type: application/json');
echo json_encode($allPosts);


?>