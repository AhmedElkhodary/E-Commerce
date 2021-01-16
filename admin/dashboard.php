<?php	
        ob_start(); // Output Buffering Start
	session_start();

	if (isset($_SESSION['userName'])){


	    $pageTitle = 'Dashboard';
	    include 'init.php';
	    /********************************************* Start Dashborad *************************************/
            
            $numUsers = 3;     // latestUsers number
            $latestUsers = getLatest('*', 'users', 'userId', $numUsers, 'WHERE groupId != 1');  // latest users array
            
            $numItems = 3;
            $latesItems  = getLatest('*', 'items', 'item_ID', $numItems); // lates Items array

            ?>

            <div class="container home-stats text-center">
                <h1>Dashboard</h1>
                <div class="row">
                        <div class="col-md-3">
                                <div class="stat st-members">
                                    <i class="fa fa-users"></i>
                                    <div class="info">
                                        Total Members
                                        <span><a href="members.php"><?php echo countItems('userId','users'); ?></a></span>
                                    </div>
                                </div>  
                    </div>
                    <div class="col-md-3">
                                <div class="stat st-pending">
                                    <i class="fa fa-user-plus"></i>
                                    <div class="info"> 
                                        Pending Members
                                        <span><a href="members.php?do=manage&page=pending"><?php echo checkItem('regStatus', 'users', 0); ?></a></span>
                                    </div>    
                                </div>  
                    </div>
                    <div class="col-md-3">
                                <div class="stat st-items">
                                    <i class="fa fa-tag"></i>
                                    <div class="info"> 
                                        Total Items
                                        <span><a href="items.php"><?php echo countItems('item_ID','items'); ?></a></span>
                                    </div>    
                                </div>  
                    </div>      
                    <div class="col-md-3">
                                <div class="stat st-comments">
                                    <i class="fa fa-comments"></i>
                                    <div class="info">
                                        Total Comments
                                        <span><a href="comments.php"><?php echo countItems('C_ID','comments'); ?></a></span>
                                    </div>   
                                </div>  
                    </div>                                      
                </div>
        </div>

        <div class="container latest">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <i class="fa fa-users"></i> Latest <?php echo $numUsers; ?> Registered Users
                            </div>
                            <div class="panel-body">
                                <ul class="list-unstyled latest-users">        
                                    <?php
                                        if(!empty($latestUsers)){
                                            foreach ($latestUsers as $user) {
                                                echo"<li>";
                                                    echo $user['userName'];
                                                    echo "<a href='members.php?do=edit&userid= ". $user['userId'] . "'>";
                                                        echo "<span class='btn btn-success pull-right'>";
                                                            echo "<i class='fa fa-edit'></i> Edit";
                                                            if( $user['regStatus'] == 0){
                                                                echo '<a href="members.php?do=activate&userid=' . $user['userId'] . '" class="btn btn-info pull-right activate"><i class="fa fa-key"></i> Activate</a>' ;
                                                            }
                                                        echo "</span>";
                                                    echo"</a>";                                
                                                echo"</li>";
                                            }
                                        }
                                        else{
                                            echo"<div>There is no Members to show</div>";
                                        }        
                                    ?>
                                </ul>
                            </div>
                        </div>            
                    </div>

                    <div class="col-sm-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <i class="fa fa-tag"></i> Latest <?php echo $numItems; ?> Items
                            </div>
                            <div class="panel-body">
                                <ul class="list-unstyled latest-users">        
                                    <?php
                                        if(!empty($latesItems)){
                                            foreach ($latesItems as $item) {
                                                echo"<li>";
                                                    echo $item['Name'];
                                                    echo "<a href='items.php?do=edit&id= ". $item['Item_ID'] . "'>";
                                                        echo "<span class='btn btn-success pull-right'>";
                                                            echo "<i class='fa fa-edit'></i> Edit";
                                                            if( $item['Approve'] == 0){
                                                                echo '<a href="items.php?do=approve&id=' . $item['Item_ID'] . '" class="btn btn-info pull-right activate"><i class="fa fa-check"></i> Approve</a>' ;
                                                            }
                                                        echo "</span>";
                                                    echo"</a>";                                
                                                echo"</li>";
                                            }         
                                        }
                                        else{
                                            echo"<div>There is no Items to show</div>";
                                        }        
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
        </div>  

        <?php
        /********************************************* End   Dashborad *************************************/
            include $tp1 . 'footer.php';
        }
	else{

		header('Location: index.php');
		exit();
	}
        ob_end_flush();
?>        