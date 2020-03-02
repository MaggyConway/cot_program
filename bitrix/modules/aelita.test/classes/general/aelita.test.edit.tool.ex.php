<?
IncludeModuleLangFile(__FILE__);
class AelitaTestEditToolEx
{

    const module_id="aelita.test";

	function GetByID_admin($ID, $current_section=false)
	{
	
		$ListItem="/bitrix/admin/aelita.test.test.list.php?lang=";
		$EditItem="/bitrix/admin/aelita.test.test.edit.ex.php?lang=";
		
		$ListAnswer="/bitrix/admin/aelita.test.answer.list.php?lang=";
		$ListQuestion="/bitrix/admin/aelita.test.question.list.php?lang=";
		$ListQuestionGroup="/bitrix/admin/aelita.test.question.group.list.php?lang=";
		$ListResult="/bitrix/admin/aelita.test.result.list.php?lang=";

		if ($ID<=0)
			return false;
		$el =new AelitaTestTest;
		$resExtra=$el->GetByID($ID);
		if ($arExtra=$resExtra->GetNext())
		{
			if(strlen($arExtra["CODE"])>0)
				$arExtra["TXT_NAME"]='('.htmlspecialcharsbx($arExtra["CODE"]).')&nbsp;'.htmlspecialcharsbx($arExtra["NAME"]);
			else
				$arExtra["TXT_NAME"]=$arExtra["NAME"];
			
			$arExtra['ADMIN_MENU'] = array();
		
			$arExtra['ADMIN_MENU'][] = array(
				"ICON"=>$current_section=='form' ? 'btn_active' : '',
				"TEXT"=>GetMessage("FORM_MENU_EDIT"),
				"LINK"=>$EditItem.LANGUAGE_ID."&ID=".$ID,
				"TITLE"=> htmlspecialcharsbx(str_replace("#NAME#", $arExtra["NAME"], GetMessage("FORM_MENU_EDIT_TITLE")))
			);
			
			$arExtra['ADMIN_MENU'][] = array(
				"ICON"=>$current_section=='question_group'?'btn_active':'',
				"TEXT"=>GetMessage("FORM_MENU_QUESTION_GROUP"),
				"LINK"=>$ListQuestionGroup.LANGUAGE_ID."&TEST_ID=".$ID,
				"TITLE"=>htmlspecialcharsbx(str_replace("#NAME#", $arExtra["NAME"], GetMessage("FORM_MENU_QUESTION_GROUP_TITLE")))
			);
			
			$arExtra['ADMIN_MENU'][] = array(
				"ICON"=>$current_section=='question'?'btn_active':'',
				"TEXT"=>GetMessage("FORM_MENU_QUESTION"),
				"LINK"=>$ListQuestion.LANGUAGE_ID."&TEST_ID=".$ID,
				"TITLE"=>htmlspecialcharsbx(str_replace("#NAME#", $arExtra["NAME"], GetMessage("FORM_MENU_QUESTION_TITLE")))
			);
			
			$arExtra['ADMIN_MENU'][] = array(
				"ICON"=>$current_section=='result'?'btn_active':'',
				"TEXT"=>GetMessage("FORM_MENU_RESULT"),
				"LINK"=>$ListResult.LANGUAGE_ID."&TEST_ID=".$ID,
				"TITLE"=>htmlspecialcharsbx(str_replace("#NAME#", $arExtra["NAME"], GetMessage("FORM_MENU_RESULT_TITLE")))
			);
			
			return $arExtra;
		}else
			return false;
	}

    function GetUseCorrect($TestId=0,$FieldUseCorrect="N")
    {
        //$UseCorrect=COption::GetOptionString(self::module_id, "aelita_test_use_correct","N");
        //if($UseCorrect)
        //    return true;

        if($TestId>0)
        {
            $el=new AelitaTestTest();
            $resQuestion=$el->GetByID($TestId);
            if($Question=$resQuestion->GetNext())
            {
                if($Question["USE_CORRECT"]=="Y")
                    return true;
            }
        }elseif($FieldUseCorrect=="Y"){
            return true;
        }

        return false;
    }


}


?>