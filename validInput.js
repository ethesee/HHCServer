// jQuery functions that handle the changes from the text box. 
// Test all enters, make sure they are valid against rules    
$(document).ready(function(){
	
$("#btnSubmit").click(function(){
	//Family info
	var agentID = $("#thisAgent").val();
	var shelterWorker = $("#shelterWorker").val();
	var agentPhone = $("#agentPhone").val();
	var agentEmail = $("#agentEmail").val();
	
	var familyName = $("#familyName").val();
	var familyAddr = $("#familyAddr").val();
	var familyCity = $("#familyCity").val();
	var familyState = $("#familyState").val();
	var familyZip = $("#familyZip").val();
	var familyTel = $("#familyTel").val();   // for 1st parent
	var family2Tel = $("#family2Tel").val();  // for 2nd parent
	var familyEmail = $("#familyEmail").val();	
	var familyLan = $("#familyLanguage").val(); // added field
	var familyIncm = $("#familyIncome").val();  // added field
	var familyAct = $("#familyActivity").val(); // added field
	//Parents info
	var PP_related = $("#PP_related").val();    
	var PP_fname = $("#PP_fname").val();
	var PP_lname = $("#PP_lname").val();
	var PP_gender = $("#PP_gender").val(); 
	var PP_DOB = $("#PP_DOB").val(); 
	var PP_lives = $( "#PP_lives" ).val();
	
	var SP_related = $("#SP_related").val();    
	var SP_fname = $("#SP_fname").val();
	var SP_lname = $("#SP_lname").val();
	var SP_gender = $("#SP_gender").val(); 
	var SP_DOB = $("#SP_DOB").val(); 
	var SP_lives = $( "#SP_lives" ).val();
	//Children info
	var FC_fname = $("#FC_fname" ).val(); 
	var FC_mname = $("#FC_mname").val();    
	var FC_lname = $("#FC_lname").val();
	var FC_gender = $("#FC_gender").val(); 
	var FC_DOB = $("#FC_DOB").val(); 
	
	var SC_fname = $("#SC_fname" ).val(); 
	var SC_mname = $("#SC_mname").val();    
	var SC_lname = $("#SC_lname").val();
	var SC_gender = $("#SC_gender").val(); 
	var SC_DOB = $("#SC_DOB").val(); 

	var warning = false;

	// check the data validation and act highlights
	if (agentID==''){            
			$("#selectedAgent").css("background-color", "yellow");
			warning = true;
		}else{
			$("#selectedAgent").css("background-color", "white");
		}
		
	if (shelterWorker==''){            
			$("#shelterWorker").css("background-color", "yellow");
			warning = true;
		}else{
			$("#shelterWorker").css("background-color", "white");
		}
		
	if (agentPhone==''){            
			$("#agentPhone").css("background-color", "yellow");
			warning = true;
		}else{
			$("#agentPhone").css("background-color", "white");
		}
		
	if (agentEmail==''){            
			$("#agentEmail").css("background-color", "yellow");
			warning = true;
		}else{
			$("#agentEmail").css("background-color", "white");
		}	
	
	if (familyName==''){            
			$("#familyName").css("background-color", "yellow");
			warning = true;
		}else{
			$("#familyName").css("background-color", "white");
		}
	// for 1st parent	
	if (familyTel==''){            
			$("#familyTel").css("background-color", "yellow");
			warning = true;
		}else{
			$("#familyTel").css("background-color", "white");
		}
		
	if (familyAddr==''){            
			$("#familyAddr").css("background-color", "yellow");
			warning = true;
		}else{
			$("#familyAddr").css("background-color", "white");
		}		
	if (familyCity==''){            
			$("#familyCity").css("background-color", "yellow");
			warning = true;
		}else{
			$("#familyCity").css("background-color", "white");
		}
	if (familyState==''){            
			$("#familyState").css("background-color", "yellow");
			warning = true;
		}else{
			$("#familyState").css("background-color", "white");
		}		
	if (familyZip==''){            
			$("#familyZip").css("background-color", "yellow");
			warning = true;
		}else{
			$("#familyZip").css("background-color", "white");
		}	
	
	if (PP_related==''){            
			$("#PP_related").css("background-color", "yellow");
			warning = true;
		}else{
			$("#PP_related").css("background-color", "white");
		}

	if(PP_fname=='' ){
			$("#PP_fname").css("background-color", "yellow");
			warning = true;
		}else{
			$("#PP_fname").css("background-color", "white");
		}

	if (PP_lname==''){            
			$("#PP_lname").css("background-color", "yellow");
			warning = true;
		}else{
			$("#PP_lname").css("background-color", "white");
		}

	if(PP_gender=='' ){
			$("#PP_gender").css("background-color", "yellow");
			warning = true;
		}else{
			$("#PP_gender").css("background-color", "white");
		}

	if (PP_DOB==''){            
			$("#PP_DOB").css("background-color", "yellow");
			warning = true;
		}else{
			$("#PP_DOB").css("background-color", "white");
		}

	if(PP_lives=='' ){
			$("#PP_lives").css("background-color", "yellow");
			warning = true;
		}else{
			$("#PP_lives").css("background-color", "white");
		}


	if(FC_fname=='' ){
			$("#FC_fname").css("background-color", "yellow");
			warning = true;
		}else{
			$("#FC_fname").css("background-color", "white");
		}

	// added field
	if (familyLan==''){            
			$("#familyLanguage").css("background-color", "yellow");
			warning = true;
		}else{
			$("#familyLanguage").css("background-color", "white");
		}
	// added field	
	if (familyIncm==''){            
			$("#familyIncome").css("background-color", "yellow");
			warning = true;
		}else{
			$("#familyIncome").css("background-color", "white");
		}
	// added field	
	if (familyAct==''){            
			$("#familyActivity").css("background-color", "yellow");
			warning = true;
		}else{
			$("#familyActivity").css("background-color", "white");
		}	
		
	if(FC_lname=='' ){
			$("#FC_lname").css("background-color", "yellow");
			warning = true;
		}else{
			$("#FC_lname").css("background-color", "white");
		}

	if (FC_gender==''){            
			$("#FC_gender").css("background-color", "yellow");
			warning = true;
		}else{
			$("#FC_gender").css("background-color", "white");
		}

	if(FC_DOB=='' ){
			$("#FC_DOB").css("background-color", "yellow");
			warning = true;
		}else{
			$("#FC_DOB").css("background-color", "white");
		}	


	if(warning == true){
		alert("All highlighted field(s) need to be completed before proceeding the process.");
	}else if((agentID =="")||(familyName =="")){
		alert("The Agency and Family information need to be completed before proceeding the process.");
	}else{
		//save to the Familynt atble;
		$.post("AddFirst.php",{
			fid66: agentID,
			fid29: familyName,
			fid14: familyAddr,
			fid15: familyCity,
			fid44: familyState,
			fid105: familyZip,
			fid45: familyTel,
			fid46: family2Tel,
			fid58: familyEmail,			
			cfid501: familyLan,
			cfid1029: familyIncm,
			cfid1030: familyAct,
			ppfid16: PP_related,
			ppfid8: PP_fname,
			ppfid10: PP_lname,
			ppfid12: PP_gender,
			ppfid15: PP_DOB,
			ppfid253: PP_lives,
			spfid16: SP_related,
			spfid8: SP_fname,
			spfid10: SP_lname,
			spfid12: SP_gender,
			spfid15: SP_DOB,
			spfid253: SP_lives,	
			fcfid6: FC_fname,
			fcfid25: FC_mname,
			fcfid7: FC_lname,
			fcfid12: FC_gender,
			fcfid10: FC_DOB,
			scfid6: SC_fname,
			scfid25: SC_mname,
			scfid7: SC_lname,
			scfid12: SC_gender,
			scfid10: SC_DOB,
			cfid391: shelterWorker,
			cfid392: agentPhone,
			cfid1028: agentEmail
			}, function(familyBack,statusF){
				if(familyBack.length > 6){
					alert(familyBack);
				}else{
						$("#thisAgent").val('');
						$("#shelterWorker").val('');
						$("#agentPhone").val('');
						$("#agentEmail").val('');
						$("#selectedAgent").val('');

						$("#familyName").val('');
						$("#familyAddr").val('');
						$("#familyCity").val('');
						$("#familyState").val('');
						$("#familyZip").val('');
						$("#familyTel").val('');   
						$("#family2Tel").val('');  
						$("#familyEmail").val('');	
						$("#familyLanguage").val(''); 
						$("#familyIncome").val('');  
						$("#familyActivity").val(''); 
						//Parents info
						$("#PP_related").val('');    
						$("#PP_fname").val('');
						$("#PP_lname").val('');
						$("#PP_gender").val(''); 
						$("#PP_DOB").val(''); 
						$( "#PP_lives" ).val('');

						$("#SP_related").val('');    
						$("#SP_fname").val('');
						$("#SP_lname").val('');
						$("#SP_gender").val(''); 
						$("#SP_DOB").val(''); 
						$( "#SP_lives" ).val('');
						//Children info
						$("#FC_fname" ).val(''); 
						$("#FC_mname").val('');    
						$("#FC_lname").val('');
						$("#FC_gender").val(''); 
						$("#FC_DOB").val(''); 

						$("#SC_fname" ).val(''); 
						$("#SC_mname").val('');    
						$("#SC_lname").val('');
						$("#SC_gender").val(''); 
						$("#SC_DOB").val(''); 
					alert("Your input is added to the QuickBase.");
				}
			}
		);  
		
	}
	//    document.getElementById("thePassword").value = ''; 
	//    document.getElementById("varPassword").value = '';       
});



$("#clearout").click(function(){
	$("#thisAgent").val('');
	$("#shelterWorker").val('');
	$("#agentPhone").val('');
	$("#agentEmail").val('');
	$("#selectedAgent").val('');
	
	$("#familyName").val('');
	$("#familyAddr").val('');
	$("#familyCity").val('');
	$("#familyState").val('');
	$("#familyZip").val('');
	$("#familyTel").val('');   
	$("#family2Tel").val('');  
	$("#familyEmail").val('');	
	$("#familyLanguage").val(''); 
	$("#familyIncome").val('');  
	$("#familyActivity").val(''); 
	//Parents info
	$("#PP_related").val('');    
	$("#PP_fname").val('');
	$("#PP_lname").val('');
	$("#PP_gender").val(''); 
	$("#PP_DOB").val(''); 
	$( "#PP_lives" ).val('');
	
	$("#SP_related").val('');    
	$("#SP_fname").val('');
	$("#SP_lname").val('');
	$("#SP_gender").val(''); 
	$("#SP_DOB").val(''); 
	$( "#SP_lives" ).val('');
	//Children info
	$("#FC_fname" ).val(''); 
	$("#FC_mname").val('');    
	$("#FC_lname").val('');
	$("#FC_gender").val(''); 
	$("#FC_DOB").val(''); 
	
	$("#SC_fname" ).val(''); 
	$("#SC_mname").val('');    
	$("#SC_lname").val('');
	$("#SC_gender").val(''); 
	$("#SC_DOB").val(''); 

});



//Load Agent info when Agency name selected
$("#selectedAgent").change(function(){
	var AgentFID_6 = $( "#selectedAgent" ).val(); 	
	var urlsubmitdata = { AgentName:AgentFID_6};
	$.post("loadAgentInfo.php",urlsubmitdata, function(data,status){
		var dataString = data.toString();
		var agentObj = JSON.parse(dataString);
		$( "#agencyPhone" ).val(agentObj.AgentTel);
		$( "#agencyEmail" ).val(agentObj.AgentEmail);
		$( "#thisAgent" ).val(agentObj.AgentID);
		$( "#agentContact" ).val(agentObj.Contact);
	});    
});
	
	
//Check the Family name from database when type the name
$("#familyName").change(function(){
	var FamilyFID_29 = $( "#familyName" ).val(); 	
	var urlfamilydata = { FamilyName:FamilyFID_29 };
	$.get("loadFamilyInfo.php",urlfamilydata, function(familyData,status){				
		if(familyData=='TRUE'){
			alert("The Family information entered is a duplicate to information  already entered, please send an email to referrals@horizonschildren.org with the information.");
		}
	});    
});
	
		
	
});      


