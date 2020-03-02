<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
$module_id="aelita.test";
$TableModule="b_aelita_test_questioning_list";
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
	"test_id",
);
$USER_FIELD_MANAGER->AdminListAddFilterFields("AELITA_TEST_STATISTICS", $arFilterFields);

$lAdmin->InitFilter($arFilterFields);

$arFilter=array();
if($del_filter!="Y")
{
	if (strlen($filter_test_id) > 0)
		$arFilter["TEST_ID"] = $filter_test_id;
}
$USER_FIELD_MANAGER->AdminListAddFilter("AELITA_TEST_STATISTICS", $arFilter);

$Tests=array();
$elTest=new AelitaTestTest();
$dbResultListTest = $elTest->GetList();
while ($arExtraTest = $dbResultListTest->GetNext())
    $Tests[$arExtraTest["ID"]]=$arExtraTest["NAME"];


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
$elGlasses=new AelitaTestGlasses();
if ($filter_test_id>0 && $del_filter!="Y")
{

    $Questions=array();
    $elQuestion=new AelitaTestQuestion();
    $arFilterQuestion=array(
        "TEST_ID"=>$filter_test_id,
    );
    $dbResultListQuestion = $elQuestion->GetList(array("ID"=>"ASC"),$arFilterQuestion);
    while ($arQuestion = $dbResultListQuestion->GetNext())
        $Questions[$arQuestion["ID"]]=$arQuestion["NAME"];


    $dbResultList = $el->GetList(array($by=>$order),$arFilter,$arGroup,false,$arSelect);

    $dbResultList = new CAdminResult($dbResultList, $sTableID);
    $dbResultList->NavStart();

    $lAdmin->NavText($dbResultList->GetNavPrint(GetMessage("cat_extra_nav")));

    $arHeaders=array();


    $arHeaders[]=array("id"=>"USER_ID","content"=>GetMessage("AT_USER_ID"), "sort"=>"USER_ID", "default"=>true);
    $arHeaders[]=array("id"=>"USER_FIO","content"=>GetMessage("AT_USER_FIO"), "sort"=>false, "default"=>true);
    $arHeaders[]=array("id"=>"TEST_NAME","content"=>GetMessage("AT_TEST_NAME"), "sort"=>"", "default"=>true);
    $arHeaders[]=array("id"=>"RESULT_NAME","content"=>GetMessage("AT_RESULT_NAME"), "sort"=>"", "default"=>true);
    $arHeaders[]=array("id"=>"SCORES","content"=>GetMessage("AT_SCORES"), "sort"=>"SCORES", "default"=>true);
    $arHeaders[]=array("id"=>"DATE_START","content"=>GetMessage("AT_DATE_START"), "sort"=>"DATE_START", "default"=>true);
    $arHeaders[]=array("id"=>"DATE_STOP","content"=>GetMessage("AT_DATE_STOP"), "sort"=>"DATE_STOP", "default"=>true);
    $arHeaders[]=array("id"=>"DURATION","content"=>GetMessage("AT_DURATION"), "sort"=>"DURATION", "default"=>true);
    $arHeaders[]=array("id"=>"FINAL","content"=>GetMessage("AT_FINAL"), "sort"=>"FINAL", "default"=>true);
    $arHeaders[]=array("id"=>"CLOSED","content"=>GetMessage("AT_CLOSED"), "sort"=>"CLOSED", "default"=>true);

    foreach ($Questions as $key=>$Question)
        $arHeaders[]=array("id"=>"Question".$key,"content"=>$Question, "sort"=>false, "default"=>true);

    $lAdmin->AddHeaders($arHeaders);
    $arVisibleColumns = $lAdmin->GetVisibleHeaderColumns();

    while ($arExtra = $dbResultList->NavNext(true, "f_"))
    {

        $row=&$lAdmin->AddRow($f_ID, $arExtra);


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

        if (!$bReadOnly)
        {
            $aContext = array(
            );
            $lAdmin->AddAdminContextMenu($aContext);
        }


        $arSelectGlasses=array(
            "ID",
            "QUESTION_ID",
            "QUESTION_NAME",
            "SCORES",
            "SERIALIZED_RESULT",
            "COMMENTS",
        );
        $arFilterGlasses=array(
            "QUESTIONING_ID"=>$f_ID,
        );
        $arGroupGlasses=array(
            "ID",
        );
        $dbResultListGlasses = $elGlasses->GetList(array("ID"=>"ASC"),$arFilterGlasses,$arGroupGlasses,false,$arSelectGlasses);
        while ($arQuestionGlasses = $dbResultListGlasses->GetNext())
        {
            $SerializedResult=$arQuestionGlasses["SERIALIZED_RESULT"];
            if($SerializedResult)
            {
                $SerializedResult=unserialize(base64_decode($SerializedResult));
                $SerializedResult=implode("<br />",$SerializedResult);
            }
            $row->AddViewField("Question".$arQuestionGlasses["QUESTION_ID"], $SerializedResult);
            //echo "<pre>";print_r($arQuestionGlasses);echo "</pre>";
        }
    }

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
		GetMessage("AT_TEST_ID"),
		//GetMessage("AT_SCORES"),
	)
);

$oFilter->Begin();
?>
	<tr>
		<td><?echo GetMessage("AT_TEST_ID")?>:</td>
		<td>
            <select name="filter_test_id">
                <option value=""><?echo GetMessage("AT_ACTIVE_N")?></option>
                <?foreach($Tests as $key=>$value){
                    if($key>0){?>
                        <option <?if($key==$filter_test_id){?>selected="selected"<?};?> value="<?=$key?>">
                            <?=$value?>
                        </option>
                    <?}
                }?>
            </select>
        </td>
	</tr>


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