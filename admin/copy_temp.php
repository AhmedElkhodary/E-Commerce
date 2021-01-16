<?php


/********************************************************************************
 * > Copy_template Page
 *	 - You can copy some template from here to another new Page
 *
 *********************************************************************************
 */
	ob_start(); // Output Buffering Start
	session_start();
	$pageTitle = '';

	if (isset($_SESSION['userName'])){

		
	    include 'init.php';

	    $do = ( isset( $_GET['do'] ) ?  $_GET['do']  :  'manage');

	    if ($do == 'manage'){

	    }
	    elseif ($do == 'add') {
	    	# code...
	    }
	    elseif ($do == 'insert') {
	    	# code...
	    }
	    elseif ($do == 'edit') {
	    	# code...
	    }
	    elseif ($do == 'update') {
	    	# code...
	    }
	    elseif ($do == 'delete') {
	    	# code...
	    }
	    elseif ($do == 'activate') {
	    	# code...
	    }
	    
	    include $tp1 . 'footer.php';
	}
	else{
		header('Location: index.php');
		exit();
	}
	ob_end_flush();    

?>	