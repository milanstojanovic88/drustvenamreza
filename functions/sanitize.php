<?php

	function sanitize($value){
		return htmlentities($value, ENT_QUOTES, 'UTF-8');
	}

?>