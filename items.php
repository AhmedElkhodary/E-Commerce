<?php

	session_start();
	$pageTitle = 'Items';
	include 'init.php';
	
  
	// check if a get request existed and Is numeric value 
	$itemid = ( isset($_GET['id']) && is_numeric($_GET['id']) )? intval($_GET['id']): 0;

	    	
	if($itemid == 0){
		echo "<div class='container'>";
            $msg =  "<div class='alert alert-danger'>ID is not existed or not numeric</div>";
            redirectHome($msg);
        echo "</div>";

	} 
	else{   	
		// query to get all details about  the item that own id 
		$stmt = $con->prepare(" SELECT  items.* ,categories.Name as catName, users.userName as userName 
								From items INNER JOIN categories INNER JOIN users 
								WHERE Item_ID =?   AND Approve = 1
								AND  users.userID  = items.Member_ID
								AND  categories.ID = items.Cat_ID
								LIMIT 1");
		$stmt->execute(array($itemid));
		$row = $stmt->fetch();
		$count = $stmt->rowCount();
        // check if the id is valid then view form     
		if ($count > 0){
		   	?>
		   	<h1 class='text-center'><?php echo $row['Name']; ?></h1>;
		   	<div class="container">
		   		<div class="row">
		   			<div class="col-md-3">
		   				<img class="img-responsive img-thumbnail" src="lock.jpg" alt=""/>
		   			</div>
		   			<div class="col-md-9 item-info">
		   				<h2><?php echo $row['Name'];?></h2>                 
			   		    <p><?php echo $row['Description'];?></p>              
		   				<ul class="list-unstyled">
			   				
			   				<li> 
			   					<i class="fa fa-calendar"></i>
			   					<span>Add Date: </span><?php echo $row['Add_Date'];?>
			   				</li>
			   				<li> 
			   					<i class="fa fa-money fa-fw"></i>
			   					<span>Price: </span>$<?php echo $row['Price'];?>        
			   				</li>
			   				<li> 
			   					<i class="fa fa-tree fa-fw"></i>
			   					<span>Made In: </span> <?php echo $row['Country_Made'];?>
			   				</li>
			   				<li> 
			   					<i class="fa fa-bars fa-fw"></i>
			   					<span>Category: </span><a href="categories.php?pageid=<?php echo $row['Cat_ID'];?>"><?php echo $row['catName'];?></a>   
			   				</li>
			   				<li>
			   					<i class="fa fa-user fa-fw"></i>
			   					<span>Add by: </span> <?php echo $row['userName'];?>
			   				</li>
			   				<li class="tags-items">
			   					<i class="fa fa-tags fa-fw"></i>
			   					<span>Tags: </span>
			   					<?php 
			   						$Tags = explode(',', $row['Tags']);
			   						foreach ($Tags as $tag) {
			   							$tag = str_replace(" ", "", $tag);
			   							
			   							$lowertag = strtolower($tag);
			   							if (isset($tag) && !empty($tag)){
			   								echo "<a href='tags.php?name={$tag}'>". $tag .  "</a>";
			   							}
			   							
			   						}
			   					?>
			   				</li>
		   				</ul>
		   			</div>
		   		</div>
		   		<hr class="custom-hr">

		   		<?php if(isset($_SESSION['user'])){?>
			   		<!-- Start Add Comment -->
			   		<div class="row">
			   			<div class="col-md-offset-3">
			   				<div class="add-comment">
			   					<h3>Add Your Comment</h3>
			   					<form action="<?php echo $_SERVER['PHP_SELF'] . '?id=' . $row['Item_ID'] ;?>" method="POST">
			   						<textarea name="comment" required></textarea>
			   						<input class="btn btn-primary" type="submit" value="Add Comment"  >
			   					</form>

			   					<?PHP
			   					if($_SERVER['REQUEST_METHOD'] == 'POST'){

			   						// Validate Comment field
			   						if(isset($_POST['comment'])){

			   							$filter_com = filter_var($_POST['comment'], FILTER_SANITIZE_STRING);
			   							$item_id = $row['Item_ID'];
			   							$user_id = $_SESSION['uid'];
			   							

			   							if(!empty($filter_com)){

			   								//Insert Commnet to Database
			   								$stmt = $con->prepare("INSERT INTO comments(Comment, Status, Comment_Date, item_Id, user_Id) 
			   									                        VALUES (:ncomment, 0, now(), :nitem_Id, :nuser_Id)");
			   							
			   								$stmt->execute(array(

			   									'ncomment'  => $filter_com,
			   									'nitem_Id'  => $item_id,
			   									'nuser_Id'  => $user_id,

			   								));
			   								if($stmt){
			   									echo "<div class='alert alert-success'>Comment Add</div>";
			   								}
			   							}
			   							else{
			   								echo "<div class='alert alert-danger'>Comment Empty!</div>";
			   							}
			   						}
			   					}
			   					?>
			   				</div>
			   			</div>
			   		</div>
			   		<!-- End Add Comment -->
		   		<?php 
		   		}
		   		else{
		   			echo "<a href='login.php'>Login</a> or <a href='login.php'>Register</a> to add comment";
		   		}
		   		?>	
		   		<hr class="custom-hr">
		   		<?php
		   				// Select Comments from database
	    				$stmt = $con->prepare(" SELECT 
	    												comments.*, users.userName AS User
											    FROM
													   	users INNER JOIN comments  
											    ON
														users.userId = comments.user_Id 
												
												where   item_Id = ? AND Status = 1

												ORDER BY 
														C_ID DESC 	 	
			                                    ");
				    	$stmt->execute(array($row['Item_ID']));
				    	$comments = $stmt->fetchAll();

				    	
		   		?>

		   		
		   		<?php
		   			foreach ($comments as $comment) {?>
		   				<div class="comment-box">
					    	<div class='row'>
						    	<div class='col-md-2 text-center'>
						    		<img class="img-responsive img-thumbnail img-circle center-block" src="user.png" alt=""/>
						    		<?php echo $comment['User'];?>		
						    	</div>
						    	<div class='col-md-10'>
						    		<p class="lead"><?php echo $comment['Comment'];?></p>		
						    	</div>		
					   		</div>
				   		</div>
				   		<hr class="custom-hr">
				   		<?php
				    }
		   		?>	


		   	</div>				
	    <?php 
        }

        // if any error or no id such that    
        else{
           	echo "<div class='container'>";
           	$msg =  "<div class='alert alert-danger'>ItemId not exist or Item not approved</div>";
           	redirectHome($msg, 'back');
           	echo "</div>";
        }
    }        
?>	

<?php
		
	include $tp1 . 'footer.php';
?>