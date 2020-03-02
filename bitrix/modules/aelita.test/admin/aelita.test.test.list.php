<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
$module_id="aelita.test";
//$ListItem="/bitrix/admin/aelita.test.group.list.php?lang=";
$EditItem="aelita.test.test.edit.php";
if(COption::GetOptionString($module_id, "aelita_test_sadvanced_mode","N")=="Y")
	$EditItem="aelita.test.test.edit.ex.php";
$TableModule="b_aelita_test_test";
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
$sTableID = $TableModule;
$oSort = new CAdminSorting($sTableID, "ID", "ASC");
$lAdmin = new CAdminList($sTableID, $oSort);


$arFilterFields = array(
	"filter_name",
	"filter_active",
	"filter_group_id",
    "test_type",
);
$USER_FIELD_MANAGER->AdminListAddFilterFields("AELITA_TEST_TEST", $arFilterFields);

$lAdmin->InitFilter($arFilterFields);

$arFilter = array();
if (strlen($filter_name) > 0)
	$arFilter["NAME"] = $filter_name;
if (strlen($filter_active) > 0)
	$arFilter["ACTIVE"] = $filter_active;
if (strlen($test_type) > 0)
    $arFilter["USE_CORRECT"] = $test_type;
if (is_array($filter_group_id))
	$arFilter["GROUP_ID"] = $filter_group_id;
else
	$filter_group_id = array();


$USER_FIELD_MANAGER->AdminListAddFilter("AELITA_TEST_TEST", $arFilter);


if ($lAdmin->EditAction() && !$bReadOnly /*$catalogModulePermissions >= "W"*/)
{
	foreach ($FIELDS as $ID => $arFields)
	{
		$DB->StartTransaction();
		$ID = IntVal($ID);

		if (!$lAdmin->IsUpdated($ID))
			continue;
		$el=new AelitaTestTest();
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
		$el=new AelitaTestTest();
		$dbResultList = $el->GetList(false,$arFilter);
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
				$el=new AelitaTestTest();
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

$el=new AelitaTestTest();
$dbResultList = $el->GetList(false,$arFilter);

$dbResultList = new CAdminResult($dbResultList, $sTableID);
$dbResultList->NavStart();

$lAdmin->NavText($dbResultList->GetNavPrint(GetMessage("cat_extra_nav")));

$arHeaders=array();
$arHeaders[]=array("id"=>"ID", "content"=>"ID", "sort"=>"", "default"=>true);
if($ShowXmlID=="Y")
	$arHeaders[]=array("id"=>"XML_ID","content"=>GetMessage("AT_XML_ID"), "sort"=>"", "default"=>true);
$arHeaders[]=array("id"=>"ACTIVE","content"=>GetMessage("AT_ACTIVE"), "sort"=>"", "default"=>true);
$arHeaders[]=array("id"=>"USE_CORRECT","content"=>GetMessage("AT_TEST_TYPE"), "sort"=>"", "default"=>true);
$arHeaders[]=array("id"=>"SORT","content"=>GetMessage("AT_SORT"), "sort"=>"", "default"=>true);
$arHeaders[]=array("id"=>"NAME","content"=>GetMessage("AT_NAME"), "sort"=>"", "default"=>true);
$arHeaders[]=array("id"=>"GROUP_ID","content"=>GetMessage("AT_GROUP_ID"), "sort"=>"", "default"=>true);

$lAdmin->AddHeaders($arHeaders);
$arVisibleColumns = $lAdmin->GetVisibleHeaderColumns();

$Groups=array(
	0=>GetMessage("AT_NON"),
	);
	
$ListGroups = new AelitaTestGroup();
$dbGroups = $ListGroups->GetList(array("SORT"=>"ASC"),false,false,false,array("ID","NAME"));
while ($Group = $dbGroups->Fetch())
	$Groups[$Group['ID']] = $Group['NAME'];

while ($arExtra = $dbResultList->NavNext(true, "f_"))
{
	
	$row =& $lAdmin->AddRow($f_ID, $arExtra);

	$row->AddField("ID", $f_ID);

	if ($bReadOnly)
	{
		if($ShowXmlID=="Y")
			$row->AddViewField("XML_ID", $f_XML_ID);
		$row->AddCheckField("ACTIVE", false);

        if($f_USE_CORRECT=="Y")
            $row->AddViewField("USE_CORRECT", GetMessage("AT_TEST_USE_CORRECT_Y"));
        else
            $row->AddViewField("USE_CORRECT", GetMessage("AT_TEST_USE_CORRECT_N"));

		$row->AddViewField("SORT", $f_SORT);
		$row->AddViewField("NAME", $f_NAME);
		$row->AddViewField("GROUP_ID", $Groups[$f_GROUP_ID]);
	}
	else
	{
		if($ShowXmlID=="Y")
			$row->AddInputField("XML_ID", array("size" => "25"));
		$row->AddCheckField("ACTIVE");

        if($f_USE_CORRECT=="Y")
            $row->AddViewField("USE_CORRECT", GetMessage("AT_TEST_USE_CORRECT_Y"));
        else
            $row->AddViewField("USE_CORRECT", GetMessage("AT_TEST_USE_CORRECT_N"));

		$row->AddInputField("SORT", array("size" => "25"));
		$row->AddInputField("NAME", array("size" => "25"));
		$row->AddSelectField("GROUP_ID",$Groups);
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
    $arDDMenu=array();

    $arDDMenu[] = array(
        "TEXT" => GetMessage("AT_TEST_TYPE_SEP"),
        "ACTION" => false
    );
    $arDDMenu[] = array(
        "TEXT" => GetMessage("AT_TEST_USE_CORRECT_N"),
        "ACTION" => "window.location = '".$EditItem."?lang=".LANG."&USE_CORRECT=N';"
    );
    $arDDMenu[] = array(
        "TEXT" => GetMessage("AT_TEST_USE_CORRECT_Y"),
        "ACTION" => "window.location = '".$EditItem."?lang=".LANG."&USE_CORRECT=Y';"
    );




    $aContext = array(
        array(
            "TEXT" => GetMessage("AT_NEW_GROUP"),
            "TITLE" => GetMessage("AT_NEW_GROUP"),
            "ICON" => "btn_new",
            "MENU" => $arDDMenu
        ),
    );


	$lAdmin->AddAdminContextMenu($aContext);
}

$lAdmin->CheckListMode();

$APPLICATION->SetTitle(GetMessage("TITLE"));
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
?>

<form name="find_form" method="GET" action="<?echo $APPLICATION->GetCurPage()?>?">
<?
$oFilter = new CAdminFilter(
	$sTableID."_filter",
	array(
		GetMessage("AT_NAME"),
		GetMessage("AT_ACTIVE"),
		GetMessage("AT_GROUP_ID"),
        GetMessage("AT_TEST_TYPE"),
	)
);

$oFilter->Begin();
?>
	<tr>
		<td><?echo GetMessage("AT_NAME")?>:</td>
		<td><input type="text" name="filter_name" value="<?echo htmlspecialcharsbx($filter_name)?>" size="40"></td>
	</tr>
	<tr>
		<td><?echo GetMessage("AT_ACTIVE")?>:</td>
		<td>
			<select name="filter_active">
				<option value=""><?echo GetMessage("AT_ACTIVE_NON")?></option>
				<option value="Y"<?if ($filter_active=="Y") echo " selected"?>><?echo GetMessage("AT_ACTIVE_Y")?></option>
				<option value="N"<?if ($filter_active=="N") echo " selected"?>><?echo GetMessage("AT_ACTIVE_N")?></option>
			</select>
		</td>
	</tr>
	<tr>
		<td valign="top"><?echo GetMessage("AT_GROUP_ID");?>:</td>
		<td>
			<select name="filter_group_id[]" multiple size="5">
				<option value=""><?echo GetMessage("AT_GROUP_NON")?></option>
				
				<?foreach($Groups as $key=>$value){
					if($key>0){?>
					<option <?if(in_array($key, $filter_group_id)){?>selected="selected"<?};?> value="<?=$key?>">
						<?=$value?>
					</option>
					<?}
				}?>
			</select>
		</td>
	</tr>
    <tr>
        <td><?echo GetMessage("AT_TEST_TYPE")?>:</td>
        <td>
            <select name="test_type">
                <option value=""><?echo GetMessage("AT_ACTIVE_NON")?></option>
                <option value="N"<?if (test_type=="N") echo " selected"?>><?echo GetMessage("AT_TEST_USE_CORRECT_N")?></option>
                <option value="Y"<?if (test_type=="Y") echo " selected"?>><?echo GetMessage("AT_TEST_USE_CORRECT_Y")?></option>
            </select>
        </td>
    </tr>
<?
$USER_FIELD_MANAGER->AdminListShowFilter("AELITA_TEST_TEST");

$oFilter->Buttons(
	array(
		"table_id" => $sTableID,
		"url" => $APPLICATION->GetCurPage(),
		"form" => "find_form"
	)
);
$oFilter->End();
?>
</form>

<?
$lAdmin->DisplayList();
?>


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");?>