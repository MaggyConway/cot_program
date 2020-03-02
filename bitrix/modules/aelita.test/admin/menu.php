<?
if(!is_object($GLOBALS["USER_FIELD_MANAGER"]))
	return false;

IncludeModuleLangFile(__FILE__);
$module_id="aelita.test" ; 

if($APPLICATION->GetGroupRight($module_id) >= "R" && IsModuleInstalled($module_id))
{
	$aMenu = array(
		"parent_menu" => "global_menu_services",
		"section" => "aelita_test",
		"sort" => 800,
		"text" => GetMessage("AELITA_TEST_MAIN"),
		"title"=> GetMessage("AELITA_TEST_MAIN_TITLE"),
		"icon" => "aelita_test_menu_icon",
		"page_icon" => "aelita_test_page_icon",
		"items_id" => "aelita_test_menu",
		"items" => array(
			array(
				"text" => GetMessage("AELITA_TEST_GROUP"),
				"url" => "aelita.test.group.list.php?lang=".LANGUAGE_ID,
				"more_url" => array("aelita.test.group.edit.php"),
				"title" => GetMessage("AELITA_TEST_GROUP_TITLE"),
			),
			array(
				"text" => GetMessage("AELITA_TEST_TEST"),
				"url" => "aelita.test.test.list.php?lang=".LANGUAGE_ID,
				"more_url" => array("aelita.test.test.edit.php",
					"aelita.test.test.edit.ex.php",
					"aelita.test.result.list.php",
					"aelita.test.result.edit.php",
					"aelita.test.question.list.php",
					"aelita.test.question.group.list.php",
					"aelita.test.question.group.edit.php",
					"aelita.test.question.edit.php",
					"aelita.test.answer.list.php",
					"aelita.test.answer.edit.php",
					),
				"title" => GetMessage("AELITA_TEST_TEST_TITLE"),
			),
			array(
				"text" => GetMessage("AELITA_TEST_STATISTICS"),
				"title" => GetMessage("AELITA_TEST_STATISTICS_TITLE"),
				"items_id" => "aelita_test_statistics",
				"items" => array(
					array(
						"text" => GetMessage("AELITA_TEST_STATISTICS_PROFILES"),
						"url" => "aelita.test.statistics.profiles.php?lang=".LANGUAGE_ID,
						"title" => GetMessage("AELITA_TEST_STATISTICS_PROFILES_TITLE"),
					),
					array(
						"text" => GetMessage("AELITA_TEST_STATISTICS_TESTS"),
						"url" => "aelita.test.statistics.tests.php?lang=".LANGUAGE_ID,
						"title" => GetMessage("AELITA_TEST_STATISTICS_TESTS_TITLE"),
					),
					array(
						"text" => GetMessage("AELITA_TEST_STATISTICS_TESTING"),
						"url" => "aelita.test.statistics.testing.php?lang=".LANGUAGE_ID,
						"title" => GetMessage("AELITA_TEST_STATISTICS_TESTING_TITLE"),
						"more_url" => array("aelita.test.statistics.testing.edit.php"),
					),
                    array(
                        "text" => GetMessage("AELITA_TEST_STATISTICS_TESTING_LIST"),
                        "url" => "aelita.test.statistics.testing.list.php?lang=".LANGUAGE_ID,
                        "title" => GetMessage("AELITA_TEST_STATISTICS_TESTING_LIST_TITLE"),
                    ),
				),
			),
		)
	);
	return $aMenu;
}
return false;
?>
