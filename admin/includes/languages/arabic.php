<?php

 function lang($phrase) {
 	
 	static $lang = array(
 		'MESSAGE' => 'welcome arabic', 
 		'admin'   => 'arabic admin'
 	);

 	return $lang[$phrase];
 }

