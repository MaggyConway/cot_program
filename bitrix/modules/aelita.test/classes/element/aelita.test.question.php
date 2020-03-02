<?
/**
* Aelita Test Question
* 
* @author Danilin Alexander I. <Danilin2010@yandex.ru>
* @version 1.0
**/
IncludeModuleLangFile(__FILE__);
class AelitaTestQuestion extends AelitaElement
{
	function __construct()
	{
		$this->TABLE_NAME= "b_aelita_test_question";
	}

	function CheckFields(&$arFields, $strOperation = 'ADD')
	{
		if(!$this->CheckName($arFields,$strOperation,'NAME')) return false;
		if(!$this->CheckImages($arFields,$strOperation,'PICTURE')) return false;

		if(!$this->CheckTestID($arFields,$strOperation,'TEST_ID')) return false;
		
		$this->CheckActive($arFields,$strOperation,'ACTIVE');
		$this->CheckType($arFields,$strOperation,'DESCRIPTION_TYPE');
		$this->CheckSort($arFields,$strOperation,'SORT');
		
		$this->CheckTestType($arFields,$strOperation,'TEST_TYPE');
		
		$this->CheckInt($arFields,$strOperation,'SCORES');
		
		if(!$this->CheckGroupID($arFields,$strOperation,'GROUP_ID')) return false;
		
		$this->CheckNonActive($arFields,$strOperation,'SHOW_COMMENTS');
		
		//if($arFields["DESCRIPTION"])
		//	$arFields["DESCRIPTION"] = str_replace("\r\n", "\r", $arFields["DESCRIPTION"]);
		
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
			"TEST_GROUP_ID" => Array("FIELD" => $NAME.".TEST_GROUP_ID", "TYPE" => "int"),
			"SCORES" => Array("FIELD" => $NAME.".SCORES", "TYPE" => "int"),
			"CORRECT_ANSWER" => Array("FIELD" => $NAME.".CORRECT_ANSWER", "TYPE" => "string"),
			"TEST_TYPE" => Array("FIELD" => $NAME.".TEST_TYPE", "TYPE" => "string"),
			"SHOW_COMMENTS" => Array("FIELD" => $NAME.".SHOW_COMMENTS", "TYPE" => "string"),
			);
	}
	
	public function GetAfterFields(&$arFields,$NAME)
	{
		$arFields["COUNT"]=Array("FIELD" => "COUNT(".$NAME.".ID)", "TYPE" => "string","AS"=>"COUNT");
	}
	
	function DelRelated($ID)
	{
		$el =new AelitaTestAnswer;
		$el->GroupDelete("QUESTION_ID", $ID);
		
		$el =new AelitaTestGlasses;
		$el->GroupDelete("QUESTION_ID", $ID);
		return true;
	}
	
	function BeforeDelete($ID)
	{
		$this->DelRelated($ID);
		$this->DeletePicture($ID,'PICTURE');
		return true;
	}
	
	function CheckTestType(&$arFields,$strOperation,$Prop)
	{
		if (array_key_exists($Prop, $arFields) || $strOperation == "ADD")
		{
			$arFields[$Prop]=trim($arFields[$Prop]);
			if($arFields[$Prop]!="check" && $arFields[$Prop]!="input")
				$arFields[$Prop]="radio";
		}
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
	
	function CheckGroupID(&$arFields,$strOperation,$Prop)
	{
		global $APPLICATION;
		if (array_key_exists($Prop, $arFields))
		{
			$arFields[$Prop]=(int)$arFields[$Prop];
			if ($arFields[$Prop]>0){
				$el =new AelitaTestGroup;
				$arExtra=$el->GetByID($arFields[$Prop]);
				if (!$arExtra=$arExtra->GetNext())
				{
					$APPLICATION->ThrowException(GetMessage("ERROR_ATG_NO_GROUP"));
					return false;
				}
			}else{
				$arFields[$Prop]="";
			}
		}
		return true;
	}
}
?>
