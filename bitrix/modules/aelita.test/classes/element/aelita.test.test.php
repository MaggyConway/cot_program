<?
/**
* Aelita Test Test
* 
* @author Danilin Alexander I. <Danilin2010@yandex.ru>
* @version 1.0
**/
IncludeModuleLangFile(__FILE__);
class AelitaTestTest extends AelitaElement
{
	
	function __construct()
	{
		$this->TABLE_NAME= "b_aelita_test_test";
	}

	function CheckFields(&$arFields, $strOperation = 'ADD')
	{

		if(!$this->CheckName($arFields,$strOperation,'NAME')) return false;
		if(!$this->CheckImages($arFields,$strOperation,'PICTURE')) return false;
        if(!$this->CheckImages($arFields,$strOperation,'SPONSOR_PICTURE')) return false;

		if(!$this->CheckGroupID($arFields,$strOperation,'GROUP_ID')) return false;
		
		$this->CheckActive($arFields,$strOperation,'ACTIVE');
		$this->CheckType($arFields,$strOperation,'DESCRIPTION_TYPE');
        $this->CheckType($arFields,$strOperation,'SPONSOR_DESCRIPTION_TYPE');
        $this->CheckType($arFields,$strOperation,'TO_TITLE_TYPE');
		$this->CheckSort($arFields,$strOperation,'SORT');
		
		$this->CheckActive($arFields,$strOperation,'ACCESS_ALL');
		$this->CheckActive($arFields,$strOperation,'ACCESS_GROUP');
		
		$this->CheckNonActive($arFields,$strOperation,'SHOW_ANSWERS');
		$this->CheckNonActive($arFields,$strOperation,'MIX_QUESTION');
        $this->CheckNonActive($arFields,$strOperation,'COUNT_USER_AUTOR');
		
		$this->CheckInt($arFields,$strOperation,'NUMBER_ATTEMPTS');
		$this->CheckInt($arFields,$strOperation,'PERIOD_ATTEMPTS');
        $this->CheckInt($arFields,$strOperation,'MULTIPLE_QUESTION_COUNT');
		
		$this->CheckInt($arFields,$strOperation,'TEST_TIME');

        $this->CheckNonActive($arFields,$strOperation,'AUTO_START_OVER');
		
		$this->CheckNonActive($arFields,$strOperation,'SHOW_COMMENTS');
		
		$this->CheckResultType($arFields,$strOperation,'TYPE_RESULT');

        $this->CheckMultipleQuestion($arFields,$strOperation,'MULTIPLE_QUESTION');

        $this->CheckNonActive($arFields,$strOperation,'USE_CORRECT');
		
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
            "TO_TITLE" => Array("FIELD" => $NAME.".TO_TITLE", "TYPE" => "string"),
            "TO_TITLE_TYPE" => Array("FIELD" => $NAME.".TO_TITLE_TYPE", "TYPE" => "string"),
			"SORT" => Array("FIELD" => $NAME.".SORT", "TYPE" => "int"),
			"GROUP_ID" => Array("FIELD" => $NAME.".GROUP_ID", "TYPE" => "int"),
			"CODE" => Array("FIELD" => $NAME.".CODE", "TYPE" => "string"),
			"ACCESS_ALL" => Array("FIELD" => $NAME.".ACCESS_ALL", "TYPE" => "string"),
			"ACCESS_GROUP" => Array("FIELD" => $NAME.".ACCESS_GROUP", "TYPE" => "string"),
			"DATE_FROM" => Array("FIELD" => $NAME.".DATE_FROM", "TYPE" => "string"),
			"DATE_TO" => Array("FIELD" => $NAME.".DATE_TO", "TYPE" => "string"),
			"NUMBER_ATTEMPTS" => Array("FIELD" => $NAME.".NUMBER_ATTEMPTS", "TYPE" => "int"),
			"PERIOD_ATTEMPTS" => Array("FIELD" => $NAME.".PERIOD_ATTEMPTS", "TYPE" => "int"),
			"TEST_TIME" => Array("FIELD" => $NAME.".TEST_TIME", "TYPE" => "int"),
			"SHOW_ANSWERS" => Array("FIELD" => $NAME.".SHOW_ANSWERS", "TYPE" => "string"),
			"MIX_QUESTION" => Array("FIELD" => $NAME.".MIX_QUESTION", "TYPE" => "string"),
            "AUTO_START_OVER" => Array("FIELD" => $NAME.".AUTO_START_OVER", "TYPE" => "string"),
			"TYPE_RESULT" => Array("FIELD" => $NAME.".TYPE_RESULT", "TYPE" => "string"),
			"SHOW_COMMENTS" => Array("FIELD" => $NAME.".SHOW_COMMENTS", "TYPE" => "string"),
            "MULTIPLE_QUESTION" => Array("FIELD" => $NAME.".MULTIPLE_QUESTION", "TYPE" => "string"),
            "MULTIPLE_QUESTION_COUNT" => Array("FIELD" => $NAME.".MULTIPLE_QUESTION_COUNT", "TYPE" => "int"),
            "USE_CORRECT" => Array("FIELD" => $NAME.".USE_CORRECT", "TYPE" => "string"),
            "SPONSOR_NAME" => Array("FIELD" => $NAME.".SPONSOR_NAME", "TYPE" => "string"),
            "SPONSOR_PICTURE" => Array("FIELD" => $NAME.".SPONSOR_PICTURE", "TYPE" => "int"),
            "SPONSOR_ALT" => Array("FIELD" => $NAME.".SPONSOR_ALT", "TYPE" => "string"),
            "SPONSOR_LINK" => Array("FIELD" => $NAME.".SPONSOR_LINK", "TYPE" => "string"),
            "SPONSOR_DESCRIPTION" => Array("FIELD" => $NAME.".SPONSOR_DESCRIPTION", "TYPE" => "string"),
            "SPONSOR_DESCRIPTION_TYPE" => Array("FIELD" => $NAME.".SPONSOR_DESCRIPTION_TYPE", "TYPE" => "string"),
            "COUNT_USER_AUTOR" => Array("FIELD" => $NAME.".COUNT_USER_AUTOR", "TYPE" => "string"),
			);
	}
	
	function CheckResultType(&$arFields,$strOperation,$Prop)
	{
		if (array_key_exists($Prop, $arFields) || $strOperation == "ADD")
		{
			$arFields[$Prop]=trim($arFields[$Prop]);
			if($arFields[$Prop]!="aver" && $arFields[$Prop]!="suer")
				$arFields[$Prop]="summ";
		}
		return true;
	}

    function CheckMultipleQuestion(&$arFields,$strOperation,$Prop)
    {
        if (array_key_exists($Prop, $arFields) || $strOperation == "ADD")
        {
            $arFields[$Prop]=trim($arFields[$Prop]);
            if($arFields[$Prop]!="anum" && $arFields[$Prop]!="gnum" && $arFields[$Prop]!="allq" && $arFields[$Prop]!="clst")
                $arFields[$Prop]="none";
        }
        return true;
    }
	
	public function GetAfterFields(&$arFields,$NAME)
	{
		$arFields["COUNT"]=Array("FIELD" => "COUNT(".$NAME.".ID)", "TYPE" => "string","AS"=>"COUNT");
		
		$JoinName="ACS";
		$JoinTable="b_aelita_test_questioning";
		$JoinAlias="COUNT(".$JoinName.".ID)";
		$JoinAs="ATTEMPTS";
		$JoinOn="ID";
		$arFields[$JoinAs]=Array(
			"FIELD" => $JoinAlias, 
			"TYPE" => "int",
			"JOIN"=>array(
				"LEFT OUTER JOIN ".$JoinTable." ".$JoinName." ON ".$NAME.".".$JoinOn."=".$JoinName.".TEST_ID",
				),
			"AS"=>$JoinAs
		);
		
		$JoinName="ACT";
		$JoinTable="b_aelita_test_access_test";
		$JoinAlias="USER_GROUP_ID";
		$JoinAs="ACCESS_TEST";
		$JoinOn="TEST_ID";
		$arFields[$JoinAs]=Array(
			"FIELD" => $JoinName.".".$JoinAlias, 
			"TYPE" => "string",
			"JOIN"=>array(
				"LEFT OUTER JOIN ".$JoinTable." ".$JoinName." ON ".$NAME.".ID"."=".$JoinName.".".$JoinOn,
				),
			"AS"=>$JoinAs
		);
		
		$JoinName="AGT";
		$JoinTable="b_aelita_test_access_group";
		$JoinAlias="USER_GROUP_ID";
		$JoinAs="ACCESS_ALL_GROUP";
		$JoinOn="GROUP_ID";
		$arFields[$JoinAs]=Array(
			"FIELD" => $JoinName.".".$JoinAlias, 
			"TYPE" => "string",
			"JOIN"=>array(
				"LEFT OUTER JOIN ".$JoinTable." ".$JoinName." ON ".$NAME.".GROUP_ID"."=".$JoinName.".".$JoinOn,
				),
			"AS"=>$JoinAs
		);
		
		$JoinName="ACG";
		$JoinTable="b_aelita_test_group";
		$JoinAlias="ACCESS_ALL";
		$JoinAs="GROUP_ACCESS_ALL";
		$JoinOn="ID";
		$arFields[$JoinAs]=Array(
			"FIELD" => $JoinName.".".$JoinAlias, 
			"TYPE" => "string",
			"JOIN"=>array(
				"LEFT OUTER JOIN ".$JoinTable." ".$JoinName." ON ".$NAME.".GROUP_ID"."=".$JoinName.".".$JoinOn,
				),
			"AS"=>$JoinAs
		);
		
		$JoinName="ACG";
		$JoinTable="b_aelita_test_group";
		$JoinAlias="CODE";
		$JoinAs="GROUP_CODE";
		$JoinOn="ID";
		$arFields[$JoinAs]=Array(
			"FIELD" => $JoinName.".".$JoinAlias, 
			"TYPE" => "string",
			"JOIN"=>array(
				"LEFT OUTER JOIN ".$JoinTable." ".$JoinName." ON ".$NAME.".GROUP_ID"."=".$JoinName.".".$JoinOn,
				),
			"AS"=>$JoinAs
		);
		
	}
	
	function DelRelated($ID)
	{
	
		$el =new AelitaTestResult;
		$el->GroupDelete("TEST_ID", $ID);
		
		//$el =new AelitaTestAnswer;
		//$el->GroupDelete("TEST_ID", $ID);
		
		$el =new AelitaTestQuestion;
		$el->GroupDelete("TEST_ID", $ID);
		
		//$el =new AelitaTestGlasses;
		//$el->GroupDelete("TEST_ID", $ID);
		
		$el =new AelitaTestQuestioning;
		$el->GroupDelete("TEST_ID", $ID);
		
		$el =new AelitaTestAccessTest;
		$el->GroupDelete("TEST_ID", $ID);
		
		$el =new AelitaTestQuestionGroup;
		$el->GroupDelete("TEST_ID", $ID);
		
		return true;
	}
	
	function BeforeDelete($ID)
	{
		$this->DelRelated($ID);
		$this->DeletePicture($ID,'PICTURE');
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
