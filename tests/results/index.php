<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Результаты");
?><?$APPLICATION->IncludeComponent("aelita:test.profile", "test_profile", Array(
	"ADD_QUESTIONING_CHAIN" => "N",	// Добавлять попытка дата в цепочку навигации
		"ADD_TEST_CHAIN" => "N",	// Добавлять тест в цепочку навигации
		"COUNT_TEST" => "20",	// Количество тестов на странице
		"DISPLAY_BOTTOM_PAGER" => "Y",	// Выводить под списком
		"DISPLAY_TOP_PAGER" => "N",	// Выводить над списком
		"PAGER_DESC_NUMBERING" => "N",	// Использовать обратную навигацию
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",	// Время кеширования страниц для обратной навигации
		"PAGER_SHOW_ALL" => "N",	// Показывать ссылку "Все"
		"PAGER_SHOW_ALWAYS" => "N",	// Выводить всегда
		"PAGER_TEMPLATE" => "",	// Название шаблона
		"PAGER_TITLE" => "",	// Название категорий
		"REPEATED_URL" => "",	// Адрес повторного прохождения теста
		"SEF_FOLDER" => "/tests/results/",	// Каталог ЧПУ (относительно корня сайта)
		"SEF_MODE" => "Y",	// Включить поддержку ЧПУ
		"SEF_URL_TEMPLATES" => array(
			"questioning" => "#TEST_CODE#/#QUESTIONING_CODE#/",
			"test" => "#TEST_CODE#/",
			"tests" => "",
		),
		"SET_TITLE_QUESTIONING" => "N",	// Устанавливать заголовок из попытка дата
		"SET_TITLE_TEST" => "N",	// Устанавливать заголовок из имени теста
	),
	false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>