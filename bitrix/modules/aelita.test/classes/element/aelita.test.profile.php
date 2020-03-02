<?
/**
* Aelita Test Profile
* 
* @author Danilin Alexander I. <Danilin2010@yandex.ru>
* @version 1.0
**/
IncludeModuleLangFile(__FILE__);
class AelitaTestProfile extends AelitaElement
{
	
	function __construct()
	{
		$this->TABLE_NAME= "b_aelita_test_profile";
	}

	function CheckFields(&$arFields, $strOperation = 'ADD')
	{
		global $DB, $DBType;
		
		$this->CheckInt($arFields,$strOperation,'USER_ID');
		
		if (array_key_exists('SESS_ID',$arFields) || $strOperation == "ADD")
			$arFields['SESS_ID']=trim($arFields['SESS_ID']);
			
		unset($arFields['DATE_CREATE']);
		return true;
	}
	
	public function GetFields(&$arFields,$NAME)
	{
		$arFields = array(
			"ID" => Array("FIELD" => $NAME.".ID", "TYPE" => "int"),
			"SESS_ID" => Array("FIELD" => $NAME.".SESS_ID", "TYPE" => "string"),
			"USER_ID" => Array("FIELD" => $NAME.".USER_ID", "TYPE" => "int"),
			"DATE_CREATE" => Array("FIELD" => $NAME.".DATE_CREATE", "TYPE" => "string"),
			);
	}
	
	public function GetAfterFields(&$arFields,$NAME)
	{
		$arFields["COUNT"]=Array("FIELD" => "COUNT(".$NAME.".ID)", "TYPE" => "string","AS"=>"COUNT");
	}
	
	function BeforeDelete($ID)
	{
		//$el =new AelitaTestGlasses;
		//$el->GroupDelete("PROFILE_ID", $ID);
		
		$el =new AelitaTestQuestioning;
		$el->GroupDelete("PROFILE_ID", $ID);
		
		return true;
	}

}

?>