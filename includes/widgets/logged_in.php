<div class="widget">
	<h3>Hello, 
	
<?php 
	$user = new User();
	
	echo $user->data()->name; 
	
?>!</h3>
	<div class="profile_picture">
	<?php
		if(!empty($user->data()->profile_picture_path)){
			echo '<img src="'. $user->data()->profile_picture_path .'">';
		}
	?>
	</div>
	<ul>
		<li>
			<a href="logout.php">Log out</a>
		</li>
		<li>
			<a href="<?php echo $user->data()->username; ?>">Profile</a>
		</li>
		<li>
			<a href="settings.php">Settings</a>
		</li>
	</ul>
</div>