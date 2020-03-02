<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$module_id="aelita.test";
if(!CModule::IncludeModule($module_id))
	return;

if(!isset($arParams["CACHE_TIME"]))
	$arParams["CACHE_TIME"] = 36000;

$arParams["CACHE_TIME"]=(int)$arParams["CACHE_TIME"];

$arParams["TEST_GROUP"]=trim($arParams["TEST_GROUP"]);
$arParams["DETAIL_URL"]=trim($arParams["~DETAIL_URL"]);
$arParams["LIST_PAGE_URL"]=trim($arParams["~LIST_PAGE_URL"]);
$arParams["MAIN_PAGE_URL"]=trim($arParams["~MAIN_PAGE_URL"]);

$arParams["SHOW_NO_GROUP"] = $arParams["SHOW_NO_GROUP"]=="Y";
$arParams["SHOW_ALL"] = $arParams["SHOW_ALL"]=="Y";
$arParams["ADD_GROUP_CHAIN"] = $arParams["ADD_GROUP_CHAIN"]=="Y";
$arParams["SET_TITLE_GROUP"] = $arParams["SET_TITLE_GROUP"]=="Y";

$arResult = array();

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

$arResult["ID_GROUP"]=(int)$arParams["TEST_GROUP"];
if(strlen($arParams["TEST_GROUP"])>0 && $arParams["TEST_GROUP"]<>"0")
	$arResult["CODE_GROUP"]=$arParams["TEST_GROUP"];

$arNavParams["iNumPage"]=$arNavigation["PAGEN"];

global $USER;
$USER_ID=$USER->GetID();
$arGroups=CUser::GetUserGroup($USER_ID);

if($this->StartResultCache(false, array(($arParams["CACHE_GROUPS"]==="N"? false: $USER->GetGroups()),$arNavigation, $arResult,$arParams)))
{
	$arResult["ITEMS"]=array();
	
	$FilterGroup=array();
	if(strlen($arResult["CODE_GROUP"])>0)
		$FilterGroup["CODE"]=$arResult["CODE_GROUP"];
	elseif($arResult["ID_GROUP"]>0)
		$FilterGroup["ID"]=$arResult["ID_GROUP"];
		
	if(count($FilterGroup)>0)
	{
		$FilterGroup["ACTIVE"]="Y";
		$el=new AelitaTestGroup();
		$res=$el->GetList(array(),$FilterGroup,false,array("nPageSize"=>1),[
            "ID",
            "XML_ID",
            "GROUP_ID",
            "NAME",
            "ACTIVE",
            "PICTURE",
            "ALT",
            "DESCRIPTION",
            "DESCRIPTION_TYPE",
            "SORT",
            "CODE",
            "ACCESS_ALL",
            "GROUP_NAME",
            "GROUP_CODE",
        ]);
		if($test=$res->GetNext())
		{
			$arResult["GROUP"]=$test;
		}elseif($arResult["ID_GROUP"]>0){
			unset($FilterGroup["CODE"]);
			$FilterGroup["ID"]=$arResult["ID_GROUP"];
            $res=$el->GetList(array(),$FilterGroup,false,array("nPageSize"=>1),[
                "ID",
                "XML_ID",
                "GROUP_ID",
                "NAME",
                "ACTIVE",
                "PICTURE",
                "ALT",
                "DESCRIPTION",
                "DESCRIPTION_TYPE",
                "SORT",
                "CODE",
                "ACCESS_ALL",
				"GROUP_NAME",
				"GROUP_CODE",
            ]);
			if($test=$res->GetNext())
				$arResult["GROUP"]=$test;
		}
	}
	
	if($arResult["GROUP"])
	{
		if($arResult["GROUP"]["PICTURE"])
			$arResult["GROUP"]["PICTURE"] = AelitaTestTools::GetWatermarkPicture($arResult["GROUP"]["PICTURE"],$arResult["GROUP"]["ALT"]);
		$arResult["ID_GROUP"]=$arResult["GROUP"]["ID"];
		$Code="";
		if(strlen($arResult["GROUP"]["CODE"])>0)
			$Code=$arResult["GROUP"]["CODE"];
		else
			$Code=$arResult["GROUP"]["ID"];
		$arParams["DETAIL_URL"]=str_replace("#GROUP_CODE#", $Code, $arParams["DETAIL_URL"]);
		$arParams["LIST_PAGE_URL"]=str_replace("#GROUP_CODE#", $Code, $arParams["LIST_PAGE_URL"]);
	}else{
		$arParams["DETAIL_URL"]=str_replace("#GROUP_CODE#", 0, $arParams["DETAIL_URL"]);
		$arParams["LIST_PAGE_URL"]=str_replace("#GROUP_CODE#", 0, $arParams["LIST_PAGE_URL"]);
	}

	if($arResult["GROUP"])
	{
		if($arResult["GROUP"]["ACCESS_ALL"]!="Y")
		{
			$Access=array();
			$resAccess=new AelitaTestAccessGroup();
			$props = $resAccess->GetList(array(), array("GROUP_ID"=>$arResult["GROUP"]["ID"]));
			while($p = $props->GetNext())
				$Access[]=$p["USER_GROUP_ID"];
			$Сonvergence = array_intersect($Access, $arGroups);
			if(count($Сonvergence)<=0)
				$Result["HIDE_RESULT"]="Y";
		}
	}
	$Date=ConvertDateTime(GetTime(time(),"FULL"), "YYYY-MM-DD HH:MI:SS");
	$Filter=array(
		"ACTIVE"=>"Y",
		array(
			"LOGIC"=>"OR",
			array(
				"LOGIC"=>"AND",
				"<>ACCESS_GROUP"=>"Y",
				array(
					"LOGIC"=>"OR",
					"ACCESS_ALL"=>"Y",
					"ACCESS_TEST"=>$arGroups,
					),
			),
			array(
				"LOGIC"=>"AND",
				"ACCESS_GROUP"=>"Y",
				">GROUP_ID"=>0,
				array(
					"LOGIC"=>"OR",
					"ACCESS_ALL_GROUP"=>$arGroups,
					"GROUP_ACCESS_ALL"=>"Y",
					),
				),
			),
		array(
			"LOGIC"=>"OR",
			array(
				"LOGIC"=>"AND",
				"DATE_FROM"=>false,
				"DATE_TO"=>false,
				),
			array(
				"LOGIC"=>"AND",
				"<DATE_FROM"=>$Date,
				"DATE_TO"=>false,
				),
			array(
				"LOGIC"=>"AND",
				"DATE_FROM"=>false,
				">DATE_TO"=>$Date,
				),
			array(
				"LOGIC"=>"AND",
				"<DATE_FROM"=>$Date,
				">DATE_TO"=>$Date,
				),
			),
		);

	if(!$arParams["SHOW_ALL"])
	{
		if($arResult["ID_GROUP"]>0)
			$Filter["GROUP_ID"]=$arResult["ID_GROUP"];
		elseif($arParams["SHOW_NO_GROUP"])
			$Filter["GROUP_ID"]=0;
		else
			$Filter["GROUP_ID"]=-1;
	}
	$Sort=array("SORT"=>"ASC","ID"=>"ASC");
	$Group=array("ID");
	
	if($arNavigation["SHOW_ALL"]==1)
		$arNavigation["SHOW_ALL"]="Y";
	if($arNavigation["SHOW_ALL"]=="Y")
		$NavParams=false;
	else
		$NavParams=$arNavParams;


	
	if($Result["HIDE_RESULT"]!="Y")
	{
		$el=new AelitaTestTest();
		$res=$el->GetList(array(),$Filter,$Group,false,array("COUNT"));
		if($test=$res->GetNext())
		{

			
			$count=(int)$test["COUNT"];
			if($count>0){
				$res=$el->GetList($Sort,$Filter,$Group,$NavParams);
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
					if($test["PICTURE"])
						$test["PICTURE"] = AelitaTestTools::GetWatermarkPicture($test["PICTURE"],$test["ALT"]);
					$test["DETAIL_URL"]=$arParams["DETAIL_URL"];
					$Code="";
					if(strlen($test["CODE"])>0)
						$Code=$test["CODE"];
					else
						$Code=$test["ID"];
					$test["DETAIL_URL"]=str_replace("#TEST_CODE#", $Code, $test["DETAIL_URL"]);
					$arResult["ITEMS"][]=$test;
				}
										
				$arResult["NAV_STRING"]=$res->GetPageNavStringEx($navComponentObject, $arParams["PAGER_TITLE"], $arParams["PAGER_TEMPLATE"], $arParams["PAGER_SHOW_ALWAYS"]);
				$arResult["NAV_CACHED_DATA"] = $navComponentObject->GetTemplateCachedData();
				$arResult["NAV_RESULT"] = $res;
			}
		}
	}

    $arResult["MAIN_PAGE_URL"]=$arParams["MAIN_PAGE_URL"];

    if($arResult["GROUP"]["GROUP_ID"]>0){
        $code=$arResult["GROUP"]["GROUP_CODE"];
        if(strlen($code)<=0)
            $code=$arResult["GROUP"]["GROUP_ID"];
        $url=$arParams["~LIST_PAGE_URL"];
        $url=str_replace('#GROUP_CODE#',$code,$url);
        $url=str_replace('#GROUP_ID#',$arResult["GROUP"]["GROUP_ID"],$url);
        $arResult["GROUP"]["GROUP_PARENT_URL"]=$url;
        $arResult["MAIN_PAGE_URL"]=$url;
    }
		
	$this->SetResultCacheKeys(array(
		"ID_GROUP",
		"GROUP",
		"ITEMS",
	));
	
	$this->IncludeComponentTemplate();
}

if($arParams["ADD_GROUP_CHAIN"] && $arResult["GROUP"]["NAME"])
{
	if($arResult["GROUP"]["GROUP_ID"]>0)
        $APPLICATION->AddChainItem($arResult["GROUP"]["GROUP_NAME"],$arResult["GROUP"]["GROUP_PARENT_URL"]);
	$APPLICATION->AddChainItem($arResult["GROUP"]["NAME"],$arResult["LIST_PAGE_URL"]);
}


if($arParams["SET_TITLE_GROUP"] && $arResult["GROUP"]["NAME"])
{
	$APPLICATION->SetTitle($arResult["GROUP"]["NAME"]);
	$APPLICATION->SetPageProperty('title',$arResult["GROUP"]["NAME"]);
}

?>