<?php
	$path = ABSPATH."wp-content/plugins/dynamic-headers/header-images/";
	
	if(is__writable($path) != true){
		echo '<div class="error"><p>';
			echo 'It looks like <strong>wp-content/plugins/dynamic-headers/header-images/</strong> is not writable.<br /><br /> You will need to make this directory writable in order for the plugin to work.';
		echo '</p></div><br />';
	}
?>