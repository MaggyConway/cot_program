<?
$langs = CLanguage::GetList(($b=""), ($o=""));
$module_id="aelita.test";
while($lang = $langs->Fetch())
{
	$lid = $lang["LID"];
	$par=IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".$module_id."/install/events.php", $lid,true);
	if(count($par)>0)
	{
		global $MESS;
		$MESS=array_merge((array)$MESS,(array)$par);
		$et = new CEventType;
		$et->Add(array(
			"LID" => $lid,
			"EVENT_NAME" => "AELITA_TEST_WIN_TEST",
			"NAME" => GetMessage("AELITA_TEST_WIN_TEST_NAME"),
			"DESCRIPTION" => GetMessage("AELITA_TEST_WIN_TEST_DESC"),
		));

		$arSites = array();
		$sites = CSite::GetList(($b=""), ($o=""), Array("LANGUAGE_ID"=>$lid));
		while ($site = $sites->Fetch())
			$arSites[] = $site["LID"];

		if(count($arSites) > 0)
		{
			$emess = new CEventMessage;
			$emess->Add(array(
				"ACTIVE" => "Y",
				"EVENT_NAME" => "AELITA_TEST_WIN_TEST",
				"LID" => $arSites,
				"EMAIL_FROM" => "#DEFAULT_EMAIL_FROM#",
				"EMAIL_TO" => "#EMAIL#",
				"SUBJECT" => GetMessage("AELITA_TEST_WIN_TEST_SUBJECT"),
				"MESSAGE" => GetMessage("AELITA_TEST_WIN_TEST_MESSAGE"),
				"BODY_TYPE" => "text",
			));
		}
	}
}
?>
