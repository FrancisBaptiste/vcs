<div id="friendWrap">
	<h3 class='sectionHeader'>Friend List</h3>
	<ul id="userFriendlist">
	<?php
	$friendslist = $decoded->friendlist;
	$fq = $db->query("SELECT * FROM users WHERE id IN(" . $friendslist . ")");
	if($fq){
		while($friends = $fq->fetch_assoc() ){
			if($friends['image'] == "" || $friendImage == "undefined"){
				$friendImage = SITE_URL . "images/noProfile.jpg";
			}else{
				$friendImage = $friends['image'];
			}
			echo "<li data-friend-id='$friends[id]'><span class='friendImage' style='margin-right: 10px; position:relative; top: 5px;'>";
			echo "<img src='".$friendImage."' width='20' height='20'/>";
			echo "</span><span class='name'>" . $friends['name'] . "</span><span class='removeFriend'><img src='images/x.png'</span></li>";
		}
	}
		
	?>
	</ul>
</div>