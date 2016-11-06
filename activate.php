<?php
	include_once 'core/init.php';
	loggedin_protection();
	include_once 'includes/overalls/header.php';
	
	
	if(isset($_GET['success']) && empty($_GET['success'])){
		?>
		<h3>Activation completed</h3>
		<div class="alert alert-success" role="alert">
			<h4>Well done!</h4><br/>
			<p>You have successfully activated your account! You can log in now.</p>
		</div>
		<?php
	} else if(isset($_GET['email']) && isset($_GET['email_code'])){
		$mail = trim($_GET['email']);
		$code = trim($_GET['email_code']);
		
		$user = new User();
		
		try{
			
			if($user->activate($mail, $code)){
				Redirect::to('activate.php?success');
			} else {
				
				?> <h3>There was a problem...</h3> <?php
				foreach($user->errors() as $error){
					?>
					<div class="alert alert-danger" role="alert">
					<?php echo $error . '<br/>'; ?>
					</div>
					<?php
				}
			}
			
		} catch(Exception $e){
			echo($e->getMessage());
		}
	}
?>

	
<?php include_once 'includes/overalls/footer.php'; ?>