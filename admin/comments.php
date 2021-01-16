<?php


/********************************************************************************
 * > Comments management Page
 *	 - You can Edit| Delete | Approve Comments from here 
 *
 *********************************************************************************
 */
	ob_start(); // Output Buffering Start
	session_start();

	
	if (isset($_SESSION['userName'])){

		$pageTitle = 'Comments';
	    include 'init.php';

	    $do = ( isset( $_GET['do'] ) ?  $_GET['do']  :  'manage');

	    /************************************************ Start Manage Page ***********************************************/
	    if ($do == 'manage'){

	    	

	    	// Select Comments from database
	    	$stmt = $con->prepare(" SELECT 
	    									comments.*, users.userName AS User, items.Name AS Item
								    FROM
										   	users INNER JOIN comments INNER JOIN items 
								    ON
											users.userId = comments.user_Id 
									AND
									  	 	items.Item_ID = comments.item_Id
									ORDER BY 
											C_ID DESC 	 	
                                    ");
	    	$stmt->execute();
	    	$rows = $stmt->fetchAll();
	    	

	    	if(!empty($rows)){
	    		?>
	    	    <h1 class="text-center">Manage Comments</h1>
			    <div class="container">
				<div class="table-responsive">
					<table class="main-table table table-bordered">
						<tr>
							<td>#ID</td>
							<td>Comment</td>
							<td>Item</td>
							<td>User</td>
							<td>Comment_Date</td>
							<td>Control</td>

						</tr>
						<?php
							foreach ($rows as $row) {
								echo "<tr>";
									echo "<td>" . $row['C_ID']         . "</td>";
									echo "<td>" . $row['Comment']      . "</td>";
									echo "<td>" . $row['Item']      . "</td>";
									echo "<td>" . $row['User']      . "</td>";
									echo "<td>" . $row['Comment_Date'] . "</td>";
									echo '<td> <a href="comments.php?do=edit&id='   . $row['C_ID'] . '" class="btn btn-success"><i class="fa fa-edit"></i> Edit</a>
									           <a href="comments.php?do=delete&id=' . $row['C_ID'] . '" class="btn btn-danger confirm"><i class="fa fa-close"></i> Delete</a>';
									           if( $row['Status'] == 0){
									     	       echo '<a href="comments.php?do=approve&id=' . $row['C_ID'] . '" class="btn btn-info activate"><i class="fa fa-key"></i> Approve</a>' ;
									            }  
									echo "</td>";
								echo "</tr>";
							}
						?>
					</table> 	
				</div>
				</div>
	    		<?php
	    	}
	    	else{
	    		$msg = "<div class='container alert alert-danger'>There is no Comments to show</div>";
	    		echo "<div class='container'>";
	    		redirectHome($msg);
	    		echo "</div>";
	    	}
	    	

	    }
	    /************************************************ End Manage Page ***********************************************/
   

	    /****************************************************** Start Edit page ***************************************/
	    elseif ($do == 'edit') { 

	    	echo"<h1 class='text-center'>Edit Comments</h1>";

	    	// check if a get request existed and Is numeric value 
	    	$com_id = ( isset($_GET['id']) && is_numeric($_GET['id']) )? intval($_GET['id']): 0;

	    	
	    	
	    	// query to get all details about  the user that own id 
			$stmt = $con->prepare(" SELECT  * From comments   WHERE C_ID =?   LIMIT 1");
			$stmt->execute(array($com_id));
			$row = $stmt->fetch();
			$count = $stmt->rowCount();
            
            // check if the id is valid then view form     
			if ($count > 0){
		    	?>
		    	<div class="container">
		    		<form class="form-horizontal" action="?do=update" method="POST">
		    			<input type="hidden" name="id" value="<?php echo $row['C_ID'] ?>"/>
		    			<!-- Start Comment field -->
		    			<div class="form-group">
		    				<label class="col-sm-2 control-label">Comment *</label>
		    				<div class="col-sm-10 col-md-6">
		    					<textarea name="comment" class="form-control"><?php echo $row['Comment']?></textarea>
		    				</div>
		    			</div>
		    			<!-- End Comment field -->
		    			
		    			<!-- Start submit field -->	    			
		    			<div class="form-group">
		    				<div class="col-sm-offset-2 col-sm-10">
		    					<input type="submit" value="Update" class="btn btn-primary" />
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
            	$msg =  "<div class='alert alert-danger'>Error Comment not exist</div>";
            	redirectHome($msg);
            	echo "</div>";
            }
        }
        /****************************************************** End Edit page ***************************************/




        /****************************************************** Start Update Page *************************************/
        elseif($do == 'update'){

        	echo "<h1 class='text-center'>Update Comment</h1>";
           	echo "<div class='container'>";       	


            if ($_SERVER['REQUEST_METHOD'] == 'POST'){
                
                // Get variable from form
            	$id       = $_POST['id'];
            	$comment  = $_POST['comment'];            	

                
            	// Validate The Form

				if(empty($comment)){
					$msg = "<div class='alert alert-danger'> Comment can't be  <strong>Empty</strong></div>";
					
					redirectHome($msg);
				}
	
				//Update the database with this info
            	$stmt = $con->prepare("UPDATE comments SET  Comment=? WHERE C_ID=?");
                $stmt->execute(array($comment, $id ));
                	
               	// Echo success Message
           	    $msg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Record updated</div>";
               	redirectHome($msg, 'back');
                  	         
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
        	echo "<h1 class='text-center'>Delete Comments</h1>";
            echo "<div class='container'>";       	

	        	// check if a get request existed and Is numeric value 
		    	$id = ( isset($_GET['id']) && is_numeric($_GET['id']) )? intval($_GET['id']) : 0;

		    	// select all date about  the user that own this id
		    	if($id != 0){ 
		    		$check = checkItem('C_ID','comments', $id);				
	            }
	            else{
	            	$check = 0;
	            }

	            // check if the id is valid then view form     
				if ($check > 0){		
					$stmt = $con->prepare(" DELETE FROM comments WHERE C_ID= :nid");
					$stmt->bindParam(":nid", $id);
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
	    

	    /*************************************************** Start Approve Page **************************************/
	    elseif($do == 'approve'){

	    	// Print Page name (Approve Page)
	    	echo "<h1 class='text-center'>Approve Comment</h1>";
	    	echo "<div class='container'>";
	    	
	    	// check if a get request existed and Is numeric value
	    	$id = ( isset($_GET['id']) && is_numeric($_GET['id']) )? intval($_GET['id']) : 0;
            
            // check if user is in database
	    	if($id != 0){

	    		$check = checkItem('C_ID','comments', $id);
	    	}
	    	else{

	    		$check = 0;
	    	}

	    	// check if the id is valid then Approve 
	    	if($check > 0){
	    		$stmt = $con->prepare("UPDATE comments SET Status=1 WHERE C_ID=?");
	    		$stmt->execute(array( $id));

	    		// success Message (Approve operation Done)
	    	    $msg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Comment Approve</div>";
	    		redirectHome($msg, 'back');
	    	}
	    	else{

	    		// Error Message (Approve operation not Done {User not Existed})
	    		$msg = "<div class alert alert-danger>This Comment is not exist</div>";
	    		redirectHome($msg);
	    	}
	    	echo "</div>";

	    }
	    /*************************************************** End Approve Page **************************************/

	    include $tp1 . 'footer.php';
	}
	else{

		header('Location: index.php');
		exit();
	}
	ob_end_flush();
?>

