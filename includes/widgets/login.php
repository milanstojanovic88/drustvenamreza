<div class="widget">
	<h2>Log in</h2>
	<form action="login.php" method="post">
		<ul>
			<li>
				Username:<br/>
				<input type="text" name="username"/>
			</li>
			<li>
				Password:<br/>
				<input type="password" name="password"/>
			</li>
			<li>
				<label for="remember">
					<input type="checkbox" name="remember" id="remember"/> Remember me
				</label>
			</li>
			<li>
				<input class="btn btn-green" type="submit" value="Log in"/>
			</li>
			<li>
				<a href="register.php">Register</a>
			</li>
			<li>
				Forgot <a href="forgotten.php">username</a>/<a href="forgotten.php">password</a>?
			</li>
			<li>
				<input type="hidden" name="login_token" value="<?php echo Token::generate('login_token'); ?>"/>
			</li>
		</ul>
	</form>
</div>