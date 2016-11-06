<?php
	include_once 'core/init.php';
	include_once 'includes/overalls/header.php';
	
	if(isset($_GET['username']) && !empty($_GET['username'])){
		$username = $_GET['username'];
		$user = new User($username);
		
		if($user->exists()){
			
			$user_id = $user->data()->id;
			
			?>
			<h3><?php echo $user->data()->name; ?>'s Profile</h3>
			<?php
			echo '<img class="picture-profile" src="' . $user->data()->profile_picture_path . '" alt="profile pic">';
			?>
			<h3>About me</h3>
			<div class="about-me">
				<p class="about-me-label">My name:&nbsp;<span class="about-me-info"><?php echo $user->data()->name; ?></span></p>
				<p class="about-me-label">Place where I live:</p>
				<p class="about-me-label">Birth date:</p>
				<p class="about-me-label">My phone number:</p>
				<p class="about-me-label">My e-mail address:</p>
			</div>
			<?php
		
		} else {
			Redirect::to(404);
		}		
		
	} else {
		Redirect::to('index.php');
	}
	
?>


	
<?php include_once 'includes/overalls/footer.php'; ?>