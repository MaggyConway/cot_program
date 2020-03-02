<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$module_id="aelita.test";

if(!CModule::IncludeModule($module_id))
	return;

$arGroup = AelitaTestTools::GetTestGroup(true);
$arTests = AelitaTestTools::GetTestTest($arCurrentValues["TEST_GROUP"]);
$arComponentParameters = array(
	"GROUPS" => array(
	),
	"PARAMETERS" => array(
		"AJAX_MODE" => array(),

		"TEST_GROUP" => Array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("AT_TEST_GROUP"),
			"TYPE" => "LIST",
			"VALUES" => $arGroup,
			"DEFAULT" => "",
			"REFRESH" => "Y",
			"ADDITIONAL_VALUES" => "Y",
		),
		"TEST_ID" => Array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("AT_TEST_ID"),
			"TYPE" => "LIST",
			"VALUES" => $arTests,
			"DEFAULT" => '',
			"ADDITIONAL_VALUES" => "Y",
		),

		"LIST_PAGE_URL"=>array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("LIST_PAGE_URL"),
			"TYPE" => "STRING",
			),
			
		"DETAIL_URL" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("DETAIL_URL"),
			"TYPE" => "CUSTOM",
			"DEFAULT" => '',
			"JS_FILE"=>"/bitrix/js/iblock/path_templates.js",
			"JS_EVENT"=>"IBlockComponentProperties",
			"JS_DATA"=>"['mnu_DETAIL_URL','5000',[{'TEXT': '".GetMessage("DETAIL_URL_CODE")."','TITLE':'#TEST_CODE# - ".GetMessage("DETAIL_URL_CODE")."','ONCLICK':'window.IBlockComponentPropertiesObj.Action(\'#TEST_CODE#\', \'mnu_DETAIL_URL\', \'\')'}]]",
		),
		
		"PROFILE_DETAIL_URL" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("PROFILE_DETAIL_URL"),
			"TYPE" => "CUSTOM",
			"DEFAULT" => '',
			"JS_FILE"=>"/bitrix/js/iblock/path_templates.js",
			"JS_EVENT"=>"IBlockComponentProperties",
			"JS_DATA"=>AelitaTestTools::GetJsUrl("PROFILE_DETAIL_URL",array("TEST_CODE","QUESTIONING_CODE")),
		),
		
		"ADD_GROUP_CHAIN" => Array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("ADD_GROUP_CHAIN"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
		),
		"SET_TITLE_GROUP" => Array(
			"PARENT" => "BASE",
			"NAME"=>GetMessage("SET_TITLE_GROUP"),
			"TYPE"=>"CHECKBOX",
			"DEFAULT"=>"N",
		),
		
		"ADD_TEST_CHAIN" => Array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("ADD_TEST_CHAIN"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
		),
		"SET_TITLE_TEST" => Array(
			"PARENT" => "BASE",
			"NAME"=>GetMessage("SET_TITLE_TEST"),
			"TYPE"=>"CHECKBOX",
			"DEFAULT"=>"N",
		),
			
		//"CACHE_TIME"  =>  Array("DEFAULT"=>36000),
		/*
		"CACHE_GROUPS" => array(
			"PARENT" => "CACHE_SETTINGS",
			"NAME" => GetMessage("AT_BND_CACHE_GROUPS"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
		),
		*/
	),
);




?>
