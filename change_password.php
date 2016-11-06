<?php
	include_once 'core/init.php';
	loggedout_protection();
	include_once 'includes/overalls/header.php';
	
	$user = new User();
	
	?>
		<h3>Change your password</h3>
	<?php
	
	if(Session::exists('password_changed')){
		?>
		<div class="alert alert-success" role="alert">
		<?php echo Session::flash('password_changed'); ?>
		</div>
		<?php
	}
	
	if(Input::exists('post')){
		if(Token::check(Input::get('settings_token'))){
			
			$validate = new Validate();
			$validation = $validate->check($_POST, array(
				'current_password' => array(
					'name' => 'Current password',
					'required' => true
				),
				'password' => array(
					'name' => 'New password',
					'required' => true,
					'min' => 4
				),
				'confirm_password' => array(
					'name' => 'Confirm password',
					'matches' => 'password'
				),
			));
			
			if($validation->passed()){
				
				if(Hash::make(Input::get('current_password'), $user->data()->salt) !== $user->data()->password){
					?>
					<div class="alert alert-danger" role="alert">
					<h4>Error changing your password...</h4>
					<hr class="red-line"></hr>
					<?php
						echo 'Invalid current password!';
					?>
					</div>
					<?php
				} else {
					$salt = Hash::salt(32);
					
					try{
						$user->update(array(
							'password' => Hash::make(Input::get('password'), $salt),
							'salt' => $salt
						));
					} catch (Exception $e){
						?>
						<div class="alert alert-danger" role="alert">
							<h4>Error changing your password...</h4>
							<hr class="red-line"></hr>
						<?php
							echo $e->getMessage();
						?>
						</div>
						<?php
					}
					Session::flash('password_changed', 'You have changed your password successfully!');
					Redirect::to('change_password.php');
				}
				
			} else {
				?>
				<div class="alert alert-danger" role="alert">
					<h4>Error changing your password...</h4>
					<hr class="red-line"></hr>
				<?php
				foreach($validation->errors() as $error){
					echo $error . '<br/>';
				} ?>
				</div>
				<?php
			}
			
		}
	}
	
?>
	
	<form action="" method="post">
		<ul>
			<li>
				Current password:<br/>
				<input type="password" name="current_password">
			</li>
			<li>
				New password:<br/>
				<input type="password" name="password">
			</li>
			<li>
				Confirm new password:<br/>
				<input type="password" name="confirm_password">
			</li>
			<li>
				<input type="hidden" name="settings_token" value="<?php echo Token::generate('settings_token'); ?>">
			</li>
			<li>
				<input class="btn btn-blue" type="submit" value="Change password">
			</li>
		</ul>
	</form>

<?php include_once 'includes/overalls/footer.php'; ?>