<?;
if(!$USER->IsAdmin())
	return;
IncludeModuleLangFile(__FILE__); 
$module_id="aelita.test" ; 

if (CModule::IncludeModule($module_id)):
$MOD_RIGHT = $APPLICATION->GetGroupRight($module_id); 
if($MOD_RIGHT>="R"){
	$arAllOptions = Array(
        Array("aelita_test_show_xml_id",GetMessage("AELITA_TEST_SHOW_XML_ID"),"N", Array("checkbox", "N")),
        Array("hide_picture_and_description_in_window",GetMessage("HIDE_PICTURE_AND_DESCRIPTION_IN_WINDOW"),"N", Array("checkbox", "N")),
        Array("count_test_result_by_finish",GetMessage("COUNT_TEST_RESULT_BY_FINISH"),"N", Array("checkbox", "N")),
        Array("aelita_test_sadvanced_mode",GetMessage("AELITA_TEST_SADVANCED_MODE"),"N", Array("checkbox", "N")),
        Array("aelita_test_allow_passed_back",GetMessage("AELITA_TEST_ALLOW_PASSED_BACK"),"N", Array("checkbox", "N")),
        Array("require_confirmation_completion",GetMessage("AELITA_TEST_REQUIRE_CONFIRMATION_COMPLETION"),"N",Array("checkbox", "N")),
        Array("aelita_test_use_correct",GetMessage("AELITA_TEST_USE_CORRECT"),"N",Array("checkbox", "N")),

        Array("aelita_test_use_tree_view_directory",GetMessage("AELITA_TEST_USE_TREE_VIEW_DIRECTORY"),"N",Array("checkbox", "N")),

        Array("fields_use_watermark_file",GetMessage("FIELDS_USE_WATERMARK_FILE"),"N",Array("checkbox", "N")),
        Array("fields_watermark_file",GetMessage("FIELDS_WATERMARK_FILE"), Array("sting", "")),
        Array("fields_watermark_file_alpha",GetMessage("FIELDS_WATERMARK_FILE_ALPHA"), Array("sting", "")),
        Array("fields_watermark_file_position",GetMessage("FIELDS_WATERMARK_FILE_POSITION"), Array("sting", "")),

        Array("fields_use_watermark_text",GetMessage("FIELDS_USE_WATERMARK_TEXT"),"N",Array("checkbox", "N")),
        Array("fields_watermark_text",GetMessage("FIELDS_WATERMARK_TEXT"), Array("sting", "")),
        Array("fields_watermark_text_font",GetMessage("FIELDS_WATERMARK_TEXT_FONT"), Array("sting", "")),
        Array("fields_watermark_text_color",GetMessage("FIELDS_WATERMARK_TEXT_COLOR"), Array("sting", "")),
        Array("fields_watermark_text_size",GetMessage("FIELDS_WATERMARK_TEXT_SIZE"), Array("sting", "")),
        Array("fields_watermark_text_position",GetMessage("FIELDS_WATERMARK_TEXT_POSITION"), Array("sting", "")),

		);
	};
if($MOD_RIGHT>="W")
{
	if ($REQUEST_METHOD=="GET" && strlen($RestoreDefaults)>0)
	{
		COption::RemoveOption($module_id); 
		reset($arGROUPS); 
		while(list(,$value)=each($arGROUPS)) 
		$APPLICATION->DelGroupRight($module_id, array($value["ID"])); 
	}; 
	if($REQUEST_METHOD=="POST" && strlen($Update)>0)
	{ 
		foreach($arAllOptions as $arOption)
		{
			$name=$arOption[0];
			$val=$_REQUEST[$name];
			if($arOption[2][0]=="checkbox" && $val!="Y")
				$val="N";
			COption::SetOptionString($module_id, $name, $val, $arOption[1]);
		} 
	};
};


$aTabs = array(
	array("DIV" => "edit1", "TAB" => GetMessage("AELITA_TEST"), "ICON" => "vote_settings", "TITLE" => GetMessage("AELITA_TEST_TITLE")),
    array("DIV" => "edit3", "TAB" => GetMessage("MAIN_TAB_SCALE"), "ICON" => "vote_settings", "TITLE" => GetMessage("MAIN_TAB_TITLE_SCALE")),
	array("DIV" => "edit2", "TAB" => GetMessage("MAIN_TAB_RIGHTS"), "ICON" => "vote_settings", "TITLE" => GetMessage("MAIN_TAB_TITLE_RIGHTS")),
);
$tabControl = new CAdminTabControl("tabControl", $aTabs);
?> 
<?
$tabControl->Begin();
?>
<form method="POST" action="<?echo $APPLICATION->GetCurPage()?>?mid=<?=htmlspecialchars($mid)?>&lang=<?=LANGUAGE_ID?>">
<?=bitrix_sessid_post()?>   
<?$tabControl->BeginNextTab();?>
	<tr>
		<td valign="top"  width="50%"><?=GetMessage("AELITA_TEST_SADVANCED_MODE")?>:</td>
		<td valign="middle" width="50%">
			<?$val = COption::GetOptionString($module_id, "aelita_test_sadvanced_mode","N");?>
			<input type="checkbox" name="aelita_test_sadvanced_mode" value="Y" <?if($val=="Y"){echo "checked";};?>>
		</td>
	</tr>
	<tr>
		<td valign="top"  width="50%"><?=GetMessage("AELITA_TEST_ALLOW_PASSED_BACK")?>:</td>
		<td valign="middle" width="50%">
			<?$val = COption::GetOptionString($module_id, "aelita_test_allow_passed_back","N");?>
			<input type="checkbox" name="aelita_test_allow_passed_back" value="Y" <?if($val=="Y"){echo "checked";};?>>
		</td>
	</tr>
	<tr>
		<td valign="top"  width="50%"><?=GetMessage("AELITA_TEST_SHOW_XML_ID")?>:</td>
		<td valign="middle" width="50%">
			<?$val = COption::GetOptionString($module_id, "aelita_test_show_xml_id","N");?>
			<input type="checkbox" name="aelita_test_show_xml_id" value="Y" <?if($val=="Y"){echo "checked";};?>>
		</td>
	</tr>
	<tr>
		<td valign="top"  width="50%"><?=GetMessage("HIDE_PICTURE_AND_DESCRIPTION_IN_WINDOW")?>:</td>
		<td valign="middle" width="50%">
			<?$val = COption::GetOptionString($module_id, "hide_picture_and_description_in_window","N");?>
			<input type="checkbox" name="hide_picture_and_description_in_window" value="Y" <?if($val=="Y"){echo "checked";};?>>
		</td>
	</tr>
	<tr>
		<td valign="top"  width="50%"><?=GetMessage("COUNT_TEST_RESULT_BY_FINISH")?>:</td>
		<td valign="middle" width="50%">
			<?$val = COption::GetOptionString($module_id, "count_test_result_by_finish","N");?>
			<input type="checkbox" name="count_test_result_by_finish" value="Y" <?if($val=="Y"){echo "checked";};?>>
		</td>
	</tr>
	<tr>
		<td valign="top"  width="50%"><?=GetMessage("AELITA_TEST_REQUIRE_CONFIRMATION_COMPLETION")?>:</td>
		<td valign="middle" width="50%">
			<?$val = COption::GetOptionString($module_id, "require_confirmation_completion","N");?>
			<input type="checkbox" name="require_confirmation_completion" value="Y" <?if($val=="Y"){echo "checked";};?>>
		</td>
	</tr>

    <tr>
        <td valign="top"  width="50%"><?=GetMessage("AELITA_TEST_USE_TREE_VIEW_DIRECTORY")?>:</td>
        <td valign="middle" width="50%">
            <?$val = COption::GetOptionString($module_id, "aelita_test_use_tree_view_directory","N");?>
            <input type="checkbox" name="aelita_test_use_tree_view_directory" value="Y" <?if($val=="Y"){echo "checked";};?>>
        </td>
    </tr>


    <?/*
    <tr>
        <td valign="top"  width="50%"><?=GetMessage("AELITA_TEST_USE_CORRECT")?>:</td>
        <td valign="middle" width="50%">
            <?$val = COption::GetOptionString($module_id, "aelita_test_use_correct","N");?>
            <input type="checkbox" name="aelita_test_use_correct" value="Y" <?if($val=="Y"){echo "checked";};?>>
        </td>
    </tr>*/?>
    <?$tabControl->BeginNextTab();?>


    <div class="adm-list">
        <?$fields_use_watermark_file=COption::GetOptionString($module_id, "fields_use_watermark_file","N");?>
        <div class="adm-list-item">
            <div class="adm-list-control">
                <input
                    type="checkbox"
                    value="Y"
                    id="fields_use_watermark_file"
                    name="fields_use_watermark_file"
                    <?
                    if($fields_use_watermark_file==="Y")
                        echo "checked";
                    ?>
                    onclick="
                        BX('settings_use_watermark_file').style.display =
                        BX('settings_watermark_file_alpha').style.display =
                        BX('settings_watermark_file_position').style.display =
                        this.checked? 'block': 'none';
                        "
                    >
            </div>
            <div class="adm-list-label">
                <label
                    for="fields_use_watermark_file"
                    ><?echo GetMessage("FIELDS_USE_WATERMARK_FILE")?></label>
            </div>
        </div>
        <div class="adm-list-item"
             id="settings_use_watermark_file"
             style="padding-left:16px;display:<?
             if($fields_use_watermark_file==="Y") echo 'block'; else echo 'none';
             ?>"
            >
            <?CAdminFileDialog::ShowScript(array(
                "event" => "BtnClick_WATERMARK_FILE",
                "arResultDest" => array("ELEMENT_ID" => "FIELDS_DEFAULT_VALUE_WATERMARK_FILE"),
                "arPath" => array("PATH" => GetDirPath((COption::GetOptionString($module_id, "fields_watermark_file","")))),
                "select" => 'F',// F - file only, D - folder only
                "operation" => 'O',// O - open, S - save
                "showUploadTab" => true,
                "showAddToMenuTab" => false,
                "fileFilter" => 'jpg,jpeg,png,gif',
                "allowAllFiles" => false,
                "SaveConfig" => true,
            ));?>
            <?echo GetMessage("FIELDS_WATERMARK_FILE")?>:&nbsp;<input
                name="fields_watermark_file"
                id="FIELDS_DEFAULT_VALUE_WATERMARK_FILE"
                type="text"
                value="<?echo htmlspecialcharsbx(COption::GetOptionString($module_id, "fields_watermark_file",""))?>"
                size="35"
                >&nbsp;<input type="button" value="..." onClick="BtnClick_WATERMARK_FILE()">
        </div>
        <div class="adm-list-item"
             id="settings_watermark_file_alpha"
             style="padding-left:16px;display:<?
             if($fields_use_watermark_file==="Y") echo 'block'; else echo 'none';
             ?>"
            >
            <?echo GetMessage("FIELDS_WATERMARK_FILE_ALPHA")?>:&nbsp;<input
                name="fields_watermark_file_alpha"
                type="text"
                value="<?echo htmlspecialcharsbx(COption::GetOptionString($module_id, "fields_watermark_file_alpha",""))?>"
                size="3"
                >
        </div>
        <div class="adm-list-item"
             id="settings_watermark_file_position"
             style="padding-left:16px;display:<?
             if($fields_use_watermark_file==="Y") echo 'block'; else echo 'none';
             ?>"
            >
            <?echo GetMessage("FIELDS_WATERMARK_FILE_POSITION")?>:&nbsp;<?echo SelectBox(
                "fields_watermark_file_position",
                AelitaTestTools::GetWatermarkPositions(),
                "",
                COption::GetOptionString($module_id, "fields_watermark_file_position","")
            );?>
        </div>
        <?$fields_use_watermark_text=COption::GetOptionString($module_id, "fields_use_watermark_text","N");?>
        <div class="adm-list-item">
            <div class="adm-list-control">
                <input
                    type="checkbox"
                    value="Y"
                    id="fields_use_watermark_text"
                    name="fields_use_watermark_text"
                    <?
                    if($fields_use_watermark_text==="Y")
                        echo "checked";
                    ?>
                    onclick="
                        BX('settings_use_watermark_text').style.display =
                        BX('settings_watermark_text_font').style.display =
                        BX('settings_watermark_text_color').style.display =
                        BX('settings_watermark_text_size').style.display =
                        BX('settings_watermark_text_position').style.display =
                        this.checked? 'block': 'none';
                        "
                    >
            </div>
            <div class="adm-list-label">
                <label
                    for="fields_use_watermark_text"
                    ><?echo GetMessage("FIELDS_USE_WATERMARK_TEXT")?></label>
            </div>
        </div>
        <div class="adm-list-item"
             id="settings_use_watermark_text"
             style="padding-left:16px;display:<?
             if($fields_use_watermark_text==="Y") echo 'block'; else echo 'none';
             ?>"
            >
            <?echo GetMessage("FIELDS_WATERMARK_TEXT")?>:&nbsp;<input
                name="fields_watermark_text"
                type="text"
                value="<?echo COption::GetOptionString($module_id, "fields_watermark_text","")?>"
                size="35"
                >
            <?CAdminFileDialog::ShowScript(array(
                "event" => "BtnClickFontTEXT_FONT",
                "arResultDest" => array("ELEMENT_ID" => "fields_watermark_text_font"),
                "arPath" => array("PATH" => GetDirPath((COption::GetOptionString($module_id, "fields_watermark_text_font","")))),
                "select" => 'F',// F - file only, D - folder only
                "operation" => 'O',// O - open, S - save
                "showUploadTab" => true,
                "showAddToMenuTab" => false,
                "fileFilter" => 'ttf',
                "allowAllFiles" => false,
                "SaveConfig" => true,
            ));?>
        </div>
        <div class="adm-list-item"
             id="settings_watermark_text_font"
             style="padding-left:16px;display:<?
             if($fields_use_watermark_text==="Y") echo 'block'; else echo 'none';
             ?>"
            >
            <?echo GetMessage("FIELDS_WATERMARK_TEXT_FONT")?>:&nbsp;<input
                name="fields_watermark_text_font"
                id="fields_watermark_text_font"
                type="text"
                value="<?echo htmlspecialcharsbx(COption::GetOptionString($module_id, "fields_watermark_text_font",""))?>"
                size="35">&nbsp;<input
                type="button"
                value="..."
                onClick="BtnClickFontTEXT_FONT()"
                >
        </div>
        <div class="adm-list-item"
             id="settings_watermark_text_color"
             style="padding-left:16px;display:<?
             if($fields_use_watermark_text==="Y") echo 'block'; else echo 'none';
             ?>"
            >
            <?echo GetMessage("FIELDS_WATERMARK_TEXT_COLOR")?>:&nbsp;<input
                name="fields_watermark_text_color"
                id="fields_watermark_text_color"
                type="text"
                value="<?echo htmlspecialcharsbx(COption::GetOptionString($module_id, "fields_watermark_text_color",""))?>"
                size="7"
                ><script>
                function WATERMARK_TEXT_COLOR(color)
                {
                    BX('fields_watermark_text_color').value = color.substring(1);
                }
            </script>&nbsp;<input
                type="button"
                value="..."
                onclick="BX.findChildren(this.parentNode, {'tag': 'IMG'}, true)[0].onclick();"
                ><span style="float:left;width:1px;height:1px;visibility:hidden;position:absolute;"><?
                $APPLICATION->IncludeComponent(
                    "bitrix:main.colorpicker",
                    "",
                    array(
                        "SHOW_BUTTON" =>"Y",
                        "ONSELECT" => "WATERMARK_TEXT_COLOR",
                    )
                );
                ?></span>
        </div>
        <div class="adm-list-item"
             id="settings_watermark_text_size"
             style="padding-left:16px;display:<?
             if($fields_use_watermark_text==="Y") echo 'block'; else echo 'none';
             ?>"
            >
            <?echo GetMessage("FIELDS_WATERMARK_TEXT_SIZE")?>:&nbsp;<input
                name="fields_watermark_text_size"
                type="text"
                value="<?echo COption::GetOptionString($module_id, "fields_watermark_text_size","")?>"
                size="3"
                >
        </div>
        <div class="adm-list-item"
             id="settings_watermark_text_position"
             style="padding-left:16px;display:<?
             if($fields_use_watermark_text==="Y") echo 'block'; else echo 'none';
             ?>"
            >
            <?echo GetMessage("FIELDS_WATERMARK_TEXT_POSITION")?>:&nbsp;<?echo SelectBox(
                "fields_watermark_text_position",
                AelitaTestTools::GetWatermarkPositions(),
                "",
                COption::GetOptionString($module_id, "fields_watermark_text_position","")
            );?>
        </div>
    </div>




<?$tabControl->BeginNextTab();?>
<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/admin/group_rights.php");?>
            
<?$tabControl->Buttons();?>
<script language="JavaScript">
function RestoreDefaults()
{
    if(confirm('<?=CUtil::JSEscape(GetMessage("MAIN_HINT_RESTORE_DEFAULTS_WARNING"))?>'))
        window.location = "<?=$APPLICATION->GetCurPage()?>?RestoreDefaults=Y&lang=<?=LANGUAGE_ID?>&mid=<?echo urlencode($mid)?>";
}
</script>
<input <?//if ($FORUM_RIGHT<"W") echo "disabled" ?> type="submit" name="Update" value="<?echo GetMessage("PATH_SAVE")?>">
<input type="hidden" name="Update" value="Y">
<input type="reset" name="reset" value="<?echo GetMessage("PATH_RESET")?>">
<input <?i//f ($FORUM_RIGHT<"W") echo "disabled" ?> type="button" title="<?echo GetMessage("MAIN_RESTORE_DEFAULTS")?>" OnClick="RestoreDefaults();" value="<?echo GetMessage("MAIN_RESTORE_DEFAULTS")?>">
<?$tabControl->End();?>
</form> 
<?endif?>
