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

	if (isset($_POST['AgentName'])) {
		$thisAgent = htmlentities($_POST['AgentName']);
	//	echo $thisAgent;
	}else{
		$thisAgent = "";
		echo ("bad url!!!");
	}
	
// Get Agency Data Shelter
    $AgencyQStr = "{'6'.EX.'".$thisAgent."'}AND{'14'.EX.'Shelter'}";
    $AgencyCList="3.6.11.108.109";
    $AgencySList="6";
    $AgencyQResults = $qb->DoQuery($AgencyDbid, $AgencyQStr, $AgencyCList, $AgencySList);
	if($AgencyQResults) {
	//	$thisAgentInfo = '{"records":[';
		$thisAgentInfo = '{"AgentID":"'  . $AgencyQResults[0][3] .  '",';
		$thisAgentInfo .= '"Contact":"'  . $AgencyQResults[0][108] .  '"}'; 
	//	$thisAgentInfo .= '"AgentEmail":"'  . $AgencyQResults[0][109] .  '",';
	//	$thisAgentInfo .= '"AgentTel":"'  . $AgencyQResults[0][11] .  '"}'; 
	//	$thisAgentInfo .=']}';
		echo($thisAgentInfo);
	}else{	
		echo "Error fetch the data from QuickBase";
    }		

	
	

?>