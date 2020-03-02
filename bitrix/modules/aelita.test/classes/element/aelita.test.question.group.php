<?
/**
* Aelita Test Group
* 
* @author Danilin Alexander I. <Danilin2010@yandex.ru>
* @version 1.0
**/
IncludeModuleLangFile(__FILE__);
class AelitaTestQuestionGroup extends AelitaElement
{
	
	function __construct()
	{
		$this->TABLE_NAME= "b_aelita_test_question_group";
	}

	function CheckFields(&$arFields, $strOperation = 'ADD')
	{
		if(!$this->CheckName($arFields,$strOperation,'NAME')) return false;
		if(!$this->CheckImages($arFields,$strOperation,'PICTURE')) return false;

		$this->CheckActive($arFields,$strOperation,'ACTIVE');
		$this->CheckType($arFields,$strOperation,'DESCRIPTION_TYPE');
		$this->CheckSort($arFields,$strOperation,'SORT');
		$this->CheckInt($arFields,$strOperation,'COUNT');

		$this->CheckInt($arFields,$strOperation,'MULTIPLE_QUESTION_COUNT');
		
		if(!$this->CheckTestID($arFields,$strOperation,'TEST_ID')) return false;
		
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
            "DESCRIPTION" => Array("FIELD" => $NAME.".DESCRIPTION", "TYPE" => "string"),
			"ALT" => Array("FIELD" => $NAME.".ALT", "TYPE" => "string"),
			"DESCRIPTION_TYPE" => Array("FIELD" => $NAME.".DESCRIPTION_TYPE", "TYPE" => "string"),
			"SORT" => Array("FIELD" => $NAME.".SORT", "TYPE" => "int"),
			"COUNT" => Array("FIELD" => $NAME.".COUNT", "TYPE" => "int"),
			"CODE" => Array("FIELD" => $NAME.".CODE", "TYPE" => "string"),
			"TEST_ID" => Array("FIELD" => $NAME.".TEST_ID", "TYPE" => "int"),
			"MULTIPLE_QUESTION_COUNT" => Array("FIELD" => $NAME.".MULTIPLE_QUESTION_COUNT", "TYPE" => "int"),
			);
	}
	
	public function GetAfterFields(&$arFields,$NAME)
	{
		$arFields["COUNT_LIST"]=Array("FIELD" => "COUNT(".$NAME.".ID)", "TYPE" => "string","AS"=>"COUNT");
	}
	
	function ClearQuestion($ID)
	{
		$el =new AelitaTestQuestion;
		$arFields=array(
			"TEST_GROUP_ID"=>"",
			);
		$el->GroupUpdate("TEST_GROUP_ID", $ID, $arFields);
		return true;
	}
	
	function BeforeDelete($ID)
	{
		$this->ClearQuestion($ID);
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