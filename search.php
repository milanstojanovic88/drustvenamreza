<?php
	include_once 'core/init.php';
	loggedout_protection();
	include_once 'includes/overalls/header.php';
	
	$output = array();
	if(Input::exists('post')){
		$searchq = $_POST['search'];
		$query = DB::getInstance()->get('users', array('name', 'LIKE', '%'. $searchq .'%'));
		
		$count = $query->counting();
		
		if($count == 0){
			$output = 'No results!';
		} else {
			foreach($query->results() as $row){
				$username = $row->username;
				$name = $row->name;
				$img = $row->profile_picture_path;
				$mail = $row->email;
				
				$output[] = array('username' => $username, 'name' => $name, 'img' => $img, 'mail' => $mail);
			}
		}
		
	}
	?>
		<h3>Search results</h3>
	<?php
	foreach($output as $key => $value){
		?>
		<div class="search-result-row">
			<a href="<?php echo $value['username']; ?>"><img class="search-img" src="<?php echo $value['img']; ?>"></a>
			<div class="search-info">
			<?php
			unset($value['username']);
			unset($value['img']);
			foreach($value as $info){
				echo $info . '<br/>';
			}
			?>
			</div>
		</div><?php
	}
	
	include_once 'includes/overalls/footer.php';

?>