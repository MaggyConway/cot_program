<?
/**
* Aelita Test Questioning
* 
* @author Danilin Alexander I. <Danilin2010@yandex.ru>
* @version 1.0
**/
IncludeModuleLangFile(__FILE__);
class AelitaTestQuestioning extends AelitaElement
{
	
	function __construct()
	{
		$this->TABLE_NAME= "b_aelita_test_questioning";
	}

	function CheckFields(&$arFields, $strOperation = 'ADD')
	{
		if(!$this->CheckProfileID($arFields,$strOperation,'PROFILE_ID')) return false;
		if(!$this->CheckTestID($arFields,$strOperation,'TEST_ID')) return false;
		if(!$this->CheckResultID($arFields,$strOperation,'RESULT_ID')) return false;
		$this->CheckActive($arFields,$strOperation,'CLOSED');
		$this->CheckActive($arFields,$strOperation,'FINAL');
		$this->CheckInt($arFields,$strOperation,'DURATION');
		$this->CheckInt($arFields,$strOperation,'AVER_DURATION');
		$this->CheckInt($arFields,$strOperation,'GLASSES_ID');
		return true;
	}
	
	public function GetFields(&$arFields,$NAME)
	{
		$arFields = array(
			"ID" => Array("FIELD" => $NAME.".ID", "TYPE" => "int"),
			"PROFILE_ID" => Array("FIELD" => $NAME.".PROFILE_ID", "TYPE" => "int"),
			"TEST_ID" => Array("FIELD" => $NAME.".TEST_ID", "TYPE" => "int"),
			"RESULT_ID" => Array("FIELD" => $NAME.".RESULT_ID", "TYPE" => "int"),
			"CLOSED" => Array("FIELD" => $NAME.".CLOSED", "TYPE" => "string"),
			"FINAL" => Array("FIELD" => $NAME.".FINAL", "TYPE" => "string"),
			"DATE_START" => Array("FIELD" => $NAME.".DATE_START", "TYPE" => "string"),
			"DATE_STOP" => Array("FIELD" => $NAME.".DATE_STOP", "TYPE" => "string"),
			"DURATION" => Array("FIELD" => $NAME.".DURATION", "TYPE" => "int"),
			"GLASSES_ID" => Array("FIELD" => $NAME.".GLASSES_ID", "TYPE" => "int"),

            "STEP_MULTIPLE" => Array("FIELD" => $NAME.".STEP_MULTIPLE", "TYPE" => "string"),

			);
	}
	
	public function GetAfterFields(&$arFields,$NAME)
	{
		$arFields["COUNT"]=Array("FIELD" => "COUNT(".$NAME.".ID)", "TYPE" => "string","AS"=>"COUNT");
		$arFields["COUNT_TEST"]=Array("FIELD" => "COUNT(DISTINCT ".$NAME.".TEST_ID)", "TYPE" => "string","AS"=>"COUNT_TEST");
		
		$JoinName="ACC";
		$JoinTable="b_aelita_test_test";
		$JoinAlias="NAME";
		$JoinAs="TEST_NAME";
		$JoinOn="TEST_ID";
		$arFields[$JoinAs]=Array(
			"FIELD" => $JoinName.".".$JoinAlias, 
			"TYPE" => "string",
			"JOIN"=>array(
				"LEFT OUTER JOIN ".$JoinTable." ".$JoinName." ON ".$NAME.".".$JoinOn."=".$JoinName.".ID",
				),
			"AS"=>$JoinAs
		);
		
		$JoinName="ACC";
		$JoinTable="b_aelita_test_test";
		$JoinAlias="CODE";
		$JoinAs="TEST_CODE";
		$JoinOn="TEST_ID";
		$arFields[$JoinAs]=Array(
			"FIELD" => $JoinName.".".$JoinAlias, 
			"TYPE" => "string",
			"JOIN"=>array(
				"LEFT OUTER JOIN ".$JoinTable." ".$JoinName." ON ".$NAME.".".$JoinOn."=".$JoinName.".ID",
				),
			"AS"=>$JoinAs
		);
		
		$JoinName="ACC";
		$JoinTable="b_aelita_test_test";
		$JoinAlias="DESCRIPTION";
		$JoinAs="TEST_DESCRIPTION";
		$JoinOn="TEST_ID";
		$arFields[$JoinAs]=Array(
			"FIELD" => $JoinName.".".$JoinAlias, 
			"TYPE" => "string",
			"JOIN"=>array(
				"LEFT OUTER JOIN ".$JoinTable." ".$JoinName." ON ".$NAME.".".$JoinOn."=".$JoinName.".ID",
				),
			"AS"=>$JoinAs
		);
		
		$JoinName="ACC";
		$JoinTable="b_aelita_test_test";
		$JoinAlias="SHOW_COMMENTS";
		$JoinAs="TEST_SHOW_COMMENTS";
		$JoinOn="TEST_ID";
		$arFields[$JoinAs]=Array(
			"FIELD" => $JoinName.".".$JoinAlias, 
			"TYPE" => "string",
			"JOIN"=>array(
				"LEFT OUTER JOIN ".$JoinTable." ".$JoinName." ON ".$NAME.".".$JoinOn."=".$JoinName.".ID",
				),
			"AS"=>$JoinAs
		);
		
		$JoinName="ACC";
		$JoinTable="b_aelita_test_test";
		$JoinAlias="TYPE_RESULT";
		$JoinAs="TEST_TYPE_RESULT";
		$JoinOn="TEST_ID";
		$arFields[$JoinAs]=Array(
			"FIELD" => $JoinName.".".$JoinAlias, 
			"TYPE" => "string",
			"JOIN"=>array(
				"LEFT OUTER JOIN ".$JoinTable." ".$JoinName." ON ".$NAME.".".$JoinOn."=".$JoinName.".ID",
				),
			"AS"=>$JoinAs
		);
		
		$JoinName="ACC";
		$JoinTable="b_aelita_test_test";
		$JoinAlias="DESCRIPTION_TYPE";
		$JoinAs="TEST_DESCRIPTION_TYPE";
		$JoinOn="TEST_ID";
		$arFields[$JoinAs]=Array(
			"FIELD" => $JoinName.".".$JoinAlias, 
			"TYPE" => "string",
			"JOIN"=>array(
				"LEFT OUTER JOIN ".$JoinTable." ".$JoinName." ON ".$NAME.".".$JoinOn."=".$JoinName.".ID",
				),
			"AS"=>$JoinAs
		);
		
		$JoinName="ACC";
		$JoinTable="b_aelita_test_test";
		$JoinAlias="PICTURE";
		$JoinAs="TEST_PICTURE";
		$JoinOn="TEST_ID";
		$arFields[$JoinAs]=Array(
			"FIELD" => $JoinName.".".$JoinAlias, 
			"TYPE" => "string",
			"JOIN"=>array(
				"LEFT OUTER JOIN ".$JoinTable." ".$JoinName." ON ".$NAME.".".$JoinOn."=".$JoinName.".ID",
				),
			"AS"=>$JoinAs
		);

        $JoinName="ACC";
        $JoinTable="b_aelita_test_test";
        $JoinAlias="ALT";
        $JoinAs="TEST_ALT";
        $JoinOn="TEST_ID";
        $arFields[$JoinAs]=Array(
            "FIELD" => $JoinName.".".$JoinAlias,
            "TYPE" => "string",
            "JOIN"=>array(
                "LEFT OUTER JOIN ".$JoinTable." ".$JoinName." ON ".$NAME.".".$JoinOn."=".$JoinName.".ID",
            ),
            "AS"=>$JoinAs
        );
		
		$JoinName="ACT";
		$JoinTable="b_aelita_test_result";
		$JoinAlias="NAME";
		$JoinAs="RESULT_NAME";
		$JoinOn="RESULT_ID";
		$arFields[$JoinAs]=Array(
			"FIELD" => $JoinName.".".$JoinAlias, 
			"TYPE" => "string",
			"JOIN"=>array(
				"LEFT OUTER JOIN ".$JoinTable." ".$JoinName." ON ".$NAME.".".$JoinOn."=".$JoinName.".ID",
				),
			"AS"=>$JoinAs
		);

        $JoinName="ACT";
        $JoinTable="b_aelita_test_result";
        $JoinAlias="PICTURE";
        $JoinAs="RESULT_PICTURE";
        $JoinOn="RESULT_ID";
        $arFields[$JoinAs]=Array(
            "FIELD" => $JoinName.".".$JoinAlias,
            "TYPE" => "string",
            "JOIN"=>array(
                "LEFT OUTER JOIN ".$JoinTable." ".$JoinName." ON ".$NAME.".".$JoinOn."=".$JoinName.".ID",
            ),
            "AS"=>$JoinAs
        );

        $JoinName="ACT";
        $JoinTable="b_aelita_test_result";
        $JoinAlias="ALT";
        $JoinAs="RESULT_ALT";
        $JoinOn="RESULT_ID";
        $arFields[$JoinAs]=Array(
            "FIELD" => $JoinName.".".$JoinAlias,
            "TYPE" => "string",
            "JOIN"=>array(
                "LEFT OUTER JOIN ".$JoinTable." ".$JoinName." ON ".$NAME.".".$JoinOn."=".$JoinName.".ID",
            ),
            "AS"=>$JoinAs
        );

        $JoinName="ACT";
        $JoinTable="b_aelita_test_result";
        $JoinAlias="DESCRIPTION";
        $JoinAs="RESULT_DESCRIPTION";
        $JoinOn="RESULT_ID";
        $arFields[$JoinAs]=Array(
            "FIELD" => $JoinName.".".$JoinAlias,
            "TYPE" => "string",
            "JOIN"=>array(
                "LEFT OUTER JOIN ".$JoinTable." ".$JoinName." ON ".$NAME.".".$JoinOn."=".$JoinName.".ID",
            ),
            "AS"=>$JoinAs
        );

        $JoinName="ACT";
        $JoinTable="b_aelita_test_result";
        $JoinAlias="DESCRIPTION_TYPE";
        $JoinAs="RESULT_DESCRIPTION_TYPE";
        $JoinOn="RESULT_ID";
        $arFields[$JoinAs]=Array(
            "FIELD" => $JoinName.".".$JoinAlias,
            "TYPE" => "string",
            "JOIN"=>array(
                "LEFT OUTER JOIN ".$JoinTable." ".$JoinName." ON ".$NAME.".".$JoinOn."=".$JoinName.".ID",
            ),
            "AS"=>$JoinAs
        );

		$JoinName="ACP";
		$JoinTable="b_aelita_test_profile";
		$JoinAlias="USER_ID";
		$JoinAs="USER_ID";
		$JoinOn="PROFILE_ID";
		$arFields[$JoinAs]=Array(
			"FIELD" => $JoinName.".".$JoinAlias, 
			"TYPE" => "string",
			"JOIN"=>array(
				"LEFT OUTER JOIN ".$JoinTable." ".$JoinName." ON ".$NAME.".".$JoinOn."=".$JoinName.".ID",
				),
			"AS"=>$JoinAs
		);
		
		$JoinName="ACS";
		$JoinTable="b_aelita_test_glasses";
		$JoinAlias="SUM(".$JoinName.".SCORES)";
		$JoinAs="SCORES";
		$JoinOn="ID";
		$arFields[$JoinAs]=Array(
			"FIELD" => $JoinAlias, 
			"TYPE" => "int",
			"JOIN"=>array(
				"LEFT OUTER JOIN ".$JoinTable." ".$JoinName." ON ".$NAME.".".$JoinOn."=".$JoinName.".QUESTIONING_ID",
				),
			"AS"=>$JoinAs
		);
	}
	
	function BeforeDelete($ID)
	{
		$el =new AelitaTestGlasses;
		$el->GroupDelete("QUESTIONING_ID", $ID);
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
	
	function CheckProfileID(&$arFields,$strOperation,$Prop)
	{
		global $APPLICATION;
		if (array_key_exists($Prop, $arFields) || $strOperation == "ADD")
		{
			$arFields[$Prop]=(int)$arFields[$Prop];
			if ($arFields[$Prop]>0){
				$el =new AelitaTestProfile;
				$arExtra=$el->GetByID($arFields[$Prop]);
				if (!$arExtra=$arExtra->GetNext())
				{
					$APPLICATION->ThrowException(GetMessage("ERROR_ATG_NO_PROFILE"));
					return false;
				}
			}else{
				$APPLICATION->ThrowException(GetMessage("ERROR_ATG_PROFILE"));
				return false;
			}
		}
		return true;
	}
	
	function CheckResultID(&$arFields,$strOperation,$Prop)
	{
		global $APPLICATION;
		if (array_key_exists($Prop, $arFields))
		{
			$arFields[$Prop]=(int)$arFields[$Prop];
			if ($arFields[$Prop]>0){
				$el =new AelitaTestResult;
				$arExtra=$el->GetByID($arFields[$Prop]);
				if (!$arExtra=$arExtra->GetNext())
				{
					$APPLICATION->ThrowException(GetMessage("ERROR_ATG_NO_RESULT"));
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
