<?php
	include_once 'core/init.php';
	loggedin_protection();
	include_once 'includes/overalls/header.php';
	?>
	<h3>Registration page</h3><br/>
	<?php
	if(empty($_GET['success']) && isset($_GET['success'])){
		?>
		<div class="alert alert-info" role="alert">
			<b>Well done!</b> You have been registered successfuly.<br/>
			Check your email to activate your account!
		</div>
		<?php
	} else {
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
					'password' => array(
						'name' => 'Password',
						'required' => true,
						'min' => 6
					),
					've_password' => array(
						'name' => 'Verify password',
						'required' => true,
						'matches' => 'password'
					),
					'name' => array(
						'name' => 'Name',
						'required' => true,
						'min' => 4
					),
					'email' => array(
						'name' => 'Email',
						'required' => true,
						'unique' => 'users'
					),
					've_email' => array(
						'name' => 'Verify email',
						'required' => true,
						'matches' => 'email'
					)
				));
				
				if($validation->passed() === true){
					$user = new User();
					$salt = Hash::salt(32);
					
					try{
						$user->create(array(
							'username' => Input::get('username'),
							'password' => Hash::make(Input::get('password'), $salt),
							'salt' => $salt,
							'name' => Input::get('name'),
							'activated' => 0,
							'email' => Input::get('email'),
							'email_code' => md5(Input::get('username') + microtime())
						));
						
						Redirect::to('register.php?success');
					} catch(Exception $e){
						die($e->getMessage());
					}
				} else {
					?>
					<div class="alert alert-danger" role="alert">
					<?php
					foreach($validation->errors() as $error){
						echo $error . '<br/>';
					}
					?>
					</div>
					<?php
				}
			}
		}
?>
<div class="reg_form">
	<form action="" method="POST">
		<ul>
			<li>
				Username*:<br/>
				<input type="text" name="username" value="<?php echo Input::get('username'); ?>">
			</li>
			<li>
				Password*:<br/>
				<input type="password" name="password" />
			</li>
			<li>
				Verify password*:<br/>
				<input type="password" name="ve_password" />
			</li>
			<li>
				Name*:<br/>
				<input type="text" name="name" value="<?php echo Input::get('name'); ?>">
			</li>
			<li>
				Email*:<br/>
				<input type="text" name="email" value="<?php echo Input::get('email'); ?>">
			</li>
			<li>
				Verify email*:<br/>
				<input type="text" name="ve_email" value="" autocomplete="off">
			</li>
			<li>
				<input type="hidden" name="reg_token" value="<?php echo Token::generate('reg_token'); ?>">
			</li>
			<li>
				<input class="btn btn-green" type="submit" value="Register" />
			</li>
		</ul>
	</form>
</div>
<?php 
	}
include_once 'includes/overalls/footer.php'; 

?>

