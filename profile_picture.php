<?php 
	include_once 'core/init.php';
	loggedout_protection();
	include_once 'includes/overalls/header.php';
	
	?>
		<h3>Profile picture</h3>
	<?php
	
	$user = new User();
	
	if(Session::exists('image_uploaded')){
		?>
		<div class="alert alert-success" role="alert">
		<?php echo Session::flash('image_uploaded'); ?>
		</div>
		<?php
	}
	?>
	<div class="profile_picture profile_picture_settings">
	<?php
		
		if(isset($_FILES['profile_picture'])){
			if(empty($_FILES['profile_picture']['name'])){
				
			} else {
				
				$allowed = array('jpg', 'jpeg', 'png');
				
				$file_name = $_FILES['profile_picture']['name'];
				$file_extn_parts = explode('.', $file_name);
				$file_extn = strtolower(end($file_extn_parts));
				$file_temp = $_FILES['profile_picture']['tmp_name'];
				
				if(in_array($file_extn, $allowed)){
					try{
						$user->profile_image($file_temp, $file_extn);
					} catch (Exception $e){
						?>
						<div class="alert alert-danger" role="alert">
							<h4>Error uploading image...</h4>
							<hr class="red-line"></hr>
						<?php
							echo $e->getMessage();
						?>
						</div>
						<?php
					}
					
					Session::flash('image_uploaded', 'You have successfully uploaded the image.');
					Redirect::to('profile_picture.php');
					
				} else {
					echo 'Only jpg, jpeg and png are allowed!';
				}
				
			}
		}
	
		if(!empty($user->data()->profile_picture_path)){
			echo '<img src="'. $user->data()->profile_picture_path .'">';
		}
	?>
		<form action="" method="post" enctype="multipart/form-data">
			<ul>
				<li>
					<input type="file" name="profile_picture">
				</li>
				<li>
					<input class="btn btn-blue" type="submit">
				</li>
			</ul>
		</form>
	</div>
<?php
	include_once 'includes/overalls/footer.php';
?>