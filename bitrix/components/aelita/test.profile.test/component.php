<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$module_id="aelita.test";
if(!CModule::IncludeModule($module_id))
	return;

$arParams["DETAIL_URL"]=trim($arParams["~DETAIL_URL"]);
$arParams["TEST_URL"]=trim($arParams["~TEST_URL"]);
$arParams["REPEATED_URL"]=trim($arParams["~REPEATED_URL"]);

$arParams["TEST_GROUP"]=trim($arParams["TEST_GROUP"]);
$arParams["TEST_ID"]=trim($arParams["TEST_ID"]);

$arParams["ADD_TEST_CHAIN"] = $arParams["ADD_TEST_CHAIN"]=="Y";
$arParams["SET_TITLE_TEST"] = $arParams["SET_TITLE_TEST"]=="Y";

$arResult = array();

global $USER;
$USER_ID=$USER->GetID();
$arGroups=CUser::GetUserGroup($USER_ID);

$arResult["PROFAIL_ID"]=AelitaTestTools::GetIDProfail();

$arParams["DISPLAY_TOP_PAGER"] = $arParams["DISPLAY_TOP_PAGER"]=="Y";
$arParams["DISPLAY_BOTTOM_PAGER"] = $arParams["DISPLAY_BOTTOM_PAGER"]!="N";
$arParams["PAGER_TITLE"] = trim($arParams["PAGER_TITLE"]);
$arParams["PAGER_SHOW_ALWAYS"] = $arParams["PAGER_SHOW_ALWAYS"]!="N";
$arParams["PAGER_TEMPLATE"] = trim($arParams["PAGER_TEMPLATE"]);
$arParams["PAGER_DESC_NUMBERING"] = $arParams["PAGER_DESC_NUMBERING"]=="Y";
$arParams["PAGER_DESC_NUMBERING_CACHE_TIME"] = intval($arParams["PAGER_DESC_NUMBERING_CACHE_TIME"]);
$arParams["PAGER_SHOW_ALL"] = $arParams["PAGER_SHOW_ALL"]!=="N";

if($arParams["DISPLAY_TOP_PAGER"] || $arParams["DISPLAY_BOTTOM_PAGER"])
{
	$arNavParams = array(
		"nPageSize" => $arParams["COUNT_TEST"],
		"bDescPageNumbering" => $arParams["PAGER_DESC_NUMBERING"],
		"bShowAll" => $arParams["PAGER_SHOW_ALL"],
	);
	$arNavigation = CDBResult::GetNavParams($arNavParams);
	if($arNavigation["PAGEN"]==0 && $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"]>0)
		$arParams["CACHE_TIME"] = $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"];
}else{
	$arNavParams = array(
		"nTopCount" => $arParams["COUNT_TEST"],
		"bDescPageNumbering" => $arParams["PAGER_DESC_NUMBERING"],
	);
	$arNavigation = false;
}

$arNavParams["iNumPage"]=$arNavigation["PAGEN"];

$arResult["ITEMS"]=array();


$Sort=array("SCORES"=>"DESC","DATE_START"=>"DESC");

$Filter=array(
	"PROFILE_ID"=>$arResult["PROFAIL_ID"]["ID"],
	);

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
    "TEST_PICTURE",
    "TEST_ALT",
	);
if($arNavigation["SHOW_ALL"]==1)
	$arNavigation["SHOW_ALL"]="Y";
if($arNavigation["SHOW_ALL"]=="Y")
	$NavParams=false;
else
	$NavParams=$arNavParams;

if(is_numeric($arParams["TEST_ID"]))
{
    $arResult["TEST_ID"]=(int)$arParams["TEST_ID"];
}else{
    if(strlen($arParams["TEST_ID"])>0 && $arParams["TEST_ID"]<>"0")
        $arResult["CODE_TEST"]=$arParams["TEST_ID"];
}
	
$TestGroup=array();
if(strlen($arResult["CODE_TEST"])>0)
	$TestGroup["CODE"]=$arResult["CODE_TEST"];
elseif($arResult["TEST_ID"]>0)
	$TestGroup["ID"]=$arResult["TEST_ID"];

$arrSelectTest=array(
	"ID",
	"XML_ID",
	"NAME",
	"ACTIVE",
	"PICTURE",
    "ALT",
	"DESCRIPTION",
	"DESCRIPTION_TYPE",
	"SORT",
	"GROUP_ID",
	"CODE",
	"ACCESS_ALL",
	"ACCESS_GROUP",
	"GROUP_CODE",
	"NUMBER_ATTEMPTS",
	"DATE_FROM",
	"DATE_TO",
	);
	
if(count($TestGroup)>0)
{
	$TestGroup["ACTIVE"]="Y";
	$el=new AelitaTestTest();
	$res=$el->GetList(array(),$TestGroup,false,array("nPageSize"=>1),$arrSelectTest);
	if($test=$res->GetNext())
	{
		$arResult["TEST"]=$test;
	}elseif($arResult["TEST_ID"]>0){
		unset($TestGroup["CODE"]);
		$TestGroup["ID"]=$arResult["TEST_ID"];
		$res=$el->GetList(array(),$TestGroup,false,array("nPageSize"=>1),$arrSelectTest);
		if($test=$res->GetNext())
			$arResult["TEST"]=$test;
	}
}

if($arResult["TEST"])
{
	$arResult["TEST_ID"]=$arResult["TEST"]["ID"];
	$Code="";
	if(strlen($arResult["TEST"]["CODE"])>0)
		$Code=$arResult["TEST"]["CODE"];
	else
		$Code=$arResult["TEST"]["ID"];
	$arResult["TEST"]["REPEATED_URL"]=str_replace("#TEST_CODE#", $Code, $arParams["REPEATED_URL"]);
	$Code="";
	if(strlen($arResult["TEST"]["GROUP_CODE"])>0)
		$Code=$arResult["TEST"]["GROUP_CODE"];
	else
		$Code=$arResult["TEST"]["GROUP_ID"];
	$arResult["TEST"]["REPEATED_URL"]=str_replace("#GROUP_CODE#", $Code, $arResult["TEST"]["REPEATED_URL"]);
	$arResult["TEST"]["SHOW_REPEATED"]="Y";
	
	$Filter["TEST_ID"]=$arResult["TEST"]["ID"];
}

if($arResult["TEST"])
{
	$Access=array();
	if($arResult["TEST"]["ACCESS_GROUP"]!="Y")
	{
		if($arResult["TEST"]["ACCESS_ALL"]!="Y")
		{
			$resAccess=new AelitaTestAccessTest();
			$props = $resAccess->GetList(array(), array("TEST_ID"=>$arResult["TEST"]["ID"]));
			while($p = $props->GetNext())
				$Access[]=$p["USER_GROUP_ID"];
		}
	}elseif($arResult["GROUP"]){
		if($arResult["GROUP"]["ACCESS_ALL"]!="Y")
		{
			$resAccess=new AelitaTestAccessGroup();
			$props = $resAccess->GetList(array(), array("GROUP_ID"=>$arResult["GROUP"]["ID"]));
			while($p = $props->GetNext())
				$Access[]=$p["USER_GROUP_ID"];
		}
	}
	if(count($Access)>0)
	{
		$Сonvergence = array_intersect($Access, $arGroups);
		if(count($Сonvergence)<=0)
			$arResult["TEST"]["SHOW_REPEATED"]="N";
	}
}

if($arResult["TEST"])
{
	$Date=ConvertDateTime(GetTime(time(),"FULL"), "YYYY-MM-DD HH:MI:SS");
	if(
		(!$arResult["TEST"]["DATE_FROM"] && !$arResult["TEST"]["DATE_TO"]) || 
		($arResult["TEST"]["DATE_FROM"]<$Date && !$arResult["TEST"]["DATE_TO"]) || 
		(!$arResult["TEST"]["DATE_FROM"] && $arResult["TEST"]["DATE_TO"]>$Date) || 
		($arResult["TEST"]["DATE_FROM"]<$Date && $arResult["TEST"]["DATE_TO"]>$Date)
	){
		//$Result["HIDE_RESULT"]="N";
	}else{
		$arResult["TEST"]["SHOW_REPEATED"]="N";
	}
}

if($arResult["TEST"] && $arResult["PROFAIL_ID"])
{
	$arResult["COUNT_QUESTIONING"]=AelitaTestTools::GetCountQuestioning($arResult["PROFAIL_ID"]["ID"],$arResult["TEST"]["ID"]);
	if($arResult["TEST"]["NUMBER_ATTEMPTS"]>0)
		if($arResult["COUNT_QUESTIONING"]>=$arResult["TEST"]["NUMBER_ATTEMPTS"])
			$arResult["TEST"]["SHOW_REPEATED"]="N";
}

if($arResult["TEST"])
{
	$el=new AelitaTestQuestioning();
	$res=$el->GetList(array(),$Filter,false,false,array("COUNT"));
	if($test=$res->GetNext())
	{
		$count=(int)$test["COUNT"];
		if($count>0){
			//$Group=array("TEST_ID");
			$res=$el->GetList($Sort,$Filter,array("ID"),$NavParams,$arSelect);
			
			$res->NavStart($arNavParams["nPageSize"]);
			if($arNavigation["SHOW_ALL"]!="Y")
				$res->NavPageCount=ceil($count/$arNavParams["nPageSize"]);
			$res->NavRecordCount=$count;
			$res->NavPageNomer=$arNavParams["iNumPage"];
			$res->bShowAll=$arNavParams["bShowAll"];
			$res->bDescPageNumbering=$arNavParams["bDescPageNumbering"];
			
			if($arNavParams["nPageSize"]<$count)
				$res->NavPageSize=$arNavParams["nPageSize"];
			else
				$res->NavPageSize=$count;
				
			while($test=$res->GetNext())
			{
				if($test["TEST_PICTURE"])
					$test["TEST_PICTURE"] = AelitaTestTools::GetWatermarkPicture($test["TEST_PICTURE"],$test["TEST_ALT"]);
				$test["DETAIL_URL"]=$arParams["DETAIL_URL"];
				$Code="";
				$Code=$test["TEST_ID"];
				$test["DETAIL_URL"]=str_replace("#TEST_CODE#", $Code, $test["DETAIL_URL"]);

				$Code="";
				$Code=$test["ID"];
				$test["DETAIL_URL"]=str_replace("#QUESTIONING_CODE#", $Code, $test["DETAIL_URL"]);

				if($test["DATE_START"])
					$test["DATE_START"]=ConvertTimeStamp(MakeTimeStamp($test["DATE_START"], "YYYY-MM-DD HH:MI:SS"),"FULL");
					
				if($test["DATE_STOP"])
					$test["DATE_STOP"]=ConvertTimeStamp(MakeTimeStamp($test["DATE_STOP"], "YYYY-MM-DD HH:MI:SS"),"FULL");
					
				if($test["DURATION"])
					$test["DURATION"]=AelitaTestTools::GetTxtTime($test["DURATION"]);

				$arResult["ITEMS"][]=$test;
			}
			$arResult["NAV_STRING"]=$res->GetPageNavStringEx($navComponentObject, $arParams["PAGER_TITLE"], $arParams["PAGER_TEMPLATE"], $arParams["PAGER_SHOW_ALWAYS"]);
			$arResult["NAV_CACHED_DATA"] = $navComponentObject->GetTemplateCachedData();
			$arResult["NAV_RESULT"] = $res;
		}
	}
}

$this->IncludeComponentTemplate();

if($arParams["ADD_TEST_CHAIN"] && $arResult["TEST"]["NAME"])
	$APPLICATION->AddChainItem($arResult["TEST"]["NAME"],$arResult["TEST"]["DETAIL_URL"]);
	
if($arParams["SET_TITLE_TEST"] && $arResult["TEST"]["NAME"])
{
	$APPLICATION->SetTitle($arResult["TEST"]["NAME"]);
	$APPLICATION->SetPageProperty('title',$arResult["TEST"]["NAME"]);	
}
?>
