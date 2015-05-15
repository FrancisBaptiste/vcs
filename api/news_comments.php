<?php

$allComments = array();

$storyID = $_GET['story'];

$m = new mysqli("localhost", "vancitys_vcs", "golightly", "vancitys_vancitysocial");
$q = $m->query("SELECT * FROM news_comments WHERE story_id=$storyID AND response_id=0");

while($row = $q->fetch_assoc()){
	$commentID = $row['id'];
	echo "<div>" . $row['text'] . "</div>";
	checkForResponse($commentID);
}


function checkForResponse($CID){
	$m = new mysqli("localhost", "vancitys_vcs", "golightly", "vancitys_vancitysocial");
	$thisCID = $CID;
	$q2 = $m->query("SELECT * FROM news_comments WHERE response_id=$thisCID");
	
	while($resRow = $q2->fetch_assoc()){
		$thisID = $resRow['id'];
		$tcount = $resRow['thread_count'];
		$spaces = "";
		for($i = 0; $i<$tcount; $i++){
			$spaces .= "-";
		}
		echo "<div>$spaces" . $resRow['text'] . "</div>";
		checkForResponse($thisID);
	}
}

#header('Content-Type: application/json');
#echo json_encode($allComments);

?>