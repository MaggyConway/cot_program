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
		"TOP_TESTS_GROUP" => array(
			"NAME" => GetMessage("TOP_TESTS_GROUP"),
		),
	),
	"PARAMETERS" => array(
	
		"VARIABLE_ALIASES" => Array(
			"GROUP_CODE" => Array("NAME" => GetMessage("VARIABLE_GROUP_ID")),
			"TEST_CODE" => Array("NAME" => GetMessage("VARIABLE_TEST_ID")),
		),
		
		"SEF_MODE" => Array(
			"groups" => array(
				"NAME" => GetMessage("SEF_MODE_GROUPS"),
				"DEFAULT" => "",
				"VARIABLES" => array(),
			),
			"group" => array(
				"NAME"=>GetMessage("SEF_MODE_GROUP"),
				"DEFAULT"=>"#GROUP_CODE#/",
				"VARIABLES" => array("GROUP_CODE"),
			),
			"test" => array(
				"NAME" => GetMessage("SEF_MODE_TEST"),
				"DEFAULT" => "#GROUP_CODE#/#TEST_CODE#/",
				"VARIABLES" => array("TEST_CODE", "GROUP_CODE"),
			),
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
		
		"AJAX_MODE" => array(),

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

		"CACHE_TIME"  =>  Array("DEFAULT"=>36000),
		"CACHE_GROUPS" => array(
			"PARENT" => "CACHE_SETTINGS",
			"NAME" => GetMessage("CP_BNL_CACHE_GROUPS"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
		),
		
		"TOP_TESTS" => Array(
			"PARENT" => "TOP_TESTS_GROUP",
			"NAME" => GetMessage("TOP_TESTS"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
			"REFRESH" => "Y",
		),
		
	),
);

if($arCurrentValues["TOP_TESTS"]=="Y")
{
	$arComponentParameters["PARAMETERS"]["COUNT_TOP"]=array(
		"PARENT" => "TOP_TESTS_GROUP",
		"NAME" => GetMessage("COUNT_TOP"),
		"TYPE" => "STRING",
		"DEFAULT" => "5",
	);
	$arComponentParameters["PARAMETERS"]["HIDE_UNAUTHORIZED_USERS"]=array(
		"PARENT" => "TOP_TESTS_GROUP",
		"NAME"=>GetMessage("HIDE_UNAUTHORIZED_USERS"),
		"TYPE"=>"CHECKBOX",
		"DEFAULT"=>"N",
	);
	$arComponentParameters["PARAMETERS"]["SHOW_POINTS_TOP"]=array(
		"PARENT" => "TOP_TESTS_GROUP",
		"NAME"=>GetMessage("SHOW_POINTS_TOP"),
		"TYPE"=>"CHECKBOX",
		"DEFAULT"=>"N",
	);
}

?>
