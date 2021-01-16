<?php


/********************************************************************************
 * > - Categories Page
 *	 - You can Add | Edit | Delete category from here
 *
 *********************************************************************************
 */
	ob_start(); // Output Buffering Start
	session_start();
	$pageTitle = 'Categories';

	if (isset($_SESSION['userName'])){

		
	    include 'init.php';
      
	    $do = ( isset( $_GET['do'] ) ?  $_GET['do']  :  'manage');
        
        /************************************************ Start Manage Page ***********************************************/
	    if ($do == 'manage'){

	    	$sort = 'ASC';
	    	$sort_arr = array('ASC','DESC');
	    	if (isset($_GET['sort']) && in_array($_GET['sort'], $sort_arr)){

	    		$sort = $_GET['sort'];
	    	}
	    	// Select Categories from database
	    	$stmt = $con->prepare("SELECT * FROM Categories WHERE Parent = 0 ORDER BY Ordering $sort ");
	    	$stmt->execute();
	    	$rows = $stmt->fetchAll();
	    	?>
	    	<h1 class="text-center">Manage Categories</h1>
	    	<div class="container categories">
	    		<div class="panel panel-default">
	    			<div class="panel-heading">
	    				Manage categories
	    				<div class="ordering pull-right">
	    					Ordering:
	    					<a class="<?php if($sort == 'ASC'){echo "active";}?>" href="?sort=ASC">ASC</a> |
	    					<a class="<?php if($sort == 'DESC'){echo "active";}?>" href="?sort=DESC">DESC</a>
	    				</div>
	    			</div>
	    			<div class="panel-body">
	    				<?php
	    					foreach ($rows as $row) {
	    						echo "<div class='cat'>";
	    							echo "<div class='hidden-button'>";
	    								echo"<a href='categories.php?do=edit&id="  .$row['ID'] . "'class='btn btn-xs btn-primary'><i class='fa fa-edit'></i> Edit</a>";
	    								echo"<a href='categories.php?do=delete&id=" .$row['ID'] . "'class=' confirm btn btn-xs btn-danger'><i class='fa fa-close'></i> Delete</a>";
	    							echo "</div>";
		    						echo "<h3>"    			     . $row['Name']          . "</h3>";
		    						echo "<p>";  if($row['Description'] == ' ') {echo "Empty description";} else {echo $row['Description'];}  echo "</p>";
		    						if ($row['Visibility']    == 1) {echo "<span class='visibility'><i class='fa fa-eye'></i>Hidden</span>";}
		    						if ($row['Allow_Comment'] == 1) {echo "<span class='commenting'><i class='fa fa-close'></i>Comment Disable</span>";}
		    						if ($row['Allow_Ads']     == 1) {echo "<span class='advertises'><i class='fa fa-close'></i>Ads Disable</span>";}
	    						
	    							// Child Category
		    						$childcats = getAll( "*", "categories", "ID", "WHERE Parent = {$row['ID']}", "", "ASC"); 
							        if(!empty($childcats)){
							        	echo "<h4 class'child-head'>Children Categories</h4>";
							        	echo "<ul class='list-unstyled child-cats'>";
								        foreach ($childcats as $c) {
								            echo "<li>
								            		<a href='categories.php?do=edit&id=" . $c['ID']. "'> ". $c['Name'] . "</a>
								            		<a href='categories.php?do=delete&id=" .$c['ID'] . "'class=' child-del confirm'>Delete</a>
								            	  </li>";  
								        }
								        echo "</ul>";
							        }
							    echo "</div>";
							    echo "<hr>";
	    					}
	    				?>
	    			</div>
	    		</div>
	    		<a class=" add_cat btn btn-primary" href="categories.php?do=add"><i class="fa fa-plus"></i> Add New</a>
	    	</div>


	    	<?php

	    }
	    /************************************************ End Manage Page ***********************************************/


	    /************************************************* Start Add Page *************************************************/
	    elseif ($do == 'add') {

	    	?>
	    	<h1 class="text-center">Add New Category</h1>

		    <div class="container">
		    	<form class="form-horizontal" action="?do=insert" method="POST">

		   			<!-- Start CategoryName field -->
		   			<div class="form-group">
		   				<label class="col-sm-2 control-label">Name *</label>
		   				<div class="col-sm-10 col-md-6">
	    					<input type="text" name="name"  class="form-control" autocomplete="off"
	    					 placeholder="Enter CategoryName" required />
	    				</div>
	    			</div>
		    		<!-- End CategoryName field -->

		    		<!-- Start CategoryDescription field -->
		    		<div class="form-group">
		   				<label class="col-sm-2 control-label">Description </label>
		   				<div class="col-sm-10 col-md-6">
		   					<textarea name="description" class="form-control" placeholder="Enter Description"> </textarea>	
		    			</div>
		    		</div>
	    			<!-- End CategoryDescription field -->

	    			<!-- Start Ordering field -->
	    	   		<div class="form-group">
		    			<label class="col-sm-2 control-label">Ordering </label>
		    			<div class="col-sm-10 col-md-6">
		   					<input type="text" name="ordering" class="form-control" placeholder="Enter Ordering" />
		    			</div>
		    		</div>
		    		<!-- End Ordering field -->
		    		
		    		<!-- Start Category Type -->
		    		<div class="form-group">
		    			<label class="col-sm-2 control-label">Category Type </label>
		    			<div class="col-sm-10 col-md-6">
		    				<select  name="parent" >
	    						<option value="0">None</option>
	    						<?php
	    							$cats = getAll("ID, Name", "categories", "ID", "WHERE Parent = 0", "", "ASC");
	    							foreach ($cats as $cat) {
	    								echo "<option value='" . $cat['ID'] . "'>" . $cat['Name']  . "</option>";
	    							}
	    						?>
	    					</select>
		    			</div>
		    		</div>
		    		<!-- End   Category Type -->

		    		<!-- Start Visibility field -->
		    		<div class="form-group">
		    			<label class="col-sm-2 control-label">Visible </label>
		    			<div class="col-sm-10 col-md-6">
		   					<div>
		   						<input type="radio" id="div-yes" name="visibility" value="0" checked/>
		   						<label for="div-yes">Yes</label>
		   					</div>
		   					<div>
		   						<input type="radio" id="div-no" name="visibility" value="1" />
		   						<label for="div-no">No</label>
		   					</div>
		   				</div>
		    		</div>
		    		<!-- End Visibility field -->

		    		<!-- Start Allow_Comment field -->
		    		<div class="form-group">
		    			<label class="col-sm-2 control-label">Comments </label>
		    			<div class="col-sm-10 col-md-6">
		   					<div>
		   						<input type="radio" id="com-yes" name="allow_comment" value="0" checked/>
		   						<label for="com-yes">Yes</label>
		   					</div>
		   					<div>
		   						<input type="radio" id="com-no" name="allow_comment" value="1" />
		   						<label for="com-no">No</label>
		   					</div>
		   				</div>
		    		</div>
		    		<!-- End Allow_Comment field -->
		    		
		    		<!-- Start Allow_Ads field -->
		    		<div class="form-group">
		    			<label class="col-sm-2 control-label">Ads </label>
		    			<div class="col-sm-10 col-md-6">
		   					<div>
		   						<input type="radio" id="ads-yes" name="allow_ads" value="0" checked/>
		   						<label for="ads-yes">Yes</label>
		   					</div>
		   					<div>
		   						<input type="radio" id="ads-no" name="allow_ads" value="1" />
		   						<label for="ads-no">No</label>
		   					</div>
		   				</div>
		    		</div>
		    		<!-- End Allow_Ads field -->

		    		

		    		<!-- Start submit field -->	    			
		   			<div class="form-group">
		   				<div class="col-sm-offset-2 col-sm-10">
		   					<input type="submit" value="Add Category" class="btn btn-primary" />
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
	    	echo "<h1 class='text-center'>Insert Category</h1>";
            echo "<div class= 'container'>"; 
	    	
	    	// check the request is POST
	    	if($_SERVER['REQUEST_METHOD'] == 'POST'){	 
	    		
				// Get Variables from the form
 				$name 		    = $_POST['name'];
				$description 	= $_POST['description'];
				$ordering       = $_POST['ordering'];
				$visibility  	= $_POST['visibility'];
				$allow_comment  = $_POST['allow_comment'];
				$allow_ads  	= $_POST['allow_ads']; 
				$parent  	    = $_POST['parent'];
				
				// Validate the form
				if(empty($name)){
					$msg =  "<div class='alert alert-danger'>CategoryName can't be <strong>empty</strong></div>";
					redirectHome( $msg,'back');
				}

				// if no existed error
                else{

                	//check if the user is existed in database
                	$check = checkItem("Name","categories",$name);
                	if ($check == 0){
                		
                		//Insert userInfo into database
                		$stmt = $con->prepare("INSERT INTO categories(  Name,  Description, Parent , Ordering,  Visibility,  Allow_Comment,  Allow_Ads)
                						                      VALUES ( :nname, :ndescription, :nparent ,:nordering, :nvisibility, :nallow_comment, :nallow_ads)");
                 		
                 		$stmt->execute(array(
                 			'nname'          => $name,
                 			'ndescription'   => $description,
                 			'nparent'        => $parent,
                 			'nordering'      => $ordering,
                 			'nvisibility'    => $visibility,
                 			'nallow_comment' => $allow_comment,
                 			'nallow_ads'     => $allow_ads,
                 		));

                 		// Echo success Message
            	    	$msg =  "<div class='alert alert-success'>" . $stmt->rowCount() . " Record inserted</div>";
                		redirectHome($msg,'back');
                	}
                	else{
                		$msg = "<div class='alert alert-danger'>Sorry Category is Existed</div>";
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
	    /************************************************* End Insert Page *************************************************/
	   





	    /************************************************* Start Edit Page *************************************************/
	    elseif ($do == 'edit') {
	    	echo"<h1 class='text-center'>Edit Categories</h1>";

	    	// check if a get request existed and Is numeric value 
	    	$catid = ( isset($_GET['id']) && is_numeric($_GET['id']) )? intval($_GET['id']): 0;

	    	
	    	
	    	// query to get all details about  the category that own id 
			$stmt = $con->prepare(" SELECT  * From categories   WHERE ID =?   LIMIT 1");
			$stmt->execute(array($catid));
			$row = $stmt->fetch();
			$count = $stmt->rowCount();
            
            // check if the id is valid then view form     
			if ($count > 0){
		    	?>
		    	<div class="container">
		    		<form class="form-horizontal" action="?do=update" method="POST">
		    			<input type="hidden" name="id" value="<?php echo $row['ID'] ?>"/>
		    			<!-- Start Name field -->
		    			<div class="form-group">
		    				<label class="col-sm-2 control-label">Name *</label>
		    				<div class="col-sm-10 col-md-6">
		    					<input type="text" name="name"  class="form-control" value="<?php echo $row['Name']?>" autocomplete="off" required />
		    				</div>
		    			</div>
		    			<!-- End Name field -->
		    			<!-- Start Description field -->
		    			<div class="form-group">
		    				<label class="col-sm-2 control-label">Description </label>
		    				<div class="col-sm-10 col-md-6">
		    					<input type="text" name="description" class="form-control" value="<?php echo $row['Description'] ?>" />
		    				</div>
		    			</div>
		    			<!-- End Description field -->

		    			<!-- Start Ordering field -->
		    			<div class="form-group">
		    				<label class="col-sm-2 control-label">Ordering </label>
		    				<div class="col-sm-10 col-md-6">
		    					<input type="text" name="ordering" class="form-control" value="<?php echo $row['Ordering'] ?>"/>
		    				</div>
		    			</div>
		    			<!-- End Ordering field -->

			    		<!-- Start Category Type -->
			    		<div class="form-group">
			    			<label class="col-sm-2 control-label">Category Type </label>
			    			<div class="col-sm-10 col-md-6">
			    				<select  name="parent" >
			    					<option value="0">None</option>
		    						<?php
		    							$parentId = $row['Parent'];
		    							$cats = getAll("ID, Name", "categories", "ID", "WHERE Parent = 0", "", "ASC");
		    							foreach ($cats as $cat) {
		    								
		    								if($cat['ID'] == $parentId){
		    									echo "<option selected value='" . $cat['ID'] . "'>" . $cat['Name']  . "</option>";
		    								}
		    								else{
		    									echo "<option  value='" . $cat['ID'] . "'>" . $cat['Name']  . "</option>";
		    								}

		    							}
		    						?>
		    					</select>
			    			</div>
			    		</div>
			    		<!-- End   Category Type -->

		    			<!-- Start Visibility field -->
		    			<div class="form-group">
		    				<label class="col-sm-2 control-label">Visibile </label>
		    				<div class="col-sm-10 col-md-6">
		    					<div>
			    					<input id="div-yes" type="radio" name="visibility" value="0" <?php if($row['Visibility'] == 0) {echo 'checked';} ?> />
			    					<label for="div-yes">YES</label>
		    					</div>
		    					<div>
			    					<input id="div-no" type="radio" name="visibility" value="1"  <?php if($row['Visibility'] == 1) {echo 'checked';} ?> />
			    					<label for="div-no">NO</label>
		    					</div>
		    				</div>
		    			</div>
		    			<!-- End Visibility field -->
		    			<!-- Start Allow_Comment field -->
			    		<div class="form-group">
			    			<label class="col-sm-2 control-label">Comments </label>
			    			<div class="col-sm-10 col-md-6">
			   					<div>
			   						<input type="radio" id="com-yes" name="allow_comment" value="0" <?php if($row['Allow_Comment'] == 0) {echo 'checked';} ?> />
			   						<label for="com-yes">Yes</label>
			   					</div>
			   					<div>
			   						<input type="radio" id="com-no" name="allow_comment" value="1"  <?php if($row['Allow_Comment'] == 1) {echo 'checked';} ?> />
			   						<label for="com-no">No</label>
			   					</div>
			   				</div>
			    		</div>
		    			<!-- End Allow_Comment field -->
		    		
			    		<!-- Start Allow_Ads field -->
			    		<div class="form-group">
			    			<label class="col-sm-2 control-label">Ads </label>
			    			<div class="col-sm-10 col-md-6">
			   					<div>
			   						<input type="radio" id="ads-yes" name="allow_ads" value="0"  <?php if($row['Allow_Ads'] == 0) {echo 'checked';} ?> />
			   						<label for="ads-yes">Yes</label>
			   					</div>
			   					<div>
			   						<input type="radio" id="ads-no" name="allow_ads" value="1"   <?php if($row['Allow_Ads'] == 1) {echo 'checked';} ?> />
			   						<label for="ads-no">No</label>
			   					</div>
			   				</div>
			    		</div>
			    		<!-- End Allow_Ads field -->


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
	    /************************************************* End Edit Page *************************************************/


	    /************************************************* Start Update Page *************************************************/
	    elseif ($do == 'update') {
	 	 
	 	   	echo "<h1 class='text-center'>Update Page</h1>";
	    	echo "<div class='container'>";

	    		// check if the request is 'POST'
	    		if($_SERVER['REQUEST_METHOD'] == 'POST'){

	    			$id 				=  $_POST['id'];
	    			$name 				=  $_POST['name'];
	    			$description        =  $_POST['description'];
	    			$ordering     		=  $_POST['ordering'];
	    			$parent 			=  $_POST['parent'];
	    			$visibile    		=  $_POST['visibility'];
	    			$allow_comment      =  $_POST['allow_comment'];
	    			$allow_ads 			=  $_POST['allow_ads'];

	    			
	    			// Validate The Form
	    			if(empty($name)){
	    				$msg = "<div class='alert alert-danger'>Name can not be <strong> Empty </strong> </div>";
	    				redirectHome($msg, 'back');
	    			}



	    			// Update data to Database
	    			$stmt = $con->prepare("UPDATE categories SET Name = ?, Description = ?, Ordering = ?, Parent = ?, Visibility = ?, Allow_Comment = ?, Allow_Ads = ? 
	    								   WHERE ID = ?  ");
	    			$stmt->execute(array( $name, $description, $ordering, $parent, $visibile, $allow_comment, $allow_ads, $id));

	    			// Echo success Message
            	    $msg = "<div class='alert alert-success'>" . $stmt->rowCount() . " Record updated</div>";
                	redirectHome($msg, 'back');


	    		}

	    		// if the the request is not 'POST'
	    		else{
	    			$msg ="<div class='alert alert-danger'>Error you can't access this page directly</div>";
	    			redirectHome($msg);
	    		}

	    	echo "</div>";


	    }
        /************************************************* End Update Page *************************************************/


        /************************************************* Start Delete Page *************************************************/
	    elseif ($do == 'delete') {
	    	echo"<h1 class='text-center'>Delete Categories</h1>";
	    	echo"<div class='container'>";

		    	// check if a get request existed and Is numeric value 
		    	$id = (isset($_GET['id']) && is_numeric($_GET['id']))?  intval($_GET['id']) :  0 ;
		    	

		    	if($id != 0){
		    		$check = checkItem('ID', 'categories', $id);
		    	}
		    	else{ $check = 0;}

		    	// if the category is exsited in database
		        if ($check > 0){
		           	$stmt = $con->prepare("DELETE FROM categories WHERE ID= :nid ");
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

	    	echo"</div>";

	    	
	    }
	    /************************************************* End Delete Page *************************************************/
	    
	    include $tp1 . 'footer.php';
	}
	else{
		header('Location: index.php');
		exit();
	}
	ob_end_flush();    

?>	