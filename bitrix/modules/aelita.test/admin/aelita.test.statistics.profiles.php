<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
$module_id="aelita.test";

$TableModule="b_aelita_test_profile";
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


if (($arID = $lAdmin->GroupAction()) && !$bReadOnly )
{
	if ($_REQUEST['action_target']=='selected')
	{
		$arID = Array();
		$el=new AelitaTestProfile();
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
				$el=new AelitaTestProfile();
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

$el=new AelitaTestProfile();
$arSelect=array(
	"ID",
	"USER_ID",
	);

$dbResultList = $el->GetList(array($by=>$order),$arFilter,false,false,$arSelect);

$dbResultList = new CAdminResult($dbResultList, $sTableID);
$dbResultList->NavStart();

$lAdmin->NavText($dbResultList->GetNavPrint(GetMessage("cat_extra_nav")));

$arHeaders=array();
$arHeaders[]=array("id"=>"ID", "content"=>"ID", "sort"=>"ID", "default"=>true);
$arHeaders[]=array("id"=>"USER_ID","content"=>GetMessage("AT_USER_ID"), "sort"=>"USER_ID", "default"=>true);
$arHeaders[]=array("id"=>"ATTEMPTS","content"=>GetMessage("AT_ATTEMPTS"), "sort"=>"ATTEMPTS", "default"=>true);


$lAdmin->AddHeaders($arHeaders);
$arVisibleColumns = $lAdmin->GetVisibleHeaderColumns();


	

while ($arExtra = $dbResultList->NavNext(true, "f_"))
{
	$row=&$lAdmin->AddRow($f_ID, $arExtra);
	$row->AddField("ID", $f_ID);
	if($f_USER_ID>0)
		$row->AddViewField("USER_ID", GetMessage("LINK_USER_ID",array("#ID#"=>$f_USER_ID,"#LANG#"=>LANG)));
	else
		$row->AddViewField("USER_ID", GetMessage("AT_NON_USER"));
	$row->AddViewField("ATTEMPTS", GetMessage("AT_ATTEMPTS_LINK",array("#ID_TEST#"=>$f_ID)));
}


$lAdmin->CheckListMode();

$APPLICATION->SetTitle(GetMessage("TITLE"));
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
?>

<?
$lAdmin->DisplayList();
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");?>