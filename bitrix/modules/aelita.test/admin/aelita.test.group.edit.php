<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
$module_id="aelita.test";

$ListItem="/bitrix/admin/aelita.test.group.list.php?lang=";
$EditItem="/bitrix/admin/aelita.test.group.edit.php?lang=";
$TableModule="b_aelita_test_group";

IncludeModuleLangFile(__FILE__);
$POST_RIGHT = $APPLICATION->GetGroupRight($module_id);
if($POST_RIGHT=="D")
	$APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
if(!CModule::IncludeModule($module_id))die();
	
CUtil::InitJSCore(array('jquery'));

ClearVars();
ClearVars("str_");

$errorMessage = "";
$bVarsFromForm = false;

$ID = IntVal($ID);
$ShowXmlID=COption::GetOptionString($module_id, "aelita_test_show_xml_id","N");
$TreeDirectory=COption::GetOptionString($module_id, "aelita_test_use_tree_view_directory","N");
$arrUserGroups=array();
$rsGroups = CGroup::GetList(($by="ID"), ($order="ASC"));
while($Group=$rsGroups->GetNext()) 
	$arrUserGroups[]=$Group;

if ($REQUEST_METHOD=="POST" && strlen($Update) > 0 && !$bReadOnly && check_bitrix_sessid())
{
	$arPICTURE = $HTTP_POST_FILES["PICTURE"];
	$arPICTURE["del"] = ${"PICTURE_del"};
	
	$arFields = array(
		"NAME"=>$NAME,
		"CODE"=>$CODE,
        "GROUP_ID"=>$GROUP_ID,
		"ACTIVE"=>$ACTIVE,
		"PICTURE"=>$arPICTURE,
		"DESCRIPTION"=>$DESCRIPTION,
		"DESCRIPTION_TYPE"=>$DESCRIPTION_TYPE,
		"SORT"=>$SORT,
		"ACCESS_ALL"=>$ACCESS_ALL,
        "ALT"=>$ALT,
	);
	
	if($ShowXmlID=="Y")
		$arFields["XML_ID"]=$XML_ID;

	$el=new AelitaTestGroup;
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
	
	$arrACCESS_GROUP=array();
	
	
	if($ID > 0)
	{
		$el=new AelitaTestAccessGroup();
		$props = $el->GetList(array(), array("GROUP_ID" => $ID));
		while($p = $props->GetNext())
		{
			$k=array_search($p["USER_GROUP_ID"], $ACCESS_GROUP);
			if($k!==false)
			{
				$arrACCESS_GROUP[$p["ID"]] = $prop;
				unset($ACCESS_GROUP[$k]);
			}else{
				$arrACCESS_GROUP[$p["ID"]] = array("DEL"=>"Y","ID"=>$p["ID"]);
			}
		}
	}
	
	$i=0;
	foreach($ACCESS_GROUP as $k)
	{
		if(!$arrACCESS_GROUP[$k])
		{
			$arProperty=array(
				"USER_GROUP_ID"=>$k,
				);
			$arrACCESS_GROUP["n".$i] = $arProperty;
		}
		$i++;
	}
	
	if($ID>0)
	{
		foreach($arrACCESS_GROUP as &$Properties)
		{
			$Properties["GROUP_ID"]=(int)$Properties["GROUP_ID"];
			if($Properties["GROUP_ID"]<=0)
				$Properties["GROUP_ID"]=$ID;
			$el=new AelitaTestAccessGroup();
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

$Groups=array(
    0=>GetMessage("AT_NON"),
);
$ListGroups = new AelitaTestGroup();


if ($ID > 0)
{
	$el =new AelitaTestGroup;
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
			$str_XML_ID = htmlspecialchars($arExtra["XML_ID"]);
		$str_ACTIVE = htmlspecialchars($arExtra["ACTIVE"]);
		$str_PICTURE= $arExtra["PICTURE"];
		$str_DESCRIPTION = $arExtra["DESCRIPTION"];
		$str_DESCRIPTION_TYPE = $arExtra["DESCRIPTION_TYPE"];
		$str_SORT = $arExtra["SORT"];
		$str_ACCESS_ALL = $arExtra["ACCESS_ALL"];
        $str_ALT = $arExtra["ALT"];
        $str_GROUP_ID = $arExtra["GROUP_ID"];
		
		$str_ACCESS_GROUP=array();
		

		$el=new AelitaTestAccessGroup();
		$props = $el->GetList(array(), array("GROUP_ID" => $ID));
		while($p = $props->GetNext())
			$str_ACCESS_GROUP[]=$p["USER_GROUP_ID"];
	}

    $dbResultList = $ListGroups->GetList(array("SORT"=>"ASC"),[
            "<>ID"=>$ID,
    ],false,false,array("ID","NAME"));
    while ($Group = $dbResultList->Fetch())
        $Groups[$Group['ID']] = $Group['NAME'];

}else{
    $dbResultList = $ListGroups->GetList(array("SORT"=>"ASC"),false,false,false,array("ID","NAME"));
    while ($Group = $dbResultList->Fetch())
        $Groups[$Group['ID']] = $Group['NAME'];
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
	
$context = new CAdminContextMenu($aMenu);
$context->Show();
	
CAdminMessage::ShowMessage($errorMessage);
?>
<script>
$(document).ready(function(){
	$('#ACCESS_ALL').live("change",function(){
		CheckAccessGroup();
	});
	function CheckAccessGroup()
	{
		var AccessAll=$("#ACCESS_ALL:checked").length;
		if(AccessAll>0)
			$(".ACCESS_GROUP").prop("disabled",true);
		else
			$(".ACCESS_GROUP").prop("disabled",false);
	}
	CheckAccessGroup();
});
</script>
<form method="POST" action="<?echo $APPLICATION->GetCurPage()?>?lang=<?echo LANG?><?if ($ID > 0):?>&ID=<?=$ID?><?endif;?>" name="form1" ENCTYPE="multipart/form-data">

<input type="hidden" name="Update" value="Y">
<input type="hidden" name="lang" value="<?echo LANG ?>">
<input type="hidden" name="ID" value="<?echo $ID ?>">
<?=bitrix_sessid_post()?>
<?
$aTabs = array(
		array("DIV" => "edit1", "TAB" => GetMessage("AT_TAB_GROUP"), "ICON" => "catalog", "TITLE" => GetMessage("AT_TAB_GROUP_DESCR")),
		array("DIV" => "edit2", "TAB" => GetMessage("AT_TAB_ACCESS"), "ICON" => "catalog", "TITLE" => GetMessage("AT_TAB_ACCESS_DESCR")),
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
    <?if($TreeDirectory=="Y"){?>
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
	<?}?>
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
$tabControl->BeginNextTab();
?>
	
	<tr>
		<td width="30%"><?echo GetMessage("AT_ACCESS_ALL")?>:</td>
		<td width="70%">
			<input type="checkbox" name="ACCESS_ALL" id="ACCESS_ALL" value="Y"<?if ($str_ACCESS_ALL == "Y" || $ID<=0) echo " checked";?>>
		</td>
	</tr>
	<tr>
		<td width="30%"><?echo GetMessage("AT_ACCESS_GROUP")?>:</td>
		<td width="70%">
			<?foreach($arrUserGroups as $Groups){?>
				<input type="checkbox" name="ACCESS_GROUP[]" class="ACCESS_GROUP" value="<?=$Groups["ID"]?>"<?if (in_array($Groups["ID"],$str_ACCESS_GROUP)) echo " checked";?>> <?=$Groups["NAME"]?><br /><br />
			<?}?>
		</td>
	</tr>
<?
$tabControl->EndTab();
?>

<?
$tabControl->Buttons(
		array(
				"disabled" => $bReadOnly,
				"back_url" => $ListItem.LANG,
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