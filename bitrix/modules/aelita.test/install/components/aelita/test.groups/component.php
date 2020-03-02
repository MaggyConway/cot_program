<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$module_id="aelita.test";
if(!CModule::IncludeModule($module_id))
	return;

if(!isset($arParams["CACHE_TIME"]))
	$arParams["CACHE_TIME"] = 36000;

$arParams["CACHE_TIME"]=(int)$arParams["CACHE_TIME"];

$arParams["DETAIL_URL"]=trim($arParams["DETAIL_URL"]);
$arResult = array();

global $USER;
$USER_ID=$USER->GetID();
$arGroups=CUser::GetUserGroup($USER_ID);

if($this->StartResultCache(false, array(($arParams["CACHE_GROUPS"]==="N"? false: $USER->GetGroups()),$arResult,$arParams)))
{
	$arResult["ITEMS"]=array();
	$Filter=array(
		"ACTIVE"=>"Y",
		array(
			"LOGIC"=>"OR",
			"ACCESS_ALL"=>"Y",
			"ACCESS_GROUP"=>$arGroups,
			),
		);
	if((int)$arParams["PARENT_GROUP"]>0 || strlen($arParams["PARENT_GROUP_CODE"])>0)
    {
        if((int)$arParams["PARENT_GROUP"]>0)
            $Filter["GROUP_ID"]=$arParams["PARENT_GROUP"];
        if(strlen($arParams["PARENT_GROUP_CODE"])>0)
            $Filter["GROUP_CODE"]=$arParams["PARENT_GROUP_CODE"];
    }else{
        $Filter[]=array(
            "LOGIC"=>"OR",
            ["LOGIC"=>"AND","GROUP_ID"=>false],
            ["LOGIC"=>"AND","GROUP_ID"=>0],
        );
    }
	$Sort=array("SORT"=>"ASC","ID"=>"ASC");
	$Select=array("ID","XML_ID","NAME","ACTIVE","PICTURE","ALT","DESCRIPTION","DESCRIPTION_TYPE","SORT","CODE","ACCESS_ALL");
	$Group=array("ID");
	$el=new AelitaTestGroup();
	$res=$el->GetList($Sort,$Filter,$Group,false,$Select);
	while($test=$res->GetNext())
	{
		if($test["PICTURE"])
			$test["PICTURE"] = AelitaTestTools::GetWatermarkPicture($test["PICTURE"],$test["ALT"]);
		$test["DETAIL_URL"]=$arParams["DETAIL_URL"];
		$Code="";
		if(strlen($test["CODE"])>0)
			$Code=$test["CODE"];
		else
			$Code=$test["ID"];
		$test["DETAIL_URL"]=str_replace("#GROUP_CODE#", $Code, $test["DETAIL_URL"]);
		$arResult["ITEMS"][]=$test;
	}
	$this->SetResultCacheKeys(array(
		"ID_GROUP",
		"GROUP",
		"ITEMS",
	));
	$this->IncludeComponentTemplate();
}

return $arResult["ITEMS"];
?>