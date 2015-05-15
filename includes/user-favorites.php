<!-- this is currently not being used. -Fran -->
<div <?php if($id==null){ echo "style='display:none;'"; }?>>
	<h3 class="sectionHeader">Favorited Topics</h3>
	<ul id="userTopics">
		<?php
		echo "<li><a href='".SITE_URL."app.php'>all</a></li>";
		$userInterests = array();
		foreach($decoded->interests as $interest){
			$interestID = $interest->id;
			array_push($userInterests, $interestID);
			$interestName = $interest->interest;
			echo "<li style='border-color: ". colorTag($interestID) .";'><a href='".SITE_URL."app.php?i=$interestID'>$interestName</a> <span class='removeFav' data-topic-id='$interestID'>[-]</span></li>";
		}
		?>
	</ul>
</div>
