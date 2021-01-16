<?php


/********************************************************************************
 * > Items Page
 *	 - You can make all operations on all Items from Here
 *
 *********************************************************************************
 */
	ob_start(); // Output Buffering Start
	session_start();
	$pageTitle = 'Items';

	if (isset($_SESSION['userName'])){

		
	    include 'init.php';

	    $do = ( isset( $_GET['do'] ) ?  $_GET['do']  :  'manage');

	    /************************************************ Start Manage Page ***********************************************/
	    if ($do == 'manage'){

	   
	    	// Select users from database
	    	$stmt = $con->prepare("SELECT 
	    								    items.*, categories.Name AS category_name, users.userName AS User_Name
                                    FROM   
                                            items INNER JOIN categories INNER JOIN users
                                    ON 
                                            categories.ID = items.Cat_ID AND users.userId = items.Member_ID
                                    ORDER BY
                                 			Item_ID DESC
                                  ");
	    	$stmt->execute();
	    	$rows = $stmt->fetchAll();


	    	?>
	    	<h1 class="text-center">Manage Items</h1>
			<div class="container">
				<div class="table-responsive">
					<table class="main-table table table-bordered">
						<tr>
							<td>#ID</td>
							<td>Name</td>
							<td>Description</td>
							<td>Price</td>
							<td>Category</td>
							<td>Member</td>
							<td>Add Date</td>
							<td>Control</td>
						</tr>
						<?php
							foreach ($rows as $row) {
								echo "<tr>";
									echo "<td>" . $row['Item_ID']        . "</td>";
									echo "<td>" . $row['Name']           . "</td>";
									echo "<td>" . $row['Description']    . "</td>";
									echo "<td>" . $row['Price']          . "</td>";
									echo "<td>" . $row['category_name']  . "</td>";
									echo "<td>" . $row['User_Name']      . "</td>";
									echo "<td>" . $row['Add_Date']       . "</td>";
									echo '<td> <a href="items.php?do=edit&id=' .$row['Item_ID'] . '" class="btn btn-success"><i class="fa fa-edit"></i> Edit</a>
									           <a href="items.php?do=delete&id=' .$row['Item_ID'] . '" class="btn btn-danger confirm"><i class="fa fa-close"></i> Delete</a>';
									           if( $row['Approve'] == 0){
									     	       echo '<a href="items.php?do=approve&id=' . $row['Item_ID'] . '" class="btn btn-info activate"><i class="fa fa-check"></i> Approve</a>' ;
									            }  
									echo "</td>";
								echo "</tr>";
							}
						?>
					</table> 
					
				</div>
				<a href="items.php?do=add" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> New Item</a>
			</div>
	    	<?php
	    }
	    /************************************************ End Manage Page ***********************************************/


	   
	    /*********************************************** Start ADD Page ******************************************************/
	    elseif ($do == 'add') {
	    	?>
	    	<h1 class="text-center">Add New Item</h1>
	    	<div class="container">
	    		<form class="form-horizontal" action="?do=insert" method="POST">
	    			
	    			<!-- Start ItemName field -->
	    			<div class="form-group ">
	    				<label class="col-sm-2 control-label">Name*</label>
	    				<div class="col-sm-10 col-md-6">
	    					<input type="text" name="name" class="form-control" 
	    					 placeholder="Enter Item Name" required />
	    				</div>
	    			</div>
	    			<!-- End ItemName field -->

	    			<!-- Start ItemDescription field -->
		    		<div class="form-group">
		   				<label class="col-sm-2 control-label">Description*</label>
		   				<div class="col-sm-10 col-md-6">
		   					<textarea name="description" class="form-control" placeholder="Enter Description"></textarea>	
		    			</div>
		    		</div>
	    			<!-- End ItemDescription field -->

	    			<!-- Start ItemPrice field -->
	    			<div class="form-group ">
	    				<label class="col-sm-2 control-label">Price*</label>
	    				<div class="col-sm-10 col-md-6">
	    					<input type="text" name="price" class="form-control" autocomplete="off"
	    					 placeholder="Enter Item Price" />
	    				</div>
	    			</div>
	    			<!-- End ItemPrice field -->

	    			<!-- Start Item Country_Made field -->
	    			<div class="form-group ">
	    				<label class="col-sm-2 control-label">Country_Made*</label>
	    				<div class="col-sm-10 col-md-6">
	    					<input type="text" name="country" class="form-control" autocomplete="off"
	    					 placeholder="Enter Item Country_Made" />
	    				</div>
	    			</div>
	    			<!-- End Item Country_Made field -->


	    			<!-- Start Item Status field -->
	    			<div class="form-group ">
	    				<label class="col-sm-2 control-label">Status*</label>
	    				<div class="col-sm-10 col-md-6">
	    					<select  name="status" >
	    						<option value="0"  selected></option>
	    						<option value="1">New</option>
	    						<option value="2">Like New</option>
	    						<option value="3">Used</option>
	    						<option value="4">Old</option>
	    					</select>
	    				</div>
	    			</div>
	    			<!-- End Item Status field -->

	    			<!-- Start Members field -->
	    			<div class="form-group ">
	    				<label class="col-sm-2 control-label">Member</label>
	    				<div class="col-sm-10 col-md-6">
	    					<select  name="member" >
	    						<option value="0"></option>
	    						<?php
	    							$users = getAll("userId, userName" , "users", "userId", "WHERE groupId = 0" , "AND regStatus = 1 ", "ASC");
	    							foreach ($users as $user) {
	    								echo "<option value='" . $user['userId'] . "'>" . $user['userName']  . "</option>";
	    							}
	    						    ?>
	    					</select>
	    				</div>
	    			</div>
	    			<!-- End Members field -->


	    			<!-- Start Categories field -->
	    			<div class="form-group ">
	    				<label class="col-sm-2 control-label">Category</label>
	    				<div class="col-sm-10 col-md-6">
	    					<select  name="category" >
	    						<option value="0"></option>
	    						<?php
	    							$cats = getAll("ID, Name" , "categories", "ID", "WHERE Parent = 0" , "", "ASC");
	    							foreach ($cats as $cat) {
	    								echo "<option value='" . $cat['ID'] . "'>" . $cat['Name']  . "</option>";
	    								$childCats = getAll("ID, Name", "Categories" , "ID" , "WHERE Parent = {$cat['ID']}" , "", "ASC"  );
	    								if(!empty($childCats)){
	    									foreach ($childCats as $child) {
	    										echo "<option value='" .$child['ID']. "'>..." .$child['Name']. "</option>";
	    									}
	    									
	    								}	
	    							}
	    						    ?>
	    					</select>
	    				</div>
	    			</div>
	    			<!-- End Categories field -->

	    			<!-- Start Tags field -->
	    			<div class="form-group ">
	    				<label class="col-sm-2 control-label">Tags</label>
	    				<div class="col-sm-10 col-md-6">
	    					<input type="text" name="tags" class="form-control" autocomplete="off"
	    					 placeholder="Enter Tags separated with comma (.)" />
	    				</div>
	    			</div>
	    			<!-- End Tags field -->

	    			
	    			<!-- Start submit field -->	    			
		   			<div class="form-group">
		   				<div class="col-sm-offset-2 col-sm-10">
		   					<input type="submit" value="Add Item" class="btn btn-primary btn-sm" />

		   				</div>
	    			</div>
	    			<!-- End submit field -->

	    		</form>
	    	</div>


	    	<?php
	    }
	    /*********************************************** End ADD Page ****************************/

	    /*********************************************** Start Insert Page ****************************/
	    elseif ($do == 'insert') {
	    	echo "<h1 class='text-center'>Insert Items</h1>";
            echo "<div class= 'container'>"; 
	    	
	    	// check the request is POST
	    	if($_SERVER['REQUEST_METHOD'] == 'POST'){	 
	    		
				// Get Variables from the form
 				$name        = $_POST['name'];
				$description = $_POST['description'];
				$price       = $_POST['price'];
				$country     = $_POST['country'];
				$status      = $_POST['status'];
				$member      = $_POST['member'];
				$cat         = $_POST['category'];
				$tags        = $_POST['tags'];

				// Validate the form
				$formErrors = array();
				
				if(empty($name)){
					$formErrors[] = "ItemName can't be <strong>empty</strong>";
				}
				if(empty($description)){
					$formErrors[] = "Description can't be <strong>empty</strong>";
				}
				if(empty($price)){
					$formErrors[] = "Price can't be <strong>empty</strong>";	
				}
				if(empty($country)){
					$formErrors[] = "Country can't be <strong>empty</strong>";
				}
				if($status === "0"){
					$formErrors[] = "You must choose the <strong>status</strong>";
				}
				if($member === "0"){
					$formErrors[] = "You must choose the <strong>Member</strong>";
				}
				if($cat === "0"){
					$formErrors[] = "You must choose the <strong>Categories</strong>";
				}



				// Loop into Error array and Echo it
				foreach ($formErrors as $error) {
					$msg = "<div class='alert alert-danger'>". $error ."</div>";
					redirectHome( $msg, 'back');
				}

			

				// check if no existed error
                if(empty($formErrors)){
                	//Insert userInfo into database
                	$stmt = $con->prepare("INSERT INTO items(Name, Description, Price, Country_Made, Status, Add_Date, Member_ID, Cat_ID, Tags)
                						       VALUES (:nname, :ndescription, :nprice, :ncountry, :nstatus, now(), :nmem, :ncat, :ntags )");
                 		
                 	$stmt->execute(array(
                 		'nname'        => $name,
                		'ndescription' => $description,
                		'nprice'       => $price,
                		'ncountry'     => $country,
                		'nstatus'      => $status,
                		'nmem'		   => $member,
                		'ncat'		   => $cat,
                		'ntags'		   => $tags
                 	));

                 	// Echo success Message
            	   	$msg =  "<div class='alert alert-success'>" . $stmt->rowCount() . " Record inserted</div>";
                	redirectHome($msg,'back');
                }
			}
			else{
				$msg = "<div class='alert alert-danger'>access denied You can't browse this page directly</div>";
				redirectHome( $msg,'back');
				}
			echo"</div>;";
	    }
	    /********************************************************************* End Insert Page ***************************************/
	    
	    /********************************************************************* Start Edit Page ***************************************/
	    elseif ($do == 'edit') {
	    	echo"<h1 class='text-center'>Edit Items</h1>";

	    	// check if a get request existed and Is numeric value 
	    	$itemid = ( isset($_GET['id']) && is_numeric($_GET['id']) )? intval($_GET['id']): 0;

	    	
	    	
	    	// query to get all details about  the item that own id 
			$stmt = $con->prepare(" SELECT  * From items   WHERE Item_ID =?   LIMIT 1");
			$stmt->execute(array($itemid));
			$row = $stmt->fetch();
			$count = $stmt->rowCount();
            
            // check if the id is valid then view form     
			if ($count > 0){
		    	?>
		    	<div class="container">
		    		<form class="form-horizontal" action="?do=update" method="POST">
		    			<input type="hidden" name="id" value="<?php echo $row['Item_ID'] ?>"/>
		    			
		    			<!-- Start Name field -->
		    			<div class="form-group">
		    				<label class="col-sm-2 control-label">Name*</label>
		    				<div class="col-sm-10 col-md-6">
		    					<input type="text" name="name"  class="form-control" value="<?php echo $row['Name']?>"  />
		    				</div>
		    			</div>
		    			<!-- End Name field -->

		    			<!-- Start Description field -->
		    			<div class="form-group">
		    				<label class="col-sm-2 control-label">Description* </label>
		    				<div class="col-sm-10 col-md-6">
		    					<input type="text" name="description" class="form-control" value="<?php echo $row['Description'] ?>" />
		    				</div>
		    			</div>
		    			<!-- End Description field -->

		    			<!-- Start Price field -->
		    			<div class="form-group">
		    				<label class="col-sm-2 control-label">Price* </label>
		    				<div class="col-sm-10 col-md-6">
		    					<input type="text" name="price" class="form-control" value="<?php echo $row['Price'] ?>"/>
		    				</div>
		    			</div>
		    			<!-- End Price field -->

		    			<!-- Start Country_Made field -->
		    			<div class="form-group">
		    				<label class="col-sm-2 control-label">Country_Made* </label>
		    				<div class="col-sm-10 col-md-6">
		    					<input type="text" name="country" class="form-control" value="<?php echo $row['Country_Made'] ?>"/>
		    				</div>
		    			</div>
		    			<!-- End Country_Made field -->


		    			<!-- Start Item Status field -->
		    			<div class="form-group ">
		    				<label class="col-sm-2 control-label">Status*</label>
		    				<div class="col-sm-10 col-md-6">
		    					<select  name="status" >
		    						<option value="1" <?php if($row['Status'] == "1") {echo "selected";} ?> >New</option>
		    						<option value="2" <?php if($row['Status'] == "2") {echo "selected";} ?> >Like New</option>
		    						<option value="3" <?php if($row['Status'] == "3") {echo "selected";} ?> >Used</option>
		    						<option value="4" <?php if($row['Status'] == "4") {echo "selected";} ?> >Old</option>
		    					</select>
		    				</div>
		    			</div>
		    			<!-- End Item Status field -->

		    			<!-- Start Members field -->
		    			<div class="form-group ">
		    				<label class="col-sm-2 control-label">Member*</label>
		    				<div class="col-sm-10 col-md-6">
		    					<select  name="member" >
		    						<?php
		    							$stmt = $con->prepare("SELECT userId, userName FROM users");
		    							$stmt->execute();
		    							$users = $stmt->fetchAll();
		    							foreach ($users as $user) {
		    								echo "<option value='" . $user['userId'] . "'"; 
		    								if($row['Member_ID'] == $user['userId']) {echo "selected";} ;
		    								echo  ">" . $user['userName']  . "</option>";
		    							}
		    						?>
		    					</select>
		    				</div>
		    			</div>
		    			<!-- End Members field -->


		    			<!-- Start Categories field -->
		    			<div class="form-group ">
		    				<label class="col-sm-2 control-label">Category*</label>
		    				<div class="col-sm-10 col-md-6">
		    					<select  name="category" >
		    						<?php
		    							$stmt = $con->prepare("SELECT ID, Name FROM categories");
		    							$stmt->execute();
		    							$cats = $stmt->fetchAll();
		    							foreach ($cats as $cat) {
		    								echo "<option value='" . $cat['ID'] . "'";
		    								if($row['Cat_ID'] == $cat['ID']) {echo "selected";} ;
		    								echo ">" . $cat['Name']  . "</option>";
		    							}
		    						?>
		    					</select>
		    				</div>
		    			</div>
		    			<!-- End Categories field -->

		    			<!-- Start Tags field -->
		    			<div class="form-group ">
		    				<label class="col-sm-2 control-label">Tags</label>
		    				<div class="col-sm-10 col-md-6">
		    					<input type="text" name="tags" class="form-control" autocomplete="off"
		    					 placeholder="Enter Tags separated with comma (.)" />
		    				</div>
		    			</div>
	    				<!-- End Tags field -->


		

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
            	$msg =  "<div class='alert alert-danger'>Error ItemId not exist</div>";
            	redirectHome($msg);
            	echo "</div>";
            }
	    }
	    /********************************************************************* End Edit Page ******************************************************************/
	    


	    /********************************************************************* Start Update Page ******************************************************************/
	    elseif ($do == 'update') {

	    		echo "<h1 class='text-center'>Update Page</h1>";
	    		echo "<div class='container'>";

	    		// check if the request is 'POST'
	    		if($_SERVER['REQUEST_METHOD'] == 'POST'){

	    			$id 				=  $_POST['id'];
	    			$name 				=  $_POST['name'];
	    			$description        =  $_POST['description'];
	    			$price     		    =  $_POST['price'];
	    			$country    		=  $_POST['country'];
	    			$status   		    =  $_POST['status'];
	    			$member 			=  $_POST['member'];
	    			$category 			=  $_POST['category'];
	    			$tags 			    =  $_POST['tags'];

	    			
		    		// Validate The Form
	            	$formErrors = array();

					if(empty($name)){
						$formErrors[] = "ItemName can't be  <strong>Empty</strong>";
					}
					
					if(empty($description)){
						$formErrors[] = "Description can't be <strong>Empty</strong>";
					}

					if(empty($price)){
						$formErrors[] = "Price can't be  <strong>Empty</strong>";
					}
					
					if(empty($country)){
						$formErrors[] = "Country can't be <strong>Empty</strong>";
					}


					// Loop into Error array and Echo it
					foreach ($formErrors as $error) {
						$msg = "<div class='alert alert-danger'>". $error ."</div>";
						redirectHome($msg, 'back');
					}

	                // check if no existed error
	                if(empty($formErrors)){
	                	
	                	//Update the database with this info
	            	    $stmt = $con->prepare("UPDATE items SET  Name=?, Description=?, Price=?, Country_Made=?, Status=?, Member_ID=?, Cat_ID=?, Tags=? WHERE Item_ID=?");
	            	    $stmt->execute(array($name, $description, $price, $country, $status, $member, $category, $tags, $id ));
	                 	
	                 	// Echo success Message
	            	    $msg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Record updated</div>";
	                	redirectHome($msg, 'back');
	                }
	    		}

	    		// if the the request is not 'POST'
	    		else{
	    			$msg ="<div class='alert alert-danger'>Error you can't access this page directly</div>";
	    			redirectHome($msg);
	    		}

	    	echo "</div>";
	
	    }
	    /*********************************************************************  End Update Page ******************************************************************/

  
	    /*********************************************************************  Start Delete Page ******************************************************************/
	    elseif ($do == 'delete') {
	    	echo "<h1 class='text-center'> Delete Item </h1>";
	    	echo "<div class='container'>";

	    	//check if id is exsisted and  numeric or not
	    	$id = (isset($_GET['id']) && is_numeric($_GET['id']))? intval($_GET['id']) : 0;

	    	// if id is true 
	    	if($id != 0){
	    		$check = checkItem('Item_ID', 'items', $id);

	    		if($check == 1){
	    			$stmt = $con->prepare("DELETE FROM items WHERE Item_ID = :nid");
	    			$stmt->bindParam(":nid", $id);
	    			$stmt->execute();

	    			// success Message (Delete operation Done)
					$msg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Record Deleted</div>";
					redirectHome($msg , 'back');
	    		}

	    		else{
	    			$mag = "<div class='alert alert-danger'> This Item is not existed </div>";
	    			redirectHome($mag);
	    		}
	    	}


	    	else{
	    		$msg = "<div class='alert alert-danger'> id not found or not numeric</div>";
	    		redirectHome($msg);
	    	}



	    	echo "</div>";
	    }
	    /*********************************************************************   End Delete Page ******************************************************************/



	    /*********************************************************************   Start Approve Page ******************************************************************/
	    elseif ($do == 'approve') {

	    	// Print Page name (Activate Page)
	    	echo "<h1 class='text-center'>Approve Item</h1>";
	    	echo "<div class='container'>";
	    	
	    	// check if a get request existed and Is numeric value
	    	$id = ( isset($_GET['id']) && is_numeric($_GET['id']) )? intval($_GET['id']) : 0;
            
            
            // check if user is in database
	    	if($id != 0){

	    		$check = checkItem('Item_ID','items', $id);
	    	}
	    	else{

	    		$check = 0;
	    	}

	    	// check if the id is valid then Approve 
	    	if($check > 0){
	    		$stmt = $con->prepare("UPDATE items SET Approve=1 WHERE Item_ID=?");
	    		$stmt->execute(array( $id));

	    		// success Message (Activate operation Done)
	    	    $msg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Item Approved</div>";
	    		redirectHome($msg, 'back');
	    	}
	    	else{

	    		// Error Message (Activate operation not Done {User not Existed})
	    		$msg = "<div class alert alert-danger>This Item is not exist</div>";
	    		redirectHome($msg);
	    	}
	    	echo "</div>";
	    }
	    /*********************************************************************   End Approve Page ******************************************************************/ 

	    include $tp1 . 'footer.php';
	}
	else{
		header('Location: index.php');
		exit();
	}
	ob_end_flush();    

?>	