<?php
	session_start();
	$noNavbar  = '';
	$pageTitle = 'Login'; 

	if (isset($_SESSION['userName'])){
		header('Location: dashboard.php'); // redirect to dashboard page
		exit();
	}
	include 'init.php';



	// check if user coming from HTTP post request
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

		$userName   = $_POST['user'];
		$password   = $_POST['pass'];
		$hashedPass = sha1($password);

		// check if user is exist in database
		$stmt = $con->prepare(" SELECT userId, userName, password 
							    From   users 
							    WHERE  userName = ? AND password = ? AND groupId = 1
							    LIMIT 1"
							  );
		$stmt->execute(array($userName, $hashedPass));
		$row = $stmt->fetch();
		$count = $stmt->rowCount();

		//if count > 0 this mean database contain record about this username
		if ($count > 0 ) {
			
			$_SESSION['userName'] = $userName; // register session userName
			$_SESSION['id']       = $row['userId'];   // register session userId
			header('Location: dashboard.php'); // redirect to dashboard page
			exit();
		    
		    
		}
	}		
?>

	
	<form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" >
		<h4 class="text-center">Admin login</h4>
		<input class="form-control" type="text" name="user" placeholder="userName"     autocomplete="off"  />
		<input class="form-control" type="password" name="pass" placeholder="password" autocomplete="off" />
		<input class="btn btn-primary btn-block" type="submit" value="Login">
	</form>

<?php

	include $tp1 . 'footer.php';
?>