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

// Get Agency Data 
    $AgencyQStr = "{'6'.XEX.''}AND{'14'.EX.'Shelter'}";
    $AgencyCList="3.6";
    $AgencySList="6";
    $AgencyQResults = $qb->DoQuery($AgencyDbid, $AgencyQStr, $AgencyCList, $AgencySList);
		
	$agentNames = '{"records":[';
	for($i=0; $i < count($AgencyQResults); $i++) {
		if ($agentNames != '{"records":[') {$agentNames .= ",";}
		$agentNames .= '{"Name":"'  . $AgencyQResults[$i][6] .  '"}'; 
	}
	$agentNames .=']}';

	echo($agentNames);
	
	

?>