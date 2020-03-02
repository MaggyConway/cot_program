<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
$module_id="aelita.test";

$ListItem="/bitrix/admin/aelita.test.test.list.php?lang=";
$EditItem="/bitrix/admin/aelita.test.test.edit.php?lang=";
$TableModule="b_aelita_test_test";
$DateFormat="YYYY-MM-DD HH:MI:SS";
define('PROPERTY_EMPTY_ROW_SIZE',1);

$strPREFIX_Result = 'AT_RESULT_';
$strQUESTION_Result = 'AT_QUESTION_';

IncludeModuleLangFile(__FILE__);

$APPLICATION->AddHeadScript('/bitrix/js/'.$module_id.'/result_edit.js');
//$APPLICATION->AddHeadScript('/bitrix/js/'.$module_id.'/Base64.js');

CUtil::InitJSCore(array('ajax','popup','jquery'));

$POST_RIGHT = $APPLICATION->GetGroupRight($module_id);
if($POST_RIGHT=="D")
	$APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
if(!CModule::IncludeModule($module_id))die();
	
AelitaTestEditTool::prepareSerializedData();

$ShowXmlID=COption::GetOptionString($module_id, "aelita_test_show_xml_id","N");
$HideDescription=COption::GetOptionString($module_id, "hide_picture_and_description_in_window","N");

$UseCorrect=COption::GetOptionString($module_id, "aelita_test_use_correct","N");

$arCellTemplates=AelitaTestEditTool::InitCUtilResult();
$arCellAttr = array();
	
$arCellTemplatesQuestion=AelitaTestEditTool::InitCUtilQuestion();
$arCellAttrQuestion = array();
	
ClearVars();
ClearVars("str_");

$errorMessage = "";
$bVarsFromForm = false;

$ID = IntVal($ID);

$arrUserGroups=array();
$rsGroups = CGroup::GetList(($by="ID"), ($order="ASC"));
while($Group=$rsGroups->GetNext()) 
	$arrUserGroups[]=$Group;
	
if ($REQUEST_METHOD=="POST" && strlen($Update) > 0 && !$bReadOnly && check_bitrix_sessid())
{
	$arPICTURE = $HTTP_POST_FILES["PICTURE"];
	$arPICTURE["del"] = ${"PICTURE_del"};
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
		"SHOW_ANSWERS"=>$SHOW_ANSWERS,
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
	
	//$ibp = new AelitaTestResult();
	$arProperties = array();
	
	$arQuestion = array();

	if($ID > 0)
	{
		$el=new AelitaTestResult();
		$props = $el->GetList(array(), array("TEST_ID" => $ID));
		while($p = $props->GetNext())
		{
			$prop = AelitaTestEditTool::GetPropertyInfo($strPREFIX_Result, $p['ID'], true, AelitaTestEditTool::GetHiddenResult());
			if($prop["ID"]>0)
				$arProperties[$p["ID"]] = $prop;
			else
				$arProperties[$p["ID"]] = array("DEL"=>"Y","ID"=>$p["ID"]);
		}
		
		$el=new AelitaTestQuestion();
		$props = $el->GetList(array(), array("TEST_ID" => $ID));
		while($p = $props->GetNext())
		{
			$prop = AelitaTestEditTool::GetPropertyInfoQuestion($strQUESTION_Result, $p['ID'], true, AelitaTestEditTool::GetHiddenQuestion());
			
			if($prop["ID"]>0)
			{

				
				$arAnswer = array();
				$strPREFIX_arAnswer=$strQUESTION_Result.$p["ID"]."_";
				$elAnswer=new AelitaTestAnswer();
				$resAnswer = $elAnswer->GetList(array(), array("QUESTION_ID" => $prop["ID"]));
				while($Answer = $resAnswer->GetNext())
				{
					$propAnswer = AelitaTestEditTool::GetPropertyInfoAnswer($strPREFIX_arAnswer, $Answer['ID'], true, AelitaTestEditTool::GetHiddenAnswer());
					if($propAnswer["ID"]>0)
						$arAnswer[$Answer["ID"]] = $propAnswer;
					else
						$arAnswer[$Answer["ID"]] = array("DEL"=>"Y","ID"=>$Answer["ID"]);
				}
				
				$intAnswerCount = intval($_POST[$strPREFIX_arAnswer.'ANSWER_COUNT']);
				for($i=0; $i<$intAnswerCount; $i++)
				{
					$Answer = AelitaTestEditTool::GetPropertyInfoQuestion($strPREFIX_arAnswer, 'n'.$i, true,AelitaTestEditTool::GetHiddenAnswer());
					if (false == is_array($Answer))
						continue;
					$arAnswer["n".$i] = $Answer;
				}
	
				$prop["ANSWER"]=$arAnswer;
				$arQuestion[$p["ID"]] = $prop;
			}else{
				$arQuestion[$p["ID"]] = array("DEL"=>"Y","ID"=>$p["ID"]);
			}

		}
		
	}

	$intPropCount = intval($_POST['TEST_RESULT_COUNT']);
	for($i=0; $i<$intPropCount; $i++)
	{
		$arProperty = AelitaTestEditTool::GetPropertyInfo($strPREFIX_Result, 'n'.$i, true,AelitaTestEditTool::GetHiddenResult());
		if (false == is_array($arProperty))
			continue;
			
		$arProperties["n".$i] = $arProperty;
	}
	
	$intQuestionCount = intval($_POST['TEST_QUESTION_COUNT']);
	for($i=0; $i<$intQuestionCount; $i++)
	{
		$Question = AelitaTestEditTool::GetPropertyInfoQuestion($strQUESTION_Result, 'n'.$i, true,AelitaTestEditTool::GetHiddenQuestion());
		if (false == is_array($Question))
			continue;
			
		$arAnswer = array();
		$strPREFIX_arAnswer=$strQUESTION_Result."n".$i."_";
		$intAnswerCount = intval($_POST[$strPREFIX_arAnswer.'ANSWER_COUNT']);
		for($j=0; $j<$intAnswerCount; $j++)
		{
			$Answer = AelitaTestEditTool::GetPropertyInfoAnswer($strPREFIX_arAnswer, 'n'.$j, true,AelitaTestEditTool::GetHiddenAnswer());
			if (false == is_array($Answer))
				continue;
			$arAnswer["n".$j] = $Answer;
		}
		$Question["ANSWER"]=$arAnswer;
		 
		$arQuestion["n".$i] = $Question;
	}

	if($ID>0)
	{
		foreach($arProperties as &$Properties)
		{
			$Properties["TEST_ID"]=(int)$Properties["TEST_ID"];
			if($Properties["TEST_ID"]<=0)
				$Properties["TEST_ID"]=$ID;
			$el=new AelitaTestResult();
			if($Properties["ID"]>0)
			{
				if($Properties["DEL"]=="Y")
					$el->Delete($Properties["ID"]);
				else
					$el->Update($Properties["ID"],$Properties);
			}else{
				$Properties["ID"]=$el->add($Properties);
			}
		}unset($Properties);
		
		foreach($arQuestion as &$Question)
		{
			$Question["TEST_ID"]=(int)$Question["TEST_ID"];
			$arrAnswer="";
			$arrAnswer=$Question["ANSWER"];
			unset($Question["ANSWER"]);
			if($Question["TEST_ID"]<=0)
				$Question["TEST_ID"]=$ID;
			$el=new AelitaTestQuestion();
			if($Question["ID"]>0)
			{
				if($Question["DEL"]=="Y")
					$el->Delete($Question["ID"]);
				else
					$el->Update($Question["ID"],$Question);
			}else{
				$Question["ID"]=$el->add($Question);
			}
			if($Question["ID"]>0 && $Question["DEL"]!="Y" && is_array($arrAnswer))
			{
				foreach($arrAnswer as &$Answer)
				{
					$Answer["QUESTION_ID"]=(int)$Answer["QUESTION_ID"];
					if($Answer["QUESTION_ID"]<=0)
						$Answer["QUESTION_ID"]=$Question["ID"];
					$elAnswer=new AelitaTestAnswer();
					if($Answer["ID"]>0)
					{
						if($Answer["DEL"]=="Y")
							$elAnswer->Delete($Answer["ID"]);
						else
							$elAnswer->Update($Answer["ID"],$Answer);
					}else{
						$Answer["ID"]=$elAnswer->add($Answer);
					}
				}unset($Answer);
			}
		}unset($Question);
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

$APPLICATION->SetTitle(GetMessage("TITLE"));
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
if ($ID > 0)
{
	$el =new AelitaTestTest;
	$arExtra=$el->GetByID($ID);
	if (!$arExtra=$arExtra->GetNext())
	{
		if ($bReadOnly)
			$errorMessage .= GetMessage("CEEN_NO_PERMS2ADD").". ";
		$ID = 0;
	}
	else
	{

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
		
		$str_ACCESS_GROUPS=array();

		$el=new AelitaTestAccessTest();
		$props = $el->GetList(array(), array("TEST_ID" => $ID));
		while($p = $props->GetNext())
			$str_ACCESS_GROUPS[]=$p["USER_GROUP_ID"];
	}
}

if ($bVarsFromForm)
	$DB->InitTableVarsForEdit($TableModule, "", "str_");
	
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
<script type="text/javascript">
var CellTPL = new Array();
<?foreach ($arCellTemplates as $key => $value){?>CellTPL[<? echo $key; ?>] = '<? echo $value; ?>';<?}?>
var CellAttr = new Array();
<?foreach ($arCellAttr as $key => $value){?>CellAttr[<? echo $key; ?>] = '<? echo $value; ?>';<?}?>

var CellTPLQuestion = new Array();
<?foreach ($arCellTemplatesQuestion as $key => $value){?>CellTPLQuestion[<? echo $key; ?>] = '<? echo $value; ?>';<?}?>
var CellAttrQuestion = new Array();
<?foreach ($arCellAttrQuestion as $key => $value){?>CellAttr[<? echo $key; ?>] = '<? echo $value; ?>';<?}?>
</script>
<style>
.popup-window-content { 
	overflow: hidden;
	padding: 20px;
}
.answer_list{
	padding:10px 20px 20px 200px;
	background-color: #D7EDE4;
}

.answer_header{
	color: #3F4B54;
	font-size: 13px;
	font-weight: 700;
	padding:10px 0px;
}

    .answer_table table{
        width: 100%;
    }

</style>
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
<script>
$(document).ready(function(){
	$('select.test_type').live("change",function(){
		if($(this).val()=="input")
		{
			$(this).parent('td').parent('tr').find(".CORRECT_ANSWER,.SCORES").prop("disabled",false);
			$(this).parent('td').parent('tr').next('tr').find(".SCORES").prop("disabled",true);
			$(this).parent('td').parent('tr').next('tr').find(".add_ansver").click(function (){
					CheckScores(this);
				});

		}else{
			$(this).parent('td').parent('tr').find(".CORRECT_ANSWER,.SCORES").prop("disabled",true);
			$(this).parent('td').parent('tr').next('tr').find(".SCORES").prop("disabled",false);
			$(this).parent('td').parent('tr').next('tr').find(".add_ansver").unbind("click");
		}
	});
	function CheckScores(_this)
	{
		$(_this).parent().parent().find(".SCORES:enabled").prop("disabled",true);
	}
	$('select.test_type').each(function(){
		if($(this).val()=="input")
		{
			$(this).parent('td').parent('tr').next('tr').find(".add_ansver").click(function (){
					CheckScores(this);
				});
		}
	});
});
</script>
<?
$arrSkipName=array(
	"apply",
	"serializedData",
	"sessid",
	"autosave_id",
	"tabControl_active_tab",
	"Update",
	"lang",
	"ID",
	"NAME",
	"ACTIVE",
	"PICTURE",
	"DESCRIPTION",
	"DESCRIPTION_TYPE",
    "TO_TITLE",
    "TO_TITLE_TYPE",
	"GROUP_ID",
	"SORT",
	"DATE_FROM",
	"DATE_TO",
	"CODE",
	"NUMBER_ATTEMPTS",
	"ACCESS_GROUPS[]",
	"ACCESS_ALL",
	"ACCESS_GROUP",
	"SHOW_ANSWERS",
	);
?>
<script>
$(document).ready(function(){
	$('#frmtest').live("submit",function(){
		$(this).find('input[type=checkbox]:checked').each(function(){
			$(this).prev("input[type=hidden]").remove();
		});
	
		$(this).append(
			$("<input type=\"hidden\" name=\"serializedData\">").val($(this).serialize())
		)
		.find("input[type!=file], select, textarea")
		.filter(function (index){
			<?foreach($arrSkipName as $value){?>
			if($(this).attr("name")=="<?=$value?>") return false;
			<?}?>
			return true;
		})
		.remove();
	});
});
</script>

<form id="frmtest" method="POST" action="<?echo $APPLICATION->GetCurPage()?>?lang=<?echo LANG?><?if ($ID > 0):?>&ID=<?=$ID?><?endif;?>" name="frmtest" ENCTYPE="multipart/form-data" id="frmtest">

<input type="hidden" name="Update" value="Y">

<input type="hidden" name="lang" value="<?echo LANG ?>">
<input type="hidden" name="ID" value="<?echo $ID ?>">
<?=bitrix_sessid_post()?>
<?
$aTabs = array(
		array("DIV" => "edit1", "TAB" => GetMessage("AT_TAB_GROUP"), "ICON" => "catalog", "TITLE" => GetMessage("AT_TAB_GROUP_DESCR")),
		array("DIV" => "edit2", "TAB" => GetMessage("AT_TAB_QUESTION"), "ICON" => "catalog", "TITLE" => GetMessage("AT_TAB_QUESTION_DESCR")),
		array("DIV" => "edit3", "TAB" => GetMessage("AT_TAB_RESULT"), "ICON" => "catalog", "TITLE" => GetMessage("AT_TAB_RESULT_DESCR")),
		array("DIV" => "edit4", "TAB" => GetMessage("AT_TAB_ACCESS"), "ICON" => "catalog", "TITLE" => GetMessage("AT_TAB_ACCESS_DESCR")),
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
						'description' => false,
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

<?$tabControl->BeginNextTab();?>

	<tr>
		<td>
		
			<?
				$cell=-1;
				$SendEval="Y";
				if($HideDescription=="Y"){
					$cell=7;
					$SendEval="N";
					if($ShowXmlID=="Y")
						$cell++;
				}
			?>
			<script type="text/javascript">
			
			var obQuestionProps = new JAelitaTestResultProperty({
				'PREFIX': '<? echo $strQUESTION_Result ?>',
				'FORM_ID': 'frmtest',
				'TABLE_PROP_ID': 'question_prop_list',
				'PROP_COUNT_ID': 'INT_TEST_QUESTION_COUNT',
				'TEST_ID': <? echo $ID; ?>,
				'LANG': '<? echo LANGUAGE_ID; ?>',
				'TITLE': '<? echo CUtil::JSEscape(GetMessage('AT_EDIT_TITLE')); ?>',
				'BUTTON_SAVE':'<?echo GetMessage("BUTTON_SAVE"); ?>',
				'BUTTON_CLOSE':'<?echo GetMessage("BUTTON_CLOSE"); ?>',
				'SENDEVAL':'<?=$SendEval?>',
				'OBJ': 'obQuestionProps',
				'ADD_TR':'<?=AelitaTestEditTool::InitCUtilTableAnswer()?>',
				'ADD_SCRIPT':'<?=AelitaTestEditTool::InitCUtilScriptAnswer()?>',
			});
			obQuestionProps.SetCells(CellTPLQuestion,<?=$cell?>,CellAttrQuestion);
			</script>
		

			<table border="0" cellspacing="0" cellpadding="0" class="internal" align="center" id="question_prop_list" width="100%">
				<tr class="heading">
					<td>ID</td>
					<td><?echo GetMessage("AT_TEST_TYPE"); ?></td>
					<td><?echo GetMessage("AT_CORRECT_ANSWER"); ?></td>
                    <?if(!$UseCorrect){?>
					<td><?echo GetMessage("AT_SCORES_CORRECT_ANSWER"); ?></td>
                    <?}?>
					<td><?echo GetMessage("AT_ACTIVE"); ?></td>
					<td><?echo GetMessage("AT_SORT"); ?></td>
					<td><?echo GetMessage("AT_NAME"); ?></td>
					<?if($ShowXmlID=="Y"):?>
					<td><?echo GetMessage("AT_XML_ID"); ?></td>
					<?endif;?>
					<?if($HideDescription=="Y"):?>
					<td><?echo GetMessage("AT_DESCRIPTION"); ?></td>
					<?else:?>
					<td><?echo GetMessage("AT_PICTURE"); ?></td>
					<td><?echo GetMessage("AT_DESCRIPTION"); ?></td>
					<?endif;?>
					<td><?echo GetMessage("AT_DELETE"); ?></td>
				</tr>
				<?
				$arPropList = array();
				if (0 < $ID)
				{
					$el=new AelitaTestQuestion();
					$rsProps= $el->GetList(array("SORT"=>"ASC",'ID' => 'ASC'), array("TEST_ID" => $ID));
					while ($arProp = $rsProps->GetNext())
					{
						AelitaTestEditTool::ConvProp($arProp,AelitaTestEditTool::GetHiddenQuestion());
						$arProp = AelitaTestEditTool::ConvertToSafe($arProp,AelitaTestEditTool::GetDisabledQuestion());
						$arProp['SHOW_DEL'] = 'Y';
						$arPropList[$arProp['ID']] = $arProp;
					}
				}
				
				$intQuestionCount = intval($_POST['TEST_QUESTION_COUNT']);
				if (0 >= $intQuestionCount)
					$intQuestionCount = PROPERTY_EMPTY_ROW_SIZE;
				$intPropNumber = 0;
				
				for ($i = 0; $i < $intQuestionCount; $i++)
				{
					$arProp = AelitaTestEditTool::GetPropertyInfoQuestion($strQUESTION_Result, 'n'.$i, false, AelitaTestEditTool::GetHiddenQuestion());
					if (true == is_array($arProp))
					{
						$arProp = AelitaTestEditTool::ConvertToSafe($arProp,AelitaTestEditTool::GetDisabledQuestion());
						$arProp['ID'] = 'n'.$intPropNumber;
						$arPropList['n'.$intPropNumber] = $arProp;
						$intPropNumber++;
					}
				}
				
				for (0; $intPropNumber < PROPERTY_EMPTY_ROW_SIZE; $intPropNumber++)
				{
					$arProp = AelitaTestEditTool::GetDefaultQuestion();
					$arProp['TEST_ID'] = $ID;
					AelitaTestEditTool::ConvProp($arProp,AelitaTestEditTool::GetHiddenQuestion());
					$arProp['ID'] = 'n'.$intPropNumber;
					$arPropList['n'.$intPropNumber] = $arProp;
				}

				foreach ($arPropList as $mxPropID => $arProp)
				{
					echo AelitaTestEditTool::AddPropRowQuestion($mxPropID,$strQUESTION_Result,$arProp);
				}
				?>
				
			</table>

			
			<br />
			<div style="width: 100%; text-align: center;">
				<input onclick="obQuestionProps.addPropRow();" type="button" value="<? echo GetMessage('AT_SHOW_ADD_PROP_ROW')?>" title="<? echo GetMessage('AT_SHOW_ADD_PROP_ROW_DESCR')?>">
			</div>
			<input type="hidden" name="TEST_QUESTION_COUNT" id="INT_TEST_QUESTION_COUNT" value="<? echo $intPropNumber; ?>">
		</td>
	</tr>
<?$tabControl->BeginNextTab();?>
	<tr>
		<td>
			<?
				$cell=-1;
				$SendEval="Y";
				if($HideDescription=="Y"){
					$cell=6;
					$SendEval="N";
					if($ShowXmlID=="Y")
						$cell++;
				}
			?>
		
			<script type="text/javascript">
			
			var obResultProps = new JAelitaTestResultProperty({
				'PREFIX': '<? echo $strPREFIX_Result ?>',
				'FORM_ID': 'frmtest',
				'TABLE_PROP_ID': 'result_prop_list',
				'PROP_COUNT_ID': 'INT_TEST_RESULT_COUNT',
				'TEST_ID': <? echo $ID; ?>,
				'LANG': '<? echo LANGUAGE_ID; ?>',
				'TITLE': '<? echo CUtil::JSEscape(GetMessage('AT_EDIT_TITLE')); ?>',
				'BUTTON_SAVE':'<?echo GetMessage("BUTTON_SAVE"); ?>',
				'BUTTON_CLOSE':'<?echo GetMessage("BUTTON_CLOSE"); ?>',
				'SENDEVAL':'<?=$SendEval?>',
				'OBJ': 'obResultProps'
			});
			obResultProps.SetCells(CellTPL,<?=$cell?>,CellAttr);
			</script>
			<table border="0" cellspacing="0" cellpadding="0" class="internal" align="center" id="result_prop_list" width="100%">
				<tr class="heading">
					<td>ID</td>
                    <?if($UseCorrect){?>
                        <td><?echo GetMessage("AT_MIN_CORRECT"); ?></td>
                        <td><?echo GetMessage("AT_MAX_CORRECT"); ?></td>
                    <?}else{?>
                        <td><?echo GetMessage("AT_MIN_SCORES"); ?></td>
                        <td><?echo GetMessage("AT_MAX_SCORES"); ?></td>
					<?}?>
                    <td><?echo GetMessage("AT_ACTIVE"); ?></td>
					<td><?echo GetMessage("AT_SORT"); ?></td>
					<td><?echo GetMessage("AT_NAME"); ?></td>
					<?if($ShowXmlID=="Y"):?>
					<td><?echo GetMessage("AT_XML_ID"); ?></td>
					<?endif;?>
					<?if($HideDescription=="Y"):?>
					<td><?echo GetMessage("AT_DESCRIPTION"); ?></td>
					<?else:?>
					<td><?echo GetMessage("AT_PICTURE"); ?></td>
					<td><?echo GetMessage("AT_DESCRIPTION"); ?></td>
					<?endif;?>
					<td><?echo GetMessage("AT_DELETE"); ?></td>
				</tr>
				<?
				$arPropList = array();
				if (0 < $ID)
				{
					$el=new AelitaTestResult();
					$rsProps= $el->GetList(array("SORT"=>"ASC",'ID' => 'ASC'), array("TEST_ID" => $ID));
					while ($arProp = $rsProps->GetNext())
					{

						AelitaTestEditTool::ConvProp($arProp,AelitaTestEditTool::GetHiddenResult());
						$arProp = AelitaTestEditTool::ConvertToSafe($arProp,AelitaTestEditTool::GetDisabledResult());
						$arProp['SHOW_DEL'] = 'Y';
						$arPropList[$arProp['ID']] = $arProp;
					}
				}
				
				$intPropCount = intval($_POST['TEST_RESULT_COUNT']);
				if (0 >= $intPropCount)
					$intPropCount = PROPERTY_EMPTY_ROW_SIZE;
				$intPropNumber = 0;
				
				for ($i = 0; $i < $intPropCount; $i++)
				{
					$arProp = AelitaTestEditTool::GetPropertyInfo($strPREFIX_Result, 'n'.$i, false, AelitaTestEditTool::GetHiddenResult());
					if (true == is_array($arProp))
					{
						$arProp = AelitaTestEditTool::ConvertToSafe($arProp,AelitaTestEditTool::GetDisabledResult());
						$arProp['ID'] = 'n'.$intPropNumber;
						$arPropList['n'.$intPropNumber] = $arProp;
						$intPropNumber++;
					}
				}
				
				for (0; $intPropNumber < PROPERTY_EMPTY_ROW_SIZE; $intPropNumber++)
				{
					$arProp = AelitaTestEditTool::GetDefaultResult();
					$arProp['TEST_ID'] = $ID;
					AelitaTestEditTool::ConvProp($arProp,AelitaTestEditTool::GetHiddenResult());
					$arProp['ID'] = 'n'.$intPropNumber;
					$arPropList['n'.$intPropNumber] = $arProp;
				}

				foreach ($arPropList as $mxPropID => $arProp)
				{
					echo AelitaTestEditTool::AddPropRowResult($mxPropID,$strPREFIX_Result,$arProp);
				}
				?>
				
			</table><br />
			<div style="width: 100%; text-align: center;">
				<input onclick="obResultProps.addPropRow();" type="button" value="<? echo GetMessage('AT_SHOW_ADD_PROP_ROW')?>" title="<? echo GetMessage('AT_SHOW_ADD_PROP_ROW_DESCR')?>">
			</div>
			<input type="hidden" name="TEST_RESULT_COUNT" id="INT_TEST_RESULT_COUNT" value="<? echo $intPropNumber; ?>">
		</td>
	</tr>
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
				<input type="checkbox" name="ACCESS_GROUPS[]" class="ACCESS_GROUPS" id="ACCESS_GROUPS_<?=$Groups["ID"]?>" value="<?=$Groups["ID"]?>"<?if (in_array($Groups["ID"],$str_ACCESS_GROUPS)) echo " checked";?>> <?=$Groups["NAME"]?><br /><br />
			<?}?>
		</td>
	</tr>
	
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