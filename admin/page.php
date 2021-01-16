<?php
	
	$do = ( isset( $_GET['do'] ) ?  $_GET['do']  :  'manage');

	if ($do == 'manage') {
		echo "welcome to manage page<br>";
		echo "<a href='page.php?do=add'>To Add Page </a>";

	}
	elseif($do == 'add') {
		echo "welcome to add page";
	}
	elseif ($do == 'insert') {
		echo "welcome to insert page ";
	}
	else {
		echo "error there are not page same this name ";
	}


?>