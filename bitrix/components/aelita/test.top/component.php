<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$module_id="aelita.test";
if(!CModule::IncludeModule($module_id))
	return;
	
$arParams["CACHE_TIME"]=(int)$arParams["CACHE_TIME"];

if(!isset($arParams["CACHE_TIME"]))
	$arParams["CACHE_TIME"] = 36000;

$arParams["COUNT_TOP"]=(int)$arParams["COUNT_TOP"];

if($arParams["COUNT_TOP"]<=0)
	$arParams["COUNT_TOP"]=5;

$arParams["TEST_GROUP"]=trim($arParams["TEST_GROUP"]);
$arParams["TEST_ID"]=trim($arParams["TEST_ID"]);

$arParams["HIDE_UNAUTHORIZED_USERS"] = $arParams["HIDE_UNAUTHORIZED_USERS"]=="Y";
$arParams["SHOW_POINTS_TOP"]=$arParams["SHOW_POINTS_TOP"]=="Y";

if($this->StartResultCache(false, array(($arParams["CACHE_GROUPS"]==="N"? false: $USER->GetGroups()),$arParams)))
{
	$arResult=array();
	$arResult["ID_GROUP"]=(int)$arParams["TEST_GROUP"];
	if(strlen($arParams["TEST_GROUP"])>0 && $arParams["TEST_GROUP"]<>"0")
		$arResult["CODE_GROUP"]=$arParams["TEST_GROUP"];

	$FilterGroup=array();
	if(strlen($arResult["CODE_GROUP"])>0)
		$FilterGroup["CODE"]=$arResult["CODE_GROUP"];
	elseif($arResult["ID_GROUP"]>0)
		$FilterGroup["ID"]=$arResult["ID_GROUP"];

	if(count($FilterGroup)>0)
	{
		$FilterGroup["ACTIVE"]="Y";
		$el=new AelitaTestGroup();
		$res=$el->GetList(array(),$FilterGroup,false,array("nPageSize"=>1));
		if($test=$res->GetNext())
		{
			$arResult["GROUP"]=$test;
		}elseif($arResult["ID_GROUP"]>0){
			unset($FilterGroup["CODE"]);
			$FilterGroup["ID"]=$arResult["ID_GROUP"];
			$res=$el->GetList(array(),$FilterGroup,false,array("nPageSize"=>1));
			if($test=$res->GetNext())
				$arResult["GROUP"]=$test;
		}
	}

	$arResult["TEST_ID"]=(int)$arParams["TEST_ID"];
	if(strlen($arParams["TEST_ID"])>0 && $arParams["TEST_ID"]<>"0")
		$arResult["CODE_TEST"]=$arParams["TEST_ID"];

	$TestGroup=array();
	if(strlen($arResult["CODE_TEST"])>0)
		$TestGroup["CODE"]=$arResult["CODE_TEST"];
	elseif($arResult["TEST_ID"]>0)
		$TestGroup["ID"]=$arResult["TEST_ID"];

	if(count($TestGroup)>0)
	{
		$TestGroup["ACTIVE"]="Y";
		$TestGroup["GROUP_ID"]=$arResult["GROUP"]["ID"];
		$el=new AelitaTestTest();
		$res=$el->GetList(array(),$TestGroup,false,array("nPageSize"=>1));
		if($test=$res->GetNext())
		{
			$arResult["TEST"]=$test;
		}elseif($arResult["TEST_ID"]>0){
			unset($TestGroup["CODE"]);
			$TestGroup["ID"]=$arResult["TEST_ID"];
			$res=$el->GetList(array(),$TestGroup,false,array("nPageSize"=>1));
			if($test=$res->GetNext())
				$arResult["TEST"]=$test;
		}
	}

	$ArrProfile=array();

	if($arResult["TEST"])
	{
		$el=new AelitaTestQuestioning();
		
		$Sort=array("SCORES"=>"DESC","DATE_START"=>"DESC");

		$Filter=array(
			"TEST_ID"=>$arResult["TEST"]["ID"],
			);
		if($arParams["HIDE_UNAUTHORIZED_USERS"]=="Y")
			$Filter["<>USER_ID"]="0";
		$arSelect=array(
			"ID",
			"PROFILE_ID",
			"TEST_ID",
			"RESULT_ID",
			"CLOSED",
			"FINAL",
			"DATE_START",
			"DATE_STOP",
			"DURATION",
			"SCORES",
			"USER_ID",
			);
		$arNavParams = array(
			"nTopCount"=>$arParams["COUNT_TOP"],
		);
		$res=$el->GetList($Sort,$Filter,array("ID"),array("nTopCount"=>1000),$arSelect);
		while($test=$res->GetNext())
		{
			if(!in_array($test["PROFILE_ID"],$ArrProfile))
			{
				$ArrProfile[]=$test["PROFILE_ID"];
				if($test["USER_ID"]>0)
				{
					$rsUser = CUser::GetByID($test["USER_ID"]);
					$arUser = $rsUser->Fetch();
					$txt=$arUser["NAME"]." ".$arUser["LAST_NAME"]." ".$arUser["SECOND_NAME"];
					$txt=trim($txt);
					if(strlen($txt)>0)
						$txt=$txt." (".$arUser["NAME"].")";
					else
						$txt=$arUser["LOGIN"];
					if(!strlen($txt)>0)
						$txt=GetMessage("NO_USER");
					$test["TXT_USER"]=$txt;
				}
				if($test["DURATION"])
					$test["TXT_DURATION"]=AelitaTestTools::GetTxtTime($test["DURATION"]);
				$arResult["ITEMS"][]=$test;
				if(count($arResult["ITEMS"])>=$arParams["COUNT_TOP"])
					break;
			}
		}
	}
	$this->IncludeComponentTemplate();
}
?>