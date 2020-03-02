<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$module_id="aelita.test";
if(!CModule::IncludeModule($module_id))
	return;

$arParams["DETAIL_URL"]=trim($arParams["DETAIL_URL"]);

$arResult = array();

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
}
else
{
	$arNavParams = array(
		"nTopCount" => $arParams["COUNT_TEST"],
		"bDescPageNumbering" => $arParams["PAGER_DESC_NUMBERING"],
	);
	$arNavigation = false;
}

$arNavParams["iNumPage"]=$arNavigation["PAGEN"];

$arResult["ITEMS"]=array();


$Sort=array("ID"=>"DESC");

$Group=false;
$Filter=array(
	"PROFILE_ID"=>$arResult["PROFAIL_ID"]["ID"],
	//"ACTIVE"=>"Y",
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
	"TEST_NAME",
	"TEST_CODE",
    "TEST_PICTURE",
    "TEST_ALT",
	"TEST_DESCRIPTION_TYPE",
	"TEST_DESCRIPTION",
	);
if($arNavigation["SHOW_ALL"]==1)
	$arNavigation["SHOW_ALL"]="Y";
if($arNavigation["SHOW_ALL"]=="Y")
	$NavParams=false;
else
	$NavParams=$arNavParams;
$el=new AelitaTestQuestioning();
$res=$el->GetList(array(),$Filter,$Group,false,array("COUNT_TEST"));
if($test=$res->GetNext())
{
	$count=(int)$test["COUNT_TEST"];
	if($count>0){
		$Group=array("TEST_ID");
		$res=$el->GetList($Sort,$Filter,$Group,$NavParams,$arSelect);
		
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
			//if(strlen($test["CODE"])>0)
			//	$Code=$test["CODE"];
			//else
				$Code=$test["TEST_ID"];
			$test["DETAIL_URL"]=str_replace("#TEST_CODE#", $Code, $test["DETAIL_URL"]);
			
			$elScores=new AelitaTestQuestioning();
			$SorScorest=array("SCORES"=>"DESC");
			$FilterScores=array(
				"PROFILE_ID"=>$arResult["PROFAIL_ID"]["ID"],
				"TEST_ID"=>$test["TEST_ID"],
			//	"FINAL"=>"Y",
				);
			$arSelectScores=array(
				"ID",
				"SCORES",
				);
			$GroupScores=array("ID");
			$NavParamsScores=array("nPageSize"=>1);
			$resScores=$elScores->GetList($SorScorest,$FilterScores,$GroupScores,$NavParamsScores,$arSelectScores);
			if($Scores=$resScores->GetNext())
				$test["SCORES"]=$Scores["SCORES"];
			//if(!$test["SCORES"])
			//	$test["SCORES"]=0;
			$arResult["ITEMS"][]=$test;
		}
										
		$arResult["NAV_STRING"]=$res->GetPageNavStringEx($navComponentObject, $arParams["PAGER_TITLE"], $arParams["PAGER_TEMPLATE"], $arParams["PAGER_SHOW_ALWAYS"]);
		$arResult["NAV_CACHED_DATA"] = $navComponentObject->GetTemplateCachedData();
		$arResult["NAV_RESULT"] = $res;
	}
}
$this->IncludeComponentTemplate();
?>