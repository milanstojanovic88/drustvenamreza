<?php
	include_once 'core/init.php';
	loggedout_protection();
	include_once 'includes/overalls/header.php';
?>
	<h3>Account settings</h3>
	
	<ul>
		<li><a href="profile_picture.php">Profile picture</a></li>
		<li><a href="general_settings.php">General settings</a></li>
		<li><a href="change_password.php">Change password</a></li>
	</ul>

<?php include_once 'includes/overalls/footer.php'; ?>