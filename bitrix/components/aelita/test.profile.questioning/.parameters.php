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
		"QUESTIONING_ID" => Array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("AT_QUESTIONING_ID"),
			"TYPE" => "STRING",
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
		"ADD_QUESTIONING_CHAIN" => Array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("ADD_QUESTIONING_CHAIN"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
		),
		"SET_TITLE_QUESTIONING" => Array(
			"PARENT" => "BASE",
			"NAME"=>GetMessage("SET_TITLE_QUESTIONING"),
			"TYPE"=>"CHECKBOX",
			"DEFAULT"=>"N",
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
        "NON_CHECK_USER" => Array(
            "PARENT" => "BASE",
            "NAME"=>GetMessage("NON_CHECK_USER"),
            "TYPE"=>"CHECKBOX",
            "DEFAULT"=>"N",
        ),
	),
);

?>
