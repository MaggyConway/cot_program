<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
$module_id="aelita.test";
$EditItem="aelita.test.result.edit.php";
$TableModule="b_aelita_test_result";

$EditTest="/bitrix/admin/aelita.test.test.edit.ex.php?lang=";

IncludeModuleLangFile(__FILE__);


if ($ex = $APPLICATION->GetException())
{
	require($DOCUMENT_ROOT."/bitrix/modules/main/include/prolog_admin_after.php");
	
	$strError = $ex->GetString();
	ShowError($strError);
	
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
	die();
}



$POST_RIGHT = $APPLICATION->GetGroupRight($module_id);
if($POST_RIGHT=="D")
	$APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
if(!CModule::IncludeModule($module_id))die();
	
	
$TEST_ID=IntVal($TEST_ID);
$arTest = AelitaTestEditToolEx::GetByID_admin($TEST_ID, 'result');
if (!$arTest)
{
	require($DOCUMENT_ROOT."/bitrix/modules/main/include/prolog_admin_after.php");
	echo BeginNote('width="100%"');
	echo GetMessage("NO_TEST_ERROR");
	echo EndNote();
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
	die();
}
	
	
	
$ShowXmlID=COption::GetOptionString($module_id, "aelita_test_show_xml_id","N");

$UseCorrect=AelitaTestEditToolEx::GetUseCorrect($TEST_ID);


$sTableID = $TableModule;
$oSort = new CAdminSorting($sTableID, "ID", "ASC");
$lAdmin = new CAdminList($sTableID, $oSort);
$arFilterFields = array();
$lAdmin->InitFilter($arFilterFields);

$arFilter = array();

if ($lAdmin->EditAction() && !$bReadOnly /*$catalogModulePermissions >= "W"*/)
{
	foreach ($FIELDS as $ID => $arFields)
	{
		$DB->StartTransaction();
		$ID = IntVal($ID);

		if (!$lAdmin->IsUpdated($ID))
			continue;
		$el=new AelitaTestResult();
		if (!$el->Update($ID, $arFields))
		{
			if ($ex = $APPLICATION->GetException())
				$lAdmin->AddUpdateError($ex->GetString(), $ID);
			else
				$lAdmin->AddUpdateError(GetMessage("AT_ERROR_UPDATE"), $ID);

			$DB->Rollback();
		}

		$DB->Commit();
	}
}


if (($arID = $lAdmin->GroupAction()) && !$bReadOnly )
{
	if ($_REQUEST['action_target']=='selected')
	{
		$arID = Array();
		$el=new AelitaTestResult();
		$dbResultList = $el->GetList();
		while ($arResult = $dbResultList->Fetch())
			$arID[] = $arResult['ID'];
	}

	foreach ($arID as $ID)
	{
		if (strlen($ID) <= 0)
			continue;

		switch ($_REQUEST['action'])
		{
			case "delete":
				@set_time_limit(0);

				$DB->StartTransaction();
				$el=new AelitaTestResult();
				if (!$el->Delete($ID))
				{
					$DB->Rollback();

					if ($ex = $APPLICATION->GetException())
						$lAdmin->AddGroupError($ex->GetString(), $ID);
					else
						$lAdmin->AddGroupError(GetMessage("AT_ERROR_DELETE"), $ID);
				}

				$DB->Commit();

				break;
		}
	}
}

$el=new AelitaTestResult();
$dbResultList = $el->GetList(array(),array("TEST_ID"=>$TEST_ID));

$dbResultList = new CAdminResult($dbResultList, $sTableID);
$dbResultList->NavStart();

$lAdmin->NavText($dbResultList->GetNavPrint(GetMessage("cat_extra_nav")));

$arHeaders=array();
$arHeaders[]=array("id"=>"ID", "content"=>"ID", "sort"=>"", "default"=>true);
if($ShowXmlID=="Y")
	$arHeaders[]=array("id"=>"XML_ID","content"=>GetMessage("AT_XML_ID"), "sort"=>"", "default"=>true);
$arHeaders[]=array("id"=>"ACTIVE","content"=>GetMessage("AT_ACTIVE"), "sort"=>"", "default"=>true);
$arHeaders[]=array("id"=>"SORT","content"=>GetMessage("AT_SORT"), "sort"=>"", "default"=>true);

if($UseCorrect)
{
    $arHeaders[]=array("id"=>"MIN_SCORES","content"=>GetMessage("AT_MIN_CORRECT"), "sort"=>"", "default"=>true);
    $arHeaders[]=array("id"=>"MAX_SCORES","content"=>GetMessage("AT_MAX_CORRECT"), "sort"=>"", "default"=>true);
}else{
    $arHeaders[]=array("id"=>"MIN_SCORES","content"=>GetMessage("AT_MIN_SCORES"), "sort"=>"", "default"=>true);
    $arHeaders[]=array("id"=>"MAX_SCORES","content"=>GetMessage("AT_MAX_SCORES"), "sort"=>"", "default"=>true);
}

$arHeaders[]=array("id"=>"NAME","content"=>GetMessage("AT_NAME"), "sort"=>"", "default"=>true);

$lAdmin->AddHeaders($arHeaders);
$arVisibleColumns = $lAdmin->GetVisibleHeaderColumns();


while ($arExtra = $dbResultList->NavNext(true, "f_"))
{
	
	$row =& $lAdmin->AddRow($f_ID, $arExtra);

	$row->AddField("ID", $f_ID);

	if ($bReadOnly)
	{
		if($ShowXmlID=="Y")
			$row->AddViewField("XML_ID", $f_XML_ID);
		$row->AddCheckField("ACTIVE", false);
		$row->AddViewField("SORT", $f_SORT);
		$row->AddViewField("MIN_SCORES", $f_MIN_SCORES);
		$row->AddViewField("MAX_SCORES", $f_MAX_SCORES);
		$row->AddViewField("NAME", $f_NAME);

	}
	else
	{
		if($ShowXmlID=="Y")
			$row->AddInputField("XML_ID", array("size" => "25"));
		$row->AddCheckField("ACTIVE");
		$row->AddInputField("SORT", array("size" => "25"));
		$row->AddInputField("MIN_SCORES", array("size" => "25"));
		$row->AddInputField("MAX_SCORES", array("size" => "25"));
		$row->AddInputField("NAME", array("size" => "25"));

	}

	$arActions = Array();
	$arActions[] = array("ICON"=>"edit", "TEXT"=>GetMessage("AT_EDIT_GROUP"), "ACTION"=>$lAdmin->ActionRedirect($EditItem."?ID=".$f_ID."&lang=".LANG.'&TEST_ID='.$TEST_ID), "DEFAULT"=>true);
	
	if (!$bReadOnly)
	{
		$arActions[] = array("SEPARATOR" => true);
		$arActions[] = array("ICON"=>"delete", "TEXT"=>GetMessage("AT_DEL_GROUP"), "ACTION"=>"if(confirm('".GetMessage('AT_DEL_GROUP_CONFIRM')."')) ".$lAdmin->ActionDoGroup($f_ID, "delete"));
	}

	$row->AddActions($arActions);
}

$lAdmin->AddFooter(
	array(
		array(
			"title" => GetMessage("MAIN_ADMIN_LIST_SELECTED"),
			"value" => $dbResultList->SelectedRowsCount()
		),
		array(
			"counter" => true,
			"title" => GetMessage("MAIN_ADMIN_LIST_CHECKED"),
			"value" => "0"
		),
	)
);


if (!$bReadOnly)
{
	$lAdmin->AddGroupActionTable(
		array(
			"delete" => GetMessage("MAIN_ADMIN_LIST_DELETE"),
		)
	);
}

if (!$bReadOnly)
{
	$aContext = array(
		array(
			"TEXT" => GetMessage("AT_NEW_GROUP"),
			"ICON" => "btn_new",
			"LINK" => $EditItem."?lang=".LANG.'&TEST_ID='.$TEST_ID,
			"TITLE" => GetMessage("AT_NEW_GROUP")
		),
	);
	$lAdmin->AddAdminContextMenu($aContext);
}

$lAdmin->CheckListMode();

if ($TEST_ID>0)
{
	$txt = $arTest["TXT_NAME"];
	$link = $EditTest.LANGUAGE_ID."&ID=".$TEST_ID;
	$adminChain->AddItem(array("TEXT"=>$txt, "LINK"=>$link));
}

$APPLICATION->SetTitle(GetMessage("TITLE").': #'.$TEST_ID.' '.htmlspecialcharsbx($arTest["NAME"]));
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");

if ($TEST_ID>0)
{
	$context = new CAdminContextMenuList($arTest['ADMIN_MENU']);
	$context->Show();
	echo BeginNote('width="100%"');
?>
	<b><?=GetMessage("TEST_TITLE")?>:</b>
	[<a title='<?=GetMessage("FORM_EDIT_TEST")?>' href='<?=$EditTest?><?=LANGUAGE_ID?>&ID=<?=$TEST_ID?>'><?=$TEST_ID?></a>]&nbsp;<?=$arTest["TXT_NAME"]?>
<?
	echo EndNote();
}

?>

<?
$lAdmin->DisplayList();
?>


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");?>