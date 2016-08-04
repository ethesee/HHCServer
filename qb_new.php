<?php
 /*----------------------------------------------------------------------
 Title : QuickBase PHP SDK
 Author : Joshua McGinnis (joshua_mcginnis@intuit.com)
 Description : This is a php wrapper of the QuickBase HTT API. 

 The QuickBase API is well documented here:
 https://www.quickbase.com/up/6mztyxu8/g/rc7/en/va/QuickBaseAPI.htm

 Credits: Alex Wilson for providing much of the initial framework.

 The MIT License
 Copyright (c) 2008 Intuit, Inc.

 Permission is hereby granted, free of charge, to any person obtaining a copy
 of this software and associated documentation files (the "Software"), to deal
 in the Software without restriction, including without limitation the rights
 to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 copies of the Software, and to permit persons to whom the Software is
 furnished to do so, subject to the following conditions:
 The above copyright notice and this permission notice shall be included in
 all copies or substantial portions of the Software.

 THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN

 THE SOFTWARE

 -----------------------------------------------------------------------*/

ini_set('display_errors', 'on');

Class QuickBase {

/*----------------------------------------------------------------------

 // User Configurable Options

------------------------------------------------------------------------*/

	var $user_name = 'hhc_helpdesk@horizonsforhomelesschildren.org'; 	// QuickBase user who will access the QuickBase
	var $passwd = 'hhcQuick2011'; 		// Password of this user
	var $db_id = ''; 		// Table/Database ID of the QuickBase being accessed
	var $xml = true;
	var $user_id = 0;
	var $qb_site = "horizons.quickbase.com";
	var $qb_ssl = "https://horizons.quickbase.com/db/";	
	var $errorcode = '';
	var $errortext = '';
	var $errordetail = '';
	var $apptoken = 'mpx3z2b8hk2dinp8n5ubjhxdu8';
/*----------------------------------------------------------------------

//	Do Not Change

------------------------------------------------------------------------*/

	var $input = '';
	var $output = '';
	var $ticket = '';

/* ---------------------------------------------------------------------*/

	public function __construct($un = null, $pw = null, $usexml = true, $db = ''){
		if($un){
			$this->user_name = $un;
		}

		if($pw){
			$this->passwd = $pw;
		}

		if($db){
			$this->db_id = $db;
		}

		$this->xml = $usexml;

		if($un && $pw){
			$uid = $this->Authenticate();
		}

		if(isset($uid)){
			$this->user_id = $uid;
		}
	}

	private function transmit($input, $action_name = '', $url = '', $return_xml = true){
		if($this->apptoken){
			if($this->xml){
				$xml_packet = simplexml_load_string($input);

				$xml_packet->addChild('apptoken', $this->apptoken);
				$xml_packet->addChild('msInUTC', '1');

				$input = $xml_packet->asXML();
			}else{
				$input .= '&apptoken='.$this->apptoken;
			}
		}

		if($this->xml){
			if($url == ''){
				$url = $this->qb_ssl.$this->db_id;
			}

			$content_length = strlen($input);
			$headers = array(
				"POST /db/".$this->db_id." HTTP/1.0",
				"Content-Type: text/xml;",
				"Accept: text/xml",
				"Cache-Control: no-cache",
				"Pragma: no-cache",
				"Content-Length: ".$content_length,
				'QUICKBASE-ACTION: '.$action_name
			);

			$this->input = $input;

			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $input);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
		}else{
			$ch = curl_init($input);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_POST, true);

			$this->input = $input;
		}

		$r = curl_exec($ch);

		if(!$r){
			return false;
		}

		if($return_xml){
			$this->errorcode = '';
			$this->errortext = '';
			$this->errordetail = '';

			$response = new SimpleXMLElement($r);

			$this->errorcode = $response->errcode;
			$this->errortext = $response->errtext;
			$this->errordetail = $response->errdetail;
		}else{
			$response = $r;
		}

		return $response;
	}

	/* API_Authenticate: https://www.quickbase.com/up/6mztyxu8/g/rc7/en/va/QuickBaseAPI.htm#_Toc126579970 */
	public function Authenticate($username = NULL, $password = NULL){
		if($username){
			$this->user_name = $username;
			$this->passwd = $password;
		}

		if($this->xml){
			$xml_packet = new SimpleXMLElement('<qdbapi></qdbapi>');

			$xml_packet->addChild('username', $this->user_name);
			$xml_packet->password = $this->passwd;
			$xml_packet->addChild('ticket', $this->ticket);

			$xml_packet = $xml_packet->asXML();

			$response = $this->transmit($xml_packet, 'API_Authenticate', $this->qb_ssl."main");
		}else{
			$url_string = $this->qb_ssl."main?act=API_Authenticate&username=".$this->user_name."&password=".$this->passwd;

			$response = $this->transmit($url_string);
		}

		if($response){
			$this->ticket = $response->ticket;
			$this->user_id = $response->userid;

			return true;
		}

		return false;
	}

	/* API_AddRecord: https://www.quickbase.com/up/6mztyxu8/g/rc7/en/va/QuickBaseAPI.htm#_Toc126579962 */
	public function AddRecord($dbid, $fields, $ignoreError){
		if($dbid){
			$this->db_id = $dbid;
		}

		if($this->xml){
			$xml_packet = new SimpleXMLElement('<qdbapi></qdbapi>');

			$i = intval(0);
			foreach($fields as $field){
				$safe_value = preg_replace('/&/', '&amp;', $field['value']);

				$bar = $xml_packet->addChild('field', $safe_value);

				$xml_packet->field[$i]->addAttribute('fid', $field['fid']);

				if(isset($field['filename'])){
					$xml_packet->field[$i]->addAttribute('filename', $field['filename']);
				}

				$i++;
			}

			if($ignoreError){
				$xml_packet->addChild('ignoreError', '1');
			}

			$xml_packet->addChild('ticket', $this->ticket);

			$xml_packet = $xml_packet->asXML();

			$response = $this->transmit($xml_packet, 'API_AddRecord');
		}else{
			$url_string = $this->qb_ssl.$this->db_id."?act=API_AddRecord&ticket=".$this->ticket;

			foreach($fields as $field){
				$url_string .= "&_fid_".$field['fid']."=".urlencode($field['value'])."";
			}

			$response = $this->transmit($url_string);
		}

		if($response){
			return $response;
		}

		return false;
	}

	/* API_DeleteRecord: https://www.quickbase.com/up/6mztyxu8/g/rc7/en/va/QuickBaseAPI.htm#_Toc126579996*/
	public function DeleteRecord($dbid, $rid){
		if($dbid){
			$this->db_id = $dbid;
		}

		if($this->xml){
			$xml_packet = new SimpleXMLElement('<qdbapi></qdbapi>');

			$xml_packet->addChild('rid', $rid);
			$xml_packet->addChild('ticket', $this->ticket);

			$xml_packet = $xml_packet->asXML();

			$response = $this->transmit($xml_packet, 'API_DeleteRecord');
		}else{
			$url_string = $this->qb_ssl.$this->db_id."?act=API_DeleteRecord&ticket=".$this->ticket."&rid=".$rid;

			$response = $this->transmit($url_string);
		}

		if($response){
			return true;
		}

		return false;
	}

	/* API_DoQuery: https://www.quickbase.com/up/6mztyxu8/g/rc7/en/va/QuickBaseAPI.htm#_Toc126579999 */
	public function DoQuery($dbid, $queries = 0, $clist = 0, $slist = 0, $options = '', $fmt = 'structured'){
		if($dbid){
			$this->db_id = $dbid;
		}

		if($this->xml){
			$xml_packet='<qdbapi>';
			$pos = 0;

			if($queries){
				$xml_packet .= '<query>'.$queries.'</query>';
			}else{
				return false;
			}

			$xml_packet .= '<fmt>'.$fmt.'</fmt>';

			if($clist){
				$xml_packet .= '<clist>'.$clist.'</clist>';
			}

			if($slist){
				$xml_packet .= '<slist>'.$slist.'</slist>';
				$xml_packet .= '<options>'.$options.'</options>';
			}

			$xml_packet .= '<ticket>'.$this->ticket.'</ticket>
				</qdbapi>';

			$response = $this->transmit($xml_packet, 'API_DoQuery');

			if($response === false){
				return false;
			}

			$this->latestSchema = (array) $response->table->fields;
			$this->latestSchema = $this->latestSchema['field'];

			$arrayresponse = array();

			$i = 0;
			while($i < count($response->table->records->record)){
				$m = 0;
				while($m < count($response->table->records->record[$i]->f)){
					$arrayresponse[$i][(string) $response->table->fields->field[$m]->attributes()->id] = (string) $response->table->records->record[$i]->f[$m];

					$m++;
				}

				$i++;
			}

			$response = $arrayresponse;
		}else{
			$url_string = $this->qb_ssl.$this->db_id."?act=API_DoQuery&ticket=".$this->ticket."&fmt=".$fmt;

			$pos = 0;

			if($queries){
				$url_string .= "&query=".$queries;
			}else{
				return false;
			}

			if($clist){
				$url_string .= "&clist=".$clist;
			}

			if($slist){
				$url_string .= "&slist=".$slist;
			}

			if($options){
				$url_string .= "&options=".$options;
			}

			$response = $this->transmit($url_string);
		}

		if($response){
			return $response;
		}

		return false;
	}

	/* API_EditRecord: https://www.quickbase.com/up/6mztyxu8/g/rc7/en/va/QuickBaseAPI.htm#_Toc126580003 */
	public function EditRecord($dbid, $rid, $fields, $useKey = false){
		if($dbid){
			$this->db_id = $dbid;
		}

		if($this->xml){
			$xml_packet = new SimpleXMLElement('<qdbapi></qdbapi>');

			if($useKey){
				$xml_packet->addChild('key', $rid);
			}else{
				$xml_packet->addChild('rid', $rid);
			}

			$i = intval(0);
			foreach($fields as $field){
				$safe_value = preg_replace('/&(?!\w+;)/', '&amp;', $field['value']);

				$bar = $xml_packet->addChild('field', $safe_value);

				$xml_packet->field[$i]->addAttribute('fid', $field['fid']);

				if(isset($field['filename'])){
					$xml_packet->field[$i]->addAttribute('filename', $field['filename']);
				}

				$i++;
			}

			$xml_packet->addChild('ticket', $this->ticket);

			$xml_packet = $xml_packet->asXML();

			$response = $this->transmit($xml_packet, 'API_EditRecord');
		}else{
			$url_string = $this->qb_ssl.$this->db_id."?act=API_EditRecord&ticket=".$this->ticket."&rid=".$rid;

			foreach ($fields as $field){
				$url_string .= "&_fid_".$field['id']."=".$field['value'];
			}

			$response = $this->transmit($url_string);
		}

		if($response){
			return $response;
		}

		return false;
	}

	/* API_GetSchema: https://www.quickbase.com/up/6mztyxu8/g/rc7/en/va/QuickBaseAPI.htm#_Toc126580049 */
	public function GetSchema($dbid){
		if($dbid){
			$this->db_id = $dbid;
		}

		if($this->xml){
			$xml_packet = new SimpleXMLElement('<qdbapi></qdbapi>');

			$xml_packet->addChild('ticket', $this->ticket);

			$xml_packet = $xml_packet->asXML();

			$response = $this->transmit($xml_packet, 'API_GetSchema');
		}else{
			$url_string = $this->qb_ssl.$this->db_id."?act=API_GetSchema&ticket=".$this->ticket;

			$response = $this->transmit($url_string);
		}

		if($response){
			return $response;
		}

		return false;
	}

	public function GetLists($dbid, $queries = 0, $queryName = '', $qid = 1, $options = '', $fmt = 'structured'){
		if($dbid){
			$this->db_id = $dbid;
		}

		if($this->xml){
			$xml_packet='<qdbapi>';

			$pos = 0;

			$xml_packet .= "<query>{'1'.EX.'-1'}</query>";
			$xml_packet .= '<fmt>'.$fmt.'</fmt>';
			$xml_packet .= '<ticket>'.$this->ticket.'</ticket>
				</qdbapi>';

			$response = $this->transmit($xml_packet, 'API_GetSchema');

			if($queryName != ''){
				foreach($response->table->queries->query as $query){
					if($query->qyname == $queryName){
						$list['clst'] = (string) $query->qyclst[0];

						$list['slst'] = (string) $query->qyclst[0];

						$list['calst'] = (string) $query->qycalst[0];

						$list['opts'] = (string) $query->qyopts[0];
					}
				}	// TODO Return error if we didn't find the list
			}else{
				foreach($response->table->queries->query as $query){
					if($query->attributes()->id == $qid){
						$list['clst'] = (string) $query->qyclst[0];
						$list['slst'] = (string) $query->qyclst[0];
						$list['calst'] = (string) $query->qycalst[0];
						$list['opts'] = (string) $query->qyopts[0];
					}
				}	// TODO Return error if we didn't find the list
			}

			// find field attributes
			$clist = explode('.', $list['clst']);

			$cCount = 0;
			foreach($clist as $cfield){
				foreach($response->table->fields->field as $field){
					if($cfield == $field->attributes()->id){
						$list['fieldname'][$cCount] = (string) $field->label;
						$list['fieldtype'][$cCount] = (string) $field->attributes()->field_type;
						$list['fieldid'][$cCount] = (string) $cfield;

						$cCount++;
					}
				}
			}

			return $list;
		}else{
			$url_string = $this->qb_ssl.$this->db_id."?act=API_DoQuery&ticket=".$this->ticket."&fmt=".$fmt;

			$pos = 0;

			$url_string .= "&query={'1'.EX.'-1'}";

			if($clist){
				$url_string .= "&clist=".$clist;
			}

			if($slist){
				$url_string .= "&slist=".$slist;
			}

			if($options){
				$url_string .= "&options=".$options;
			}

			$response = $this->transmit($url_string);
		}

		if($response){
			return $response;
		}

		return false;
	}

	public function GetMultiChoiceValues($dbid, $fieldid){
		$choices = array();
		$response = $this->GetSchema($dbid);

		foreach($response->table->fields->field as $field){
			if($field->attributes()->id == $fieldid){
				$i = 0;
				foreach($field->choices->choice as $choice){
					$choices[$i] = (string) $choice;

					$i++;
				}

				break;
			}
		}

		if(count($choices > 0)){
			return $choices;
		}else{
			return false;
		}
	}

	public function GetUserInfo($email){
		if(!isset($email)){
			$email = $this->user_name;
		}

		$xml_packet = new SimpleXMLElement('<qdbapi></qdbapi>');

		$xml_packet->addChild('ticket', $this->ticket);
		$xml_packet->addChild('email', $email);

		$xml_packet = $xml_packet->asXML();

		$response = $this->transmit($xml_packet, 'API_GetUserInfo', $this->qb_ssl.'main');

		return $response;
	}

	public function GetUserRole($dbid, $userid){
		$xml_packet = new SimpleXMLElement('<qdbapi></qdbapi>');

		$xml_packet->addChild('ticket', $this->ticket);
		$xml_packet->addChild('userid', $userid);

		$xml_packet = $xml_packet->asXML();

		$response = $this->transmit($xml_packet, 'API_GetUserRole', $this->qb_ssl.$dbid);

		return $response;
	}

	public function GetRole($email){
		if(!isset($email)){
			$email = $this->user_name;
		}

		$userinfo = $this->GetUserInfo($email);

		$userid = $userinfo->user->attributes()->id;

		$userrole = $this->GetUserRole($userid);

		$role = $userrole->user->roles->role->name;

		return $role;
	}

	public function DoQueryCount($dbid, $query){
		if($dbid){
			$this->db_id = $dbid;
		}

		$xml_packet = new SimpleXMLElement('<qdbapi></qdbapi>');

		$xml_packet->addChild('query', $query);
		$xml_packet->addChild('ticket', $this->ticket);

		$xml_packet = $xml_packet->asXML();

		$response = $this->transmit($xml_packet, 'API_DoQueryCount');

		if($response){
			return $response->numMatches;
		}

		return false;
	}
	
	public function GetDBVar($dbid, $varname){
		if($dbid){
			$this->db_id = $dbid;
		}

		$xml_packet = new SimpleXMLElement('<qdbapi></qdbapi>');

		$xml_packet->addChild('varname', $varname);
		$xml_packet->addChild('ticket', $this->ticket);

		$xml_packet = $xml_packet->asXML();

		$response = $this->transmit($xml_packet, 'API_GetDBVar');

		if($response){
			return $response->value;
		}

		return false;
	}

	public function SetDBVar($dbid, $varname, $value){
		if($dbid){
			$this->db_id = $dbid;
		}

		$xml_packet = new SimpleXMLElement('<qdbapi></qdbapi>');

		$xml_packet->addChild('varname', $varname);
		$xml_packet->addChild('value', $value);
		$xml_packet->addChild('ticket', $this->ticket);

		$xml_packet = $xml_packet->asXML();

		$response = $this->transmit($xml_packet, 'API_SetDBVar');

		if($response){
			return $response->value;
		}

		return false;
	}

	public function ImportFromCSV($dbid, $records_csv, $clist){
		if($dbid){
			$this->db_id = $dbid;
		}

		$xml_packet = new SimpleXMLElement('<qdbapi></qdbapi>');

		$xml_packet->addChild('records_csv', $records_csv);
		$xml_packet->addChild('clist', $clist);

		$xml_packet->addChild('ticket', $this->ticket);

		$xml_packet = $xml_packet->asXML();

		$response = $this->transmit($xml_packet, 'API_ImportFromCSV');

		if($response){
			return $response;
		}

		return false;
	}

	public function PurgeRecords($dbid, $query){
		if($dbid){
			$this->db_id = $dbid;
		}

		$xml_packet = new SimpleXMLElement('<qdbapi></qdbapi>');

		$xml_packet->addChild('query', $query);
		$xml_packet->addChild('ticket', $this->ticket);

		$xml_packet = $xml_packet->asXML();

		$response = $this->transmit($xml_packet, 'API_PurgeRecords');

		if($response){
			return $response->num_records_deleted;
		}

		return false;
	}

	public function GetRecordInfo($dbid, $rid){
		if($dbid){
			$this->db_id = $dbid;
		}

		if($this->xml){
			$xml_packet = new SimpleXMLElement('<qdbapi></qdbapi>');

			$xml_packet->addChild('ticket', $this->ticket);
			$xml_packet->addChild('rid', $rid);

			$xml_packet = $xml_packet->asXML();

			$response = $this->transmit($xml_packet, 'API_GetRecordInfo');
		}else{
			$url_string = $this->qb_ssl.$this->db_id."?act=API_GetRecordInfo&ticket=".$this->ticket;

			$response = $this->transmit($url_string);
		}

		if($response){
			return $response;
		}

		return false;
	}

}

?>