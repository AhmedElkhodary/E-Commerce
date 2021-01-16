<?php 

	session_start();
	$pageTitle = 'Categories';
	include 'init.php'; 
?>

<div class="container">
	
	<?php
		if(isset($_GET['pageid']) && is_numeric($_GET['pageid'])){
			echo '<h1 class="text-center">Show Category Items<h1>';
			$allItems = getAll("*", "items", "Item_ID","WHERE CAT_ID = {$_GET['pageid']}", "AND Approve = 1", "DESC" );
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
			$msg =  "<div class='alert alert-danger'>ID is not existed or not numeric</div>";
            redirectHome($msg);
		}	
	?>

</div>







<?php 
	include $tp1 . 'footer.php'; 
?>