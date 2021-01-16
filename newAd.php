<?php

	session_start();
	$pageTitle = 'New Ad';
	include 'init.php';
	if(isset($_SESSION['user'])){


	if ($_SERVER['REQUEST_METHOD'] == 'POST'){

		$formErrors = array();
		$name 	  = filter_var($_POST['name'],        FILTER_SANITIZE_STRING);
		$desc     = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
		$price    = filter_var($_POST['price'],       FILTER_SANITIZE_NUMBER_INT);
		$country  = filter_var($_POST['country'],     FILTER_SANITIZE_STRING);
		$status   = filter_vaR($_POST['status'],      FILTER_SANITIZE_NUMBER_INT);
		$category = filter_var($_POST['category'],    FILTER_SANITIZE_NUMBER_INT);
		$tags 	  = filter_var($_POST['tags'],        FILTER_SANITIZE_STRING);

		if(strlen($name) < 4){
			$formErrors[] = 'ItemTitle must be at least 4 characters!';
		}
		if(strlen($desc) < 10){
			$formErrors[] = 'Description is Short!';
		}
		if(empty($price)){
			$formErrors[] = 'Price Empty!';
		}
		if(strlen($country) < 3){
			$formErrors[] = 'Country Name is Short!';
		}
		if(empty($status)){
			$formErrors[] = 'Status Empty!';
		}
		if(empty($category)){
			$formErrors[] = 'Category Empty!';
		}

        
		if(empty($formErrors)){
        //Insert userInfo into database
            $stmt = $con->prepare("INSERT INTO items(Name, Description, Price, Country_Made, Status, Add_Date, Member_ID, Cat_ID, Tags)
       						       VALUES (:nname, :ndescription, :nprice, :ncountry, :nstatus, now(), :nmem, :ncat, :ntags )");
                 		
            $stmt->execute(array(
                 	'nname'        => $name,
                	'ndescription' => $desc,
                	'nprice'       => $price,
                	'ncountry'     => $country,
               		'nstatus'      => $status,
               		'nmem'		   => $_SESSION['uid'],
               		'ncat'		   => $category,
               		'ntags'		   => $tags
               	));

            // Echo success Message
            if($stmt){
          		$msg =  "Item Inserted"; 
            }   	
        }

	}		
?>	

<h1 class="text-center">Create New Item</h1>   

<div class="create-ad block">
	<div class="container">
		<div class="panel panel-primary">
			<div class="panel-heading">New Item</div>
			<div class="panel-body">
				<div class="row">
					
					<!-- Start Item Form-->
					<div class="col-md-8">
					
						<form class="form-horizontal" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
	    			
		    			<!-- Start ItemName field -->
		    			<div class="form-group ">
		    				<label class="col-sm-2 control-label">Name*</label>
		    				<div class="col-sm-10 col-md-8">
		    					<input pattern=".{4,}" title="Required more than 3 characters" type="text" name="name" class="form-control live-name" placeholder="Enter Item Name" required />
		    				</div>
		    			</div>
		    			<!-- End ItemName field -->

		    			<!-- Start ItemDescription field -->
			    		<div class="form-group">
			   				<label class="col-sm-2 control-label">Description*</label>
			   				<div class="col-sm-10 col-md-8">
			   					<textarea name="description" class="form-control live-desc" placeholder="Enter Description" required></textarea>	
			    			</div>
			    		</div>
		    			<!-- End ItemDescription field -->

		    			<!-- Start ItemPrice field -->
		    			<div class="form-group ">
		    				<label class="col-sm-2 control-label">Price*</label>
		    				<div class="col-sm-10 col-md-8">
		    					<input type="text" name="price" class="form-control live-price" autocomplete="off" placeholder="Enter Item Price" required />
		    				</div>
		    			</div>
		    			<!-- End ItemPrice field -->

		    			<!-- Start Item Country_Made field -->
		    			<div class="form-group ">
		    				<label class="col-sm-2 control-label">Country_Made*</label>
		    				<div class="col-sm-10 col-md-8">
		    					<input type="text" name="country" class="form-control" autocomplete="off"
		    					 placeholder="Enter Item Country_Made" required/>
		    				</div>
		    			</div>
		    			<!-- End Item Country_Made field -->


		    			<!-- Start Item Status field -->
		    			<div class="form-group ">
		    				<label class="col-sm-2 control-label">Status*</label>
		    				<div class="col-sm-10 col-md-8">
		    					<select  name="status" required >
		    						<option value="q"  selected ></option>
		    						<option value="1">New</option>
		    						<option value="2">Like New</option>
		    						<option value="3">Used</option>
		    						<option value="4">Old</option>
		    					</select>
		    				</div>
		    			</div>
		    			<!-- End Item Status field -->


		    			<!-- Start Categories field -->
		    			<div class="form-group ">
		    				<label class="col-sm-2 control-label">Category*</label>
		    				<div class="col-sm-10 col-md-8">
		    					<select  name="category" required>
		    						<option value=""></option>
		    						<?php
		    							$cats = getAll('*', 'Categories', 'ID');
		    							foreach ($cats as $cat) {
		    								echo "<option value='" . $cat['ID'] . "'>" . $cat['Name']  . "</option>";
		    							}
		    						    ?>
		    					</select>
		    				</div>
		    			</div>
		    			<!-- End Categories field -->

		    			<!-- Start Tags field -->
		    			<div class="form-group ">
		    				<label class="col-sm-2 control-label">Tags</label>
		    				<div class="col-sm-10 col-md-8">
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
					<!-- End Item Form-->

					<!-- Start View Item -->
					<div class="col-md-4">
						<div class="thumbnail item-box live-preview">
							<span class="price-tag">$0</span>
							<img class="img-responsive" src="lock.jpg" alt=""/>
							<div class="caption">
								<h3>Title</h3>
								<p>Description</p>
							</div>
						</div>
					</div>
					<!-- Start View Item -->

				</div>
				<?php
					if(!empty($formErrors)){
						foreach ($formErrors as $error) {
							echo "<div class='alert alert-danger'>" . $error .  "</div>";
						}
					}
					if(isset($msg)){
						echo "<div class='alert alert-success'>" . $msg .  "</div>";
					}
				?>
				
			</div>
		</div>
	</div>	
</div>




<?php
	}
	else{
		header('Location: login.php');
		exit();
	}	
	include $tp1 . 'footer.php';
?>