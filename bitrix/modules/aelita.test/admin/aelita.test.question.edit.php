<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
$module_id="aelita.test";

$ListItem="/bitrix/admin/aelita.test.question.list.php?lang=";
$EditItem="/bitrix/admin/aelita.test.question.edit.php?lang=";
$TableModule="b_aelita_test_question";


$EditItemAnswer="/bitrix/admin/aelita.test.answer.edit.php?lang=";


$EditTest="/bitrix/admin/aelita.test.test.edit.ex.php?lang=";

IncludeModuleLangFile(__FILE__);
$POST_RIGHT = $APPLICATION->GetGroupRight($module_id);
if($POST_RIGHT=="D")
	$APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
if(!CModule::IncludeModule($module_id))die();

$QID = IntVal($QID);
if ($QID > 0)
{
	$el =new AelitaTestQuestion;
	$arExtra=$el->GetByID($QID);
	if (!$arExtra=$arExtra->GetNext())
	{
		if ($bReadOnly)
			$errorMessage .= GetMessage("CEEN_NO_PERMS2ADD").". ";
		$QID = 0;
	}else{
		$TEST_ID=$arExtra["TEST_ID"];
	}
}else{
	$TEST_ID=IntVal($TEST_ID);
}
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
		"TEST_GROUP_ID"=>$TEST_GROUP_ID,
		"TEST_TYPE"=>$TEST_TYPE,
		//"CORRECT_ANSWER"=>$CORRECT_ANSWER,
		//"SCORES"=>$SCORES,
		"TEST_ID"=>$TEST_ID,
		"SHOW_COMMENTS"=>$SHOW_COMMENTS,
        "ALT"=>$ALT,
	);
	
	if ($arExtra["TEST_TYPE"]=="input")
	{
		$arFields["CORRECT_ANSWER"]=$CORRECT_ANSWER;
		$arFields["SCORES"]=$SCORES;
	}
	
	if($ShowXmlID=="Y")
		$arFields["XML_ID"]=$XML_ID;

	$el=new AelitaTestQuestion;
	if ($QID>0)
	{
		if (!$el->Update($QID, $arFields))
		{
			if ($ex = $APPLICATION->GetException())
				$errorMessage .= $ex->GetString().". ";
			else
				$errorMessage .= GetMessage("AT_ERROR_SAVING_EXTRA").". ";
		}
	}else{
		
		$QID = $el->Add($arFields);
		$QID = IntVal($QID);
		if ($QID <= 0)
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
			LocalRedirect($ListItem.LANG.'&TEST_ID='.$TEST_ID);
		else
			LocalRedirect($EditItem.LANG.'&TEST_ID='.$TEST_ID."&QID=".$QID."&tabControl_active_tab=".urlencode($tabControl_active_tab));
	}
	else
	{
		$bVarsFromForm = true;
	}
}

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

if ($TEST_ID>0)
{
	$txt = $arTest["TXT_NAME"];
	$link = $EditTest.LANGUAGE_ID."&ID=".$TEST_ID;
	$adminChain->AddItem(array("TEXT"=>$txt, "LINK"=>$link));
}
if ($QID > 0 && $arExtra["TEST_TYPE"]!="input")
{
	$sTableID = "b_aelita_test_answer";
	$oSort = new CAdminSorting($sTableID, "ID", "ASC");
	$lAdmin = new CAdminList($sTableID, $oSort);
	$arFilter=array("QUESTION_ID"=>$QID);
	if ($lAdmin->EditAction() && !$bReadOnly)
	{
		foreach ($FIELDS as $tID => $arFields)
		{
			$DB->StartTransaction();
			$tID = IntVal($tID);

			if (!$lAdmin->IsUpdated($tID))
				continue;
			$el=new AelitaTestAnswer();
			if (!$el->Update($tID, $arFields))
			{
				if ($ex = $APPLICATION->GetException())
					$lAdmin->AddUpdateError($ex->GetString(), $tID);
				else
					$lAdmin->AddUpdateError(GetMessage("AT_ERROR_UPDATE"), $tID);

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
			$el=new AelitaTestAnswer();
			$dbResultList = $el->GetList();
			while ($arResult = $dbResultList->Fetch())
				$arID[] = $arResult['ID'];
		}

		foreach ($arID as $tID)
		{
			if (strlen($tID) <= 0)
				continue;

			switch ($_REQUEST['action'])
			{
				case "delete":
					@set_time_limit(0);

					$DB->StartTransaction();
					$el=new AelitaTestAnswer();
					if (!$el->Delete($tID))
					{
						$DB->Rollback();

						if ($ex = $APPLICATION->GetException())
							$lAdmin->AddGroupError($ex->GetString(), $tID);
						else
							$lAdmin->AddGroupError(GetMessage("AT_ERROR_DELETE"), $tID);
					}

					$DB->Commit();

					break;
			}
		}
	}

	$el=new AelitaTestAnswer();
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
    if ($UseCorrect){
        $arHeaders[]=array("id"=>"CORRECT","content"=>GetMessage("AT_CORRECT_ANSWER"), "sort"=>"", "default"=>true);
    }else{
        $arHeaders[]=array("id"=>"SCORES","content"=>GetMessage("AT_SCORES"), "sort"=>"", "default"=>true);
    }

	$arHeaders[]=array("id"=>"NAME","content"=>GetMessage("AT_NAME"), "sort"=>"", "default"=>true);
	
	$lAdmin->AddHeaders($arHeaders);
	$arVisibleColumns = $lAdmin->GetVisibleHeaderColumns();

	while ($arExtraList = $dbResultList->NavNext(true, "fl_"))
	{
		$row =& $lAdmin->AddRow($fl_ID, $arExtraList);

		$row->AddField("ID", $fl_ID);

		if ($bReadOnly)
		{
			if($ShowXmlID=="Y")
				$row->AddViewField("XML_ID", $fl_XML_ID);
			$row->AddCheckField("ACTIVE", false);
			$row->AddViewField("SORT", $fl_SORT);
            if ($UseCorrect){
                $row->AddViewField("CORRECT", $fl_CORRECT);
            }else{
                $row->AddViewField("SCORES", $fl_SCORES);
            }
			$row->AddViewField("NAME", $fl_NAME);

		}
		else
		{
			if($ShowXmlID=="Y")
				$row->AddInputField("XML_ID", array("size" => "25"));
			$row->AddCheckField("ACTIVE");
			$row->AddInputField("SORT", array("size" => "25"));
            if ($UseCorrect){
                $row->AddCheckField("CORRECT");
            }else{
                $row->AddInputField("SCORES", array("size" => "25"));
            }
			$row->AddInputField("NAME", array("size" => "25"));

		}

		$arActions = Array();
		$arActions[] = array("ICON"=>"edit", "TEXT"=>GetMessage("AT_EDIT_GROUP"), "ACTION"=>$lAdmin->ActionRedirect($EditItemAnswer.LANG."&ID=".$fl_ID.'&TEST_ID='.$TEST_ID."&QUESTION_ID=".$QID), "DEFAULT"=>true);
		
		if (!$bReadOnly)
		{
			$arActions[] = array("SEPARATOR" => true);
			$arActions[] = array("ICON"=>"delete", "TEXT"=>GetMessage("AT_DEL_GROUP"), "ACTION"=>"if(confirm('".GetMessage('AT_DEL_GROUP_CONFIRM')."')) ".$lAdmin->ActionDoGroup("", "delete","TEST_ID=".$TEST_ID.'&ID[]='.$fl_ID));
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
				"LINK" => $EditItemAnswer.LANG.'&TEST_ID='.$TEST_ID."&QUESTION_ID=".$QID,
				"TITLE" => GetMessage("AT_NEW_GROUP")
			),
		);
		$lAdmin->AddAdminContextMenu($aContext);
	}
	
	$lAdmin->CheckListMode();
	
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



if ($QID > 0)
{
	$str_NAME = $arExtra["NAME"];
	if($ShowXmlID=="Y")
		$str_XML_ID = htmlspecialchars($arExtra["XML_ID"]);
	$str_ACTIVE = htmlspecialchars($arExtra["ACTIVE"]);
	$str_PICTURE= $arExtra["PICTURE"];
	$str_DESCRIPTION = $arExtra["~DESCRIPTION"];
	$str_DESCRIPTION_TYPE = $arExtra["DESCRIPTION_TYPE"];
	$str_SORT = $arExtra["SORT"];
	$str_TEST_GROUP_ID = $arExtra["TEST_GROUP_ID"];
	$str_TEST_TYPE = $arExtra["TEST_TYPE"];
	$str_SHOW_COMMENTS = $arExtra["SHOW_COMMENTS"];
    $str_ALT = $arExtra["ALT"];

	if ($arExtra["TEST_TYPE"]=="input")
	{
		$str_CORRECT_ANSWER = $arExtra["CORRECT_ANSWER"];
		$str_SCORES = $arExtra["SCORES"];
	}

}

if ($bVarsFromForm)
	$DB->InitTableVarsForEdit($TableModule, "", "str_");
	
$aMenu = array(
	array(
		"TEXT" => GetMessage("AT_LIST"),
		"LINK" => $ListItem.LANG.'&TEST_ID='.$TEST_ID,
		"ICON"=>"btn_list",
	)
);

if ($ID > 0 && !$bReadOnly )
{
	$aMenu[] = array(
			"TEXT" => GetMessage("AT_NEW_GROUP"),
			"ICON" => "btn_new",
			"LINK" => $EditItem.LANG.'&TEST_ID='.$TEST_ID
		);
	$aMenu[] = array(
			"TEXT" => GetMessage("AT_DEL_GROUP"), 
			"ICON" => "btn_delete",
			"LINK" => "javascript:if(confirm('".GetMessage("AT_DEL_GROUP_CONFIRM")."')) window.location='".$ListItem.LANG.'&TEST_ID='.$TEST_ID."&QID=".$QID."&action=delete&".bitrix_sessid_get()."#tb';",
			"WARNING" => "Y"
		);
}
	
$context = new CAdminContextMenu($aMenu);
$context->Show();
	
CAdminMessage::ShowMessage($errorMessage);
?>

<form method="POST" action="<?echo $APPLICATION->GetCurPage()?>?lang=<?echo LANG?><?if ($QID > 0):?>&QID=<?=$QID?><?endif;?>&TEST_ID=<?=$TEST_ID?>" name="form1" ENCTYPE="multipart/form-data">

<input type="hidden" name="Update" value="Y">
<input type="hidden" name="lang" value="<?echo LANG ?>">
<input type="hidden" name="QID" value="<?echo $QID ?>">
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

	<?if ($QID > 0):?>
		<tr>
			<td width="30%">ID:</td>
			<td width="70%"><?=$QID?></td>
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
	<tr>
		<td width="30%"><?echo GetMessage("AT_TEST_GROUP_ID")?>:</td>
		<td width="70%">
			<select name="TEST_GROUP_ID">
				<option value="0"><?echo GetMessage("AT_NON")?></option>
				<?foreach($Groups as $key=>$value){
					if($key>0){?>
					<option <?if($key==$str_TEST_GROUP_ID){?>selected="selected"<?};?> value="<?=$key?>">
						<?=$value?>
					</option>
					<?}
				}?>
			</select>
		</td>
	</tr>
	<tr>
		<td width="30%"><?echo GetMessage("AT_TEST_TYPE")?>:</td>
		<td width="70%">
			<select name="TEST_TYPE">
				<?foreach($test_type as $key=>$value){?>
					<option <?if($key==$str_TEST_TYPE){?>selected="selected"<?};?> value="<?=$key?>">
						<?=$value?>
					</option>

				<?}?>
			</select>
		</td>
	</tr>
	<?if ($arExtra["TEST_TYPE"]=="input"){?>
        <?if (!$UseCorrect){?>
            <tr>
                <td width="30%"><?echo GetMessage("AT_SCORES")?>:</td>
                <td width="70%">
                    <input type="text" name="SCORES" size="50" value="<?=$str_SCORES?>">
                </td>
            </tr>
        <?}?>
	<tr>
		<td width="30%"><?echo GetMessage("AT_CORRECT_ANSWER")?>:</td>
		<td width="70%">
			<input type="text" name="CORRECT_ANSWER" size="50" value="<?=$str_CORRECT_ANSWER?>">
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
		<td width="30%"><?echo GetMessage("AT_SHOW_COMMENTS")?>:</td>
		<td width="70%">
			<input type="checkbox" name="SHOW_COMMENTS" id="SHOW_COMMENTS" value="Y"<?if ($str_SHOW_COMMENTS == "Y") echo " checked";?>>
		</td>
	</tr>
	
	<tr class="heading">
		<td colspan="2"><?echo GetMessage("AT_DESCRIPTION")?></td>
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
<?
$tabControl->EndTab();
?>

<?
$tabControl->Buttons(
		array(
				"disabled" => $bReadOnly,
				"back_url" => $ListItem.LANG.'&TEST_ID='.$TEST_ID,
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


<?if($QID>0 && $arExtra["TEST_TYPE"]!="input"){?>
<h1><?=GetMessage("AT_LIST_TITLE");?></h1>
<a name="list_title"></a>
<?$lAdmin->DisplayList();
}?>
<?//require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".$module_id."/admin/aelita.test.answer.list.min.php");?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");?>
