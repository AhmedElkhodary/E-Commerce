<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<title> <?php printTitle()?> </title>
		<link rel="stylesheet" href= "<?php echo $css; ?>bootstrap.min.css" />
		<link rel="stylesheet" href= "<?php echo $css; ?>font-awesome.min.css"/>
		<link rel="stylesheet" href= "<?php echo $css; ?>jquery-ui.css" />
		<link rel="stylesheet" href= "<?php echo $css; ?>jquery.selectBoxIt.css" />
		<link rel="stylesheet" href= "<?php echo $css; ?>front.css" />
		
	</head>
	<body>
  <div class="upper-bar">
    <div class="container">
      <?php
        if(isset($_SESSION['user'])) { ?>


            
            <div class="btn-group my-info pull-right ">
              <span class=" btn btn-default dropdown-toggle" data-toggle="dropdown">
                <?php echo $sessionUser;?>
                <span class="caret"></span>
              </span>
              <ul class="dropdown-menu">
                <li><a href="profile.php">MyProfile</a></li>
                <li><a href="newAd.php">New Item</a></li>
                <li><a href="profile.php#my-items">My Items</a></li>
                <li><a href="logout.php">Logout</a></li>
              </ul>
            </div>
            <img class=" img-thumbnail img-circle pull-right "src="<?php echo $avatars . $_SESSION['avatar_name'];?>"  />
            <?php
            
        }
        else {
          echo '<a href="login.php"><span class="pull-right">Login/signUp</span></a>';
        }
      ?>
    </div>  
  </div>  
	<nav class="navbar navbar-inverse">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-nav" aria-expanded="false">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="index.php">Homepage</a>
    </div>
    <div class="collapse navbar-collapse" id="app-nav">
      <ul class="nav navbar-nav navbar-right">
      <?php
          $mycats = getAll( "*", "categories", "ID", "WHERE Parent = 0", "", "ASC"); 
          foreach ($mycats as $cat) {
              echo "<li><a href='categories.php?pageid=" .  $cat['ID'] . "'>". $cat['Name'] . "       </a>
                   </li>";
          }
      ?>  
      </ul>
    </div>  
  </div>
</nav>	
	

