<?php

require("../includes/setup.php");

$pid = $_POST['pid'];

$json = file_get_contents(SITE_URL . "api/posts.php?pid=$pid");
$decoded = json_decode($json);

foreach($decoded as $p){

?>

<div id="notification-head">
    notification
    <div id="notification-close"><img src='images/xw.png'></div>
</div>

<div class="post" data-post-id="<?php echo $pid; ?>" data-user-id="<?php echo $p->id; ?>">
    <div class="picMask">
        <img src="<?php echo $p->user_image; ?>">
    </div>
    <p><strong><?php echo $p->name; ?></strong> <?php echo makeClickableLinks($p->text); ?> <em class="timePosted"><?php echo $p->date; ?></em></p>
    
    <?php
    foreach($p->comments as $c){
    ?>
    
    <div class="comment">
        <div class="picMask">
            <img src="<?php echo $c->image; ?>">
        </div><p><strong><?php echo $c->user_name; ?></strong> <?php echo makeClickableLinks($c->text); ?> <em class="timePosted"><?php echo $c->date; ?></em></p>
    </div>
    
    <?php
    }
    ?>

<?php
}

function makeClickableLinks($text){
	$newString = "";
    $words = explode(" ", $text);
    foreach($words as $word){
        if(strtolower(substr($word, 0, 4)) == "http" || strtolower(substr($word, 0, 3)) == "www"){
            if(strlen($word) >= 20){
                $newString .= "<a href='$word' target='blank' alt>$word</a> ";
            }else{
                $newString .= "<a href='$word' target='blank' alt>[Link]</a> ";
            }
        }else{
			$newString .= $word . " ";
		}
    }
	$newString = substr($newString, 0, -1);
	return $newString;
}
?>


<div class="breaker"></div>
    
    <div id="commentSubmit">
        <textarea name="commentPost" id="commentPost" cols="20" rows="5">leave a comment...</textarea>
        <div id="commentPostButton">Post Comment</div>
    </div>
</div>