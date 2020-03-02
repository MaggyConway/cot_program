<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
$module_id="aelita.test";

$ListItem="/bitrix/admin/aelita.test.test.list.php?lang=";
$EditItem="/bitrix/admin/aelita.test.test.edit.ex.php?lang=";
$TableModule="b_aelita_test_test";
$DateFormat="YYYY-MM-DD HH:MI:SS";
define('PROPERTY_EMPTY_ROW_SIZE',1);


IncludeModuleLangFile(__FILE__);

CUtil::InitJSCore(array('ajax','jquery'));

$POST_RIGHT = $APPLICATION->GetGroupRight($module_id);
if($POST_RIGHT=="D")
	$APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
if(!CModule::IncludeModule($module_id))die();
	

$ShowXmlID=COption::GetOptionString($module_id, "aelita_test_show_xml_id","N");
$HideDescription=COption::GetOptionString($module_id, "hide_picture_and_description_in_window","N");


	
ClearVars();
ClearVars("str_");

$errorMessage = "";
$bVarsFromForm = false;

$ID = IntVal($ID);

$arrUserGroups=array();
$rsGroups = CGroup::GetList(($by="ID"), ($order="ASC"));
while($Group=$rsGroups->GetNext()) 
	$arrUserGroups[]=$Group;

$test_result=array(
	"summ"=>GetMessage("AT_TEST_TYPE_SUMM"),
	"aver"=>GetMessage("AT_TEST_TYPE_AVER"),
	"suer"=>GetMessage("AT_TEST_TYPE_SUER"),
	);

$multiple_question=array(
    "none"=>GetMessage("AT_MULTIPLE_QUESTION_NONE"),
    "anum"=>GetMessage("AT_MULTIPLE_QUESTION_ANUM"),
    "gnum"=>GetMessage("AT_MULTIPLE_QUESTION_GNUM"),
    "allq"=>GetMessage("AT_MULTIPLE_QUESTION_ALLQ"),
    "clst"=>GetMessage("AT_MULTIPLE_QUESTION_CLST"),
);

if ($REQUEST_METHOD=="POST" && strlen($Update) > 0 && !$bReadOnly && check_bitrix_sessid())
{
	$arPICTURE = $HTTP_POST_FILES["PICTURE"];
	$arPICTURE["del"] = ${"PICTURE_del"};

    $arSPONSOR_PICTURE = $HTTP_POST_FILES["SPONSOR_PICTURE"];
    $arSPONSOR_PICTURE["del"] = ${"SPONSOR_PICTURE_del"};

	if (!$ID>0)
	{
		$DATE_FROM=ConvertDateTime($DATE_FROM, $DateFormat);
		$DATE_TO=ConvertDateTime($DATE_TO, $DateFormat);
	}
	$arFields = array(
		"NAME"=>$NAME,
		"CODE"=>$CODE,
		"ACTIVE"=>$ACTIVE,
		"PICTURE"=>$arPICTURE,
		"DESCRIPTION"=>$DESCRIPTION,
		"DESCRIPTION_TYPE"=>$DESCRIPTION_TYPE,
        "TO_TITLE"=>$TO_TITLE,
        "TO_TITLE_TYPE"=>$TO_TITLE_TYPE,
		"SORT"=>$SORT,
		"GROUP_ID"=>$GROUP_ID,
		"ACCESS_ALL"=>$ACCESS_ALL,
		"ACCESS_GROUP"=>$ACCESS_GROUP,
		"DATE_FROM"=>$DATE_FROM,
		"DATE_TO"=>$DATE_TO,
		"NUMBER_ATTEMPTS"=>$NUMBER_ATTEMPTS,
		"PERIOD_ATTEMPTS"=>$PERIOD_ATTEMPTS,
		"TEST_TIME"=>$TEST_TIME,
		"SHOW_ANSWERS"=>$SHOW_ANSWERS,
		"MIX_QUESTION"=>$MIX_QUESTION,
		"SHOW_COMMENTS"=>$SHOW_COMMENTS,
        "AUTO_START_OVER"=>$AUTO_START_OVER,
		"TYPE_RESULT"=>$TYPE_RESULT,
        "MULTIPLE_QUESTION"=>$MULTIPLE_QUESTION,
        "MULTIPLE_QUESTION_COUNT"=>$MULTIPLE_QUESTION_COUNT,
        "USE_CORRECT"=>$USE_CORRECT,
        "SPONSOR_NAME"=>$SPONSOR_NAME,
        "SPONSOR_PICTURE"=>$arSPONSOR_PICTURE,
        "SPONSOR_LINK"=>$SPONSOR_LINK,
        "SPONSOR_DESCRIPTION"=>$SPONSOR_DESCRIPTION,
        "SPONSOR_DESCRIPTION_TYPE"=>$SPONSOR_DESCRIPTION_TYPE,
        "ALT"=>$ALT,
        "SPONSOR_ALT"=>$SPONSOR_ALT,
        "COUNT_USER_AUTOR"=>$COUNT_USER_AUTOR,

	);
	
	if($ShowXmlID=="Y")
		$arFields["XML_ID"]=$XML_ID;

	$el=new AelitaTestTest;
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
	
	
	$arrACCESS_GROUPS=array();
	
	if($ID > 0)
	{
		$el=new AelitaTestAccessTest();
		$props = $el->GetList(array(), array("TEST_ID" => $ID));
		while($p = $props->GetNext())
		{
			$k=array_search($p["USER_GROUP_ID"], $ACCESS_GROUPS);
			if($k!==false)
			{
				$arrACCESS_GROUPS[$p["ID"]] = $prop;
				unset($ACCESS_GROUPS[$k]);
			}else{
				$arrACCESS_GROUPS[$p["ID"]] = array("DEL"=>"Y","ID"=>$p["ID"]);
			}
		}
	}
	
	$i=0;
	foreach($ACCESS_GROUPS as $k)
	{
		if(!$arrACCESS_GROUPS[$k])
		{
			$arProperty=array(
				"USER_GROUP_ID"=>$k,
				);
			$arrACCESS_GROUPS["n".$i] = $arProperty;
		}
		$i++;
	}
	
	if($ID>0)
	{
		foreach($arrACCESS_GROUPS as &$Properties)
		{
			$Properties["TEST_ID"]=(int)$Properties["TEST_ID"];
			if($Properties["TEST_ID"]<=0)
				$Properties["TEST_ID"]=$ID;
			$el=new AelitaTestAccessTest();
			if($Properties["ID"]>0)
			{
				if($Properties["DEL"]=="Y")
					$el->Delete($Properties["ID"]);
				else
					$el->Update($Properties["ID"],$Properties);
			}elseif($Properties["USER_GROUP_ID"]>0){
				$Properties["ID"]=$el->add($Properties);
			}
		}unset($Properties);
	}
	
	if($ID>0 && $count_user_id>0)
	{
		$USER_ID=array();
		for($i=0;$i<$count_user_id;$i++)
		{
			$UID=(int)${"USER_ID_".$i};
			if($UID>0 && !in_array($UID,$USER_ID))
				$USER_ID[]=$UID;
		}
		
		$elr=new AelitaTestResponsible();
		$props = $elr->GetList(array(), array("TEST_ID" => $ID));
		while($p = $props->GetNext())
		{
			$k=array_search($p["USER_ID"], $USER_ID);
			if($k!==false)
				unset($USER_ID[$k]);
			else
				$elr->Delete($p["ID"]);
		}
		
		foreach($USER_ID as $k)
		{
			$Properties=array(
				"TEST_ID"=>$ID,
				"USER_ID"=>$k,
			);
			$elr->add($Properties);
		}

	}
	
	

	if (strlen($errorMessage)<=0)
	{
		if(strlen($apply)<=0)
			LocalRedirect($ListItem.LANG);
		else
			LocalRedirect($EditItem.LANG."&ID=".$ID."&tabControl_active_tab=".urlencode($tabControl_active_tab));
	}
	else
	{
		$bVarsFromForm = true;
	}
}


if ($ID > 0)
{
	$arExtra = AelitaTestEditToolEx::GetByID_admin($ID, 'form');
	if (!$arExtra)
	{
		if ($bReadOnly)
			$errorMessage .= GetMessage("CEEN_NO_PERMS2ADD").". ";
		$ID = 0;
	}else{
		
		$str_NAME = $arExtra["NAME"];
		$str_CODE=$arExtra["CODE"];
		if($ShowXmlID=="Y")
			$str_XML_ID = $arExtra["XML_ID"];
		$str_ACTIVE = $arExtra["ACTIVE"];
		$str_PICTURE= $arExtra["PICTURE"];
		$str_DESCRIPTION = $arExtra["DESCRIPTION"];
		$str_DESCRIPTION_TYPE = $arExtra["DESCRIPTION_TYPE"];
        $str_TO_TITLE = $arExtra["TO_TITLE"];
        $str_TO_TITLE_TYPE = $arExtra["TO_TITLE_TYPE"];
		$str_SORT = $arExtra["SORT"];
		$str_GROUP_ID = $arExtra["GROUP_ID"];
		
		$str_SHOW_ANSWERS = $arExtra["SHOW_ANSWERS"];
		
		$str_ACCESS_ALL = $arExtra["ACCESS_ALL"];
		$str_ACCESS_GROUP = $arExtra["ACCESS_GROUP"];
		
		$str_DATE_FROM = $arExtra["DATE_FROM"];
		$str_DATE_TO = $arExtra["DATE_TO"];
		
		$str_DATE_FROM = $DB->FormatDate($str_DATE_FROM, $DateFormat, CSite::GetDateFormat("FULL"));
		$str_DATE_TO = $DB->FormatDate($str_DATE_TO, $DateFormat, CSite::GetDateFormat("FULL"));
		
		$str_NUMBER_ATTEMPTS = $arExtra["NUMBER_ATTEMPTS"];
		$str_PERIOD_ATTEMPTS = $arExtra["PERIOD_ATTEMPTS"];
		$str_TEST_TIME = $arExtra["TEST_TIME"];
		
		$str_MIX_QUESTION = $arExtra["MIX_QUESTION"];
		
		$str_TYPE_RESULT = $arExtra["TYPE_RESULT"];
        $str_MULTIPLE_QUESTION = $arExtra["MULTIPLE_QUESTION"];
        $str_MULTIPLE_QUESTION_COUNT = $arExtra["MULTIPLE_QUESTION_COUNT"];

		$str_SHOW_COMMENTS = $arExtra["SHOW_COMMENTS"];
        $str_AUTO_START_OVER = $arExtra["AUTO_START_OVER"];

        $str_USE_CORRECT = $arExtra["USE_CORRECT"];

        $str_SPONSOR_NAME = $arExtra["SPONSOR_NAME"];
        $str_SPONSOR_PICTURE = $arExtra["SPONSOR_PICTURE"];
        $str_SPONSOR_LINK = $arExtra["SPONSOR_LINK"];
        $str_SPONSOR_DESCRIPTION = $arExtra["SPONSOR_DESCRIPTION"];
        $str_SPONSOR_DESCRIPTION_TYPE = $arExtra["SPONSOR_DESCRIPTION_TYPE"];

        $str_COUNT_USER_AUTOR=$arExtra["COUNT_USER_AUTOR"];
		
		$str_ACCESS_GROUPS=array();

  		$el=new AelitaTestAccessTest();
		$props = $el->GetList(array(), array("TEST_ID" => $ID));
		while($p = $props->GetNext())
			$str_ACCESS_GROUPS[]=$p["USER_GROUP_ID"];

        $str_ALT = $arExtra["ALT"];
        $str_SPONSOR_ALT = $arExtra["SPONSOR_ALT"];
			
		$USER_ID=array();
		$elr=new AelitaTestResponsible();
		$props = $elr->GetList(array(), array("TEST_ID" => $ID));
		while($p = $props->GetNext())
		{
			$p["USER_NAME"]="";
			if($p["USER_ID"]>0)
			{
				$rsUser = CUser::GetByID($p["USER_ID"]);
				if($arUser=$rsUser->Fetch()){
					$arrName=array();
					$arrName[]="[".$arUser["ID"]."]";
					$arrName[]="(".$arUser["LOGIN"].")";
					$arrName[]=$arUser["NAME"];
					$arrName[]=$arUser["LAST_NAME"];
					//$p["USER_NAME"]=implode(" ",$arrName);
				}
			}
			$USER_ID[]=$p;
		}
		for($i=0;$i<4;$i++)
			$USER_ID[]=array("USER_NAME"=>"","USER_ID"=>"");
	}
}else{
    if($USE_CORRECT=="Y")
        $str_USE_CORRECT="Y";
}

if ($bVarsFromForm)
	$DB->InitTableVarsForEdit($TableModule, "", "str_");

if ($ID > 0)
{
	$txt = $arExtra["TXT_NAME"];
	$link = $EditItem.LANGUAGE_ID."&ID=".$ID;
	$adminChain->AddItem(array("TEXT"=>$txt, "LINK"=>$link));
}

if ($ID>0)
	$APPLICATION->SetTitle(GetMessage("TITLE").': #'.$arExtra["ID"].' '.htmlspecialcharsbx($arExtra["NAME"]));
else
	$APPLICATION->SetTitle(GetMessage("TITLE"));

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
	
	
if ($ID>0)
{
	$context = new CAdminContextMenuList($arExtra['ADMIN_MENU']);
	$context->Show();
	echo BeginNote('width="100%"');
?>
	<b><?=GetMessage("TITLE")?>:</b>
	[<a title='<?=GetMessage("FORM_EDIT_TEST")?>' href='<?=$EditItem?><?=LANGUAGE_ID?>&ID=<?=$ID?>'><?=$ID?></a>]&nbsp;<?=$arExtra["TXT_NAME"]?>
<?
	echo EndNote();
}else{
	echo BeginNote('width="100%"');
	echo GetMessage("NO_TEST");
	echo EndNote();
}
	
	
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
			"TEXT" => GetMessage("AT_NEW_GROUP"),
			"ICON" => "btn_new",
			"LINK" => $EditItem.LANG 
		);

	$aMenu[] = array(
			"TEXT" => GetMessage("AT_DEL_GROUP"), 
			"ICON" => "btn_delete",
			"LINK" => "javascript:if(confirm('".GetMessage("AT_DEL_GROUP_CONFIRM")."')) window.location='".$ListItem.LANG."&ID=".$ID."&action=delete&".bitrix_sessid_get()."#tb';",
			"WARNING" => "Y"
		);
}

$Groups=array(
	0=>GetMessage("AT_NON"),
	);
$ListGroups = new AelitaTestGroup();
$dbResultList = $ListGroups->GetList(array("SORT"=>"ASC"),false,false,false,array("ID","NAME"));
while ($Group = $dbResultList->Fetch())
	$Groups[$Group['ID']] = $Group['NAME'];
	
$context = new CAdminContextMenu($aMenu);
$context->Show();
	
CAdminMessage::ShowMessage($errorMessage);
?>

<script>
$(document).ready(function(){
	$('#ACCESS_ALL').live("change",function(){
		CheckAccessGroup();
	});
	$('#ACCESS_GROUP').live("change",function(){
		CheckAccessGroup();
	});
	function CheckAccessGroup()
	{
		var AccessAll=$("#ACCESS_ALL:checked").length;
		var AccessGroup=$("#ACCESS_GROUP:checked").length;
		if(AccessAll>0 || AccessGroup>0)
			$(".ACCESS_GROUPS").prop("disabled",true);
		else
			$(".ACCESS_GROUPS").prop("disabled",false);
		if(AccessGroup>0)
			$("#ACCESS_ALL").prop("disabled",true);
		else
			$("#ACCESS_ALL").prop("disabled",false);
			
	}
	CheckAccessGroup();
});
</script>

<form method="POST" action="<?echo $APPLICATION->GetCurPage()?>?lang=<?echo LANG?><?if ($ID > 0):?>&ID=<?=$ID?><?endif;?>" name="frmtest" ENCTYPE="multipart/form-data" id="frmtest">

<input type="hidden" name="Update" value="Y">

<input type="hidden" name="lang" value="<?echo LANG ?>">
<input type="hidden" name="ID" value="<?echo $ID ?>">
<?=bitrix_sessid_post()?>
<?
$aTabs = array(
		array("DIV" => "edit1", "TAB" => GetMessage("AT_TAB_GROUP"), "ICON" => "catalog", "TITLE" => GetMessage("AT_TAB_GROUP_DESCR")),
		array("DIV" => "edit4", "TAB" => GetMessage("AT_TAB_ACCESS"), "ICON" => "catalog", "TITLE" => GetMessage("AT_TAB_ACCESS_DESCR")),
		array("DIV" => "edit5", "TAB" => GetMessage("AT_TAB_RESPONSIBLE"), "ICON" => "catalog", "TITLE" => GetMessage("AT_TAB_RESPONSIBLE_DESCR")),
        array("DIV" => "edit6", "TAB" => GetMessage("AT_TEST_SPONSOR"), "ICON" => "catalog", "TITLE" => GetMessage("AT_TEST_SPONSOR")),
	);

$tabControl = new CAdminTabControl("tabControl", $aTabs);
$tabControl->Begin();
?>

<?$tabControl->BeginNextTab();?>

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

    <tr>
        <td width="30%"><?echo GetMessage("AT_TEST_TYPE")?>:</td>
        <td width="70%">
            <input type="hidden" name="USE_CORRECT" id="USE_CORRECT" value="<?=$str_USE_CORRECT?>">
            <?
            if ($str_USE_CORRECT == "Y"){
                echo GetMessage("AT_TEST_USE_CORRECT_Y");
            }else{
                echo GetMessage("AT_TEST_USE_CORRECT_N");
            }
            ?>
        </td>
    </tr>

	<tr>
		<td width="30%"><?echo GetMessage("DATE_FROM")?>:</td>
		<td width="70%">
			<input type="text" name="DATE_FROM" size="20" value="<?=$str_DATE_FROM?>">
			<?$GLOBALS["APPLICATION"]->IncludeComponent(
				'bitrix:main.calendar',
				'',
				array(
					'FORM_NAME' => "frmtest",
					'INPUT_NAME' => "DATE_FROM",
					'INPUT_VALUE' => $str_DATE_FROM,
					'SHOW_TIME' => "Y",
				),
				null,
				array('HIDE_ICONS' => 'Y')
			);?>
		</td>
	</tr>
	<tr>
		<td width="30%"><?echo GetMessage("DATE_TO")?>:</td>
		<td width="70%">
			<input type="text" name="DATE_TO" size="20" value="<?=$str_DATE_TO?>">
			<?$GLOBALS["APPLICATION"]->IncludeComponent(
				'bitrix:main.calendar',
				'',
				array(
					'FORM_NAME' => "frmtest",
					'INPUT_NAME' => "DATE_TO",
					'INPUT_VALUE' => $str_DATE_TO,
					'SHOW_TIME' => "Y",
				),
				null,
				array('HIDE_ICONS' => 'Y')
			);?>
		</td>
	</tr>
	<tr>
		<td width="30%"><?echo GetMessage("AT_NUMBER_ATTEMPTS")?>:</td>
		<td width="70%">
			<input type="text" name="NUMBER_ATTEMPTS" size="50" value="<?=$str_NUMBER_ATTEMPTS?>">
		</td>
	</tr>
	<tr>
		<td width="30%"><?echo GetMessage("AT_PERIOD_ATTEMPTS")?>:</td>
		<td width="70%">
			<input type="text" name="PERIOD_ATTEMPTS" size="50" value="<?=$str_PERIOD_ATTEMPTS?>">
		</td>
	</tr>
	<tr>
		<td width="30%"><?echo GetMessage("AT_TEST_TIME")?>:</td>
		<td width="70%">
			<input type="text" name="TEST_TIME" size="50" value="<?=$str_TEST_TIME?>">
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
		<td width="30%"><?echo GetMessage("AT_CODE")?>:</td>
		<td width="70%">
			<input type="text" name="CODE" size="50" value="<?=$str_CODE?>">
		</td>
	</tr>
	<tr>
		<td width="30%"><?echo GetMessage("AT_SORT")?>:</td>
		<td width="70%">
			<input type="text" name="SORT" size="50" value="<?=$str_SORT?>">
		</td>
	</tr>
	
	<tr>
		<td width="30%"><?echo GetMessage("AT_GROUP_ID")?>:</td>
		<td width="70%">
			<select name="GROUP_ID">
			<?foreach($Groups as $key=>$value){?>
				<option <?if ($key==$str_GROUP_ID){?>selected="selected"<?};?> value="<?=$key?>">
					<?=$value?>
				</option>
			<?}?>
			</select>
		</td>
	</tr>
	
	<tr>
		<td width="30%"><?echo GetMessage("AT_SHOW_ANSWERS")?>:</td>
		<td width="70%">
			<input type="checkbox" name="SHOW_ANSWERS" id="SHOW_ANSWERS" value="Y"<?if ($str_SHOW_ANSWERS == "Y") echo " checked";?>>
		</td>
	</tr>
	
	<tr>
		<td width="30%"><?echo GetMessage("AT_MIX_QUESTION")?>:</td>
		<td width="70%">
			<input type="checkbox" name="MIX_QUESTION" id="MIX_QUESTION" value="Y"<?if ($str_MIX_QUESTION == "Y") echo " checked";?>>
		</td>
	</tr>
	
	<tr>
		<td width="30%"><?echo GetMessage("AT_SHOW_COMMENTS")?>:</td>
		<td width="70%">
			<input type="checkbox" name="SHOW_COMMENTS" id="SHOW_COMMENTS" value="Y"<?if ($str_SHOW_COMMENTS == "Y") echo " checked";?>>
		</td>
	</tr>

    <tr>
        <td width="30%"><?echo GetMessage("AT_AUTO_START_OVER")?>:</td>
        <td width="70%">
            <input type="checkbox" name="AUTO_START_OVER" id="AUTO_START_OVER" value="Y"<?if ($str_AUTO_START_OVER == "Y") echo " checked";?>>
        </td>
    </tr>


    <?//COption::SetOptionString("aelita.test","aelita_test_ex_type_result","Y");?>
	
	<?$ex_type_result = COption::GetOptionString($module_id, "aelita_test_ex_type_result","N");?>
	<?if($ex_type_result=="Y"){?>
	<tr>
		<td width="30%"><?echo GetMessage("AT_TYPE_RESULT")?>:</td>
		<td width="70%">
			<select name="TYPE_RESULT">
				<?foreach($test_result as $key=>$value){?>
					<option <?if($key==$str_TYPE_RESULT){?>selected="selected"<?};?> value="<?=$key?>">
						<?=$value?>
					</option>

				<?}?>
			</select>
		</td>
	</tr>
	<?}?>

    <tr>
        <td width="30%"><?echo GetMessage("AT_MULTIPLE_QUESTION")?>:</td>
        <td width="70%">
            <select name="MULTIPLE_QUESTION">
                <?foreach($multiple_question as $key=>$value){?>
                    <option <?if($key==$str_MULTIPLE_QUESTION){?>selected="selected"<?};?> value="<?=$key?>">
                        <?=$value?>
                    </option>
                <?}?>
            </select>
        </td>
    </tr>

    <?if($str_MULTIPLE_QUESTION=="anum"){?>
        <tr>
            <td width="30%"><?echo GetMessage("AT_MULTIPLE_QUESTION_COUNT")?>:</td>
            <td width="70%">
                <input type="text" name="MULTIPLE_QUESTION_COUNT" size="50" value="<?=$str_MULTIPLE_QUESTION_COUNT?>">
            </td>
        </tr>
    <?}?>


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
        <tr class="heading">
            <td colspan="2"><?echo GetMessage("AT_DESCRIPTION_HELLO")?></td>
        </tr>
		<tr>
			<td colspan="2" align="center">
				<?CFileMan::AddHTMLEditorFrame("DESCRIPTION", $str_DESCRIPTION, "DESCRIPTION_TYPE", $str_DESCRIPTION_TYPE, 250);?>
			</td>
		</tr>
        <tr class="heading">
            <td colspan="2"><?echo GetMessage("AT_TO_TITLE")?></td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                <?CFileMan::AddHTMLEditorFrame("TO_TITLE", $str_TO_TITLE, "TO_TITLE_TYPE", $str_TO_TITLE_TYPE, 250);?>
            </td>
        </tr>
	<?else:?>
        <tr class="heading">
            <td colspan="2"><?echo GetMessage("AT_DESCRIPTION_HELLO")?></td>
        </tr>
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
        <tr class="heading">
            <td colspan="2"><?echo GetMessage("AT_TO_TITLE")?></td>
        </tr>
        <tr>
            <td ><?echo GetMessage("AT_TO_TITLE_TYPE")?></td>
            <td >
                <input type="radio" name="TO_TITLE_TYPE" id="TO_TITLE_TYPE1" value="text"<?if($str_TO_TITLE_TYPE!="html")echo " checked"?>><label for="TO_TITLE_TYPE1"> <?echo GetMessage("AT_DESCRIPTION_TYPE_TEXT")?></label> /
                <input type="radio" name="TO_TITLE_TYPE" id="TO_TITLE_TYPE2" value="html"<?if($str_TO_TITLE_TYPE=="html")echo " checked"?>><label for="TO_TITLE_TYPE2"> <?echo GetMessage("AT_DESCRIPTION_TYPE_HTML")?></label>
            </td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                <textarea cols="60" rows="15" name="TO_TITLE" style="width:100%;"><?echo $str_TO_TITLE?></textarea>
            </td>
        </tr>
	<?endif?>

<?
$tabControl->BeginNextTab();
?>
	<tr>
		<td width="30%"><?echo GetMessage("AT_ACCESS_GROUP")?>:</td>
		<td width="70%">
			<input type="checkbox" name="ACCESS_GROUP" id="ACCESS_GROUP" value="Y"<?if ($str_ACCESS_GROUP == "Y" || $ID<=0) echo " checked";?>>
		</td>
	</tr>
	<tr>
		<td width="30%"><?echo GetMessage("AT_ACCESS_ALL")?>:</td>
		<td width="70%">
			<input type="checkbox" name="ACCESS_ALL" id="ACCESS_ALL" value="Y"<?if ($str_ACCESS_ALL == "Y" || $ID<=0) echo " checked";?>>
		</td>
	</tr>
	<tr>
		<td width="30%"><?echo GetMessage("AT_ACCESS_GROUPS")?>:</td>
		<td width="70%">
			<?foreach($arrUserGroups as $Groups){?>
				<input type="checkbox" name="ACCESS_GROUPS[]" class="ACCESS_GROUPS" id="ACCESS_GROUPS_<?=$Groups["ID"]?>" value="<?=$Groups["ID"]?>"<?if ($str_ACCESS_GROUPS && is_array($str_ACCESS_GROUPS) && in_array($Groups["ID"],$str_ACCESS_GROUPS)) echo " checked";?>> <?=$Groups["NAME"]?><br /><br />
			<?}?>
		</td>
	</tr>
<?
$tabControl->BeginNextTab();
?>

    <tr>
        <td width="30%"><?echo GetMessage("AT_COUNT_USER_AUTOR")?>:</td>
        <td width="70%">
            <input type="checkbox" name="COUNT_USER_AUTOR" id="COUNT_USER_AUTOR" value="Y"<?if ($str_COUNT_USER_AUTOR == "Y") echo " checked";?>>
        </td>
    </tr>
	
	<tr>
		<td width="30%" valign="top"><?echo GetMessage("AT_TAB_RESPONSIBLE")?>:</td>
		<td width="70%">
		<?for($i=0;$i<count($USER_ID);$i++){
			echo FindUserID("USER_ID_".$i,$USER_ID[$i]["USER_ID"], $USER_ID[$i]["USER_NAME"],"frmtest", "3", "", "...", "inputtext", "inputbodybutton");
			echo "<br /><br />";
		}?>
		<input type="hidden" name="count_user_id" value="<?=$i?>" />
		
		<?echo BeginNote(), GetMessage("ADD_RESPONSIBLE"), EndNote()?>
		
		</td>
	</tr>


    <?
    $tabControl->BeginNextTab();
    ?>


    <tr>
        <td width="30%"><?echo GetMessage("AT_SPONSOR_NAME")?>:</td>
        <td width="70%">
            <input type="text" name="SPONSOR_NAME" size="50" value="<?=$str_SPONSOR_NAME?>">
        </td>
    </tr>
    <tr>
        <td class="adm-detail-valign-top"><?echo GetMessage("AT_SPONSOR_PICTURE")?></td>
        <td>
            <?echo CFileInput::Show('SPONSOR_PICTURE', $str_SPONSOR_PICTURE, array(
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
                    'description' => array("NAME"=>"SPONSOR_ALT","VALUE"=>$str_SPONSOR_ALT),
                )
            );?>
        </td>
    </tr>
    <tr>
        <td width="30%"><?echo GetMessage("AT_SPONSOR_LINK")?>:</td>
        <td width="70%">
            <input type="text" name="SPONSOR_LINK" size="50" value="<?=$str_SPONSOR_LINK?>">
        </td>
    </tr>
    <?if(CModule::IncludeModule("fileman")):?>
        <tr class="heading">
            <td colspan="2"><?echo GetMessage("AT_SPONSOR_DESCRIPTION")?></td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                <?CFileMan::AddHTMLEditorFrame("SPONSOR_DESCRIPTION", $str_SPONSOR_DESCRIPTION, "SPONSOR_DESCRIPTION_TYPE", $str_SPONSOR_DESCRIPTION_TYPE, 250);?>
            </td>
        </tr>
    <?else:?>
        <tr class="heading">
            <td colspan="2"><?echo GetMessage("AT_SPONSOR_DESCRIPTION")?></td>
        </tr>
        <tr>
            <td ><?echo GetMessage("AT_SPONSOR_DESCRIPTION_TYPE")?></td>
            <td >
                <input type="radio" name="SPONSOR_DESCRIPTION_TYPE" id="SPONSOR_DESCRIPTION_TYPE1" value="text"<?if($str_SPONSOR_DESCRIPTION_TYPE!="html")echo " checked"?>><label for="SPONSOR_DESCRIPTION_TYPE1"> <?echo GetMessage("AT_DESCRIPTION_TYPE_TEXT")?></label> /
                <input type="radio" name="SPONSOR_DESCRIPTION_TYPE" id="SPONSOR_DESCRIPTION_TYPE2" value="html"<?if($str_SPONSOR_DESCRIPTION_TYPE=="html")echo " checked"?>><label for="SPONSOR_DESCRIPTION_TYPE2"> <?echo GetMessage("AT_DESCRIPTION_TYPE_HTML")?></label>
            </td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                <textarea cols="60" rows="15" name="SPONSOR_DESCRIPTION" style="width:100%;"><?echo $str_SPONSOR_DESCRIPTION?></textarea>
            </td>
        </tr>
    <?endif?>



<?$tabControl->EndTab();?>

<?
$tabControl->Buttons(
		array(
				"disabled" => $bReadOnly,
				"back_url" => $ListItem.LANG,
			)
	);
?>

<?$tabControl->End();?>


</form>

<?echo BeginNote();?>
<span class="required">*</span> <?echo GetMessage("REQUIRED_FIELDS")?>
<?echo EndNote(); ?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");?>
