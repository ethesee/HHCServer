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

// Get All info to QB
//----------------- Save the Family information into QB, and return the rid	
if(($_POST['fid66'])and($_POST['fid29'])){
	// Get data from url
	if($_POST['fid66']!=""){$theAgentId = htmlentities($_POST['fid66']);}else{$theAgentId ="";}
	if($_POST['fid14']!=""){$familyAddr = htmlentities($_POST['fid14']);}else{$familyAddr ="";}
	if($_POST['fid15']!=""){$familyCity = htmlentities($_POST['fid15']);}else{$familyCity ="";}
	if($_POST['fid44']!=""){$familyState = htmlentities($_POST['fid44']);}else{$familyState ="";}
	if($_POST['fid105']!=""){$familyZip = htmlentities($_POST['fid105']);}else{$familyZip ="";}
	if($_POST['fid45']!=""){$familyTel = htmlentities($_POST['fid45']);}else{$familyTel ="";}
	if($_POST['fid46']!=""){$family2Tel = htmlentities($_POST['fid46']);}else{$family2Tel ="";}
	if($_POST['fid58']!=""){$familyEmail = htmlentities($_POST['fid58']);}else{$familyEmail ="";}
	if($_POST['fid29']!=""){$thisFamilyName = htmlentities($_POST['fid29']);}else{$thisFamilyName ="";}

	// check for duplicate Family Name 
	// if found, plus 1 at end of the name until no duplicate		
	$FamilyCList="3.29";
	$FamilySList="3";

	$duplicatedf = true;
	$i = 1;
	while ($duplicatedf) {
		$FamilyQStr = "{'29'.EX.'".$thisFamilyName."'}";
		$FamilyQResults = $qb->DoQuery($FamilyDbid, $FamilyQStr, $FamilyCList, $FamilySList);
		if($FamilyQResults){
			$thisFamilyName = $thisFamilyName.$i;
			$i++;
		}else{
			$duplicatedf = false;
		}
	}			

	// This is not a duplicate, and get info from url
	// and save them into QB
	$familyName = $thisFamilyName; //fid-29

	// Add records to Family table
	$AddFamily = array(
		array(
			'fid'   => '66',
			'value' => $theAgentId),
		array(
			'fid'   => '29',
			'value' => $familyName),
		array(
			'fid'   => '14',
			'value' => $familyAddr),
		array(
			'fid'   => '15',
			'value' => $familyCity),
		array(
			'fid'   => '44',
			'value' => $familyState),
		array(
			'fid'   => '105',
			'value' => $familyZip),
		array(
			'fid'   => '45',
			'value' => $familyTel),
		array(
			'fid'   => '46',
			'value' => $family2Tel),
		array(
			'fid'   => '58',
			'value' => $familyEmail)
	); 

    $respFamily = $qb->AddRecord($FamilyDbid, $AddFamily, false);
	// error check
	if($qb->errorcode != 0){
		echo "<br>There was an error inserting the 'Family' record: ".$qb->errordetail;
		die();
	}
	// return the rid for related family
	$theFamilyId = $respFamily->rid;
	
		
//----------------- save the Primary parent info into Parent table	
	
	if($_POST['ppfid16']!=""){$ppRelated = htmlentities($_POST['ppfid16']);}else{$ppRelated = "";}
	if($_POST['ppfid12']!=""){$ppGender = htmlentities($_POST['ppfid12']);}else{$ppGender = "";}
	if($_POST['ppfid15']!=""){$ppDOB = htmlentities($_POST['ppfid15']);}else{$ppDOB = "";}
	if($_POST['ppfid253']!=""){$ppLives = htmlentities($_POST['ppfid253']);}else{$ppLives = "";}
	if($_POST['ppfid8']!=""){$ppfName = htmlentities($_POST['ppfid8']);}else{$ppfName = "";}
	if($_POST['ppfid10']!=""){$thisParentName = htmlentities($_POST['ppfid10']);}else{$thisParentName = "";}
	
	// check for the duplicate. If found one, plus 1 at the end of the name.
	$FPCList="3.8.9";
	$FPSList="3";	

	$duplicated = true;
	$i = 1;
	while ($duplicated) {
		$FPQStr = "{'8'.EX.'".$ppfName."'}AND{'10'.EX.'".$thisParentName."'}";
		$FPQResults = $qb->DoQuery($ParentDbid, $FPQStr, $FPCList, $FPSList);
		if($FPQResults){
			$thisParentName = $thisParentName.$i;
			$i++;
		}else{
			$duplicated = false;
		}
	}			

	// after non-duplicate name, form the aaray for the AddRecord().
	// Add records to Parent table
	$AddPParent = array(
		array(
			'fid'   => '449',
			'value' => "Primary"),
		array(
			'fid'   => '28',
			'value' => $theFamilyId),		
		array(
			'fid'   => '16',
			'value' => $ppRelated),
		array(
			'fid'   => '8',
			'value' => $ppfName),
		array(
			'fid'   => '10',
			'value' => $thisParentName),
		array(
			'fid'   => '12',
			'value' => $ppGender),
		array(
			'fid'   => '15',
			'value' => $ppDOB),
		array(
			'fid'   => '253',
			'value' => $ppLives)
					  );    
	$responsePP = $qb->AddRecord($ParentDbid, $AddPParent, false);
	// error check
	if($qb->errorcode != 0){
		echo "<br>There was an error inserting the primary 'Parent' record: ".$qb->errordetail;
		die();
	}
	
	$theParentId = (int)$responsePP->rid;	

	
//------------save the first child info into Child table
	if($_POST['fcfid6']!=""){$cfName = htmlentities($_POST['fcfid6']);}else{$cfName ="";}
	if($_POST['fcfid7']!=""){$thisChildName = htmlentities($_POST['fcfid7']);}else{$thisChildName ="";}
	if($_POST['fcfid25']!=""){$cmName = htmlentities($_POST['fcfid25']);}else{$cmName ="";}
	if($_POST['fcfid12']!=""){$cGender = htmlentities($_POST['fcfid12']);}else{$cGender ="";}
	if($_POST['fcfid10']!=""){$cDOB = htmlentities($_POST['fcfid10']);}else{$cDOB ="";}
	if($_POST['cfid391']!=""){$shelterWorker = htmlentities($_POST['cfid391']);}else{$shelterWorker ="";}
	if($_POST['cfid392']!=""){$agentPhone = htmlentities($_POST['cfid392']);}else{$agentPhone ="";}
	if($_POST['cfid1028']!=""){$agentEmail = htmlentities($_POST['cfid1028']);}else{$agentEmail ="";}	
	
	if($_POST['cfid501']!=""){$familyLan = htmlentities($_POST['cfid501']);}else{$familyLan ="";}
	if($_POST['cfid1029']!=""){$familyIncm = htmlentities($_POST['cfid1029']);}else{$familyIncm ="";}
	if($_POST['cfid1030']!=""){$familyAct = htmlentities($_POST['cfid1030']);}else{$familyAct ="";}	
		
	
	if($cGender=="M"){
		$MF = 1;
	}
	
	if($cGender=="F"){
		$MF = 2;
	}
					
	$cCList="3.6.7";
	$cSList="3";		
	$duplicated = true;
	$i = 1;
	// check for the duplicate. If found one, plus 1 at the end of the name.
	while ($duplicated) {
		$cQStr = "{'6'.EX.'".$cfName."'}AND{'7'.EX.'".$thisChildName."'}";
		$cQResults = $qb->DoQuery($ChildrenDbid, $cQStr, $cCList, $cSList);
		if($cQResults){
			$thisChildName = $thisChildName.$i;
			$i++;
		}else{
			$duplicated = false;
		}
	}		

	// after non-duplicate name, form the aaray for the AddRecord().
	$AddChild = array(
		array(
			'fid'   => '139',
			'value' => 1),
		array(
			'fid'   => '688',
			'value' => $MF),
		array(
			'fid'   => '6',
			'value' => $cfName),
		array(
			'fid'   => '25',
			'value' => $cmName),
		array(
			'fid'   => '7',
			'value' => $thisChildName),
		array(
			'fid'   => '10',
			'value' => $cDOB),
		array(
			'fid'   => '165',
			'value' => $theAgentId),
		array(
			'fid'   => '695',
			'value' => $theParentId),
		array(
			'fid'   => '391',
			'value' => $shelterWorker),
		array(
			'fid'   => '392',
			'value' => $agentPhone),
		array(
			'fid'   => '1028',
			'value' => $agentEmail),		
		array(
			'fid'   => '501',
			'value' => $familyLan),
		array(
			'fid'   => '1029',
			'value' => $familyIncm),
		array(
			'fid'   => '1030',
			'value' => $familyAct),
		array(
			'fid'   => '46',
			'value' => $theFamilyId)
	);    
	$respChild = $qb->AddRecord($ChildrenDbid, $AddChild, false);

	if($qb->errorcode != 0){
		echo "<br>There was an error inserting the first 'Child' record: ".$qb->errordetail;
		die();
	}
	
//-------- save the second parent and child
	
	if(($_POST['spfid10']!="")and($_POST['scfid7']!="")){
		// Add second parent   secondParent($theFamily)
		secondParent($theFamilyId);
		// Add second child
		secondChild($theFamilyId, $theParentId, $theAgentId);
	}	
		
}else{
	echo ("No family, no parent, and no child added. $_POST is not working!!!");
}



// -------------- save the second parent    

function secondParent($theFamily) {
	global $qb;
	global $ParentDbid;	

	if ((isset($_POST['spfid8']))and(isset($_POST['spfid10']))) {
		if($_POST['spfid16']!=""){$ppRelated = htmlentities($_POST['spfid16']);}else{$ppRelated = "";}
		if($_POST['spfid12']!=""){$ppGender = htmlentities($_POST['spfid12']);}else{$ppGender = "";}
		if($_POST['spfid15']!=""){$ppDOB = htmlentities($_POST['spfid15']);}else{$ppDOB = "";}
		if($_POST['spfid253']!=""){$ppLives = htmlentities($_POST['spfid253']);}else{$ppLives = "";}		
		if($_POST['spfid8']!=""){$ppfName = htmlentities($_POST['spfid8']);}else{$ppfName = "";}
		if($_POST['spfid10']!=""){$thisParentName = htmlentities($_POST['spfid10']);}else{$thisParentName = "";}				
				
		// check for the duplicate. If found one, plus 1 at the end of the name.
		$FPCList="3.8.10";
		$FPSList="3";	
		$duplicated = true;
		$i = 1;
		while ($duplicated) {
			$FPQStr = "{'8'.EX.'".$ppfName."'}AND{'10'.EX.'".$thisParentName."'}";
			$FPQResults = $qb->DoQuery($ParentDbid, $FPQStr, $FPCList, $FPSList);
			if($FPQResults){
				$thisParentName = $thisParentName.$i;
				$i++;
			}else{
				$duplicated = false;
			}
		}			

		// Add records to Parent table
		$AddPParent = array(
			array(
				'fid'   => '449',
				'value' => "Secondary"),
			array(
				'fid'   => '28',
				'value' => $theFamily),			
			array(
				'fid'   => '16',
				'value' => $ppRelated),
			array(
				'fid'   => '8',
				'value' => $ppfName),
			array(
				'fid'   => '10',
				'value' => $thisParentName),
			array(
				'fid'   => '12',
				'value' => $ppGender),
			array(
				'fid'   => '15',
				'value' => $ppDOB),
			array(
				'fid'   => '253',
				'value' => $ppLives)
		 );    

		$responseSP = $qb->AddRecord($ParentDbid, $AddPParent, false);

		if($qb->errorcode != 0){
			echo "<br>There was an error inserting the secomd 'Parent' record: ".$qb->errordetail;
			die();
		}

	//	$PPrid = (int)$response->rid;				
	}else{
		echo"The second parent name is incompleted.";
	}		
}	



//---------------- save the second child

function secondChild($theFamily, $theParent, $theAgent) {

	global $qb;

	global $ChildrenDbid;

	

	if ((isset($_POST['scfid6']))and(isset($_POST['scfid7']))) {

		if($_POST['scfid25']!=""){$cmName = htmlentities($_POST['scfid25']);}else{$cmName ="";}

		if($_POST['scfid12']!=""){$cGender = htmlentities($_POST['scfid12']);}else{$cGender ="";}

		if($_POST['scfid10']!=""){$cDOB = htmlentities($_POST['scfid10']);}else{$cDOB ="";}		

		if($_POST['scfid12']!=""){$cfName = htmlentities($_POST['scfid6']);}else{$cfName ="";}

		if($_POST['scfid10']!=""){$thisChildName = htmlentities($_POST['scfid7']);}else{$thisChildName ="";}

		if($_POST['cfid391']!=""){$shelterWorker = htmlentities($_POST['cfid391']);}else{$shelterWorker ="";}

		if($_POST['cfid392']!=""){$agentPhone = htmlentities($_POST['cfid392']);}else{$agentPhone ="";}

		if($_POST['cfid1028']!=""){$agentEmail = htmlentities($_POST['cfid1028']);}else{$agentEmail ="";}	

		if($_POST['cfid501']!=""){$familyLan = htmlentities($_POST['cfid501']);}else{$familyLan ="";}
		if($_POST['cfid1029']!=""){$familyIncm = htmlentities($_POST['cfid1029']);}else{$familyIncm ="";}
		if($_POST['cfid1030']!=""){$familyAct = htmlentities($_POST['cfid1030']);}else{$familyAct ="";}	

		

	if($cGender=="M"){

		$MF = 1;

	}

	

	if($cGender=="F"){

		$MF = 2;

	}

		

		$cCList="3.6.7";

		$cSList="3";		

		$duplicated = true;

		$i = 1;

		// check for the duplicate. If found one, plus 1 at the end of the name.

		while ($duplicated) {

			$cQStr = "{'6'.EX.'".$cfName."'}AND{'7'.EX.'".$thisChildName."'}";

			$cQResults = $qb->DoQuery($ChildrenDbid, $cQStr, $cCList, $cSList);

			if($cQResults){

				$thisChildName = $thisChildName.$i;

				$i++;

			}else{

				$duplicated = false;

			}

		}		

	
		// after non-duplicate name, form the aaray for the AddRecord().
		$AddChild = array(
			array(
                'fid'   => '139',
                'value' => 1),
            array(
                'fid'   => '688',
                'value' => $MF),
            array(
                'fid'   => '6',
                'value' => $cfName),
            array(
                'fid'   => '25',
                'value' => $cmName),
            array(
                'fid'   => '7',
                'value' => $thisChildName),
            array(
                'fid'   => '10',
                'value' => $cDOB),
			array(
                'fid'   => '165',
                'value' => $theAgent),
            array(
                'fid'   => '695',
                'value' => $theParent),
			array(
				'fid'   => '391',
				'value' => $shelterWorker),
			array(
				'fid'   => '392',
				'value' => $agentPhone),
			array(
				'fid'   => '1028',
				'value' => $agentEmail),
			array(
				'fid'   => '501',
				'value' => $familyLan),
			array(
				'fid'   => '1029',
				'value' => $familyIncm),
			array(
				'fid'   => '1030',
				'value' => $familyAct),
            array(
                'fid'   => '46',
                'value' => $theFamily)
        );    

        $respChild = $qb->AddRecord($ChildrenDbid, $AddChild, false);

    
        if($qb->errorcode != 0){
            echo "<br>There was an error inserting the second child record: ".$qb->errordetail;
            die();
        }

	}else{

		echo"No this child";

	}

}	




?>