<h3 class='sectionHeader'>Friend Lookup</h3>
            	
<input type="text" value="search name" id="userLookup"  style='width: 200px; padding: 10px;'/>
<span class='clearLookup'>Clear Search</span>

<ul id="allUsers">
	<?php
	
	$q = $db->query("SELECT * FROM users");
	while($row = $q->fetch_assoc() ){
    	echo "<li class='userLine' image_path='" .$row['image'] . "' data-friend-id='".$row['id']."' style='display:none; margin-bottom: 20px;'>";
    	echo "<span class='imageSpace' style='margin-right: 10px; position:relative; top: 5px;'></span><span class='name'>" . $row['name'] . "</span><br/>";
    	echo "<span class='sendUserMessage'>Message</span>";
    	echo "<span class='addToFriendlist'>Friend</span>";
    	echo "</li>";
	}
	
	?>
</ul>