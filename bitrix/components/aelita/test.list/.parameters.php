<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$module_id="aelita.test";

if(!CModule::IncludeModule($module_id))
	return;


	
$arGroup = AelitaTestTools::GetTestGroup(true);
$arTests = AelitaTestTools::GetTestTest($arCurrentValues["TEST_GROUP"]);
$arComponentParameters = array(
	"GROUPS" => array(
		"PAGER_SETTINGS"=>Array(
			"NAME"=>GetMessage("PAGER_SETTINGS"),
                ),
	),
	"PARAMETERS" => array(
		"AJAX_MODE" => array(),
		"TEST_GROUP" => Array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("AT_TEST_GROUP"),
			"TYPE" => "LIST",
			"VALUES" => $arGroup,
			"DEFAULT" => "",
			//"REFRESH" => "Y",
			"ADDITIONAL_VALUES" => "Y",
		),
		
		"MAIN_PAGE_URL"=>array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("MAIN_PAGE_URL"),
			"TYPE" => "STRING",
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

		"SHOW_NO_GROUP" => Array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("SHOW_NO_GROUP"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
		),
		"SHOW_ALL" => Array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("SHOW_ALL"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
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
		"DISPLAY_TOP_PAGER"=>Array(
			"PARENT"=>"PAGER_SETTINGS",
			"NAME"=>GetMessage("DISPLAY_TOP_PAGER"),
			"TYPE"=>"CHECKBOX",
			"DEFAULT"=>"N",
                ),
		"DISPLAY_BOTTOM_PAGER"=>Array(
			"PARENT"=>"PAGER_SETTINGS",
			"NAME"=>GetMessage("DISPLAY_BOTTOM_PAGER"),
			"TYPE"=>"CHECKBOX",
			"DEFAULT"=>"Y",
                ),
		"PAGER_TITLE"=>Array(
			"PARENT"=>"PAGER_SETTINGS",
			"NAME"=>GetMessage("PAGER_TITLE"),
			"TYPE"=>"STRING",
                ),
		"PAGER_SHOW_ALWAYS"=>Array(
			"PARENT"=>"PAGER_SETTINGS",
			"NAME"=>GetMessage("PAGER_SHOW_ALWAYS"),
			"TYPE"=>"CHECKBOX",
			"DEFAULT"=>"Y",
                ),
		"PAGER_TEMPLATE"=>Array(
			"PARENT"=>"PAGER_SETTINGS",
			"NAME"=>GetMessage("PAGER_TEMPLATE"),
			"TYPE"=>"STRING",
                ),
		"PAGER_DESC_NUMBERING"=>Array(
			"PARENT"=>"PAGER_SETTINGS",
			"NAME"=>GetMessage("PAGER_DESC_NUMBERING"),
			"TYPE"=>"CHECKBOX",
			"DEFAULT"=>"N",
                ),
		"PAGER_DESC_NUMBERING_CACHE_TIME"=>Array(
			"PARENT"=>"PAGER_SETTINGS",
			"NAME"=>GetMessage("PAGER_DESC_NUMBERING_CACHE_TIME"),
			"TYPE"=>"STRING",
			"DEFAULT"=>36000,
                ),
		"PAGER_SHOW_ALL"=>Array(
			"PARENT"=>"PAGER_SETTINGS",
			"NAME"=>GetMessage("PAGER_SHOW_ALL"),
			"TYPE"=>"CHECKBOX",
			"DEFAULT"=>"Y",
                ),
		"COUNT_TEST" => Array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("COUNT_TEST"),
			"TYPE" => "STRING",
			"DEFAULT" => "20",
		),
		"CACHE_TIME"  =>  Array("DEFAULT"=>36000),
		"CACHE_GROUPS" => array(
			"PARENT" => "CACHE_SETTINGS",
			"NAME" => GetMessage("CP_BNL_CACHE_GROUPS"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
		),
	),
);

?>
