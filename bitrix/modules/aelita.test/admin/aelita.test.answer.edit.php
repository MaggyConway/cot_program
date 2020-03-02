<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
$module_id="aelita.test";

$ListItem="/bitrix/admin/aelita.test.question.edit.php?lang=";
$EditItem="/bitrix/admin/aelita.test.answer.edit.php?lang=";
$TableModule="b_aelita_test_question_group";

$EditTest="/bitrix/admin/aelita.test.test.edit.ex.php?lang=";

IncludeModuleLangFile(__FILE__);
$POST_RIGHT = $APPLICATION->GetGroupRight($module_id);
if($POST_RIGHT=="D")
	$APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
if(!CModule::IncludeModule($module_id))die();
	
$ID = IntVal($ID);
if ($ID > 0)
{
	$el =new AelitaTestAnswer;
	$select=array(
        "ID",
        "XML_ID",
        "NAME",
        "ACTIVE",
        "PICTURE",
        "DESCRIPTION",
        "DESCRIPTION_TYPE",
        "CORRECT_DESCRIPTION",
        "CORRECT_DESCRIPTION_TYPE",
        "ERROR_DESCRIPTION",
        "ERROR_DESCRIPTION_TYPE",
        "SORT",
        "QUESTION_ID",
        "SCORES",
        "CORRECT",
        "TEST_ID",
        "ALT",
    );
	$arExtra=$el->GetList(false,array("ID"=>$ID),false,false,$select);
	if (!$arExtra=$arExtra->GetNext())
	{
		if ($bReadOnly)
			$errorMessage .= GetMessage("CEEN_NO_PERMS2ADD").". ";
		$ID = 0;
	}else{
		$TEST_ID=$arExtra["TEST_ID"];
		$QUESTION_ID=$arExtra["QUESTION_ID"];
	}
}else{
	$TEST_ID=IntVal($TEST_ID);
	$QUESTION_ID=IntVal($QUESTION_ID);
}

$e =new AelitaTestQuestion;
$arQuestion=$e->GetByID($QUESTION_ID);
if($Question=$arQuestion->GetNext())
	if($Question["TEST_ID"]!=$TEST_ID)
		unset($Question);


$arTest = AelitaTestEditToolEx::GetByID_admin($TEST_ID, 'question');
if (!$arTest || !$Question)
{
	require($DOCUMENT_ROOT."/bitrix/modules/main/include/prolog_admin_after.php");
	echo BeginNote('width="100%"');
	echo GetMessage("NO_TEST_ERROR");
	echo EndNote();
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
	die();
}

ClearVars();
ClearVars("str_");

$errorMessage = "";
$bVarsFromForm = false;


$ShowXmlID=COption::GetOptionString($module_id, "aelita_test_show_xml_id","N");
$UseCorrect=AelitaTestEditToolEx::GetUseCorrect($TEST_ID);

if ($REQUEST_METHOD=="POST" && strlen($Update) > 0 && !$bReadOnly && check_bitrix_sessid())
{
	$arPICTURE = $HTTP_POST_FILES["PICTURE"];
	$arPICTURE["del"] = ${"PICTURE_del"};
	
	$arFields = array(
		"NAME"=>$NAME,
		"ACTIVE"=>$ACTIVE,
		"PICTURE"=>$arPICTURE,
		"DESCRIPTION"=>$DESCRIPTION,
		"DESCRIPTION_TYPE"=>$DESCRIPTION_TYPE,
		"SORT"=>$SORT,
		"SCORES"=>$SCORES,
        "CORRECT"=>$CORRECT,
		"QUESTION_ID"=>$QUESTION_ID,
        "ALT"=>$ALT,
        "CORRECT_DESCRIPTION"=>$CORRECT_DESCRIPTION,
        "CORRECT_DESCRIPTION_TYPE"=>$CORRECT_DESCRIPTION_TYPE,
        "ERROR_DESCRIPTION"=>$ERROR_DESCRIPTION,
        "ERROR_DESCRIPTION_TYPE"=>$ERROR_DESCRIPTION_TYPE,

	);
	
	if($ShowXmlID=="Y")
		$arFields["XML_ID"]=$XML_ID;

	$el=new AelitaTestAnswer;
	if ($ID>0)
	{
		if (!$el->Update($ID, $arFields))
		{
			if ($ex = $APPLICATION->GetException())
				$errorMessage .= $ex->GetString().". ";
			else
				$errorMessage .= GetMessage("AT_ERROR_SAVING_EXTRA").". ";
		}
	}else{
		
		$ID = $el->Add($arFields);
		$ID = IntVal($ID);
		if ($ID <= 0)
		{
			if ($ex = $APPLICATION->GetException())
				$errorMessage .= $ex->GetString().". ";
			else
				$errorMessage .= GetMessage("AT_ERROR_SAVING_EXTRA").". ";
		}
	}

	if (strlen($errorMessage)<=0)
	{
		if(strlen($apply)<=0)
			LocalRedirect($ListItem.LANG.'&TEST_ID='.$TEST_ID."&QID=".$QUESTION_ID.'#list_title');
		else
			LocalRedirect($EditItem.LANG.'&TEST_ID='.$TEST_ID."&ID=".$ID."&QUESTION_ID=".$QUESTION_ID."&tabControl_active_tab=".urlencode($tabControl_active_tab));
	}
	else
	{
		$bVarsFromForm = true;
	}
}

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



if ($ID > 0)
{
    $str_NAME = $arExtra["NAME"];
    if($ShowXmlID=="Y")
        $str_XML_ID = htmlspecialchars($arExtra["XML_ID"]);
    $str_ACTIVE = htmlspecialchars($arExtra["ACTIVE"]);
    $str_PICTURE= $arExtra["PICTURE"];
    $str_DESCRIPTION = $arExtra["DESCRIPTION"];
    $str_DESCRIPTION_TYPE = $arExtra["DESCRIPTION_TYPE"];
    $str_SORT = $arExtra["SORT"];
    $str_SCORES = $arExtra["SCORES"];
    $str_CORRECT = $arExtra["CORRECT"];
    $str_ALT = $arExtra["ALT"];
    $str_CORRECT_DESCRIPTION = $arExtra["CORRECT_DESCRIPTION"];
    $str_CORRECT_DESCRIPTION_TYPE = $arExtra["CORRECT_DESCRIPTION_TYPE"];
    $str_ERROR_DESCRIPTION = $arExtra["ERROR_DESCRIPTION"];
    $str_ERROR_DESCRIPTION_TYPE = $arExtra["ERROR_DESCRIPTION_TYPE"];
}

if ($bVarsFromForm)
	$DB->InitTableVarsForEdit($TableModule, "", "str_");
	
$aMenu = array(
	array(
		"TEXT" => GetMessage("AT_LIST"),
		"LINK" => $ListItem.LANG.'&TEST_ID='.$TEST_ID."&QID=".$QUESTION_ID.'#list_title',
		"ICON"=>"btn_list",
	)
);

if ($ID > 0 && !$bReadOnly )
{
	$aMenu[] = array(
			"TEXT" => GetMessage("AT_NEW_GROUP"),
			"ICON" => "btn_new",
			"LINK" => $EditItem.LANG.'&TEST_ID='.$TEST_ID.'&QUESTION_ID='.$QUESTION_ID
		);
	$aMenu[] = array(
			"TEXT" => GetMessage("AT_DEL_GROUP"), 
			"ICON" => "btn_delete",
			"LINK" => "javascript:if(confirm('".GetMessage("AT_DEL_GROUP_CONFIRM")."')) window.location='".$ListItem.LANG.'&QID='.$QUESTION_ID.'&TEST_ID='.$TEST_ID."&ID[]=".$ID."&action=delete&".bitrix_sessid_get()."#list_title';",
			"WARNING" => "Y"
		);
}
	
$context = new CAdminContextMenu($aMenu);
$context->Show();
	
CAdminMessage::ShowMessage($errorMessage);
?>

<form method="POST" action="<?echo $APPLICATION->GetCurPage()?>?lang=<?echo LANG?><?if ($ID > 0):?>&ID=<?=$ID?><?endif;?>&TEST_ID=<?=$TEST_ID?>&QUESTION_ID=<?=$QUESTION_ID?>" name="form1" ENCTYPE="multipart/form-data">

<input type="hidden" name="Update" value="Y">
<input type="hidden" name="lang" value="<?echo LANG ?>">
<input type="hidden" name="ID" value="<?echo $ID ?>">
<?=bitrix_sessid_post()?>
<?
$aTabs = array(
		array("DIV" => "edit1", "TAB" => GetMessage("AT_TAB_GROUP"), "ICON" => "catalog", "TITLE" => GetMessage("AT_TAB_GROUP_DESCR")),
	);

$tabControl = new CAdminTabControl("tabControl", $aTabs);
$tabControl->Begin();
?>

<?
$tabControl->BeginNextTab();
?>

	<?if ($ID > 0):?>
		<tr>
			<td width="30%">ID:</td>
			<td width="70%"><?=$ID?></td>
		</tr>
	<?endif;?>
	<tr>
		<td width="30%"><?echo GetMessage("AT_ACTIVE")?>:</td>
		<td width="70%">
			<input type="checkbox" name="ACTIVE" id="ACTIVE" value="Y"<?if ($str_ACTIVE == "Y" || $ID<=0) echo " checked";?>>
		</td>
	</tr>
	<?if($ShowXmlID=="Y"):?>
	<tr>
		<td width="40%"><?echo GetMessage("AT_XML_ID")?>:</td>
		<td width="60%">
			<input type="text" name="XML_ID" size="50" value="<?=$str_XML_ID?>">
		</td>
	</tr>
	<?endif?>
	<tr>
		<td width="30%"><span class="required">*</span><?echo GetMessage("AT_NAME")?>:</td>
		<td width="70%">
			<input type="text" name="NAME" size="50" value="<?=$str_NAME?>">
		</td>
	</tr>
    <?if ($UseCorrect){?>
        <td width="30%"><?echo GetMessage("AT_CORRECT_ANSWER")?>:</td>
        <td width="70%">
            <input type="checkbox" name="CORRECT" id="CORRECT" value="Y"<?if ($str_CORRECT == "Y") echo " checked";?>>
        </td>
    <?}else{?>
        <tr>
            <td width="30%"><?echo GetMessage("AT_SCORES")?>:</td>
            <td width="70%">
                <input type="text" name="SCORES" size="50" value="<?=$str_SCORES?>">
            </td>
        </tr>
    <?}?>
	<tr>
		<td width="30%"><?echo GetMessage("AT_SORT")?>:</td>
		<td width="70%">
			<input type="text" name="SORT" size="50" value="<?=$str_SORT?>">
		</td>
	</tr>
	

	<tr>
		<td class="adm-detail-valign-top"><?echo GetMessage("AT_PICTURE")?></td>
		<td>
			<?echo CFileInput::Show('PICTURE', $str_PICTURE, array(
					"IMAGE" => "Y",
					"PATH" => "Y",
					"FILE_SIZE" => "Y",
					"DIMENSIONS" => "Y",
					"IMAGE_POPUP" => "Y",
					"MAX_SIZE" => array("W" => 200, "H"=>200),
					), array(
						'upload' => true,
						'medialib' => false,
						'file_dialog' => false,
						'cloud' => false,
						'del' => true,
                        'description' => array("NAME"=>"ALT","VALUE"=>$str_ALT),
					)
				);?>
		</td>
	</tr>

    <tr class="heading">
        <td colspan="2"><?echo GetMessage("AT_DESCRIPTION")?></td>
    </tr>

	<?if(CModule::IncludeModule("fileman")):?>
		<tr>
			<td colspan="2" align="center">
				<?CFileMan::AddHTMLEditorFrame("DESCRIPTION", $str_DESCRIPTION, "DESCRIPTION_TYPE", $str_DESCRIPTION_TYPE, 250);?>
			</td>
		</tr>
	<?else:?>
		<tr>
			<td ><?echo GetMessage("AT_DESCRIPTION_TYPE")?></td>
			<td >
				<input type="radio" name="DESCRIPTION_TYPE" id="DESCRIPTION_TYPE1" value="text"<?if($str_DESCRIPTION_TYPE!="html")echo " checked"?>><label for="DESCRIPTION_TYPE1"> <?echo GetMessage("AT_DESCRIPTION_TYPE_TEXT")?></label> /
				<input type="radio" name="DESCRIPTION_TYPE" id="DESCRIPTION_TYPE2" value="html"<?if($str_DESCRIPTION_TYPE=="html")echo " checked"?>><label for="DESCRIPTION_TYPE2"> <?echo GetMessage("AT_DESCRIPTION_TYPE_HTML")?></label>
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center">
				<textarea cols="60" rows="15" name="DESCRIPTION" style="width:100%;"><?echo $str_DESCRIPTION?></textarea>
			</td>
		</tr>
	<?endif?>




<?if ($UseCorrect){?>
    <tr class="heading">
        <td colspan="2"><?echo GetMessage("AT_CORRECT_DESCRIPTION")?></td>
    </tr>
    <?if(CModule::IncludeModule("fileman")):?>
        <tr>
            <td colspan="2" align="center">
                <?CFileMan::AddHTMLEditorFrame("CORRECT_DESCRIPTION", $str_CORRECT_DESCRIPTION, "CORRECT_DESCRIPTION_TYPE", $str_CORRECT_DESCRIPTION_TYPE, 250);?>
            </td>
        </tr>
    <?else:?>
        <tr>
            <td ><?echo GetMessage("AT_DESCRIPTION_TYPE")?></td>
            <td >
                <input type="radio" name="CORRECT_DESCRIPTION_TYPE" id="CORRECT_DESCRIPTION_TYPE1" value="text"<?if($str_CORRECT_DESCRIPTION_TYPE!="html")echo " checked"?>><label for="CORRECT_DESCRIPTION_TYPE1"> <?echo GetMessage("AT_DESCRIPTION_TYPE_TEXT")?></label> /
                <input type="radio" name="CORRECT_DESCRIPTION_TYPE" id="CORRECT_DESCRIPTION_TYPE2" value="html"<?if($str_CORRECT_DESCRIPTION_TYPE=="html")echo " checked"?>><label for="CORRECT_DESCRIPTION_TYPE2"> <?echo GetMessage("AT_DESCRIPTION_TYPE_HTML")?></label>
            </td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                <textarea cols="60" rows="15" name="CORRECT_DESCRIPTION" style="width:100%;"><?echo $str_CORRECT_DESCRIPTION?></textarea>
            </td>
        </tr>
    <?endif?>


    <tr class="heading">
        <td colspan="2"><?echo GetMessage("AT_ERROR_DESCRIPTION")?></td>
    </tr>
    <?if(CModule::IncludeModule("fileman")):?>
        <tr>
            <td colspan="2" align="center">
                <?CFileMan::AddHTMLEditorFrame("ERROR_DESCRIPTION", $str_ERROR_DESCRIPTION, "ERROR_DESCRIPTION_TYPE", $str_ERROR_DESCRIPTION_TYPE, 250);?>
            </td>
        </tr>
    <?else:?>
        <tr>
            <td ><?echo GetMessage("AT_DESCRIPTION_TYPE")?></td>
            <td >
                <input type="radio" name="ERROR_DESCRIPTION_TYPE" id="ERROR_DESCRIPTION_TYPE1" value="text"<?if($str_ERROR_DESCRIPTION_TYPE!="html")echo " checked"?>><label for="ERROR_DESCRIPTION1"> <?echo GetMessage("AT_DESCRIPTION_TYPE_TEXT")?></label> /
                <input type="radio" name="ERROR_DESCRIPTION_TYPE" id="ERROR_DESCRIPTION_TYPE2" value="html"<?if($str_ERROR_DESCRIPTION_TYPE=="html")echo " checked"?>><label for="ERROR_DESCRIPTION2"> <?echo GetMessage("AT_DESCRIPTION_TYPE_HTML")?></label>
            </td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                <textarea cols="60" rows="15" name="ERROR_DESCRIPTION" style="width:100%;"><?echo $str_ERROR_DESCRIPTION?></textarea>
            </td>
        </tr>
    <?endif?>



<?}?>




<?
$tabControl->EndTab();
?>

<?
$tabControl->Buttons(
		array(
				"disabled" => $bReadOnly,
				"back_url" => $ListItem.LANG.'&TEST_ID='.$TEST_ID."&QID=".$QUESTION_ID.'#list_title',
			)
	);
?>

<?
$tabControl->End();
?>

</form>

<?echo BeginNote();?>
<span class="required">*</span> <?echo GetMessage("REQUIRED_FIELDS")?>
<?echo EndNote(); ?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");?>