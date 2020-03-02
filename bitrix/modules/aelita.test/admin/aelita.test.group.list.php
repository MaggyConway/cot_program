<?


require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
$module_id="aelita.test";
//$ListItem="/bitrix/admin/aelita.test.group.list.php?lang=";
$EditItem="aelita.test.group.edit.php";
$TableModule="b_aelita_test_group";
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
	
$ShowXmlID=COption::GetOptionString($module_id, "aelita_test_show_xml_id","N");
$TreeDirectory=COption::GetOptionString($module_id, "aelita_test_use_tree_view_directory","N");
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
		$el=new AelitaTestGroup();
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
		$el=new AelitaTestGroup();
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
				$el=new AelitaTestGroup();
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

$el=new AelitaTestGroup();
$dbResultList = $el->GetList([],[],false,false,[
    "ID",
    "XML_ID",
    "GROUP_ID",
    "NAME",
    "ACTIVE",
    "PICTURE",
    "ALT",
    "DESCRIPTION",
    "DESCRIPTION_TYPE",
    "SORT",
    "CODE",
    "ACCESS_ALL",
    "GROUP_NAME",
]);

$dbResultList = new CAdminResult($dbResultList, $sTableID);
$dbResultList->NavStart();

$lAdmin->NavText($dbResultList->GetNavPrint(GetMessage("cat_extra_nav")));

$arHeaders=array();
$arHeaders[]=array("id"=>"ID", "content"=>"ID", "sort"=>"", "default"=>true);
if($ShowXmlID=="Y")
	$arHeaders[]=array("id"=>"XML_ID","content"=>GetMessage("AT_XML_ID"), "sort"=>"", "default"=>true);
$arHeaders[]=array("id"=>"ACTIVE","content"=>GetMessage("AT_ACTIVE"), "sort"=>"", "default"=>true);
$arHeaders[]=array("id"=>"SORT","content"=>GetMessage("AT_SORT"), "sort"=>"", "default"=>true);
$arHeaders[]=array("id"=>"NAME","content"=>GetMessage("AT_NAME"), "sort"=>"", "default"=>true);

if($TreeDirectory=="Y")
    $arHeaders[]=array("id"=>"GROUP_NAME","content"=>GetMessage("AT_GROUP_NAME"), "sort"=>"", "default"=>true);

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
		$row->AddViewField("NAME", $f_NAME);

		if($TreeDirectory=="Y"){
            if(strlen($f_GROUP_NAME)>0){
                $row->AddViewField("GROUP_NAME", $f_GROUP_NAME);
            }else{

            }
		}

	}
	else
	{
		if($ShowXmlID=="Y")
			$row->AddInputField("XML_ID", array("size" => "25"));
		$row->AddCheckField("ACTIVE");
		$row->AddInputField("SORT", array("size" => "25"));
		$row->AddInputField("NAME", array("size" => "25"));

	}

	$arActions = Array();
	$arActions[] = array("ICON"=>"edit", "TEXT"=>GetMessage("AT_EDIT_GROUP"), "ACTION"=>$lAdmin->ActionRedirect($EditItem."?ID=".$f_ID."&lang=".LANG), "DEFAULT"=>true);
	
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
			"LINK" => $EditItem."?lang=".LANG,
			"TITLE" => GetMessage("AT_NEW_GROUP")
		),
	);
	$lAdmin->AddAdminContextMenu($aContext);
}

$lAdmin->CheckListMode();

$APPLICATION->SetTitle(GetMessage("TITLE"));
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
?>

<?
$lAdmin->DisplayList();
?>


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");?>