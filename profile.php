<?php

	session_start();
	$pageTitle = 'Profile';
	include 'init.php';
	if(isset($_SESSION['user'])){
		$getuser = $con->prepare("SELECT * FROM users WHERE userName = ?");
		$getuser->execute(array($sessionUser));
		$info = $getuser->fetch();
		$userId = $info['userId'];
?>	

<h1 class="text-center">MY Profile</h1>
		<div class="information block">
			<div class="container">
				<div class="panel panel-primary">
					<div class="panel-heading">My Information</div>
						<div class="panel-body">
							<ul class="list-unstyled">
								<li>
									<i class="fa fa-unlock-alt fa-fw"></i>
									<span>Name</span> :  <?php echo $info['userName'];?> 
								</li>
								<li>
									<i class="fa fa-envelope-o fa-fw"></i>
									<span>Email</span> :  <?php echo $info['email'];?>     
								</li>
								<li>
									<i class="fa fa-user fa-fw"></i>
									<span>FullName</span> :  <?php echo $info['fullName'];?> 
								</li>
								<li>
									<i class="fa fa-calendar fa-fw"></i>
									<span>Registered Date</span> :  <?php echo $info['date'];?>     
								</li>
								<li>
									<i class="fa fa-tags fa-fw"></i>
									<span>Fatorite Category</span> :                                
								</li>
							</ul>
							<a href="manage.php?do=edit" class="btn btn-default">Edit Info</a>
						</div>
				</div>
			</div>	
		</div>	



<div  id="my-items" class="my-advs block">
	<div class="container">
		<div class="panel panel-primary">
			<div class="panel-heading">My Items</div>
			<div class="panel-body">
				<?php

					$Items = getAll("*", "items", "Item_ID" ,"WHERE Member_ID = $userId" );
					if(!empty($Items)){
						foreach ($Items as $item) {
							echo "<div class='col-sm-6 col-md-3'>";
								echo "<div class='thumbnail item-box'>";
									if($item['Approve'] == 0){
										echo "<span class='approve-status'>Waiting Approval</span>";
									}
									echo "<span class='price-tag'>$" .$item['Price']. "</span>";
									echo "<img class='img-responsive' src='lock.jpg' alt='photo'/>";
									echo "<div class='caption'>";
										echo "<h3><a href='items.php?&id=" . $item['Item_ID'] . "'>" . $item['Name'] . "</a></h3>";
										echo "<p>" . $item['Description'] . "<p>";
										echo "<div class='add_date'>" . $item['Add_Date'] . "</div>";
									echo "</div>";
								echo "</div>";
							echo "</div>";
						}
				    }
				    else{
				    	echo "No Add To Show ". "<a href='newAd.php'>New Ad</a>";
				    }
				?>
			</div>
		</div>
	</div>	
</div>

<div class="my-comments block">
	<div class="container">
		<div class="panel panel-primary">
			<div class="panel-heading">Latest Comments</div>
			<div class="panel-body">
				<?php
					$comments = getAll("Comment", "comments", "C_ID", "WHERE user_id = $userId");
					
					if(!empty($comments)){
						foreach ($comments as $com) {
							echo "<p> " . $com['Comment'] . " </p>";
						}	
					}
					else{
						echo "No Comments";
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