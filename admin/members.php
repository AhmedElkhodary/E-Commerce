<?php


/********************************************************************************
 * > Members management Page
 *	 - You can 
       -> Add 
       -> Edit 
       -> Delete 
       	  Members from here 
 *
 *********************************************************************************
 */
	ob_start(); // Output Buffering Start
	session_start();

	
	if (isset($_SESSION['userName'])){

		$pageTitle = 'Members';
	    include 'init.php';

	    $do = ( isset( $_GET['do'] ) ?  $_GET['do']  :  'manage');

	    /************************************************ Start Manage Page ***********************************************/
	    if ($do == 'manage'){

	    	
	    	if(isset($_GET['page']) && $_GET['page'] == 'pending'){

	    		// Select unactive users from database
	    		$rows = getAll( '*', 'users', 'userId', 'WHERE groupId != 1', 'AND regStatus = 0', $ordering='DESC');
	    		
	    	}
	    	else{

	    		// Select All users from database
	    		$rows = getAll( '*', 'users', 'userId', 'WHERE groupId != 1', $and= NULL, $ordering='DESC');
	    	}

	    	
	    	
	    	echo "<h1 class='text-center'>Manage Members</h1>";
	    	echo "<div class='container'>";
	    	if (!empty($rows)){
		    	?>
				<div class="table-responsive">
					<table class="main-table text-center table table-bordered">
						<tr>
							<td>#ID</td>
							<td>Avatar</td>
							<td>Username</td>
							<td>Email</td>
							<td>Full Name</td>
							<td>Registered Date</td>
							<td>Control</td>
						</tr>
						<?php
							foreach ($rows as $row) {
								$img = (!empty($row['Avatar']))? $row['Avatar'] : "default.png";
								
								echo "<tr>";
									echo "<td>" . $row['userId']   . "</td>";
									echo "<td><img src=uploads/avatars/". ((!empty($row['Avatar']))? $row['Avatar'] : "default.png") ."></img></td>";   
									echo "<td>" . $row['userName'] . "</td>";
									echo "<td>" . $row['email']    . "</td>";
									echo "<td>" . $row['fullName'] . "</td>";
									echo "<td>" . $row['date']     . "</td>";
									echo '<td> <a href="members.php?do=edit&userid='. $row['userId'] . '" class="btn btn-success"><i class="fa fa-edit"></i> Edit</a>
									           <a href="members.php?do=delete&userid='. $row['userId'] . '" class="btn btn-danger confirm"><i class="fa fa-close"></i> Delete</a>';
									           if( $row['regStatus'] == 0){
									     	       echo '<a href="members.php?do=activate&userid=' . $row['userId'] . '" class="btn btn-info activate"><i class="fa fa-key"></i> Activate</a>' ;
									            }  
									echo "</td>";
								echo "</tr>";
							}
						?>
					</table> 			
					<a href="members.php?do=add" class="btn btn-primary"><i class="fa fa-plus"></i> New Member</a>
				</div>
		    	<?php
	    	}
	    	else{
	    		echo "<div class='alert alert-danger'>There is no Members to show</div>";
				echo '<a href="members.php?do=add" class="btn btn-primary"><i class="fa fa-plus"></i> New Member</a>';
	    	}
	    	echo "</div>";
	    }
	    /********************************************************************** End Manage Page ******************************************************************************************/


	  
	    /********************************************************************** Start Add Page *************************************************/
	    elseif($do == 'add'){
	    	?>
	    	<h1 class="text-center">Add New Member</h1>

		    <div class="container">
		    	<form class="form-horizontal" action="?do=insert" method="POST" enctype="multipart/form-data">

		   			<!-- Start Username field -->
		   			<div class="form-group">
		   				<label class="col-sm-2 control-label">Username *</label>
		   				<div class="col-sm-10 col-md-6">
	    					<input type="text" name="username"  class="form-control" autocomplete="off"  placeholder="Enter Username" />
	    				</div>
	    			</div>
		    		<!-- End Username field -->

		    		<!-- Start Password field -->
		    		<div class="form-group">
		   				<label class="col-sm-2 control-label">Password *</label>
		   				<div class="col-sm-10 col-md-6">
		   					<input type="password" name="password" class="form-control" autocomplete="new-password" placeholder="Enter Password" />
		    				
		    				</div>
		    			</div>
	    			<!-- End Password field -->

	    			<!-- Start Email field -->
	    	   		<div class="form-group">
		    			<label class="col-sm-2 control-label">Email *</label>
		    			<div class="col-sm-10 col-md-6">
		   					<input type="email" name="email" class="form-control"  autocomplete="off"  placeholder="Enter Email" />
		    			</div>
		    		</div>
		    		<!-- End Email field -->
		    		
		    		<!-- Start Full Name field -->
		    		<div class="form-group">
		    			<label class="col-sm-2 control-label">Full Name *</label>
		    			<div class="col-sm-10 col-md-6">
		   					<input type="text" name="full" class="form-control"  autocomplete="off"  placeholder="Enter Full Name" />
		   				</div>
		    		</div>
		    		<!-- End Full Name field -->

		    		<!-- Start Avatar field -->
		    		<div class="form-group">
		    			<label class="col-sm-2 control-label">Avatar photo *</label>
		    			<div class="col-sm-10 col-md-6">
		   					<input type="file" name="avatar" class="form-control"  autocomplete="off" />
		   				</div>
		    		</div>
		    		<!-- End Avatar field -->

		    		<!-- Start submit field -->	    			
		   			<div class="form-group">
		   				<div class="col-sm-offset-2 col-sm-10">
		   					<input type="submit" value="Add Member" class="btn btn-primary" />
		   				</div>
	    			</div>
	    			<!-- End submit field -->
		    	</form>
		    </div>
		    <?php		
	    }
	    /************************************************* End Add Page *************************************************/



	    /************************************************* Start Insert Page *************************************************/
	    elseif ($do == 'insert') {
             
            echo "<h1 class='text-center'>Insert Members</h1>";
            echo "<div class= 'container'>"; 
	    	
	    	// check the request is POST
	    	if($_SERVER['REQUEST_METHOD'] == 'POST'){	 
	    		

	    		
	    		//Upload Variables
	    		$avatarName = $_FILES['avatar']['name'];
	    		$avatarSize = $_FILES['avatar']['size'];
	    		$avatarTmp  = $_FILES['avatar']['tmp_name'];
	    		$avatarType = $_FILES['avatar']['type'];

	    		
	    		//List of Allowed File Typed
	    		$avatarAllowedExtension = array( "jpeg", "jpg", "png", "gif");

	    		$name_exp = explode('.', $avatarName); 
	    		$avatarExtension = strtolower(end($name_exp));
	    		

				// Get Variables from the form
 				$userName = $_POST['username'];
				$password = $_POST['password'];
				$email    = $_POST['email'];
				$fullName = $_POST['full'];

				$hashpass = sha1($_POST['password']);

				// Validate the form
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
				if(empty($password)){
					$formErrors[] = "password can't be  <strong>empty</strong>";
				}
				if(empty($email)){
					$formErrors[] = "Email can't be  <strong>empty</strong>";	
				}
				if(empty($fullName)){
					$formErrors[] = "fullName can't be <strong>empty</strong>";
				}
				if(!empty($avatarName) && !in_array($avatarExtension, $avatarAllowedExtension)){

					$formErrors[] = " Image Extension is <strong>Not Allowed</strong> ";
	    		}
	    		if(empty($avatarName)){

					$formErrors[] = " Avatar Image is <strong>Required</strong> ";
	    		}
	    		if($avatarSize > 2097152 ){

					$formErrors[] = " Maximum size image<strong> 2MB</strong> ";
	    		}


				// Loop into Error array and Echo it
				foreach ($formErrors as $error) {

					$msg = "<div class='alert alert-danger'>". $error ."</div>";
					redirectHome( $msg, 'back');
				}
                
                
				// check if no existed error
                if(empty($formErrors)){

                	$avatar = rand(0,1000000) . "_" . $avatarName;
                	move_uploaded_file($avatarTmp, "uploads\avatars\\" . $avatar );

                	
                	//check if the user is existed in database
                	$check = checkItem("userName","users",$userName);
                	if ($check == 0){
                		
                		//Insert userInfo into database
                		$stmt = $con->prepare("INSERT INTO users(userName, password, email, fullName, regStatus,date, Avatar)
                						       VALUES (:nuserName, :npassword, :nmail, :nfullName, 1, now(), :navatar)");
                 		
                 		$stmt->execute(array(
                 			'nuserName' => $userName,
                 			'npassword' => $hashpass,
                 			'nmail'     => $email,
                 			'nfullName' => $fullName,
                 			'navatar'   => $avatar,
                 		));

                 		// Echo success Message
            	    	$msg =  "<div class='alert alert-success'>" . $stmt->rowCount() . " Record inserted</div>";
                		redirectHome($msg,'back');
                	}
                	else{
                		$msg = "<div class='alert alert-danger'>Sorry UserName is Existed</div>";
                		redirectHome($msg,'back');
                	}
                	
                }
                
			}
			else{
				$msg = "<div class='alert alert-danger'>access denied You can't browse this page directly</div>";
				redirectHome( $msg,'back');
				}
			echo"</div>;";	    
	    }

	    /****************************************************** End Insert Page ***************************************/ 

	    


	    /****************************************************** Start Edit page ***************************************/
	    elseif ($do == 'edit') { 

	    	echo"<h1 class='text-center'>Edit Member</h1>";

	    	// check if a get request existed and Is numeric value 
	    	$userid = ( isset($_GET['userid']) && is_numeric($_GET['userid']) )? intval($_GET['userid']): 0;

	    	
	    	
	    	// query to get all details about  the user that own id 
			$stmt = $con->prepare(" SELECT  * From users   WHERE userId =?   LIMIT 1");
			$stmt->execute(array($userid));
			$row = $stmt->fetch();
			$count = $stmt->rowCount();
            
            // check if the id is valid then view form     
			if ($count > 0){
		    	?>
		    	

		    	<div class="container">
		    		<form class="form-horizontal" action="?do=update" method="POST">
		    			<input type="hidden" name="userid" value="<?php echo $row['userId'] ?>"/>
		    			<!-- Start Username field -->
		    			<div class="form-group">
		    				<label class="col-sm-2 control-label">Username *</label>
		    				<div class="col-sm-10 col-md-6">
		    					<input type="text" name="username"  class="form-control" value="<?php echo $row['userName']?>" autocomplete="off" required />
		    				</div>
		    			</div>
		    			<!-- End Username field -->
		    			<!-- Start Password field -->
		    			<div class="form-group">
		    				<label class="col-sm-2 control-label">Password</label>
		    				<div class="col-sm-10 col-md-6">
		    					<input type="hidden" name="oldpassword" value="<?php echo $row['password']?>" />
		    					<input type="password" name="newpassword" class="form-control" autocomplete="new-password" placeholder="Leave it Empty if you don't change it" />
		    				</div>
		    			</div>
		    			<!-- End Password field -->
		    			<!-- Start Email field -->
		    			<div class="form-group">
		    				<label class="col-sm-2 control-label">Email *</label>
		    				<div class="col-sm-10 col-md-6">
		    					<input type="email" name="email" class="form-control" value="<?php echo $row['email'] ?>" autocomplete="off" required />
		    				</div>
		    			</div>
		    			<!-- End Email field -->
		    			<!-- Start Full Name field -->
		    			<div class="form-group">
		    				<label class="col-sm-2 control-label">Full Name *</label>
		    				<div class="col-sm-10 col-md-6">
		    					<input type="text" name="full" class="form-control" value="<?php echo $row['fullName'] ?>" autocomplete="off" required />
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
            }
            // if any error or no id such that
            
            else{
            	echo "<div class='container'>";
            	$msg =  "<div class='alert alert-danger'>Error userId not exist</div>";
            	redirectHome($msg);
            	echo "</div>";
            }
        }
        /****************************************************** End Edit page ***************************************/




        /****************************************************** Start Update Page *************************************/
        elseif($do == 'update'){

        	echo "<h1 class='text-center'>Update Member</h1>";
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
        /*************************************************** End Update Page *****************************************/


        /*************************************************** Start Delete Page *****************************************/
        elseif($do == 'delete'){
            
            // Print PageName (Delete Members)
        	echo "<h1 class='text-center'>Delete Members</h1>";
            echo "<div class='container'>";       	

	        	// check if a get request existed and Is numeric value 
		    	$userid = ( isset($_GET['userid']) && is_numeric($_GET['userid']) )? intval($_GET['userid']) : 0;

		    	// select all date about  the user that own this id
		    	if($userid != 0){ 
		    		$check = checkItem('userId','users', $userid);				
	            }
	            else{
	            	$check = 0;
	            }

	            // check if the id is valid then view form     
				if ($check > 0){		
					$stmt = $con->prepare(" DELETE FROM users WHERE userId= :nuser");
					$stmt->bindParam(":nuser", $userid);
					$stmt->execute();

					// success Message (Delete operation Done)
					$msg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Record Deleted</div>";
					redirectHome($msg , 'back');
				}
				else{

					// Error Message (Delete operation not Done {User not Existed})
					$msg = "<div class='alert alert-danger'>This User is not exist</div>";
					redirectHome($msg);
				}
			echo "</div>";

        }
        /*************************************************** End Delete  Page *****************************************/
	    

	    /*************************************************** Start Activate Page **************************************/
	    elseif($do == 'activate'){

	    	// Print Page name (Activate Page)
	    	echo "<h1 class='text-center'>Activate Page</h1>";
	    	echo "<div class='container'>";
	    	
	    	// check if a get request existed and Is numeric value
	    	$userid = ( isset($_GET['userid']) && is_numeric($_GET['userid']) )? intval($_GET['userid']) : 0;
            
            // check if user is in database
	    	if($userid != 0){

	    		$check = checkItem('userId','users', $userid);
	    	}
	    	else{

	    		$check = 0;
	    	}

	    	// check if the id is valid then activate 
	    	if($check > 0){
	    		$stmt = $con->prepare("UPDATE users SET regStatus=1 WHERE userId=?");
	    		$stmt->execute(array( $userid));

	    		// success Message (Activate operation Done)
	    	    $msg = "<div class='alert alert-success'>" . $stmt->rowCount() . " User Activated</div>";
	    		redirectHome($msg, 'back');
	    	}
	    	else{

	    		// Error Message (Activate operation not Done {User not Existed})
	    		$msg = "<div class alert alert-danger>This User is not exist</div>";
	    		redirectHome($msg);
	    	}
	    	echo "</div>";

	    }
	    /*************************************************** End Activate Page **************************************/

	    include $tp1 . 'footer.php';
	}
	else{

		header('Location: index.php');
		exit();
	}
	ob_end_flush();
?>

