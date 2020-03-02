<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("AT_TEST_LIST_NAME"),
	"DESCRIPTION" => GetMessage("AT_TEST_LIST_DESC"),
	"ICON" => "/images/test.test.png",
	"SORT" => 10,
	"CACHE_PATH" => "Y",
	"PATH" => array(
		"ID" => "aelita",
		"NAME" => GetMessage("AELITA"),
		"SORT" => 10,
		"CHILD" => array(
			"ID" => "aelita_test",
			"NAME" => GetMessage("AT_TEST"),
			"SORT" => 10,
		),
	),
);

?>