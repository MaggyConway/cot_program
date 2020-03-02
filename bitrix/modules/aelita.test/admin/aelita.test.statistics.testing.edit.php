<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
$module_id="aelita.test";
$ex_type_result = COption::GetOptionString($module_id, "aelita_test_ex_type_result","N");
$ListItem="/bitrix/admin/aelita.test.statistics.testing.php?lang=";
$EditItem="/bitrix/admin/aelita.test.statistics.testing.edit.php?lang=";
$TableModule="b_aelita_test_glasses";

IncludeModuleLangFile(__FILE__);
$POST_RIGHT = $APPLICATION->GetGroupRight($module_id);
if($POST_RIGHT=="D")
	$APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
if(!CModule::IncludeModule($module_id))die();
	
CUtil::InitJSCore(array('jquery'));

$errorMessage = "";
$bVarsFromForm = false;
$ID = IntVal($ID);

if ($ID > 0)
{
	$el=new AelitaTestQuestioning();
	$arSelect=array("ID","PROFILE_ID","TEST_ID","RESULT_ID","CLOSED","TEST_NAME","RESULT_NAME","SCORES","USER_ID","DATE_START","DATE_STOP","FINAL","DURATION","TEST_SHOW_COMMENTS","TEST_TYPE_RESULT");
	$arGroup=array("ID");
	$arFilter=array("ID"=>$ID);
	$arExtra=$el->GetList(array("ID"=>"ASC"),$arFilter,$arGroup,array("nPageSize"=>1),$arSelect);
	if (!$arExtra=$arExtra->GetNext())
	{
		if ($bReadOnly)
			$errorMessage .= GetMessage("CEEN_NO_PERMS2ADD").". ";
		$ID = 0;
	}
}
?>
<?if($ID>0){?>
	
<?
$aMenu = array(
	array(
		"TEXT" => GetMessage("AT_LIST"),
		"LINK" => $ListItem.LANG,
		"ICON"=>"btn_list",
	)
);

if ($ID > 0 && !$bReadOnly )
{
	$aMenu[] = array(
			"TEXT" => GetMessage("AT_DEL_GROUP"), 
			"ICON" => "btn_delete",
			"LINK" => "javascript:if(confirm('".GetMessage("AT_DEL_GROUP_CONFIRM")."')) window.location='".$ListItem.LANG."&ID=".$ID."&action=delete&".bitrix_sessid_get()."#tb';",
			"WARNING" => "Y"
		);
}

?>


<?
$aTabs = array(
		array("DIV" => "edit1", "TAB" => GetMessage("AT_TAB_GROUP"), "ICON" => "catalog", "TITLE" => GetMessage("AT_TAB_GROUP_DESCR")),
	);

$tabControl = new CAdminTabControl("tabControl", $aTabs);

?>

<?
$sTableID = $TableModule;
$oSort = new CAdminSorting($sTableID, "ID", "ASC");
$lAdmin = new CAdminList($sTableID, $oSort);

$arFilter=array(
	"QUESTIONING_ID"=>$ID,
);

$el=new AelitaTestGlasses();
$arSelect=array(
	"ID",
	"QUESTION_ID",
	"QUESTION_NAME",
	"SCORES",
	"SERIALIZED_RESULT",
	"SERIALIZED_RESULT_TEXT",
	"COMMENTS",
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
$arHeaders[]=array("id"=>"QUESTION_ID","content"=>GetMessage("AT_QUESTION_ID"), "sort"=>"QUESTION_ID", "default"=>true);
$arHeaders[]=array("id"=>"QUESTION_NAME","content"=>GetMessage("AT_QUESTION_NAME"), "sort"=>"QUESTION_NAME", "default"=>true);
$arHeaders[]=array("id"=>"SCORES","content"=>GetMessage("AT_SCORES"), "sort"=>"SCORES", "default"=>true);
$arHeaders[]=array("id"=>"SERIALIZED_RESULT","content"=>GetMessage("AT_SERIALIZED_RESULT"), "sort"=>"", "default"=>true);
$arHeaders[]=array("id"=>"SERIALIZED_RESULT_TEXT","content"=>GetMessage("SERIALIZED_RESULT_TEXT"), "sort"=>"", "default"=>true);
//if($arExtra["TEST_SHOW_COMMENTS"]=="Y")
	$arHeaders[]=array("id"=>"COMMENTS","content"=>GetMessage("AT_COMMENTS"), "sort"=>"", "default"=>true);

$lAdmin->AddHeaders($arHeaders);
$arVisibleColumns = $lAdmin->GetVisibleHeaderColumns();

while ($arElement = $dbResultList->NavNext(true, "f_"))
{
	$row=&$lAdmin->AddRow($f_ID, $arElement);
	$row->AddField("ID", $f_ID);
	$SerializedResult=$f_SERIALIZED_RESULT;
	if($SerializedResult)
	{
		$SerializedResult=unserialize(base64_decode($SerializedResult));
		$SerializedResult=implode("<br />",$SerializedResult);
	}
	$row->AddViewField("SERIALIZED_RESULT", $SerializedResult);

	$SerializedResultText=$f_SERIALIZED_RESULT_TEXT;
	if($SerializedResultText)
	{
		$SerializedResultText=unserialize(base64_decode($SerializedResultText));
		$SerializedResultText=implode("<br />",$SerializedResultText);
	}
	$row->AddViewField("SERIALIZED_RESULT_TEXT", $SerializedResultText);


	//if($arExtra["TEST_SHOW_COMMENTS"]=="Y")
		$row->AddField("COMMENTS", $f_COMMENTS);
}


$lAdmin->CheckListMode();
$APPLICATION->SetTitle(GetMessage("TITLE"));
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
?>
<?
$context = new CAdminContextMenu($aMenu);
$context->Show();

$tabControl->Begin();
$tabControl->BeginNextTab();

?>

	<?if ($ID > 0):?>
		<tr>
			<td width="30%">ID:</td>
			<td width="70%"><?=$ID?></td>
		</tr>
	<?endif;?>

	<tr>
		<td width="30%"><?=GetMessage("AT_PROFILE_ID")?>:</td>
		<td width="70%"><?=$arExtra["PROFILE_ID"]?></td>
	</tr>
	<tr>
		<td width="30%"><?=GetMessage("AT_USER_ID")?>:</td>
		<td width="70%">
		<?
			if($arExtra["USER_ID"]>0)
				echo GetMessage("LINK_USER_ID",array("#ID#"=>$arExtra["USER_ID"],"#LANG#"=>LANG));
			else
				echo GetMessage("AT_NON_USER");
		?>
		</td>
	</tr>
    <tr>
        <td width="30%"><?=GetMessage("AT_USER_FIO")?>:</td>
        <td width="70%">
            <?
                if($arExtra["USER_ID"]>0)
                {
                    $rsUser = CUser::GetByID($arExtra["USER_ID"]);
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
                    echo $textUrl;
                }
            ?>
        </td>
    </tr>
	<tr>
		<td width="30%"><?=GetMessage("AT_TEST_ID")?>:</td>
		<td width="70%"><?=$arExtra["TEST_ID"]?></td>
	</tr>
	<tr>
		<td width="30%"><?=GetMessage("AT_TEST_NAME")?>:</td>
		<td width="70%"><?=$arExtra["TEST_NAME"]?></td>
	</tr>
	<tr>
		<td width="30%"><?=GetMessage("AT_RESULT_ID")?>:</td>
		<td width="70%"><?=$arExtra["RESULT_ID"]?></td>
	</tr>
	<tr>
		<td width="30%"><?=GetMessage("AT_RESULT_NAME")?>:</td>
		<td width="70%">
		<?if($arExtra["RESULT_ID"])
			echo $arExtra["RESULT_NAME"];
		else
			echo GetMessage("AT_NON_RESULT");
		?>
		</td>
	</tr>
	
	<?
	if($ex_type_result=="Y"){
		$AverScores=AelitaTestTools::GetAverResult($arExtra["ID"]);
		switch ($arExtra["TEST_TYPE_RESULT"]){
			case 'summ':?>
				<tr>
					<td width="30%"><?=GetMessage("AT_SCORES")?>:</td>
					<td width="70%"><?=$arExtra["SCORES"]?></td>
				</tr>
				<?break;
			case 'aver':?>
				<tr>
					<td width="30%"><?=GetMessage("AT_AVERS_CORES")?>:</td>
					<td width="70%"><?=$AverScores?></td>
				</tr>
				<?break;
			case 'suer':?>
				<tr>
					<td width="30%"><?=GetMessage("AT_SCORES")?>:</td>
					<td width="70%"><?=$arExtra["SCORES"]?></td>
				</tr>
				<tr>
					<td width="30%"><?=GetMessage("AT_AVERS_CORES")?>:</td>
					<td width="70%"><?=$AverScores?></td>
				</tr>
				<?break;
		}; 

	}else{?>
		<tr>
			<td width="30%"><?=GetMessage("AT_SCORES")?>:</td>
			<td width="70%"><?=$arExtra["SCORES"]?></td>
		</tr>
	<?}?>
	
	<tr>
		<td width="30%"><?=GetMessage("AT_DATE_START")?>:</td>
		<td width="70%"><?=$arExtra["DATE_START"]?></td>
	</tr>
	<tr>
		<td width="30%"><?=GetMessage("AT_DATE_STOP")?>:</td>
		<td width="70%"><?=$arExtra["DATE_STOP"]?></td>
	</tr>
	<tr>
		<td width="30%"><?=GetMessage("AT_DURATION")?>:</td>
		<td width="70%">
		<?=	AelitaTestTools::GetTxtTime($arExtra["DURATION"]);?>
		</td>
	</tr>
	<tr>
		<td width="30%"><?=GetMessage("AT_FINAL")?>:</td>
		<td width="70%"><?=GetMessage("AT_ACTIVE_".$arExtra["FINAL"])?></td>
	</tr>
	<tr>
		<td width="30%"><?=GetMessage("AT_CLOSED")?>:</td>
		<td width="70%"><?=GetMessage("AT_ACTIVE_".$arExtra["CLOSED"])?></td>
	</tr>

<?
$tabControl->EndTab();
?>

<?
$tabControl->End();
?>
<br />
<?
$lAdmin->DisplayList();
?>


<?}else{?>
<?echo BeginNote();?>
<?echo GetMessage("NO_TEST")?>
<?echo EndNote(); ?>
<?}?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");?>
