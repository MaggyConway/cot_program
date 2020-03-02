<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Экзамен по охране труда");
?><?$APPLICATION->IncludeComponent(
	"aelita:test.test",
	"main_test",
	Array(
		"ADD_GROUP_CHAIN" => "N",
		"ADD_TEST_CHAIN" => "N",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"DETAIL_URL" => "#TEST_CODE#",
		"LIST_PAGE_URL" => "#GROUP_CODE#",
		"PROFILE_DETAIL_URL" => "",
		"SET_TITLE_GROUP" => "N",
		"SET_TITLE_TEST" => "Y",
		"TEST_GROUP" => "1",
		"TEST_ID" => "2"
	)
);?><br>
 <br><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>