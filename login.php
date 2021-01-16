<?php 
	
	session_start(); 
	$pageTitle = 'Login';

	if (isset($_SESSION['user'])){
		header('Location: index.php'); // redirect to dashboard page
		exit();
	} 	

	include 'init.php'; 

	// check if user coming from HTTP post request
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {

		// Check if coming POST request is from 'Login' Form 
		if(isset($_POST['login'])){
			$user       = $_POST['nusername'];
			$pass       = $_POST['npassword'];
			$hashedPass = sha1($pass);
	         
	        // check all texts not Empty  
	        		
			// check if user is exist in database
			$stmt = $con->prepare(" SELECT *
								    From   users 
								    WHERE  userName = ? AND password = ? 
								  ");

			$stmt->execute(array($user, $hashedPass));
			$count = $stmt->rowCount();
			$get   = $stmt->fetch();

			//if count = 1 this mean database contain record about this username
			if ($count == 1 ) {
				
				$_SESSION['user'] 			= $user;          // register session userName
				$_SESSION['uid']  			= $get['userId']; // register user Id
				$_SESSION['avatar_name']	= $get['Avatar']; // register user avaterName
				header('Location: index.php'); 	              // redirect to dashboard page
				exit(); 
			}
		}
		// otherwise coming POST request is from 'Signup' Form
		else{

			$formErrors = array();

			// Validate UserName field
			if(isset($_POST['nusername'])){

				$filterUser = filter_var( $_POST['nusername'], FILTER_SANITIZE_STRING);
				if(strlen($filterUser) < 4){
					$formErrors[]  = 'UserName Must be Larger Than 3 characters';

				}
			}

			// Validate Password field
			if( isset($_POST['npassword']) && isset($_POST['npassword2']) ){

				if(empty($_POST['npassword'])){

					$formErrors[] = 'Password is Empty!';
				}
				else{

					$pass1 = sha1($_POST['npassword']);
					$pass2 = sha1($_POST['npassword2']);

					if($pass1 !== $pass2){

						$formErrors[] = 'Password Not Match!';
					}	
				}
			}

			// Validate Email field
			if(isset($_POST['email'])){

				$filterEmail = filter_var( $_POST['email'], FILTER_SANITIZE_EMAIL);
				if( filter_var( $filterEmail, FILTER_VALIDATE_EMAIL ) != true){
					$formErrors[]  = 'Invaild Email Name!';

				}
			}	

						
			$user       = $_POST['nusername'];
			$pass       = $_POST['npassword'];
			$email 		= $_POST['email'];
			$hashedPass = sha1($pass);

			// check all texts not Empty 

			//check if the user is existed in database
       		$check = checkItem("userName","users",$user);
   			 if ($check == 0){
                		
	            //Insert userInfo into database
	          	$stmt = $con->prepare("INSERT INTO users(userName, password, email, regStatus,date)
	                					   VALUES (:nuserName, :npassword, :nmail, 0, now())");
	                 		
	            $stmt->execute(array(
	                	'nuserName' => $user,
	                 	'npassword' => $hashedPass,
	                 	'nmail'     => $email,
	               	));

	            // Echo success Message
	           	$msg =  "User Registerd";
            }
            else{
       	    	$formErrors[] = " UserName is Existed";
            }                        	
		}	
	}
?>

<div class="container login_page">
	<h1 class="text-center">
		<span class="selected" data-class="login">Login</span> | <span data-class="signup">Sign Up</span>
	</h1>	
	<!-- Start Login Form-->
	<form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" >
		<input class="form-control" type="text" name="nusername" placeholder="Enter Username                                                               *" autocomplete="off" />
		<input class="form-control" type="password" name="npassword" placeholder="Enter Password                                                                *" autocomplete="new-password" />
		<input class="btn btn-primary btn-block" name="login" type="submit" value="Login"/>
	</form>
	<!-- End Login Form-->

	<!-- Start SignUp Form-->
	<form class="signup" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
		<input  class="form-control" type="text" name="nusername" placeholder="Enter Username                                                               *" autocomplete="off"   />
		<input  class="form-control" type="password" name="npassword" placeholder="Enter Password                                                                *" autocomplete="new-password"  />
		<input  class="form-control" type="password" name="npassword2" placeholder="Confirm Password                                                            *" autocomplete="new-password"   />
		<input class="form-control" type="email" name="email" placeholder="Enter Email                                                                       *"   />
		<input class="btn btn-success btn-block" name="signup" type="submit" value="SignUp"/>
	</form>
	<!-- End SignUp Form-->
	<div class="the-msg text-center">
		<?php
			
			if(!empty($formErrors)){

				foreach ($formErrors as $error) {
					echo "<div class='error'>" . $error . "</div><br>";
				}
			}
			elseif (isset($msg)) {
					echo "<div class='success'>" . $msg . "</div><br>";
				}	
		?>
	</div>
</div>


	

<?php include $tp1 . 'footer.php'; ?>
