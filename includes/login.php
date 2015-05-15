                
                              
                <!-- used to be pointed at app.php -->
            	<?php
            	$currentPage = "";
            	$story = "";
            	if(strpos($_SERVER["PHP_SELF"], 'app') !== false){
            		$currentPage = "main";
            	}else if(strpos($_SERVER["PHP_SELF"], 'news') !== false){
            		$currentPage = "news";
            		if(isset($_GET['story'])){
	            		$story = $_GET['story'];
            		}
            	}
            	
            	?>
                

                    <div id="loginBlock" <?php if($id != null){ echo "style='display:none;'"; }?>>
                        <form action="login.php" method="post">
                            <div id="emailLogin">
                                <span>Email:</span>
                                <input type="text" name="email" id="loginEmail"  class="textInput"/>
                            </div>
                            <div id="passwordLogin">
                                <span>Password:</span>
                                <input type="text" name="password" id="loginPassword"  class="textInput"/>
                                <input type="hidden" name="page" value="<?php echo $currentPage; ?>"/>
                                <input type="hidden" name="story" value="<?php echo $story; ?>"/>
                            </div>
                            <input type="submit" value="LOGIN" id="bigButton2"/>
                        </form>
                        
                        <div id="inviteBtn">
                            or request an invitation
                        </div>
                    </div>
                
					
