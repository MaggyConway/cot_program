<?
/**
* Aelita Test Answer
* 
* @author Danilin Alexander I. <Danilin2010@yandex.ru>
* @version 1.0
**/
IncludeModuleLangFile(__FILE__);
class AelitaTestAnswer extends AelitaElement
{
	
	function __construct()
	{
		$this->TABLE_NAME= "b_aelita_test_answer";
	}

	function CheckFields(&$arFields, $strOperation = 'ADD')
	{
		if(!$this->CheckName($arFields,$strOperation,'NAME')) return false;
		if(!$this->CheckImages($arFields,$strOperation,'PICTURE')) return false;

		if(!$this->CheckQuestionID($arFields,$strOperation,'QUESTION_ID')) return false;
		
		$this->CheckActive($arFields,$strOperation,'ACTIVE');
        $this->CheckNonActive($arFields,$strOperation,'CORRECT');
		$this->CheckType($arFields,$strOperation,'DESCRIPTION_TYPE');
		$this->CheckSort($arFields,$strOperation,'SORT');
		
		$this->CheckInt($arFields,$strOperation,'SCORES');

        $this->CheckType($arFields,$strOperation,'CORRECT_DESCRIPTION_TYPE');
        $this->CheckType($arFields,$strOperation,'ERROR_DESCRIPTION_TYPE');
		
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
			"QUESTION_ID" => Array("FIELD" => $NAME.".QUESTION_ID", "TYPE" => "int"),
			"SCORES" => Array("FIELD" => $NAME.".SCORES", "TYPE" => "int"),
            "CORRECT" => Array("FIELD" => $NAME.".CORRECT", "TYPE" => "string"),
            "CORRECT_DESCRIPTION" => Array("FIELD" => $NAME.".CORRECT_DESCRIPTION", "TYPE" => "string"),
            "CORRECT_DESCRIPTION_TYPE" => Array("FIELD" => $NAME.".CORRECT_DESCRIPTION_TYPE", "TYPE" => "string"),
            "ERROR_DESCRIPTION" => Array("FIELD" => $NAME.".ERROR_DESCRIPTION", "TYPE" => "string"),
            "ERROR_DESCRIPTION_TYPE" => Array("FIELD" => $NAME.".ERROR_DESCRIPTION_TYPE", "TYPE" => "string"),
			);
	}
	
	public function GetAfterFields(&$arFields,$NAME)
	{
		$arFields["COUNT"]=Array("FIELD" => "COUNT(".$NAME.".ID)", "TYPE" => "string","AS"=>"COUNT");
		
		$JoinName="ATQ";
		$JoinTable="b_aelita_test_question";
		$arFields["TEST_ID"]=Array(
			"FIELD" => $JoinName.".TEST_ID", 
			"TYPE" => "int",
			"JOIN"=>array(
				"LEFT OUTER JOIN ".$JoinTable." ".$JoinName." ON ".$NAME.".QUESTION_ID=".$JoinName.".ID",
				),
			"AS"=>"TEST_ID"
		);
	}
	
	function BeforeDelete($ID)
	{
		$this->DeletePicture($ID,'PICTURE');
		return true;
	}

	function CheckQuestionID(&$arFields,$strOperation,$Prop)
	{
		global $APPLICATION;
		if (array_key_exists($Prop, $arFields) || $strOperation == "ADD")
		{
			$arFields[$Prop]=(int)$arFields[$Prop];
			if ($arFields[$Prop]>0){
				$el =new AelitaTestQuestion;
				$arExtra=$el->GetByID($arFields[$Prop]);
				if (!$arExtra=$arExtra->GetNext())
				{
					$APPLICATION->ThrowException(GetMessage("ERROR_ATG_NO_QUESTION"));
					return false;
				}
			}else{
				$APPLICATION->ThrowException(GetMessage("ERROR_ATG_QUESTION"));
				return false;
			}
		}
		return true;
	}
}


?>