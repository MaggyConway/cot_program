<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$module_id="aelita.test";
if(!CModule::IncludeModule($module_id))
	return;

$arParams["DETAIL_URL"]=trim($arParams["DETAIL_URL"]);
$arParams["TEST_URL"]=trim($arParams["TEST_URL"]);
$arParams["REPEATED_URL"]=trim($arParams["REPEATED_URL"]);

$arParams["TEST_GROUP"]=trim($arParams["TEST_GROUP"]);
$arParams["TEST_ID"]=trim($arParams["TEST_ID"]);

$arParams["ADD_TEST_CHAIN"] = $arParams["ADD_TEST_CHAIN"]=="Y";
$arParams["SET_TITLE_TEST"] = $arParams["SET_TITLE_TEST"]=="Y";

$arParams["ADD_QUESTIONING_CHAIN"] = $arParams["ADD_QUESTIONING_CHAIN"]=="Y";
$arParams["SET_TITLE_QUESTIONING"] = $arParams["SET_TITLE_QUESTIONING"]=="Y";

$arParams["NON_CHECK_USER"] = $arParams["NON_CHECK_USER"]=="Y";


$arResult = array();

$arResult["PROFAIL_ID"]=AelitaTestTools::GetIDProfail();

$arResult["QUESTIONING_ID"]=(int)$arParams["QUESTIONING_ID"];

$arResult["ITEMS"]=array();

$Sort=array("SCORES"=>"DESC","DATE_START"=>"DESC");

$Filter=array(
	"ID"=>$arResult["QUESTIONING_ID"],
	);

if(!$arParams["NON_CHECK_USER"])
    $Filter["PROFILE_ID"]=$arResult["PROFAIL_ID"]["ID"];

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
	"RESULT_NAME",
	"RESULT_PICTURE",
    "RESULT_ALT",
    "RESULT_DESCRIPTION",
    "RESULT_DESCRIPTION_TYPEPICTURE",
	);
	
global $USER;
$USER_ID=$USER->GetID();
$arGroups=CUser::GetUserGroup($USER_ID);

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
	"SHOW_ANSWERS",
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
	
	$arResult["TEST"]["TEST_URL"]=$arParams["TEST_URL"];
	$Code=$arResult["TEST"]["ID"];
	$arResult["TEST"]["TEST_URL"]=str_replace("#TEST_CODE#", $Code, $arResult["TEST"]["TEST_URL"]);
	
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
	$res=$el->GetList($Sort,$Filter,array("ID"),array("nPageSize"=>1),$arSelect);
	if($test=$res->GetNext())
	{
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
		//$arResult["ITEMS"][]=$test;

        if($test["RESULT_PICTURE"])
            $test["RESULT_PICTURE"] = AelitaTestTools::GetWatermarkPicture($test["RESULT_PICTURE"],$test["RESULT_ALT"]);

		$arResult["QUESTIONING"]=$test;
	}
}

if($arResult["QUESTIONING"])
{
	$arFilter=array(
		"QUESTIONING_ID"=>$arResult["QUESTIONING"]["ID"],
	);
	$arSelect=array(
		"ID",
		"QUESTION_ID",
		"QUESTION_NAME",
		"SCORES",
		"SERIALIZED_RESULT",
        "SERIALIZED_RESULT_TEXT",
		);
	$arSort=array("ID"=>"ASC");
	$el=new AelitaTestGlasses();
	$res=$el->GetList($arSort,$arFilter,false,false,$arSelect);
	while($test=$res->GetNext())
	{
		if($test["SERIALIZED_RESULT"])
		{
			$test["SERIALIZED_RESULT"]=unserialize(base64_decode($test["SERIALIZED_RESULT"]));
			$test["SERIALIZED_RESULT"]=implode("<br />",$test["SERIALIZED_RESULT"]);
		}
        if($test["SERIALIZED_RESULT_TEXT"])
        {
            $test["SERIALIZED_RESULT_TEXT"]=unserialize(base64_decode($test["SERIALIZED_RESULT_TEXT"]));
            $test["SERIALIZED_RESULT_TEXT"]=implode("<br />",$test["SERIALIZED_RESULT_TEXT"]);
        }
		if($arResult["TEST"]["SHOW_ANSWERS"]=="Y")
		{
			$test["ANSWERS"]=AelitaTestTools::GetShowAnswers($arResult["TEST"]["ID"],$test["QUESTION_ID"]);
			$test["ANSWERS"]=implode("<br />",$test["ANSWERS"]);
		}
		$arResult["ITEMS"][]=$test;
	}
}

$this->IncludeComponentTemplate();

if($arParams["ADD_TEST_CHAIN"] && $arResult["TEST"]["NAME"])
	$APPLICATION->AddChainItem($arResult["TEST"]["NAME"],$arResult["TEST"]["TEST_URL"]);
	
if($arParams["SET_TITLE_TEST"] && $arResult["TEST"]["NAME"])
{
	$APPLICATION->SetTitle($arResult["TEST"]["NAME"]);
	$APPLICATION->SetPageProperty('title',$arResult["TEST"]["NAME"]);	
}
	
if($arParams["ADD_QUESTIONING_CHAIN"] && $arResult["QUESTIONING"])
	$APPLICATION->AddChainItem(GetMessage("ATTEMPT_NUMBER").$arResult["QUESTIONING"]["DATE_START"]);

if($arParams["SET_TITLE_QUESTIONING"] && $arResult["QUESTIONING"])
{
	$APPLICATION->SetTitle(GetMessage("ATTEMPT_NUMBER").$arResult["QUESTIONING"]["DATE_START"]);
	$APPLICATION->SetPageProperty('title',GetMessage("ATTEMPT_NUMBER").$arResult["QUESTIONING"]["DATE_START"]);
}
?>