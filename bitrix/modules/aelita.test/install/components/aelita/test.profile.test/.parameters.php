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
		"TESTS_URL" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("TESTS_URL"),
			"TYPE" => "CUSTOM",
			"DEFAULT" => '',
		),
		"TEST_URL" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("TEST_URL"),
			"TYPE" => "CUSTOM",
			"DEFAULT" => '',
			"JS_FILE"=>"/bitrix/js/iblock/path_templates.js",
			"JS_EVENT"=>"IBlockComponentProperties",
			"JS_DATA"=>AelitaTestTools::GetJsUrl("TEST_URL",array("TEST_CODE")),
		),
		"DETAIL_URL" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("DETAIL_URL"),
			"TYPE" => "CUSTOM",
			"DEFAULT" => '',
			"JS_FILE"=>"/bitrix/js/iblock/path_templates.js",
			"JS_EVENT"=>"IBlockComponentProperties",
			"JS_DATA"=>AelitaTestTools::GetJsUrl("DETAIL_URL",array("TEST_CODE","QUESTIONING_CODE")),
		),
		"REPEATED_URL" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("REPEATED_URL"),
			"TYPE" => "CUSTOM",
			"DEFAULT" => '',
			"JS_FILE"=>"/bitrix/js/iblock/path_templates.js",
			"JS_EVENT"=>"IBlockComponentProperties",
			"JS_DATA"=>AelitaTestTools::GetJsUrl("REPEATED_URL",array("GROUP_CODE","TEST_CODE")),
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
	),
);

?>
