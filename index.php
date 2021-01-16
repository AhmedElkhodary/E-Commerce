<?php

	session_start();
	$pageTitle = 'Homepage';
	include 'init.php';
?>

<div class="container">
	<h1 class="text-center"><?php echo "Homepage";  ?><h2>
	<?php
		foreach (getAll('*','items', 'Item_ID','WHERE Approve = 1') as $item) {
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
	?>

</div>
   
<?php
	include $tp1 . 'footer.php';
?>