<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
$module_id="aelita.test";
$EditItem="aelita.test.statistics.testing.edit.php";
$TableModule="b_aelita_test_questioning";
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

$sTableID = $TableModule;
$oSort = new CAdminSorting($sTableID, "ID", "ASC");
$lAdmin = new CAdminList($sTableID, $oSort);

$arFilterFields = array(
	"profile_id",
	"test_id",
	"result_id",
	"user_id",
	"closed",
//	"scores_1",
//	"scores_2",
);
$USER_FIELD_MANAGER->AdminListAddFilterFields("AELITA_TEST_STATISTICS", $arFilterFields);

$lAdmin->InitFilter($arFilterFields);

$arFilter=array();
if($del_filter!="Y")
{
	if (strlen($filter_profile_id) > 0)
		$arFilter["PROFILE_ID"] = $filter_profile_id;
	if (strlen($filter_test_id) > 0)
		$arFilter["TEST_ID"] = $filter_test_id;
	if (strlen($filter_result_id) > 0)
		$arFilter["RESULT_ID"] = $filter_result_id;
	if (strlen($filter_user_id) > 0)
		$arFilter["USER_ID"] = $filter_user_id;
	if (strlen($filter_closed) > 0)
		$arFilter["CLOSED"] = $filter_closed;
	/*if (strlen($filter_scores_1) > 0)
		$arFilter[">=SCORES"] = $filter_scores_1;
	if (strlen($filter_scores_2) > 0)
		$arFilter["<=SCORES"] = $filter_scores_2;*/
}
$USER_FIELD_MANAGER->AdminListAddFilter("AELITA_TEST_STATISTICS", $arFilter);

if (($arID = $lAdmin->GroupAction()) && !$bReadOnly )
{
	if ($_REQUEST['action_target']=='selected')
	{
		$arID = Array();
		$el=new AelitaTestQuestioning();
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
				$el=new AelitaTestQuestioning();
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

$el=new AelitaTestQuestioning();
$arSelect=array(
	"ID",
	"PROFILE_ID",
	"TEST_ID",
	"RESULT_ID",
	"CLOSED",
	"TEST_NAME",
	"RESULT_NAME",
	"SCORES",
	"USER_ID",
	"DATE_START",
	"DATE_STOP",
	"FINAL",
	"DURATION",
	);
$arGroup=array(
	"ID",
	);
$dbResultList = $el->GetList(array($by=>$order),$arFilter,$arGroup,false,$arSelect);

$dbResultList = new CAdminResult($dbResultList, $sTableID);
$dbResultList->NavStart();

$lAdmin->NavText($dbResultList->GetNavPrint(GetMessage("cat_extra_nav")));

$arHeaders=array();
$arHeaders[]=array("id"=>"ID", "content"=>"ID", "sort"=>"ID", "default"=>true);
$arHeaders[]=array("id"=>"PROFILE_ID","content"=>GetMessage("AT_PROFILE_ID"), "sort"=>"PROFILE_ID", "default"=>true);
$arHeaders[]=array("id"=>"USER_ID","content"=>GetMessage("AT_USER_ID"), "sort"=>"USER_ID", "default"=>true);
$arHeaders[]=array("id"=>"USER_FIO","content"=>GetMessage("AT_USER_FIO"), "sort"=>false, "default"=>true);
$arHeaders[]=array("id"=>"TEST_ID","content"=>GetMessage("AT_TEST_ID"), "sort"=>"TEST_ID", "default"=>true);
$arHeaders[]=array("id"=>"TEST_NAME","content"=>GetMessage("AT_TEST_NAME"), "sort"=>"", "default"=>true);
$arHeaders[]=array("id"=>"RESULT_ID","content"=>GetMessage("AT_RESULT_ID"), "sort"=>"RESULT_ID", "default"=>true);
$arHeaders[]=array("id"=>"RESULT_NAME","content"=>GetMessage("AT_RESULT_NAME"), "sort"=>"", "default"=>true);
$arHeaders[]=array("id"=>"SCORES","content"=>GetMessage("AT_SCORES"), "sort"=>"SCORES", "default"=>true);
$arHeaders[]=array("id"=>"DATE_START","content"=>GetMessage("AT_DATE_START"), "sort"=>"DATE_START", "default"=>true);
$arHeaders[]=array("id"=>"DATE_STOP","content"=>GetMessage("AT_DATE_STOP"), "sort"=>"DATE_STOP", "default"=>true);
$arHeaders[]=array("id"=>"DURATION","content"=>GetMessage("AT_DURATION"), "sort"=>"DURATION", "default"=>true);
$arHeaders[]=array("id"=>"FINAL","content"=>GetMessage("AT_FINAL"), "sort"=>"FINAL", "default"=>true);
$arHeaders[]=array("id"=>"CLOSED","content"=>GetMessage("AT_CLOSED"), "sort"=>"CLOSED", "default"=>true);

$lAdmin->AddHeaders($arHeaders);
$arVisibleColumns = $lAdmin->GetVisibleHeaderColumns();

while ($arExtra = $dbResultList->NavNext(true, "f_"))
{

	$row=&$lAdmin->AddRow($f_ID, $arExtra);
	$row->AddField("ID", $f_ID);
	$row->AddViewField("PROFILE_ID", $f_PROFILE_ID);
		
	if($f_USER_ID>0) {
        $row->AddViewField("USER_ID", GetMessage("LINK_USER_ID", array("#ID#" => $f_USER_ID, "#LANG#" => LANG)));

        $rsUser = CUser::GetByID($f_USER_ID);
        $arUser = $rsUser->Fetch();
        $arrU=array();
        if($arUser["LAST_NAME"])
            $arrU[]=$arUser["LAST_NAME"];
        if($arUser["NAME"])
            $arrU[]=$arUser["NAME"];
        if($arUser["SECOND_NAME"])
            $arrU[]=$arUser["SECOND_NAME"];
        if(count($arrU)<=0)
            $arrU[]=$arUser["LOGIN"];
        $textUrl=implode(" ",$arrU);

        $row->AddViewField("USER_FIO", $textUrl);
    }else
		$row->AddViewField("USER_ID", GetMessage("AT_NON_USER"));
		
	$row->AddViewField("TEST_ID", $f_TEST_ID);
	$row->AddViewField("TEST_NAME", $f_TEST_NAME);
	$row->AddViewField("RESULT_ID", $f_RESULT_ID);
	if($f_RESULT_ID)
		$row->AddViewField("RESULT_NAME", $f_RESULT_NAME);
	else
		$row->AddViewField("RESULT_NAME", GetMessage("AT_NON_RESULT"));
	$row->AddViewField("SCORES", $f_SCORES);
	$row->AddViewField("DATE_START", $f_DATE_START);
	$row->AddViewField("DATE_STOP", $f_DATE_STOP);
	
	$row->AddViewField("DURATION", AelitaTestTools::GetTxtTime($f_DURATION));
	
	$row->AddCheckField("FINAL");
	$row->AddCheckField("CLOSED");

	$arActions = Array();
	$arActions[] = array("ICON"=>"edit", "TEXT"=>GetMessage("AT_EDIT_GROUP"), "ACTION"=>$lAdmin->ActionRedirect($EditItem."?ID=".$f_ID."&lang=".LANG), "DEFAULT"=>true);
	if (!$bReadOnly)
	{
		$arActions[] = array("SEPARATOR" => true);
		$arActions[] = array("ICON"=>"delete", "TEXT"=>GetMessage("AT_DEL_GROUP"), "ACTION"=>"if(confirm('".GetMessage('AT_DEL_GROUP_CONFIRM')."')) ".$lAdmin->ActionDoGroup($f_ID, "delete"));
	}
	
	if (!$bReadOnly)
	{
		$aContext = array(
		);
		$lAdmin->AddAdminContextMenu($aContext);
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

$lAdmin->CheckListMode();

$APPLICATION->SetTitle(GetMessage("TITLE"));
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
?>
<form name="find_form" method="GET" action="<?echo $APPLICATION->GetCurPage()?>?">
<?
$oFilter = new CAdminFilter(
	$sTableID."_filter",
	array(
		GetMessage("AT_PROFILE_ID"),
		GetMessage("AT_TEST_ID"),
		GetMessage("AT_RESULT_ID"),
		GetMessage("AT_USER_ID"),
		GetMessage("AT_CLOSED"),
		//GetMessage("AT_SCORES"),
	)
);

$oFilter->Begin();
?>
	<tr>
		<td><?echo GetMessage("AT_PROFILE_ID")?>:</td>
		<td><input type="text" name="filter_profile_id" value="<?echo htmlspecialcharsbx($filter_profile_id)?>" size="40"></td>
	</tr>
	<tr>
		<td><?echo GetMessage("AT_TEST_ID")?>:</td>
		<td><input type="text" name="filter_test_id" value="<?echo htmlspecialcharsbx($filter_test_id)?>" size="40"></td>
	</tr>
	<tr>
		<td><?echo GetMessage("AT_RESULT_ID")?>:</td>
		<td><input type="text" name="filter_result_id" value="<?echo htmlspecialcharsbx($filter_result_id)?>" size="40"></td>
	</tr>
	<tr>
		<td><?echo GetMessage("AT_USER_ID")?>:</td>
		<td><input type="text" name="filter_user_id" value="<?echo htmlspecialcharsbx($filter_user_id)?>" size="40"></td>
	</tr>
	<tr>
		<td><?echo GetMessage("AT_CLOSED")?>:</td>
		<td>
			<select name="filter_closed">
				<option value=""><?echo GetMessage("AT_ACTIVE_NON")?></option>
				<option value="Y"<?if ($filter_closed=="Y") echo " selected"?>><?echo GetMessage("AT_ACTIVE_Y")?></option>
				<option value="N"<?if ($filter_closed=="N") echo " selected"?>><?echo GetMessage("AT_ACTIVE_N")?></option>
			</select>
		</td>
	</tr>
	<?/*<tr>
		<td><?echo GetMessage("AT_SCORES")?></td>
		<td nowrap>
			<input type="text" name="filter_scores_1" size="10" value="<?echo htmlspecialcharsex($filter_scores_1)?>">
			...
			<input type="text" name="filter_scores_2" size="10" value="<?echo htmlspecialcharsex($filter_scores_2)?>">
		</td>
	</tr>*/?>

<?
$USER_FIELD_MANAGER->AdminListShowFilter("AELITA_TEST_STATISTICS");

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