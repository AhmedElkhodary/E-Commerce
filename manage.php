<?php

	session_start();
	$pageTitle = 'Manage';
	include 'init.php';
	if(isset($_SESSION['user'])){
		$getuser = $con->prepare("SELECT * FROM users WHERE userName = ?");
		$getuser->execute(array($sessionUser));
		$info = $getuser->fetch();
		$userId = $info['userId'];

		//$do = $_GET['do'] ;
	    /****************************************************** Start Edit page ***************************************/
	    if (isset($_GET['do']) && $_GET['do'] == 'edit') { 

	    	echo"<h1 class='text-center'>". $_GET['do'] ." user Data</h1>";
	    	echo "<div class='container'>";
            ?>
		    	
		    	<div class="container">
		    		<form class="form-horizontal" action="?do=update" method="POST">
		    			<input type="hidden" name="userid" value="<?php echo $info['userId'] ?>"/>
		    			<!-- Start Username field -->
		    			<div class="form-group">
		    				<label class="col-sm-2 control-label">Username *</label>
		    				<div class="col-sm-10 col-md-6">
		    					<input type="text" name="username"  class="form-control" value="<?php echo $info['userName']?>" autocomplete="off" required />
		    				</div>
		    			</div>
		    			<!-- End Username field -->
		    			<!-- Start Password field -->
		    			<div class="form-group">
		    				<label class="col-sm-2 control-label">Password</label>
		    				<div class="col-sm-10 col-md-6">
		    					<input type="hidden" name="oldpassword" value="<?php echo $info['password']?>" />
		    					<input type="password" name="newpassword" class="form-control" autocomplete="new-password" placeholder="Leave it Empty if you don't change it" />
		    				</div>
		    			</div>
		    			<!-- End Password field -->
		    			<!-- Start Email field -->
		    			<div class="form-group">
		    				<label class="col-sm-2 control-label">Email *</label>
		    				<div class="col-sm-10 col-md-6">
		    					<input type="email" name="email" class="form-control" value="<?php echo $info['email'] ?>" autocomplete="off" required />
		    				</div>
		    			</div>
		    			<!-- End Email field -->
		    			<!-- Start Full Name field -->
		    			<div class="form-group">
		    				<label class="col-sm-2 control-label">Full Name *</label>
		    				<div class="col-sm-10 col-md-6">
		    					<input type="text" name="full" class="form-control" value="<?php echo $info['fullName'] ?>" autocomplete="off" required />
		    				</div>
		    			</div>
		    			<!-- End Full Name field -->


		    			<!-- Start submit field -->	    			
		    			<div class="form-group">
		    				<div class="col-sm-offset-2 col-sm-10">
		    					<input type="submit" value="Save" class="btn btn-primary" />
		    				</div>
		    			</div>
		    			<!-- End submit field -->			    		
		    		</form>
		    	</div>	
	            
	        <?php 
            echo "</div>";
            
        }
        /****************************************************** End Edit page ***************************************/
        
        /****************************************************** Start update page ***************************************/
        elseif (isset($_GET['do']) && $_GET['do'] == 'update'){

        	echo"<h1 class='text-center'>". $_GET['do'] ." user Data</h1>";
        	echo "<div class='container'>";
            if ($_SERVER['REQUEST_METHOD'] == 'POST'){            	
                
                // Get variable from form
            	$id       = $_POST['userid'];
            	$userName = $_POST['username'];            	
            	$email    = $_POST['email'];
            	$full     = $_POST['full'];

            	//password update
            	$pass = (empty($_POST['newpassword']))?  $_POST['oldpassword'] : sha1($_POST['newpassword']);
            	
                
            	// Validate The Form
            	$formErrors = array();

				if(strlen($userName) < 4){
					$formErrors[] = "UserName can't be less than <strong>4 charactes</strong>";
				}
				if(strlen($userName) > 20){
					$formErrors[] = "UserName cant't be greater than <strong>20 characters</strong>";
				}
				if(empty($userName)){
					$formErrors[] = "userName can't be  <strong>empty</strong>";
				}
	
				
				if(empty($full)){
					$formErrors[] = "fullName can't be <strong>empty</strong>";
				}

				// Loop into Error array and Echo it
				foreach ($formErrors as $error) {
					echo "<div class='alert alert-danger'>". $error ."</div>";
				}

				// query to get all details about  the user that own id 
				$stmt = $con->prepare(" SELECT  * From users   WHERE userName =? AND userId != ?");
				$stmt->execute(array($userName,	$id ));
				$count = $stmt->rowCount();
			

                // check if no existed error in form && UserName is not exist
                if(empty($formErrors) && $count == 0 ){

                	
                	//Update the database with this info
            	    $stmt = $con->prepare("UPDATE users SET  userName=?, password=?, email=?, fullName=? WHERE userId=?");
            	    $stmt->execute(array($userName, $pass, $email, $full, $id ));
                 	
                 	// Echo success Message
            	    $msg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Record updated</div>";
                	redirectHome($msg, 'back');
                }
                else{

                	$msg = "<div class='alert alert-danger'>Error This Name is <strong>already existed</strong> </div>";
  				    redirectHome($msg);
                }
            } 
            else{
            	
            	$msg = "<div class='alert alert-danger'>error you can't access this page directly</div>";
  				redirectHome($msg);
  				
            }
            echo "</div>";        	

        }

        /****************************************************** End update page ***************************************/

        // If Any Error in Get Request
        else{
         	echo "<div class='container'>";
           	$msg =  "<div class='alert alert-danger'>Error in GET request name</div>";
           	redirectHome($msg);
           	echo "</div>";
        }
         
        
      

?>




<?php
	}
	else{
		header('Location: login.php');
		exit();
	}	
	include $tp1 . 'footer.php';
?>	