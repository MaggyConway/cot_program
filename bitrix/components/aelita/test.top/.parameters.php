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
		"COUNT_TOP" => Array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("COUNT_TOP"),
			"TYPE" => "STRING",
			"DEFAULT" => "5",
		),
		"HIDE_UNAUTHORIZED_USERS" => Array(
			"PARENT" => "BASE",
			"NAME"=>GetMessage("HIDE_UNAUTHORIZED_USERS"),
			"TYPE"=>"CHECKBOX",
			"DEFAULT"=>"N",
		),
		"SHOW_POINTS_TOP"=>array(
			"PARENT" => "BASE",
			"NAME"=>GetMessage("SHOW_POINTS_TOP"),
			"TYPE"=>"CHECKBOX",
			"DEFAULT"=>"N",
		),
		"CACHE_TIME"  =>  Array("DEFAULT"=>3600),
		"CACHE_GROUPS" => array(
			"PARENT" => "CACHE_SETTINGS",
			"NAME" => GetMessage("CP_BNL_CACHE_GROUPS"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
		),
	),
);

?>
