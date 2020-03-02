<?
IncludeModuleLangFile(__FILE__);
define('PROPERTY_EMPTY_ROW_SIZE',1);
class AelitaTestEditTool
{
	
	function prepareSerializedData() {
		if (isset($_POST['serializedData'])) {
			$var=AelitaTestEditTool::parse_str($_POST['serializedData']);
			
			if(LANG_CHARSET!="UTF-8")
			{
				foreach($var as &$value)
				{
					$value=iconv("UTF-8", LANG_CHARSET, $value);
				}unset($value);
			}
			$_POST=array_merge($_POST, $var);
			unset($_POST['serializedData']);
		}
	}
 
	function parse_str($string) {
	    $parts = explode("&", $string);
	    $result = array();
	    foreach ($parts as $part) {
	    	$parsed = array();
	    	parse_str($part, $parsed);
	    	$result = array_merge_recursive($result, $parsed);
	    }
	    return $result;
	}
	
	function GetShowXmlID()
	{
		$module_id="aelita.test";
		$ShowXmlID=COption::GetOptionString($module_id, "aelita_test_show_xml_id","N");
		return $ShowXmlID;
	}

    function GetUseCorrect()
    {
        $module_id="aelita.test";
        $UseCorrect=COption::GetOptionString($module_id, "aelita_test_use_correct","N");
        return $UseCorrect;
    }
	
	function HideDescription()
	{
		$module_id="aelita.test";
		$HideDescription=COption::GetOptionString($module_id, "hide_picture_and_description_in_window","N");
		return $HideDescription;
	}
	
	function GetDefaultResult()
	{
		
		$arr = array(
			'ID'=>0,
			'TEST_ID'=>'',
			'NAME'=>'',
			'ACTIVE'=>'Y',
			'PICTURE'=>'',
			'DESCRIPTION'=>'',
			'DESCRIPTION_TYPE'=>'text',
			'SORT'=>500,
			'MIN_SCORES'=>0,
			'MAX_SCORES'=>0,
			);
		if(self::GetShowXmlID()=="Y")
			$arr['XML_ID']='';
		return $arr;
	}
	
	function GetHiddenResult()
	{
		$arr = array(
			'TEST_ID',
			//'PICTURE',
			//'DESCRIPTION',
			//'DESCRIPTION_TYPE',
			);
		return $arr;
	}
	
	function GetDisabledResult()
	{
		$arr = array(
			'ID',
			'TEST_ID',
			'PICTURE',
			'SORT',
			'MIN_SCORES',
			'MAX_SCORES',
			);
		return $arr;
	}
	
	
	function GetDefaultQuestion()
	{
		
		$arr = array(
			'ID'=>0,
			'TEST_ID'=>'',
			'NAME'=>'',
			'ACTIVE'=>'Y',
			'PICTURE'=>'',
			'DESCRIPTION'=>'',
			'DESCRIPTION_TYPE'=>'text',
			'TEST_TYPE'=>'radio',
			'CORRECT_ANSWER'=>'',
			'SORT'=>500,
			'SCORES'=>0,
			);
		if(self::GetShowXmlID()=="Y")
			$arr['XML_ID']='';
		return $arr;
	}
	
	function GetHiddenQuestion()
	{
		$arr = array(
			'TEST_ID',
			);
		return $arr;
	}
	
	function GetDisabledQuestion()
	{
		$arr = array(
			'ID',
			'TEST_ID',
			'PICTURE',
			'SORT',
			'SCORES',
			);
		return $arr;
	}
	
	function GetDefaultAnswer()
	{
		
		$arr = array(
			'ID'=>0,
			'QUESTION_ID'=>'',
			'NAME'=>'',
			'ACTIVE'=>'Y',
			'PICTURE'=>'',
			'DESCRIPTION'=>'',
			'DESCRIPTION_TYPE'=>'text',
			'SORT'=>500,
			'SCORES'=>0,
			);
		if(self::GetShowXmlID()=="Y")
			$arr['XML_ID']='';
        if(self::GetShowXmlID()=="Y")
            $arr['CORRECT']='N';
		return $arr;
	}
	
	function GetHiddenAnswer()
	{
		$arr = array(
			'QUESTION_ID',
			);
		return $arr;
	}
	
	function GetDisabledAnswer()
	{
		$arr = array(
			'ID',
			'QUESTION_ID',
			'PICTURE',
			'SORT',
			'SCORES',
			);
		return $arr;
	}
	
	function ConvProp(&$arProperty,$arHiddenPropFields)
	{
		$arEncodedProp = array();
		foreach ($arHiddenPropFields as &$strPropField)
		{
			if (isset($arProperty[$strPropField]))
			{
				$arEncodedProp[$strPropField] = $arProperty[$strPropField];
				unset($arProperty[$strPropField]);
			}
		}

		$arProperty['PROPINFO'] = base64_encode(serialize($arEncodedProp));
	}
	
	function GetPropertyInfo($strPrefix, $ID, $boolUnpack = true, $arHiddenPropFields = array())
	{
		$boolUnpack = (true == $boolUnpack ? true : false);
		$arResult = false;

		if (!is_array($arHiddenPropFields))
			return $arResult;

		if (isset($_POST[$strPrefix.$ID.'_NAME']) && (0 < strlen($_POST[$strPrefix.$ID.'_NAME'])) && isset($_POST[$strPrefix.$ID.'_PROPINFO']))
		{
			$strEncodePropInfo = $_POST[$strPrefix.$ID.'_PROPINFO'];
			$strPropInfo = base64_decode($strEncodePropInfo);
			if (CheckSerializedData($strPropInfo))
			{
				global $HTTP_POST_FILES;
				$arPICTURE=$HTTP_POST_FILES[$strPrefix.$ID."_PICTURE"];
				$arPICTURE["del"] = $_POST[$strPrefix.$ID."_PICTURE_del"];
				$arResult = array(
					'ID'=>(int)$ID,
					//'ID' => (isset($_POST[$strPrefix.$ID.'_ID']) && 0 < intval($_POST[$strPrefix.$ID.'_ID']) ? intval($_POST[$strPrefix.$ID.'_ID']) : 0),
					'NAME' => strval($_POST[$strPrefix.$ID."_NAME"]),
					'XML_ID' => strval($_POST[$strPrefix.$ID."_XML_ID"]),
					'SORT' => (0 < intval($_POST[$strPrefix.$ID."_SORT"]) ? intval($_POST[$strPrefix.$ID."_SORT"]) : 500),
					'MIN_SCORES' =>(int)$_POST[$strPrefix.$ID."_MIN_SCORES"],
					'MAX_SCORES' =>(int)$_POST[$strPrefix.$ID."_MAX_SCORES"],
					'ACTIVE' => (isset($_POST[$strPrefix.$ID."_ACTIVE"]) && 'Y' == $_POST[$strPrefix.$ID."_ACTIVE"] ? 'Y' : 'N'),
					
					'DESCRIPTION' => strval($_POST[$strPrefix.$ID."_DESCRIPTION"]),
					'DESCRIPTION_TYPE' => strval($_POST[$strPrefix.$ID."_DESCRIPTION_TYPE"]),
					
					"PICTURE"=>$arPICTURE,
				);
				
				if(is_array($_POST[$strPrefix.$ID."_ACTIVE"]))
				{
					if($_POST[$strPrefix.$ID."_ACTIVE"][1]=="Y")
						$arResult["ACTIVE"]="Y";
				}

				if ($boolUnpack)
				{
					$arPropInfo = unserialize($strPropInfo);
					foreach ($arHiddenPropFields as &$strFieldKey)
					{
						$arResult[$strFieldKey] = (isset($arPropInfo[$strFieldKey]) ? $arPropInfo[$strFieldKey] : $arDefPropInfo[$strFieldKey]);
					}
				}
				else
				{
					$arResult['PROPINFO'] = $strEncodePropInfo;
				}
				if (0 < intval($ID))
				{
					$arResult['DEL'] = (isset($_POST[$strPrefix.$ID."_DEL"]) && ('Y' == $_POST[$strPrefix.$ID."_DEL"]) ? 'Y' : 'N');
				}
			}
		}
		return $arResult;
	}
	
	
	function GetPropertyInfoQuestion($strPrefix, $ID, $boolUnpack = true, $arHiddenPropFields = array())
	{
		$boolUnpack = (true == $boolUnpack ? true : false);
		$arResult = false;

		if (!is_array($arHiddenPropFields))
			return $arResult;


		

		if (isset($_POST[$strPrefix.$ID.'_NAME']) && (0 < strlen($_POST[$strPrefix.$ID.'_NAME'])) && isset($_POST[$strPrefix.$ID.'_PROPINFO']))
		{
			$strEncodePropInfo = $_POST[$strPrefix.$ID.'_PROPINFO'];
			$strPropInfo = base64_decode($strEncodePropInfo);
			if (CheckSerializedData($strPropInfo))
			{
				global $HTTP_POST_FILES;
				$arPICTURE=$HTTP_POST_FILES[$strPrefix.$ID."_PICTURE"];
				$arPICTURE["del"] = $_POST[$strPrefix.$ID."_PICTURE_del"];
				$arResult = array(
					'ID'=>(int)$ID,
					//'ID' => (isset($_POST[$strPrefix.$ID.'_ID']) && 0 < intval($_POST[$strPrefix.$ID.'_ID']) ? intval($_POST[$strPrefix.$ID.'_ID']) : 0),
					'NAME' => strval($_POST[$strPrefix.$ID."_NAME"]),
					'CORRECT_ANSWER' => strval($_POST[$strPrefix.$ID."_CORRECT_ANSWER"]),
					'XML_ID' => strval($_POST[$strPrefix.$ID."_XML_ID"]),
					'SORT' => (0 < intval($_POST[$strPrefix.$ID."_SORT"]) ? intval($_POST[$strPrefix.$ID."_SORT"]) : 500),
					'SCORES' =>(int)$_POST[$strPrefix.$ID."_SCORES"],
					'ACTIVE' => (isset($_POST[$strPrefix.$ID."_ACTIVE"]) && 'Y' == $_POST[$strPrefix.$ID."_ACTIVE"] ? 'Y' : 'N'),
					'TEST_TYPE' => (0 < strlen($_POST[$strPrefix.$ID."_SORT"]) ? $_POST[$strPrefix.$ID."_TEST_TYPE"] : "radio"),
					'DESCRIPTION' => strval($_POST[$strPrefix.$ID."_DESCRIPTION"]),
					'DESCRIPTION_TYPE' => strval($_POST[$strPrefix.$ID."_DESCRIPTION_TYPE"]),
					
					"PICTURE"=>$arPICTURE,
				);
				
				if(is_array($_POST[$strPrefix.$ID."_ACTIVE"]))
				{
					if($_POST[$strPrefix.$ID."_ACTIVE"][1]=="Y")
						$arResult["ACTIVE"]="Y";
				}

				if ($boolUnpack)
				{
					$arPropInfo = unserialize($strPropInfo);
					foreach ($arHiddenPropFields as &$strFieldKey)
					{
						$arResult[$strFieldKey] = (isset($arPropInfo[$strFieldKey]) ? $arPropInfo[$strFieldKey] : $arDefPropInfo[$strFieldKey]);
					}
				}
				else
				{
					$arResult['PROPINFO'] = $strEncodePropInfo;
				}
				if (0 < intval($ID))
				{
					$arResult['DEL'] = (isset($_POST[$strPrefix.$ID."_DEL"]) && ('Y' == $_POST[$strPrefix.$ID."_DEL"]) ? 'Y' : 'N');
				}
			}
		}

		return $arResult;
	}
	
	function GetPropertyInfoAnswer($strPrefix, $ID, $boolUnpack = true, $arHiddenPropFields = array())
	{
		$boolUnpack = (true == $boolUnpack ? true : false);
		$arResult = false;

		if (!is_array($arHiddenPropFields))
			return $arResult;

		if (isset($_POST[$strPrefix.$ID.'_NAME']) && (0 < strlen($_POST[$strPrefix.$ID.'_NAME'])) && isset($_POST[$strPrefix.$ID.'_PROPINFO']))
		{
			$strEncodePropInfo = $_POST[$strPrefix.$ID.'_PROPINFO'];
			$strPropInfo = base64_decode($strEncodePropInfo);
			if (CheckSerializedData($strPropInfo))
			{
				global $HTTP_POST_FILES;
				$arPICTURE=$HTTP_POST_FILES[$strPrefix.$ID."_PICTURE"];
				$arPICTURE["del"] = $_POST[$strPrefix.$ID."_PICTURE_del"];
				$arResult = array(
					'ID'=>(int)$ID,
					'NAME' => strval($_POST[$strPrefix.$ID."_NAME"]),
					'XML_ID' => strval($_POST[$strPrefix.$ID."_XML_ID"]),
					'SORT' => (0 < intval($_POST[$strPrefix.$ID."_SORT"]) ? intval($_POST[$strPrefix.$ID."_SORT"]) : 500),
					'SCORES' =>(int)$_POST[$strPrefix.$ID."_SCORES"],
					'ACTIVE' => (isset($_POST[$strPrefix.$ID."_ACTIVE"]) && 'Y' == $_POST[$strPrefix.$ID."_ACTIVE"] ? 'Y' : 'N'),
					'DESCRIPTION' => strval($_POST[$strPrefix.$ID."_DESCRIPTION"]),
					'DESCRIPTION_TYPE' => strval($_POST[$strPrefix.$ID."_DESCRIPTION_TYPE"]),
                    'CORRECT' => (isset($_POST[$strPrefix.$ID."_CORRECT"]) && 'Y' == $_POST[$strPrefix.$ID."_CORRECT"] ? 'Y' : 'N'),
					"PICTURE"=>$arPICTURE,
				);

				if(is_array($_POST[$strPrefix.$ID."_ACTIVE"]))
				{
					if($_POST[$strPrefix.$ID."_ACTIVE"][1]=="Y")
						$arResult["ACTIVE"]="Y";
				}
				
				if ($boolUnpack)
				{
					$arPropInfo = unserialize($strPropInfo);
					foreach ($arHiddenPropFields as &$strFieldKey)
					{
						$arResult[$strFieldKey] = (isset($arPropInfo[$strFieldKey]) ? $arPropInfo[$strFieldKey] : $arDefPropInfo[$strFieldKey]);
					}
				}
				else
				{
					$arResult['PROPINFO'] = $strEncodePropInfo;
				}
				if (0 < intval($ID))
				{
					$arResult['DEL'] = (isset($_POST[$strPrefix.$ID."_DEL"]) && ('Y' == $_POST[$strPrefix.$ID."_DEL"]) ? 'Y' : 'N');
				}
			}
		}

		return $arResult;
	}
	
	function ConvertToSafe($arProp,$arDisFields)
	{
		if (true == is_array($arProp))
		{
			foreach ($arProp as $key => $value)
			{
				if (false == in_array($key,$arDisFields))
				{
					if (false == is_array($value))
					{
						$arProp[$key] = $value;
					}
					else
					{
						$arTempo = array();
						foreach ($value as $subkey => $subvalue)
						{
							$arTempo[$subkey] = $subvalue;
						}
						$arProp[$key] = $arTempo;
					}
				}
			}
		}
		else
		{
			$arProp = $arProp;
		}
		return $arProp;
	}

	
	function AddPropRowResult($intOFPropID,$strPrefix,$arPropInfo)
	{
		$strResult='<tr class="row_list" id="'.$strPrefix.$intOFPropID.'">';
		$strResult.='<td style="text-align:center">'.self::AddPropCellID($intOFPropID,$strPrefix,$arPropInfo).'</td>';
		$strResult.='<td style="text-align:center">'.self::AddPropCellInt($intOFPropID,$strPrefix,$arPropInfo,"MIN_SCORES").'</td>';
		$strResult.='<td style="text-align:center">'.self::AddPropCellInt($intOFPropID,$strPrefix,$arPropInfo,"MAX_SCORES").'</td>';
		$strResult.='<td style="text-align:center">'.self::AddPropCellCheckbox($intOFPropID,$strPrefix,$arPropInfo,"ACTIVE").'</td>';
		$strResult.='<td style="text-align:center">'.self::AddPropCellInt($intOFPropID,$strPrefix,$arPropInfo,"SORT").'</td>';
		$strResult.='<td style="text-align:center">'.self::AddPropCellName($intOFPropID,$strPrefix,$arPropInfo).'</td>';
		if(self::GetShowXmlID()=="Y")
			$strResult.='<td style="text-align:center">'.self::AddPropCellXML_ID($intOFPropID,$strPrefix,$arPropInfo).'</td>';
		if(self::HideDescription()=="Y")
		{
			$strResult.='<td style="text-align:center">'.self::AddPropCellDetail($intOFPropID,$strPrefix,$arPropInfo,"RES").'</td>';
		}else{
			$strResult.='<td style="text-align:center">'.self::AddPropCellPicture($intOFPropID,$strPrefix,$arPropInfo).'</td>';
			$strResult.='<td style="text-align:center">'.self::AddPropCellDescriptionParam($intOFPropID,$strPrefix,$arPropInfo).'</td>';
		}
		$strResult.='<td style="text-align:center">'.self::AddPropCellDelete($intOFPropID,$strPrefix,$arPropInfo).'</td>';
		$strResult.='</tr>';
		return $strResult;
	}
	
	function InitCUtilResult()
	{
		$arNewPropInfo = self::GetDefaultResult();
		self::ConvProp($arNewPropInfo,self::GetHiddenResult());
		
		$arCellTemplates = array();
		$arCellTemplates[] = CUtil::JSEscape(self::AddPropCellID('tmp_xxx','PREFIX',$arNewPropInfo));
		$arCellTemplates[] = CUtil::JSEscape(self::AddPropCellInt('tmp_xxx','PREFIX',$arNewPropInfo,"MIN_SCORES"));
		$arCellTemplates[] = CUtil::JSEscape(self::AddPropCellInt('tmp_xxx','PREFIX',$arNewPropInfo,"MAX_SCORES"));
		$arCellTemplates[] = CUtil::JSEscape(self::AddPropCellCheckbox('tmp_xxx','PREFIX',$arNewPropInfo,"ACTIVE"));
		$arCellTemplates[] = CUtil::JSEscape(self::AddPropCellInt('tmp_xxx','PREFIX',$arNewPropInfo,"SORT"));
		$arCellTemplates[] = CUtil::JSEscape(self::AddPropCellName('tmp_xxx','PREFIX',$arNewPropInfo));
		if(self::GetShowXmlID()=="Y")
			$arCellTemplates[] = CUtil::JSEscape(self::AddPropCellXML_ID('tmp_xxx','PREFIX',$arNewPropInfo));
		if(self::HideDescription()=="Y")
		{
			$arCellTemplates[] = CUtil::JSEscape(self::AddPropCellDetail('tmp_xxx','PREFIX',$arNewPropInfo,"RES"));
		}else{
			$arCellTemplates[] = CUtil::JSEscape(self::AddPropCellPicture('tmp_xxx','PREFIX',$arPropInfo));
			$arCellTemplates[] = CUtil::JSEscape(self::AddPropCellDescriptionParam('tmp_xxx','PREFIX',$strPrefix,$arPropInfo));
		}
		$arCellTemplates[] = CUtil::JSEscape(self::AddPropCellDelete('tmp_xxx','PREFIX',$arNewPropInfo));

		return $arCellTemplates;
	}
	
	function AddPropRowQuestion($intOFPropID,$strPrefix,$arPropInfo)
	{
		$disabled=false;
		if($arPropInfo["TEST_TYPE"]!="input")
			$disabled=true;
		$count=9;
		if(self::GetShowXmlID()=="Y")
			$count++;
		if(self::HideDescription()!="Y")
			$count++;
        if(self::GetUseCorrect()!="Y")
            $count--;
		$strResult='<tr class="row_list" id="'.$strPrefix.$intOFPropID.'">';
		$strResult.='<td style="text-align:center">'.self::AddPropCellID($intOFPropID,$strPrefix,$arPropInfo).'</td>';
		$strResult.='<td style="text-align:center">'.self::AddPropCellType($intOFPropID,$strPrefix,$arPropInfo).'</td>';

		$strResult.='<td style="text-align:center">'.self::AddPropCellText($intOFPropID,$strPrefix,$arPropInfo,"CORRECT_ANSWER",$disabled).'</td>';
        if(self::GetUseCorrect()!="Y")
		    $strResult.='<td style="text-align:center">'.self::AddPropCellInt($intOFPropID,$strPrefix,$arPropInfo,"SCORES",$disabled).'</td>';
		$strResult.='<td style="text-align:center">'.self::AddPropCellCheckbox($intOFPropID,$strPrefix,$arPropInfo,"ACTIVE").'</td>';
		$strResult.='<td style="text-align:center">'.self::AddPropCellInt($intOFPropID,$strPrefix,$arPropInfo,"SORT").'</td>';
		$strResult.='<td style="text-align:center">'.self::AddPropCellName($intOFPropID,$strPrefix,$arPropInfo).'</td>';
		if(self::GetShowXmlID()=="Y")
			$strResult.='<td style="text-align:center">'.self::AddPropCellXML_ID($intOFPropID,$strPrefix,$arPropInfo).'</td>';

		if(self::HideDescription()=="Y")
		{
			$strResult.='<td style="text-align:center">'.self::AddPropCellDetail($intOFPropID,$strPrefix,$arPropInfo,"RES").'</td>';
		}else{
			$strResult.='<td style="text-align:center">'.self::AddPropCellPicture($intOFPropID,$strPrefix,$arPropInfo).'</td>';
			$strResult.='<td style="text-align:center">'.self::AddPropCellDescriptionParam($intOFPropID,$strPrefix,$arPropInfo).'</td>';
		}
		
		$strResult.='<td style="text-align:center">'.self::AddPropCellDelete($intOFPropID,$strPrefix,$arPropInfo).'</td>';
		$strResult.='</tr>';
		$strResult.='<tr id="'.$strPrefix.$intOFPropID.'_ANSWER">';
		$strResult.='<td colspan="'.$count.'">';
		$strResult.=self::AddTableAnswer($intOFPropID,$strPrefix,$arPropInfo,true,$disabled);
		$strResult.='</td>';
		$strResult.='</tr>';
		return $strResult;
	}
	
	function InitCUtilQuestion()
	{
		$arNewPropInfo = self::GetDefaultQuestion();
		self::ConvProp($arNewPropInfo,self::GetHiddenQuestion());
		
		$arCellTemplates = array();
		$arCellTemplates[] = CUtil::JSEscape(self::AddPropCellID('tmp_xxx','PREFIX',$arNewPropInfo));
		$arCellTemplates[] = CUtil::JSEscape(self::AddPropCellType('tmp_xxx','PREFIX',$arNewPropInfo));

		$arCellTemplates[] = CUtil::JSEscape(self::AddPropCellText('tmp_xxx','PREFIX',$arNewPropInfo,"CORRECT_ANSWER",true));
        if(self::GetUseCorrect()!="Y")
		    $arCellTemplates[] = CUtil::JSEscape(self::AddPropCellInt('tmp_xxx','PREFIX',$arNewPropInfo,"SCORES",true));
		$arCellTemplates[] = CUtil::JSEscape(self::AddPropCellCheckbox('tmp_xxx','PREFIX',$arNewPropInfo,"ACTIVE"));
		$arCellTemplates[] = CUtil::JSEscape(self::AddPropCellInt('tmp_xxx','PREFIX',$arNewPropInfo,"SORT"));
		$arCellTemplates[] = CUtil::JSEscape(self::AddPropCellName('tmp_xxx','PREFIX',$arNewPropInfo));
		if(self::GetShowXmlID()=="Y")
			$arCellTemplates[] = CUtil::JSEscape(self::AddPropCellXML_ID('tmp_xxx','PREFIX',$arNewPropInfo));
		if(self::HideDescription()=="Y")
		{
			$arCellTemplates[] = CUtil::JSEscape(self::AddPropCellDetail('tmp_xxx','PREFIX',$arNewPropInfo,"RES"));
		}else{
			$arCellTemplates[] = CUtil::JSEscape(self::AddPropCellPicture('tmp_xxx','PREFIX',$arPropInfo));
			$arCellTemplates[] = CUtil::JSEscape(self::AddPropCellDescriptionParam('tmp_xxx','PREFIX',$arPropInfo));
		}
		$arCellTemplates[] = CUtil::JSEscape(self::AddPropCellDelete('tmp_xxx','PREFIX',$arNewPropInfo));

		return $arCellTemplates;
	}
	
	function AddPropRowAnswer($intOFPropID,$strPrefix,$arPropInfo,$disabled=false)
	{
		$disabled=!$disabled;
		$strResult='<tr class="row_list" id="'.$strPrefix.$intOFPropID.'">';

		$strResult.='<td style="text-align:center">'.self::AddPropCellID($intOFPropID,$strPrefix,$arPropInfo).'</td>';
        if(self::GetUseCorrect()!="Y")
		    $strResult.='<td style="text-align:center">'.self::AddPropCellInt($intOFPropID,$strPrefix,$arPropInfo,"SCORES",$disabled).'</td>';
		$strResult.='<td style="text-align:center">'.self::AddPropCellCheckbox($intOFPropID,$strPrefix,$arPropInfo,"ACTIVE").'</td>';

        if(self::GetUseCorrect()=="Y")
            $strResult.='<td style="text-align:center">'.self::AddPropCellCheckbox($intOFPropID,$strPrefix,$arPropInfo,"CORRECT").'</td>';

		$strResult.='<td style="text-align:center">'.self::AddPropCellInt($intOFPropID,$strPrefix,$arPropInfo,"SORT").'</td>';
		$strResult.='<td style="text-align:center">'.self::AddPropCellName($intOFPropID,$strPrefix,$arPropInfo).'</td>';
		if(self::GetShowXmlID()=="Y")
			$strResult.='<td style="text-align:center">'.self::AddPropCellXML_ID($intOFPropID,$strPrefix,$arPropInfo).'</td>';

		if(self::HideDescription()=="Y")
		{
			$strResult.='<td style="text-align:center">'.self::AddPropCellDetail($intOFPropID,$strPrefix,$arPropInfo,"RES").'</td>';
		}else{
			$strResult.='<td style="text-align:center">'.self::AddPropCellPicture($intOFPropID,$strPrefix,$arPropInfo).'</td>';
			$strResult.='<td style="text-align:center">'.self::AddPropCellDescriptionParam($intOFPropID,$strPrefix,$arPropInfo).'</td>';
		}
		$strResult.='<td style="text-align:center">'.self::AddPropCellDelete($intOFPropID,$strPrefix,$arPropInfo).'</td>';
		$strResult.='</tr>';
		return $strResult;
	}
	
	function InitCUtilAnswer()
	{
		$arNewPropInfo = self::GetDefaultAnswer();
		self::ConvProp($arNewPropInfo,self::GetHiddenAnswer());
		
		$arCellTemplates = array();
		$arCellTemplates[] = CUtil::JSEscape(self::AddPropCellID('tmp_xxx','PREFIX',$arNewPropInfo));
        if(self::GetUseCorrect()!="Y")
		    $arCellTemplates[] = CUtil::JSEscape(self::AddPropCellInt('tmp_xxx','PREFIX',$arNewPropInfo,"SCORES"));
		$arCellTemplates[] = CUtil::JSEscape(self::AddPropCellCheckbox('tmp_xxx','PREFIX',$arNewPropInfo,"ACTIVE"));

        if(self::GetUseCorrect()=="Y")
            $arCellTemplates[] = CUtil::JSEscape(self::AddPropCellCheckbox('tmp_xxx','PREFIX',$arNewPropInfo,"CORRECT"));

		$arCellTemplates[] = CUtil::JSEscape(self::AddPropCellInt('tmp_xxx','PREFIX',$arNewPropInfo,"SORT"));
		$arCellTemplates[] = CUtil::JSEscape(self::AddPropCellName('tmp_xxx','PREFIX',$arNewPropInfo));
		if(self::GetShowXmlID()=="Y")
			$arCellTemplates[] = CUtil::JSEscape(self::AddPropCellXML_ID('tmp_xxx','PREFIX',$arNewPropInfo));
		if(self::HideDescription()=="Y")
		{
			$arCellTemplates[] = CUtil::JSEscape(self::AddPropCellDetail('tmp_xxx','PREFIX',$arNewPropInfo,"RES"));
		}else{
			$arCellTemplates[] = CUtil::JSEscape(self::AddPropCellPicture('tmp_xxx','PREFIX',$arPropInfo));
			$arCellTemplates[] = CUtil::JSEscape(self::AddPropCellDescriptionParam('tmp_xxx','PREFIX',$arPropInfo));
		}
		$arCellTemplates[] = CUtil::JSEscape(self::AddPropCellDelete('tmp_xxx','PREFIX',$arNewPropInfo));

		return $arCellTemplates;
	}
	
	function InitCUtilTableAnswer()
	{
		$arNewPropInfo = self::GetDefaultAnswer();
		self::ConvProp($arNewPropInfo,self::GetHiddenAnswer());
		$strResult=CUtil::JSEscape(self::AddTableAnswer('table_xxx','PR_XXXX',$arNewPropInfo,false,true));
		return $strResult;
	}
	
	function InitCUtilScriptAnswer()
	{
		$arNewPropInfo = self::GetDefaultAnswer();
		self::ConvProp($arNewPropInfo,self::GetHiddenAnswer());
		$strResult=CUtil::JSEscape(self::AddTableScriptAnswer('table_xxx','PR_XXXX',$arNewPropInfo));
		return $strResult;
	}
	
	function AddTableScriptAnswer($intOFPropID,$strPrefix,$arPropInfo)
	{
		$strResult="";
		$strQUESTION_Result=$strPrefix.$intOFPropID."_";
		
		$arCellTemplatesAnswer=AelitaTestEditTool::InitCUtilAnswer();
		$arCellAttrAnswer = array();
		
		$cell=-1;
		$SendEval="Y";
		if(self::HideDescription()=="Y"){
			$cell=5;
			$SendEval="N";
			if(self::GetShowXmlID()=="Y")
				$cell++;
            //if(self::GetUseCorrect()=="Y")
            //    $cell++;
		}

			$strPropInfo = base64_decode($arPropInfo["PROPINFO"]);
			if (CheckSerializedData($strPropInfo))
				$hidePropInfo = unserialize($strPropInfo);
				
			if(!$hidePropInfo['TEST_ID'])
				$hidePropInfo['TEST_ID']=0;
		ob_start();?>
		
			<script type="text/javascript">
			var <?=$strQUESTION_Result?>CellTPL = new Array();
			<?foreach ($arCellTemplatesAnswer as $key => $value){?><?=$strQUESTION_Result?>CellTPL[<? echo $key; ?>] = '<? echo $value; ?>';<?}?>
			var <?=$strQUESTION_Result?>CellAttr = new Array();
			<?foreach ($arCellAttrAnswer as $key => $value){?><?=$strQUESTION_Result?>CellAttr[<? echo $key; ?>] = '<? echo $value; ?>';<?}?>
			</script>
		
			<script type="text/javascript">
			
			var <?=$strQUESTION_Result?>obQuestionProps = new JAelitaTestResultProperty({
				'PREFIX': '<?echo $strQUESTION_Result?>',
				'FORM_ID': 'frmtest',
				'TABLE_PROP_ID': '<?echo $strPrefix.$intOFPropID?>_ANSWER_LIST',
				'PROP_COUNT_ID': '<?echo $strPrefix.$intOFPropID?>_INT_ANSWER_COUNT',
				'TEST_ID': <? echo $hidePropInfo['TEST_ID']; ?>,
				'LANG': '<? echo LANGUAGE_ID; ?>',
				'TITLE': '<? echo CUtil::JSEscape(GetMessage('AT_EDIT_TITLE')); ?>',
				'BUTTON_SAVE':'<?echo GetMessage("BUTTON_SAVE"); ?>',
				'BUTTON_CLOSE':'<?echo GetMessage("BUTTON_CLOSE"); ?>',
				'SENDEVAL':'<?=$SendEval?>',
				'OBJ': '<?=$strQUESTION_Result?>obResultProps'
			});
			<?=$strQUESTION_Result?>obQuestionProps.SetCells(<?=$strQUESTION_Result?>CellTPL,<?=$cell?>,<?=$strQUESTION_Result?>CellAttr);
			</script>
		<?
		$strResult = ob_get_contents();
		ob_end_clean();
		return $strResult;
	}
	
	function AddTableAnswer($intOFPropID,$strPrefix,$arPropInfo,$script=true,$disabled=false)
	{
		$strResult="";
		$strQUESTION_Result=$strPrefix.$intOFPropID."_";



		
		$cell=-1;
		$SendEval="Y";
		if(self::HideDescription()=="Y"){
			$cell=5;
			$SendEval="N";
			if(self::GetShowXmlID()=="Y")
				$cell++;
		}
		
		ob_start();
		
		if($script==true)
		{
			echo self::AddTableScriptAnswer($intOFPropID,$strPrefix,$arPropInfo);
		}

		?>
			<div class="answer_list">
				<div id="<?echo $strPrefix.$intOFPropID?>_wrapper_script"></div>
				<div class="answer_header">
					<?echo GetMessage("AT_ANSWER_HEADER"); ?>
				</div>
				<div class="answer_table">
					<table border="0" cellspacing="0" cellpadding="0" class="internal" align="center" id="<?echo $strPrefix.$intOFPropID?>_ANSWER_LIST">
					<tr class="heading">
						<td>ID</td>
                        <?if(self::GetUseCorrect()!="Y"):?>
						<td><?echo GetMessage("AT_SCORES"); ?></td>
                        <?endif;?>
						<td><?echo GetMessage("AT_ACTIVE"); ?></td>
                        <?if(self::GetUseCorrect()=="Y"):?>
                            <td><?echo GetMessage("AT_CORRECT"); ?></td>
                        <?endif;?>
						<td><?echo GetMessage("AT_SORT"); ?></td>
						<td><?echo GetMessage("AT_NAME"); ?></td>
						<?if(self::GetShowXmlID()=="Y"):?>
						<td><?echo GetMessage("AT_XML_ID"); ?></td>
						<?endif;?>
						<?if(self::HideDescription()=="Y"):?>
						<td><?echo GetMessage("AT_DESCRIPTION"); ?></td>
						<?else:?>
						<td><?echo GetMessage("AT_PICTURE"); ?></td>
						<td><?echo GetMessage("AT_DESCRIPTION"); ?></td>
						<?endif;?>
						<td><?echo GetMessage("AT_DELETE"); ?></td>
					</tr>
					<?
					$arPropList = array();
					
					if (0 < $intOFPropID)
					{
						$el=new AelitaTestAnswer();
						$rsProps= $el->GetList(array("SORT"=>"ASC",'ID' => 'ASC'), array("QUESTION_ID" => $intOFPropID));
						while ($arProp = $rsProps->GetNext())
						{
							self::ConvProp($arProp,self::GetHiddenAnswer());
							$arProp = self::ConvertToSafe($arProp,self::GetDisabledAnswer());
							$arProp['SHOW_DEL'] = 'Y';
							$arPropList[$arProp['ID']] = $arProp;
						}
					}
					
					$intAnswerCount = intval($_POST[$strPrefix.$intOFPropID."_ANSWER_COUNT"]);
					if (0 >= $intAnswerCount)
						$intAnswerCount = PROPERTY_EMPTY_ROW_SIZE;
					$intPropNumber = 0;
					
					for ($i = 0; $i < $intAnswerCount; $i++)
					{
						$arProp = self::GetPropertyInfoAnswer($strQUESTION_Result, 'n'.$i, false, self::GetHiddenAnswer());
						if (true == is_array($arProp))
						{
							$arProp = self::ConvertToSafe($arProp,self::GetDisabledAnswer());
							$arProp['ID'] = 'n'.$intPropNumber;
							$arPropList['n'.$intPropNumber] = $arProp;
							$intPropNumber++;
						}
					}
				
					for (0; $intPropNumber < PROPERTY_EMPTY_ROW_SIZE; $intPropNumber++)
					{
						$arProp = self::GetDefaultAnswer();
						$arProp['QUESTION_ID'] = $intOFPropID;
						self::ConvProp($arProp,self::GetHiddenAnswer());
						$arProp['ID'] = 'n'.$intPropNumber;
						$arPropList['n'.$intPropNumber] = $arProp;
					}
					
					foreach ($arPropList as $mxPropID => $arProp)
					{
						echo self::AddPropRowAnswer($mxPropID,$strQUESTION_Result,$arProp,$disabled);
					}
					?>
				</table>
				<br />
				<div style="width: 100%; text-align: center;">
					<input class="add_ansver" id="<?echo $strPrefix.$intOFPropID?>_add_script" onclick="<?=$strQUESTION_Result?>obQuestionProps.addPropRow();" type="button" value="<? echo GetMessage('AT_SHOW_ADD_PROP_ROW')?>" title="<? echo GetMessage('AT_SHOW_ADD_PROP_ROW_DESCR')?>">
				</div>
				<input type="hidden" name="<?echo $strPrefix.$intOFPropID?>_ANSWER_COUNT" id="<?echo $strPrefix.$intOFPropID?>_INT_ANSWER_COUNT" value="<? echo $intPropNumber; ?>">
			</div>
		</div><?
		$strResult = ob_get_contents();
		ob_end_clean();
		return $strResult;
	}
	
	function AddPropJSResult($intOFPropID,$strPrefix,$arPropInfo)
	{
		
	}
	
	function AddPropCellID($intOFPropID,$strPrefix,$arPropInfo)
	{
		return (0 < intval($intOFPropID) ? $intOFPropID : '&nbsp;');
	}
	
	function AddPropCellName($intOFPropID,$strPrefix,$arPropInfo)
	{
		$strResult = '';
		ob_start();
		?><input type="text" size="20"  maxlength="255" name="<?echo $strPrefix.$intOFPropID?>_NAME" id="<?echo $strPrefix.$intOFPropID?>_NAME" value="<?echo $arPropInfo['NAME']?>"><?
		?><input type="hidden" name="<? echo $strPrefix.$intOFPropID?>_PROPINFO" id="<? echo $strPrefix.$intOFPropID?>_PROPINFO" value="<? echo $arPropInfo['PROPINFO']; ?>"><?
		if(self::HideDescription()=="Y")
		{
			?><div style="display:none" id="<?=$strPrefix.$intOFPropID?>_NONE_WRAPPER"><div id="<?=$strPrefix.$intOFPropID?>_NONE"><?
				echo self::AddPropCellDescription($intOFPropID,$strPrefix,$arPropInfo);
			?></div></div><?
		}
		$strResult = ob_get_contents();
		ob_end_clean();
		return $strResult;
	}
	
	function AddPropCellDescription($intOFPropID,$strPrefix,$arPropInfo)
	{

		$strResult = '';
		ob_start();?>
		<table width="100%">
			<tr>
				<td class="adm-detail-valign-top"><?echo GetMessage("AT_PICTURE")?></td>
				<td>
					<?=self::AddPropCellPicture($intOFPropID,$strPrefix,$arPropInfo)?>
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center">
					<?=self::AddPropCellDescriptionParam($intOFPropID,$strPrefix,$arPropInfo,60,15);?>
				</td>
			</tr>

		</table>
		<?
		$strResult = ob_get_contents();
		ob_end_clean();
		return $strResult;
	}
	
	function AddPropCellDescriptionParam($intOFPropID,$strPrefix,$arPropInfo,$cols=30,$rows=10)
	{

		$strResult = '';
		ob_start();?>
			<input type="hidden" name="<?echo $strPrefix.$intOFPropID?>_DESCRIPTION_TYPE" id="<?echo $strPrefix.$intOFPropID?>_DESCRIPTION_TYPE2" value="html">
			<textarea cols="<?=$cols?>" rows="<?=$rows?>" name="<?echo $strPrefix.$intOFPropID?>_DESCRIPTION"><?echo $arPropInfo['DESCRIPTION']?></textarea>
		<?$strResult = ob_get_contents();
		ob_end_clean();
		return $strResult;
	}
	
	function AddPropCellPicture($intOFPropID,$strPrefix,$arPropInfo)
	{

		$strResult = '';
		ob_start();?>
		<?CModule::IncludeModule("fileman");?>
			<div style="padding: 0px 0px;width: 210px;">
			<?echo CFileInput::Show($strPrefix.$intOFPropID."_PICTURE", $arPropInfo["PICTURE"], array(
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
			</div>
		<?$strResult = ob_get_contents();
		ob_end_clean();
		return $strResult;
	}
	
	function AddPropCellType($intOFPropID,$strPrefix,$arPropInfo)
	{
		$strResult = '';
		ob_start();
		?><select class="test_type" name="<?echo $strPrefix.$intOFPropID?>_TEST_TYPE" id="<?echo $strPrefix.$intOFPropID?>_TEST_TYPE">
			<option value="radio" <?if($arPropInfo['TEST_TYPE']=="radio") echo " selected"?>><?echo GetMessage("AT_TEST_TYPE_RADIO")?></option>
			<option value="check" <?if($arPropInfo['TEST_TYPE']=="check") echo " selected"?>><?echo GetMessage("AT_TEST_TYPE_CHECK")?></option>
			<option value="input" <?if($arPropInfo['TEST_TYPE']=="input") echo " selected"?>><?echo GetMessage("AT_TEST_TYPE_INPUT")?></option>
		</select><?
		$strResult = ob_get_contents();
		ob_end_clean();
		return $strResult;
	}
	
	function AddPropCellXML_ID($intOFPropID,$strPrefix,$arPropInfo)
	{
		$strResult = '';
		ob_start();
		?><input type="text" size="15" maxlength="20"  name="<?echo $strPrefix.$intOFPropID?>_XML_ID" id="<?echo $strPrefix.$intOFPropID?>_XML_ID" value="<?echo $arPropInfo['XML_ID']?>"><?
		$strResult = ob_get_contents();
		ob_end_clean();
		return $strResult;
	}
	
	function AddPropCellText($intOFPropID,$strPrefix,$arPropInfo,$Name,$disabled=false)
	{
		//CORRECT_ANSWER
		$strResult = '';
		ob_start();
		?><input <?if($disabled){?>disabled="disabled"<?}?> type="text" size="15" maxlength="20"  name="<?echo $strPrefix.$intOFPropID?>_<?=$Name?>" id="<?echo $strPrefix.$intOFPropID?>_<?=$Name?>" value="<?echo $arPropInfo[$Name]?>" class="<?=$Name?>"><?
		$strResult = ob_get_contents();
		ob_end_clean();
		return $strResult;
	}
	
	
	function AddPropCellInt($intOFPropID,$strPrefix,$arPropInfo,$Name,$disabled=false)
	{
		$strResult = '';
		ob_start();
		?><input <?if($disabled){?>disabled="disabled"<?}?> type="text" size="3" maxlength="10"  name="<?echo $strPrefix.$intOFPropID?>_<?=$Name?>" id="<?echo $strPrefix.$intOFPropID?>_<?=$Name?>" value="<?echo $arPropInfo[$Name]?>" class="<?=$Name?>"><?
		$strResult = ob_get_contents();
		ob_end_clean();
		return $strResult;
	}

	function AddPropCellCheckbox($intOFPropID,$strPrefix,$arPropInfo,$Name,$admstyle=false)
	{
		$strResult = '';
		ob_start();
		?><input type="hidden" name="<?echo $strPrefix.$intOFPropID?>_<?=$Name?>" id="<?echo $strPrefix.$intOFPropID?>_<?=$Name?>_N" value="N">
		<input type="checkbox" name="<?echo $strPrefix.$intOFPropID?>_<?=$Name?>" id="<?echo $strPrefix.$intOFPropID?>_<?=$Name?>_Y" value="Y"<?if ($arPropInfo[$Name]=="Y") echo " checked"; ?>>
		<?
		$strResult = ob_get_contents();
		ob_end_clean();
		return $strResult;
	}
	
	function AddPropCellDelete($intOFPropID,$strPrefix,$arPropInfo)
	{
		$strResult = '&nbsp;';
		if ((true == isset($arPropInfo['SHOW_DEL'])) && ('Y' == $arPropInfo['SHOW_DEL']))
			$strResult = '<input type="checkbox" name="'.$strPrefix.$intOFPropID.'_DEL" id="'.$strPrefix.$intOFPropID.'_DEL" value="Y">';
		return $strResult;
	}
	
	function AddPropCellDetail($intOFPropID,$strPrefix,$arPropInfo,$Name)
	{
		return '<input class="celldetail" type="button" title="'.GetMessage("AT_EDIT_TITLE").'" name="'.$strPrefix.$intOFPropID.'_'.$Name.'" id="'.$strPrefix.$intOFPropID.'_'.$Name.'" value="'.GetMessage("AT_EDIT_TITLE").'">';
	}
	
}


?>