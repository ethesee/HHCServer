<?php 
error_reporting(E_ALL);

require_once('qb_new.php');
require_once('globals.php');


// v v v v v v v v v v v v v v v v v v v v v v v v v v v v v v  
//   L O G   I N T O   Q U I C K B A S E

$qb = new QuickBase();
$qb->apptoken = $appToken1;
$qb->authenticate($adminUser,$adminPwd);
if(!$qb) {
 echo "Error connecting to QuickBase";
}

//-------------- Check if there is a duplicate name in Family table, alert if yes.
if (isset($_GET['FamilyName'])) {
	$thisFamilyName = htmlentities($_GET['FamilyName']);
	// Get All Family Name 
	$FamilyQStr = "{'29'.EX.'".$thisFamilyName."'}";
	$FamilyCList="3.29";
	$FamilySList="3";
	$FamilyQResults = $qb->DoQuery($FamilyDbid, $FamilyQStr, $FamilyCList, $FamilySList);

	if(!$FamilyQResults){
		echo ('FALSE');
	}else{
		echo ('TRUE');
	}
				
}
	
?>