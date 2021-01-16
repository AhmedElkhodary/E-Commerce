<?php

 function lang($phrase) {
 	
 	static $lang = array(

 		// Navbar Links
 		'HOME_ADMIN' => 'Home',
 		'CATEGORIES' => 'categories', 
 		'ITEMS'      => 'items',
 		'MEMBERS'    => 'members',
 		'STATISTICS' => 'statistics',
 		'COMMENTS' =>   'Comments',  
 		'LOGS'       => 'logs',
 	);

 	return $lang[$phrase];
 }

