<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$ElementParam=array(
	"AJAX_MODE"=>$arParams["AJAX_MODE"],
    "AJAX_MODE"=>"Y",
	"AJAX_OPTION_JUMP"=>$arParams["AJAX_OPTION_JUMP"],
	"AJAX_OPTION_STYLE"=>$arParams["AJAX_OPTION_STYLE"],
	"AJAX_OPTION_HISTORY"=>$arParams["AJAX_OPTION_HISTORY"],
	"AJAX_OPTION_ADDITIONAL"=>$arParams["AJAX_OPTION_ADDITIONAL"],
	"TEST_GROUP"=>$arResult["VARIABLES"]["GROUP_CODE"],
	"TEST_ID"=>$arResult["VARIABLES"]["TEST_CODE"],
	"LIST_PAGE_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["group"],
	"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["test"],
	"ADD_GROUP_CHAIN"=>$arParams["ADD_GROUP_CHAIN"],
	"SET_TITLE_GROUP"=>$arParams["SET_TITLE_GROUP"],
	"ADD_TEST_CHAIN"=>$arParams["ADD_TEST_CHAIN"],
	"SET_TITLE_TEST"=>$arParams["SET_TITLE_TEST"],
	"PROFILE_DETAIL_URL"=>$arParams["PROFILE_DETAIL_URL"],
    "QUESTIONING_URL" => $arParams["QUESTIONING_URL"],
	);?>
<?
if($arParams["SHOW_ALL"]=="Y" || $arResult["VARIABLES"]["GROUP_CODE"]=="0")
	$ElementParam["LIST_PAGE_URL"]=$arResult["FOLDER"];
?>
<?if($arParams["TOP_TESTS"]=="Y"){?>
	<?$APPLICATION->IncludeComponent(
		"aelita:test.top",
		".default",
		Array(
			"TEST_GROUP"=>$arResult["VARIABLES"]["GROUP_CODE"],
			"TEST_ID"=>$arResult["VARIABLES"]["TEST_CODE"],
			"COUNT_TOP"=>$arParams["COUNT_TOP"],
			"HIDE_UNAUTHORIZED_USERS"=>$arParams["HIDE_UNAUTHORIZED_USERS"],
			"CACHE_TYPE"=>$arParams["CACHE_TYPE"],
			"CACHE_TIME"=>$arParams["CACHE_TIME"],
			"CACHE_GROUPS"=>$arParams["CACHE_GROUPS"],
			"SHOW_POINTS_TOP"=>$arParams["SHOW_POINTS_TOP"],
		),
		$component
	);?>
<?}?>
<?$APPLICATION->IncludeComponent(
	"aelita:test.test",
	"",
	$ElementParam,
	$component
);?>