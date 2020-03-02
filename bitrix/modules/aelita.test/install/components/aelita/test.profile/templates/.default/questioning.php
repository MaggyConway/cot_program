<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
$param=array(
	"ADD_QUESTIONING_CHAIN"=>$arParams["ADD_QUESTIONING_CHAIN"],
	"SET_TITLE_QUESTIONING"=>$arParams["SET_TITLE_QUESTIONING"],
	"ADD_TEST_CHAIN"=>$arParams["ADD_TEST_CHAIN"],
	"SET_TITLE_TEST"=>$arParams["SET_TITLE_TEST"],
	
	"TEST_URL"=>$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["test"],
	"DETAIL_URL"=>$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["questioning"],
	
	"REPEATED_URL"=>$arParams["REPEATED_URL"],
	
	"TEST_ID"=>$arResult["VARIABLES"]["TEST_CODE"],
	"QUESTIONING_ID"=>$arResult["VARIABLES"]["QUESTIONING_CODE"],
	);
?>
<?$APPLICATION->IncludeComponent(
	"aelita:test.profile.questioning",
	"",
	$param,
	$component
);?>