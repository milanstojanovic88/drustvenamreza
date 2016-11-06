<?php
	include_once 'core/init.php';
	loggedout_protection();
	include_once 'includes/overalls/header.php';
	
	$user = new User();
	?>
	
	<h3>General settings</h3>
	<?php
	if(Session::exists('updated_settings')){
		?>
		<div class="alert alert-success" role="alert">
		<?php echo Session::flash('updated_settings'); ?>
		</div>
		<?php
	}
	
	if(Input::exists('post')){
		if(Token::check(Input::get('reg_token'))){
			
			$validate = new Validate();
			$validation = $validate->check($_POST, array(
				'username' => array(
					'name' => 'Username',
					'required' => true,
					'min' => 4,
					'max' => 20,
					'unique' => 'users'
				),
				'email' => array(
					'name' => 'Email',
					'required' => true,
					'unique' => 'users'
				),
				'name' => array(
					'name' => 'Name',
					'required' => true,
					'min' => 4
				)
			));
			
			if($validation->passed()){
				
			try {
				
				$user->update(array(
					'username' => Input::get('username'),
					'email' => Input::get('email'),
					'name' => Input::get('name')
				));
				Session::flash('updated_settings', 'You have changed your settings successfully!');
				Redirect::to('general_settings.php');
				
			} catch (Exception $e){
				?>
				<div class="alert alert-danger" role="alert">
				<?php echo $e->getMessage(); ?>
				</div>
				<?php
			}
				
			} else {
				?>
				<div class="alert alert-danger" role="alert">
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
				Username:<br/>
				<input type="text" name="username" value="<?php echo $user->data()->username; ?>">
			</li>
			<li>
				Email:<br/>
				<input type="text" name="email" value="<?php echo $user->data()->email; ?>">
			</li>
			<li>
				Name:<br/>
				<input type="text" name="name" value="<?php echo $user->data()->name; ?>">
			</li>
			<li>
				<input type="hidden" name="reg_token" value="<?php echo Token::generate('reg_token'); ?>">
			</li>
			<li>
				<input class="btn btn-blue" type="submit" value="Change settings">
			</li>
		</ul>
	</form>
	
	
<?php include_once 'includes/overalls/footer.php'; ?>