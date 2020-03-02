<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
$module_id="aelita.test";

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

$sTableID = $TableModule;
$oSort = new CAdminSorting($sTableID, "ID", "ASC");
$lAdmin = new CAdminList($sTableID, $oSort);

$arFilterFields = array(
	"filter_name",
	"filter_active",
	"filter_group_id",
);
$USER_FIELD_MANAGER->AdminListAddFilterFields("AELITA_TEST_STATISTICS_TEST", $arFilterFields);

$lAdmin->InitFilter($arFilterFields);

$arFilter=array();
if($del_filter!="Y")
{
	if (strlen($filter_name) > 0)
		$arFilter["NAME"] = $filter_name;
	if (strlen($filter_active) > 0)
		$arFilter["ACTIVE"] = $filter_active;
	if (is_array($filter_group_id))
		$arFilter["GROUP_ID"] = $filter_group_id;
	else
		$filter_group_id = array();
}
$USER_FIELD_MANAGER->AdminListAddFilter("AELITA_TEST_STATISTICS_TEST", $arFilter);

$el=new AelitaTestTest();
$arSelect=array(
	"ID",
	"NAME",
	"ACTIVE",
	"GROUP_ID",
	"ATTEMPTS",
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
$arHeaders[]=array("id"=>"ACTIVE","content"=>GetMessage("AT_ACTIVE"), "sort"=>"ACTIVE", "default"=>true);
$arHeaders[]=array("id"=>"NAME","content"=>GetMessage("NAME"), "sort"=>"NAME", "default"=>true);
$arHeaders[]=array("id"=>"GROUP_ID","content"=>GetMessage("AT_GROUP_ID"), "sort"=>"", "default"=>true);
$arHeaders[]=array("id"=>"ATTEMPTS","content"=>GetMessage("AT_ATTEMPTS"), "sort"=>"ATTEMPTS", "default"=>true);


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
	$row=&$lAdmin->AddRow($f_ID, $arExtra);
	$row->AddField("ID", $f_ID);
	$row->AddCheckField("ACTIVE");
	$row->AddViewField("NAME", $f_NAME);
	$row->AddViewField("GROUP_ID", $Groups[$f_GROUP_ID]);
	$row->AddViewField("ATTEMPTS", GetMessage("AT_ATTEMPTS_LINK",array("#ID_TEST#"=>$f_ID,"#COUNT#"=>$f_ATTEMPTS)));
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
				<option value=""><?echo GetMessage("AT_NON")?></option>
				
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
$USER_FIELD_MANAGER->AdminListShowFilter("AELITA_TEST_STATISTICS_TEST");

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