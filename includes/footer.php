<div id="notificationMask">
	<div id="notificationBox"></div>
</div>

<!-- popup for the message user box -->
		
<div id="messageUserMask">
	<div id="messageUserBox">
		<div id="messageHeader">
			<span id="messageHeaderText">Message User Directly</span>
			<div id="messageClose"><img src='images/xw.png'></div>
		</div>
		<div id="messageAlert" style="display:none;">Your message was sent.</div>
		<div id="submitDirect">
			<?php
			if($id == null){
			echo 'You can only send a direct message to a user if you are logged in.';
			}else{
			?>
			<p id="messageNote">*Note: Your message will be sent directly to this user's inbox.<br/>The auto-filled text is to give the receiver context.</p>
			<textarea id="message"></textarea>
			<div id="messageButton">Send Message</div>
								
			<?php } ?>
		</div>
	</div>
</div>


<div id="fixedFooter">
	<?php
		if($id == null){
			echo '<div><span id="footerForgot">Forgot Password?</span> | <span id="footerAbout">About VanCity Social</span></div>';
		}else{
			echo '<div><span id="footerContact">Contact</span> | <span id="footerAbout">About VanCity Social</span> | <a href="logout.php">Logout</a></div>';
		}
	?>
</div>


<div id="forgotBox">
	<div class='sectionHeader'>Password Recovery<div id="forgotClose"><img src='images/xw.png'></div></div>
	<form action="functions/resendPassword.php" method="post" enctype="multipart/form-data">
		If you've forgotten your password enter the email address associated with your account here and we'll send it to you:<br/><br/>
		<input type="text" name="emailPassword" id="forgotPasswordInput"/><br/><br/>
		<input type="submit" name="submit" value="Submit" class="editUserBtn">
	</form>
</div>


<div id="editBox">
	<div id="editUser">
		<div class='sectionHeader'>Edit Account Info<div id="editClose"><img src='images/xw.png'></div></div>
        <form action="functions/editProfile.php" method="post" enctype="multipart/form-data">
            Change user image:
            <input type="file" name="file" id="file"><br/><br/>
            A bit about yourself (250 characters):
            <textarea maxlength="250" name="about" rows="8" id="aboutBlurb"><?php echo $about; ?></textarea>
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <br/><br/>
            <input type="checkbox" name="emailNotifications" value="emailNotifications" <?php if($decoded->email_notifications == "1"){echo "checked";} ?>>Receive email notifications of account activity<br/><br/>
            <input type="submit" name="submit" value="Submit" class="editUserBtn">
        </form>
        <p id="cancelEdit">Cancel Edit</p>
        <p id="changePassword"><a href="password.php">Change Password</a></p>
    </div>
</div>

<div id="fullScreen"></div>
		
<?php include("includes/aboutBox.php"); ?>


<div id="newsBox">
</div>

<?php include("includes/login-signup.php"); ?>


