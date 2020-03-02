<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$module_id="aelita.test";

if(!CModule::IncludeModule($module_id))
	return;

$arComponentParameters = array(
	"GROUPS" => array(
		"PAGER_SETTINGS"=>Array(
			"NAME"=>GetMessage("PAGER_SETTINGS"),
                ),
	),
	"PARAMETERS" => array(
	
		"VARIABLE_ALIASES" => Array(
			"TEST_CODE" => Array("NAME" => GetMessage("VARIABLE_TEST_ID")),
			"QUESTIONING_CODE" => Array("NAME" => GetMessage("VARIABLE_QUESTIONING_ID")),
		),
		
		"SEF_MODE" => Array(
			"tests" => array(
				"NAME" => GetMessage("SEF_MODE_TESTS"),
				"DEFAULT" => "",
				"VARIABLES" => array(),
			),
			"test" => array(
				"NAME"=>GetMessage("SEF_MODE_TEST"),
				"DEFAULT"=>"#TEST_CODE#/",
				"VARIABLES" => array("TEST_CODE"),
			),
			"questioning" => array(
				"NAME" => GetMessage("SEF_MODE_QUESTIONING"),
				"DEFAULT" => "#TEST_CODE#/#QUESTIONING_CODE#/",
				"VARIABLES" => array("TEST_CODE", "QUESTIONING_CODE"),
			),
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
		//"AJAX_MODE" => array(),

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
		
		"COUNT_TEST" => Array(
			"PARENT" => "PAGER_SETTINGS",
			"NAME" => GetMessage("COUNT_TEST"),
			"TYPE" => "STRING",
			"DEFAULT" => "20",
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
	),
);

?>
