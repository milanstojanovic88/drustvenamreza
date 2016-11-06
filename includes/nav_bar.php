<nav>
	<ul>
		<li><a href="index.php">Home</a></li>
		<li><a href="#">Download</a></li>
		<li><a href="#">Contact</a></li>
		<li class="search">
			<form action="search.php" method="post">
				<input type="text" name="search" value="<?php echo Input::get('search'); ?>" onkeydown="searchq();" placeholder="Search for users..."/><input type="submit" name="search_submit" value="GO"/>
			</form>
		</li>
	</ul>
</nav>