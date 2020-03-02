<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$module_id="aelita.test";

if(!CModule::IncludeModule($module_id))
	return;

$arComponentParameters = array(
	"GROUPS" => array(
	),
	"PARAMETERS" => array(
		"AJAX_MODE" => array(),

        "PARENT_GROUP" => Array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("PARENT_GROUP"),
            "TYPE" => "STRING",
        ),

        "PARENT_GROUP_CODE" => Array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("PARENT_GROUP_CODE"),
            "TYPE" => "STRING",
        ),

		"DETAIL_URL" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("DETAIL_URL"),
			"TYPE" => "CUSTOM",
			"DEFAULT" => '',
			"JS_FILE"=>"/bitrix/js/iblock/path_templates.js",
			"JS_EVENT"=>"IBlockComponentProperties",
			"JS_DATA"=>"['mnu_DETAIL_URL','5000',[{'TEXT': '".GetMessage("DETAIL_URL_CODE")."','TITLE':'#GROUP_CODE# - ".GetMessage("DETAIL_URL_CODE")."','ONCLICK':'window.IBlockComponentPropertiesObj.Action(\'#GROUP_CODE#\', \'mnu_DETAIL_URL\', \'\')'}]]",
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
