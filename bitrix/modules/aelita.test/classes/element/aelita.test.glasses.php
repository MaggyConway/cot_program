<?
/**
* Aelita Test Glasses
* 
* @author Danilin Alexander I. <Danilin2010@yandex.ru>
* @version 1.0
**/
IncludeModuleLangFile(__FILE__);
class AelitaTestGlasses extends AelitaElement
{
	
	function __construct()
	{
		$this->TABLE_NAME= "b_aelita_test_glasses";
	}

	function CheckFields(&$arFields, $strOperation = 'ADD')
	{

		if(!$this->CheckQuestionID($arFields,$strOperation,'QUESTION_ID')) return false;
		if(!$this->CheckQuestioningID($arFields,$strOperation,'QUESTIONING_ID')) return false;
	
		$this->CheckInt($arFields,$strOperation,'SCORES');
		$this->CheckActive($arFields,$strOperation,'OTV');
		$this->ConverBase($arFields,$strOperation,'SERIALIZED_RESULT');
		$this->ConverBase($arFields,$strOperation,'SERIALIZED_RESULT_TEXT');
		
		//$this->ConverBase($arFields,$strOperation,'COMMENTS');
		
		return true;
	}
	
	public function GetFields(&$arFields,$NAME)
	{
		$arFields = array(
			"ID" => Array("FIELD" => $NAME.".ID", "TYPE" => "int"),
			"QUESTIONING_ID" => Array("FIELD" => $NAME.".QUESTIONING_ID", "TYPE" => "int"),
			"QUESTION_ID" => Array("FIELD" => $NAME.".QUESTION_ID", "TYPE" => "int"),
			"SCORES" => Array("FIELD" => $NAME.".SCORES", "TYPE" => "int"),
			"OTV" => Array("FIELD" => $NAME.".OTV", "TYPE" => "string"),
			"SERIALIZED_RESULT" => Array("FIELD" => $NAME.".SERIALIZED_RESULT", "TYPE" => "string"),
			"SERIALIZED_RESULT_TEXT" => Array("FIELD" => $NAME.".SERIALIZED_RESULT_TEXT", "TYPE" => "string"),
			"COMMENTS" => Array("FIELD" => $NAME.".COMMENTS", "TYPE" => "string"),

            "STEP" => Array("FIELD" => $NAME.".STEP", "TYPE" => "int"),

			);
	}
	
	public function GetAfterFields(&$arFields,$NAME)
	{
		$arFields["COUNT"]=Array("FIELD" => "COUNT(".$NAME.".ID)", "TYPE" => "string","AS"=>"COUNT");
		$arFields["SUM_SCORES"]=Array("FIELD" => "SUM(".$NAME.".SCORES)", "TYPE" => "int","AS"=>"SUM_SCORES");
		
		$JoinName="AP";
		$JoinTable="b_aelita_test_questioning";
		$arFields["PROFILE_ID"]=Array(
			"FIELD" => $JoinName.".PROFILE_ID", 
			"TYPE" => "int",
			"JOIN"=>array(
				"LEFT OUTER JOIN ".$JoinTable." ".$JoinName." ON ".$NAME.".QUESTIONING_ID=".$JoinName.".ID",
				),
			"AS"=>"PROFILE_ID"
		);
		
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
		
		$JoinName="ATQ";
		$JoinTable="b_aelita_test_question";
		$arFields["QUESTION_NAME"]=Array(
			"FIELD" => $JoinName.".NAME", 
			"TYPE" => "int",
			"JOIN"=>array(
				"LEFT OUTER JOIN ".$JoinTable." ".$JoinName." ON ".$NAME.".QUESTION_ID=".$JoinName.".ID",
				),
			"AS"=>"QUESTION_NAME"
		);

		$JoinName="ATQ";
		$JoinTable="b_aelita_test_question";
		$arFields["QUESTION_DESCRIPTION"]=Array(
			"FIELD" => $JoinName.".DESCRIPTION", 
			"TYPE" => "int",
			"JOIN"=>array(
				"LEFT OUTER JOIN ".$JoinTable." ".$JoinName." ON ".$NAME.".QUESTION_ID=".$JoinName.".ID",
				),
			"AS"=>"QUESTION_DESCRIPTION"
		);

	}
	
	function BeforeDelete($ID)
	{
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
	
	function CheckQuestioningID(&$arFields,$strOperation,$Prop)
	{
		global $APPLICATION;
		if (array_key_exists($Prop, $arFields) || $strOperation == "ADD")
		{
			$arFields[$Prop]=(int)$arFields[$Prop];
			if ($arFields[$Prop]>0){
				$el =new AelitaTestQuestioning;
				$arExtra=$el->GetByID($arFields[$Prop]);
				if (!$arExtra=$arExtra->GetNext())
				{
					$APPLICATION->ThrowException(GetMessage("ERROR_ATG_NO_QUESTIONING"));
					return false;
				}
			}else{
				$APPLICATION->ThrowException(GetMessage("ERROR_ATG_QUESTIONING"));
				return false;
			}
		}
		return true;
	}
}


?>
