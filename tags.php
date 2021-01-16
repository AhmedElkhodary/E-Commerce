<?php 

	session_start();
	$pageTitle = 'Tags';
	include 'init.php'; 
?>

<div class="container">
	
	<?php
		$tag = $_GET['name'];
		if(isset($tag)){
			echo '<h1 class="text-center"><?php echo "Show Items By Tags ";  ?><h1>';
			
			$allItems = getAll("*", "items", "Item_ID","WHERE Tags Like '%{$tag}%'", "AND Approve = 1", "DESC" );
			foreach ($allItems as $item) {
				echo "<div class='col-sm-6 col-md-3'>";
					echo "<div class='thumbnail item-box'>";
						echo "<span class='price-tag'>$" .$item['Price']. "</span>";
						echo "<img class='img-responsive' src='lock.jpg' alt='photo'/>";
						echo "<h3><a href='items.php?&id=" . $item['Item_ID'] . "'>" . $item['Name'] . "</a></h3>";
						echo "<p>" . $item['Description'] . "<p>";
						echo "<div class='add_date'>" . $item['Add_Date'] . "</div>";
					echo "</div>";
				echo "</div>";
			}
			
		}
		else{
			$msg =  "<div class='alert alert-danger'>Tag is not existed </div>";
            redirectHome($msg);
		}	
	?>

</div>







<?php 
	include $tp1 . 'footer.php'; 
?>