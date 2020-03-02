<?
/**
* Aelita Test Result
* 
* @author Danilin Alexander I. <Danilin2010@yandex.ru>
* @version 1.0
**/
IncludeModuleLangFile(__FILE__);
class AelitaTestResult extends AelitaElement
{
	
	function __construct()
	{
		$this->TABLE_NAME= "b_aelita_test_result";
	}

	function CheckFields(&$arFields, $strOperation = 'ADD')
	{
		if(!$this->CheckName($arFields,$strOperation,'NAME')) return false;
		if(!$this->CheckImages($arFields,$strOperation,'PICTURE')) return false;

		if(!$this->CheckTestID($arFields,$strOperation,'TEST_ID')) return false;
		
		$this->CheckActive($arFields,$strOperation,'ACTIVE');
		$this->CheckType($arFields,$strOperation,'DESCRIPTION_TYPE');
		$this->CheckSort($arFields,$strOperation,'SORT');
		
		$this->CheckInt($arFields,$strOperation,'MIN_SCORES');
		$this->CheckInt($arFields,$strOperation,'MAX_SCORES');
		
		return true;
	}
	
	public function GetFields(&$arFields,$NAME)
	{
		$arFields = array(
			"ID" => Array("FIELD" => $NAME.".ID", "TYPE" => "int"),
			"XML_ID" => Array("FIELD" => $NAME.".XML_ID", "TYPE" => "string"),
			"NAME" => Array("FIELD" => $NAME.".NAME", "TYPE" => "string"),
			"ACTIVE" => Array("FIELD" => $NAME.".ACTIVE", "TYPE" => "string"),
			"PICTURE" => Array("FIELD" => $NAME.".PICTURE", "TYPE" => "int"),
            "ALT" => Array("FIELD" => $NAME.".ALT", "TYPE" => "string"),
			"DESCRIPTION" => Array("FIELD" => $NAME.".DESCRIPTION", "TYPE" => "string"),
			"DESCRIPTION_TYPE" => Array("FIELD" => $NAME.".DESCRIPTION_TYPE", "TYPE" => "string"),
			"SORT" => Array("FIELD" => $NAME.".SORT", "TYPE" => "int"),
			"TEST_ID" => Array("FIELD" => $NAME.".TEST_ID", "TYPE" => "int"),
			"MIN_SCORES" => Array("FIELD" => $NAME.".MIN_SCORES", "TYPE" => "int"),
			"MAX_SCORES" => Array("FIELD" => $NAME.".MAX_SCORES", "TYPE" => "int"),
			);
	}
	
	public function GetAfterFields(&$arFields,$NAME)
	{
		$arFields["COUNT"]=Array("FIELD" => "COUNT(".$NAME.".ID)", "TYPE" => "string","AS"=>"COUNT");
	}
	
	function BeforeDelete($ID)
	{
	
		$el =new AelitaTestQuestioning;
		$arFields=array(
			"RESULT_ID"=>"",
			);
		$el->GroupUpdate("RESULT_ID", $ID, $arFields);
		
		$this->DeletePicture($ID,'PICTURE');
		return true;
	}

	function CheckTestID(&$arFields,$strOperation,$Prop)
	{
		global $APPLICATION;
		if (array_key_exists($Prop, $arFields) || $strOperation == "ADD")
		{
			$arFields[$Prop]=(int)$arFields[$Prop];
			if ($arFields[$Prop]>0){
				$el =new AelitaTestTest;
				$arExtra=$el->GetByID($arFields[$Prop]);
				if (!$arExtra=$arExtra->GetNext())
				{
					$APPLICATION->ThrowException(GetMessage("ERROR_ATG_NO_TEST"));
					return false;
				}
			}else{
				$APPLICATION->ThrowException(GetMessage("ERROR_ATG_TEST"));
				return false;
			}
		}
		return true;
	}
}


?>