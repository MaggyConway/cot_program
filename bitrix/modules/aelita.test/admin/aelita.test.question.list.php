<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
$module_id="aelita.test";
$EditItem="aelita.test.question.edit.php";
$TableModule="b_aelita_test_question";

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
$arTest = AelitaTestEditToolEx::GetByID_admin($TEST_ID, 'question');
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
$sTableID = $TableModule;
$oSort = new CAdminSorting($sTableID, "ID", "ASC");
$lAdmin = new CAdminList($sTableID, $oSort);

$arFilterFields = array(
	"filter_name",
	"filter_active",
	"filter_type_id",
	"filter_group_id",
);
$USER_FIELD_MANAGER->AdminListAddFilterFields("AELITA_TEST_QUESTION", $arFilterFields);

$lAdmin->InitFilter($arFilterFields);

$arFilter=array("TEST_ID"=>$TEST_ID);
if (strlen($filter_name) > 0)
	$arFilter["likeNAME"] = '%'.$filter_name.'%';
if (strlen($filter_active) > 0)
	$arFilter["ACTIVE"] = $filter_active;
if (strlen($filter_type_id) > 0)
	$arFilter["TEST_TYPE"] = $filter_type_id;
if (is_array($filter_group_id))
	$arFilter["TEST_GROUP_ID"] = $filter_group_id;
else
	$filter_group_id = array();

$USER_FIELD_MANAGER->AdminListAddFilter("AELITA_TEST_QUESTION", $arFilter);



if ($lAdmin->EditAction() && !$bReadOnly /*$catalogModulePermissions >= "W"*/)
{
	foreach ($FIELDS as $ID => $arFields)
	{
		$DB->StartTransaction();
		$ID = IntVal($ID);

		if (!$lAdmin->IsUpdated($ID))
			continue;
		$el=new AelitaTestQuestion();
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
		$el=new AelitaTestQuestion();
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
				$el=new AelitaTestQuestion();
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

$el=new AelitaTestQuestion();
$dbResultList = $el->GetList(array(),$arFilter);

$dbResultList = new CAdminResult($dbResultList, $sTableID);
$dbResultList->NavStart();

$lAdmin->NavText($dbResultList->GetNavPrint(GetMessage("cat_extra_nav")));

$arHeaders=array();
$arHeaders[]=array("id"=>"ID", "content"=>"ID", "sort"=>"", "default"=>true);
if($ShowXmlID=="Y")
	$arHeaders[]=array("id"=>"XML_ID","content"=>GetMessage("AT_XML_ID"), "sort"=>"", "default"=>true);
$arHeaders[]=array("id"=>"ACTIVE","content"=>GetMessage("AT_ACTIVE"), "sort"=>"", "default"=>true);
$arHeaders[]=array("id"=>"SORT","content"=>GetMessage("AT_SORT"), "sort"=>"", "default"=>true);
$arHeaders[]=array("id"=>"TEST_TYPE","content"=>GetMessage("AT_TEST_TYPE"), "sort"=>"", "default"=>true);
$arHeaders[]=array("id"=>"TEST_GROUP_ID","content"=>GetMessage("AT_TEST_GROUP_ID"), "sort"=>"", "default"=>true);
$arHeaders[]=array("id"=>"NAME","content"=>GetMessage("AT_NAME"), "sort"=>"", "default"=>true);


$lAdmin->AddHeaders($arHeaders);
$arVisibleColumns = $lAdmin->GetVisibleHeaderColumns();

$Groups=array(
	0=>GetMessage("AT_NON"),
	);
	
$ListGroups = new AelitaTestQuestionGroup();
$dbGroups = $ListGroups->GetList(array("SORT"=>"ASC"),array("TEST_ID"=>$TEST_ID),false,false,array("ID","NAME"));
while ($Group = $dbGroups->Fetch())
	$Groups[$Group['ID']] = $Group['NAME'];

$test_type=array(
	"radio"=>GetMessage("AT_TEST_TYPE_RADIO"),
	"check"=>GetMessage("AT_TEST_TYPE_CHECK"),
	"input"=>GetMessage("AT_TEST_TYPE_INPUT"),
	);

while ($arExtra = $dbResultList->NavNext(true, "f_"))
{
	$arExtra["TEST_GROUP_ID"]=(int)$arExtra["TEST_GROUP_ID"];
	$row =& $lAdmin->AddRow($f_ID, $arExtra);

	$row->AddField("ID", $f_ID);

	if ($bReadOnly)
	{
		if($ShowXmlID=="Y")
			$row->AddViewField("XML_ID", $f_XML_ID);
		$row->AddCheckField("ACTIVE", false);
		$row->AddViewField("SORT", $f_SORT);
		$row->AddViewField("TEST_TYPE", $Groups[$f_TEST_TYPE]);
		$row->AddViewField("TEST_GROUP_ID", $Groups[$f_TEST_GROUP_ID]);
		$row->AddViewField("NAME", $f_NAME);

	}
	else
	{
		if($ShowXmlID=="Y")
			$row->AddInputField("XML_ID", array("size" => "25"));
		$row->AddCheckField("ACTIVE");
		$row->AddInputField("SORT", array("size" => "25"));
		$row->AddSelectField("TEST_TYPE",$test_type);
		$row->AddSelectField("TEST_GROUP_ID",$Groups);
		$row->AddInputField("NAME", array("size" => "25"));

	}

	$arActions = Array();
	$arActions[] = array("ICON"=>"edit", "TEXT"=>GetMessage("AT_EDIT_GROUP"), "ACTION"=>$lAdmin->ActionRedirect($EditItem."?QID=".$f_ID."&lang=".LANG.'&TEST_ID='.$TEST_ID), "DEFAULT"=>true);
	
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



if ($TEST_ID>0)
{
	$txt = $arTest["TXT_NAME"];
	$link = $EditTest.LANGUAGE_ID."&ID=".$TEST_ID;
	$adminChain->AddItem(array("TEXT"=>$txt, "LINK"=>$link));
}
$lAdmin->CheckListMode();
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
<form name="find_form" method="GET" action="<?echo $APPLICATION->GetCurPage()?>">
<input type="hidden" name="TEST_ID" value="<?=$TEST_ID?>" />
<?
$oFilter = new CAdminFilter(
	$sTableID."_filter",
	array(
		GetMessage("AT_NAME"),
		GetMessage("AT_ACTIVE"),
		GetMessage("AT_TEST_TYPE"),
		GetMessage("AT_TEST_GROUP_ID"),
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
		<td valign="top"><?echo GetMessage("AT_TEST_TYPE");?>:</td>
		<td>
			<select name="filter_type_id">
				<option value=""><?echo GetMessage("AT_NON")?></option>
				<?foreach($test_type as $key=>$value){?>
					<option <?if($key==$filter_type_id){?>selected="selected"<?};?> value="<?=$key?>">
						<?=$value?>
					</option>
				<?}?>
			</select>
		</td>
	</tr>
	<tr>
		<td valign="top"><?echo GetMessage("AT_TEST_GROUP_ID");?>:</td>
		<td>
			<select name="filter_group_id[]" multiple size="5">
				<option value="0"><?echo GetMessage("AT_NON")?></option>
				
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
<?
$USER_FIELD_MANAGER->AdminListShowFilter("AELITA_TEST_QUESTION");

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