<?
/**
* Aelita Test Group
* 
* @author Danilin Alexander I. <Danilin2010@yandex.ru>
* @version 1.0
**/
IncludeModuleLangFile(__FILE__);
class AelitaTestGroup extends AelitaElement
{
	
	function __construct()
	{
		$this->TABLE_NAME= "b_aelita_test_group";
	}

	function CheckFields(&$arFields, $strOperation = 'ADD')
	{
		if(!$this->CheckName($arFields,$strOperation,'NAME')) return false;
		if(!$this->CheckImages($arFields,$strOperation,'PICTURE')) return false;

		$this->CheckActive($arFields,$strOperation,'ACTIVE');
		$this->CheckType($arFields,$strOperation,'DESCRIPTION_TYPE');
		$this->CheckSort($arFields,$strOperation,'SORT');
		
		$this->CheckActive($arFields,$strOperation,'ACCESS_ALL');
		
		return true;
	}
	
	public function GetFields(&$arFields,$NAME)
	{
		$arFields = array(
			"ID" => Array("FIELD" => $NAME.".ID", "TYPE" => "int"),
			"XML_ID" => Array("FIELD" => $NAME.".XML_ID", "TYPE" => "string"),
            "GROUP_ID" => Array("FIELD" => $NAME.".GROUP_ID", "TYPE" => "int"),
			"NAME" => Array("FIELD" => $NAME.".NAME", "TYPE" => "string"),
			"ACTIVE" => Array("FIELD" => $NAME.".ACTIVE", "TYPE" => "string"),
			"PICTURE" => Array("FIELD" => $NAME.".PICTURE", "TYPE" => "int"),
            "ALT" => Array("FIELD" => $NAME.".ALT", "TYPE" => "string"),
			"DESCRIPTION" => Array("FIELD" => $NAME.".DESCRIPTION", "TYPE" => "string"),
			"DESCRIPTION_TYPE" => Array("FIELD" => $NAME.".DESCRIPTION_TYPE", "TYPE" => "string"),
			"SORT" => Array("FIELD" => $NAME.".SORT", "TYPE" => "int"),
			"CODE" => Array("FIELD" => $NAME.".CODE", "TYPE" => "string"),
			"ACCESS_ALL" => Array("FIELD" => $NAME.".ACCESS_ALL", "TYPE" => "string"),
			);
	}
	
	public function GetAfterFields(&$arFields,$NAME)
	{
		$arFields["COUNT"]=Array("FIELD" => "COUNT(".$NAME.".ID)", "TYPE" => "string","AS"=>"COUNT");
		
		$JoinName="ACT";
		$JoinTable="b_aelita_test_access_group";
		$JoinAlias="USER_GROUP_ID";
		$JoinAs="ACCESS_GROUP";
		$JoinOn="GROUP_ID";
		$arFields[$JoinAs]=Array(
			"FIELD" => $JoinName.".".$JoinAlias, 
			"TYPE" => "string",
			"JOIN"=>array(
				"LEFT OUTER JOIN ".$JoinTable." ".$JoinName." ON ".$NAME.".ID"."=".$JoinName.".".$JoinOn,
				),
			"AS"=>$JoinAs
		);

        $JoinName="ACG";
        $JoinTable="b_aelita_test_group";
        $JoinAlias="NAME";
        $JoinAs="GROUP_NAME";
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
	
	function ClearTest($ID)
	{
		$el =new AelitaTestTest;
		$arFields=array(
			"GROUP_ID"=>"",
			);
		$el->GroupUpdate("GROUP_ID", $ID, $arFields);
		return true;
	}
	
	function BeforeDelete($ID)
	{
		$this->ClearTest($ID);
		$this->DeletePicture($ID,'PICTURE');
		
		$el =new AelitaTestAccessGroup;
		$el->GroupDelete("GROUP_ID", $ID);
		
		return true;
	}

}
?>