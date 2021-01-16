<?php

/*
* Title function that echo the page title if page has variable $pageTitle 
* and echo default title for other pages
*/

function printTitle() {
	global $pageTitle;
	
	if(isset($pageTitle)){
		echo $pageTitle;
	}
	else{
		echo "Default";
	}
}




/*
 * Redirect Function v2.0 
 * accept parameters ($msg, $seconds)
 * $msg = echo the message [Error || Success || Warning]
 * $url = the link you want to redirect to
 * $seconds = seconds before redirecting
 *
 */

function redirectHome($msg, $url=null, $seconds = 3){
   
    if($url === null){
    	$url = 'index.php';
    }
    else{
    	if(isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== ''){

    		$url = $_SERVER['HTTP_REFERER'];	
    	}
    	else{

    		$url = 'index.php';
    	}

    	
    }
	echo "$msg";
	echo "<div class='alert alert-info'>You will be redirected to previous page after" .  $seconds . " seconds</div>";
	header("refresh:$seconds;url=$url");
	exit();
}



/* check Item function v1.0
 * check  whether Item in Database or not [fun accept parameters]
 * $select = the Item to select [ex: user, item]
 * $from   = the table to select from [ex: users, items]
 * $value  = the value of select [ex: Ahmed]
 * 
 */

function checkItem($select, $from, $value){
	
	global $con;
	$statment = $con->prepare("SELECT $select FROM $from WHERE $select= ?");
	$statment->execute(array($value));

	$count = $statment->rowCount();
	return $count;
}

/*
 * Count number of items function v1.0
 * function to count number of rows accept 2 arggument
 * item-> item to count
 * table-> table that item from 
 */

function countItems($item, $table) {
 	
 	global $con;
 	$stmt2 = $con->prepare("SELECT COUNT($item) FROM $table");
    $stmt2->execute();
    return $stmt2->fetchColumn();
}

/*
 * Get latest records function v1.0
 * function to get latest items from database [user, item , comments]
 *  select -> field that be selected
 *  table  -> table that selected items from
 *	order  -> Desc ordering
 *  limit  ->  limit of selected items
 *
 */ 


function getLatest($select, $table, $order, $limit = 5, $where="") {
	global $con;

	$getStmt = $con->prepare("SELECT $select FROM $table $where  ORDER BY $order DESC LIMIT $limit");
	$getStmt->execute();

	$rows = $getStmt->fetchAll();

	return $rows; 
}

/*
 * GetAll function v2.0
 * function to Get All TableRow From  Database 
 *
 */ 

function getAll($field, $table, $orderfield, $where= NULL, $and= NULL, $ordering='DESC') {

	global $con;
	$get = $con->prepare("SELECT $field FROM $table $where $and ORDER BY $orderfield $ordering");
	$get->execute();

	$rows = $get->fetchAll();

	return $rows; 
}









/*
 * Check  if User is not activated
 * Fun to check the Regstatus of the Use
*/

function checkUserStatus($user){

	global $con;
	$stmt = $con->prepare(" SELECT userName, regStatus 
							From   users 
							WHERE  userName = ? AND regStatus = 0
						  ");

	$stmt->execute(array($user));
	$status = $stmt->rowCount();		
	return $status;
}