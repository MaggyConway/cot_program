<?
IncludeModuleLangFile(__FILE__);
class AelitaTestTools
{

    function GetWatermarkPicture($PictureID,$Alt="")
    {
        $module_id="aelita.test" ;

        $fields_use_watermark_file=COption::GetOptionString($module_id, "fields_use_watermark_file","N");
        $fields_watermark_file=COption::GetOptionString($module_id, "fields_watermark_file","");
        $fields_watermark_file_alpha=COption::GetOptionString($module_id, "fields_watermark_file_alpha","");
        $fields_watermark_file_position=COption::GetOptionString($module_id, "fields_watermark_file_position","");


        $fields_use_watermark_text=COption::GetOptionString($module_id, "fields_use_watermark_text","N");
        $fields_watermark_text=COption::GetOptionString($module_id, "fields_watermark_text","");
        $fields_watermark_text_font=COption::GetOptionString($module_id, "fields_watermark_text_font","");
        $fields_watermark_text_color=COption::GetOptionString($module_id, "fields_watermark_text_color","");
        $fields_watermark_text_size=COption::GetOptionString($module_id, "fields_watermark_text_size","");
        $fields_watermark_text_position=COption::GetOptionString($module_id, "fields_watermark_text_position","");

        $Watermark=array();

        if($fields_use_watermark_file==='Y')
        {
            $itemWatermark=array();
            $itemWatermark['type']='image';
            $itemWatermark['coefficient']=1;
            if(strlen($fields_watermark_file)>0)
                $itemWatermark['file']=$_SERVER['DOCUMENT_ROOT'].$fields_watermark_file;
            if(strlen($fields_watermark_file_alpha)>0)
                $itemWatermark['alpha_level']=$fields_watermark_file_alpha;
            if(strlen($fields_watermark_file_position)>0)
                $itemWatermark['position']=$fields_watermark_file_position;
            $Watermark[]=$Watermark;
        }

        if($fields_use_watermark_text==='Y')
        {
            $itemWatermark=array();
            $itemWatermark['type']='text';
            $itemWatermark['coefficient']=1;
            $itemWatermark['alpha_level']=100;
            if(strlen($fields_watermark_text_font)>0)
                $itemWatermark['font']=$_SERVER['DOCUMENT_ROOT'].$fields_watermark_text_font;
            if(strlen($fields_watermark_text_position)>0)
                  $itemWatermark['position']=$fields_watermark_text_position;
            if(strlen($fields_watermark_text_color)>0)
                $itemWatermark['color']=$fields_watermark_text_color;
            if(strlen($fields_watermark_text_size)>0)
                $itemWatermark['size']=$fields_watermark_text_size;
            if(strlen($fields_watermark_text)>0)
                $itemWatermark['text']=$fields_watermark_text;
            $Watermark[]=$Watermark;
        }

        $Picture=CFile::GetFileArray($PictureID);

        if(count($Watermark)>0)
        {
            $arFileTmp = CFile::ResizeImageGet(
                $PictureID,
                array("width" => $Picture["WIDTH"], "height" => $Picture["HEIGHT"]),
                BX_RESIZE_IMAGE_EXACT,
                true,$Watermark
            );

            $Picture["SRC"]=$arFileTmp["src"];
            $Picture["WIDTH"]=$arFileTmp["width"];
            $Picture["HEIGHT"]=$arFileTmp["height"];

        }
        $Picture["ALT"]=$Alt;
        return $Picture;
    }

    function GetWatermarkPositions()
    {
        $rs = new CDBResult;
        $rs->InitFromArray(array(
            array("reference_id" => "tl", "reference" => GetMessage("WATERMARK_POSITION_TL")),
            array("reference_id" => "tc", "reference" => GetMessage("WATERMARK_POSITION_TC")),
            array("reference_id" => "tr", "reference" => GetMessage("WATERMARK_POSITION_TR")),
            array("reference_id" => "ml", "reference" => GetMessage("WATERMARK_POSITION_ML")),
            array("reference_id" => "mc", "reference" => GetMessage("WATERMARK_POSITION_MC")),
            array("reference_id" => "mr", "reference" => GetMessage("WATERMARK_POSITION_MR")),
            array("reference_id" => "bl", "reference" => GetMessage("WATERMARK_POSITION_BL")),
            array("reference_id" => "bc", "reference" => GetMessage("WATERMARK_POSITION_BC")),
            array("reference_id" => "br", "reference" => GetMessage("WATERMARK_POSITION_BR")),
        ));
        return $rs;
    }

    function encodeURI($uri)
    {
        return preg_replace_callback("{[^0-9a-z_.!~*'();,/?:@&=+$#-]}i", function ($m) {
            return sprintf('%%%02X', ord($m[0]));
        }, $uri);
    }

    function MultiCheckResponse(&$arResult,&$arrClearParam,$back=false,$redir=false)
    {
        global $APPLICATION;

        $arResult["ERROR_Q"]=array();

        $Question=array();
        $ArrQuestionID=$_REQUEST["questionid"];
        if(is_array($ArrQuestionID) && count($ArrQuestionID)>0)
        {
            foreach($ArrQuestionID as $QuestionId)
            {
                $QuestionId=(int)$QuestionId;
                if($QuestionId>0)
                    $Question[]=AelitaTestTools::CheckResponse($QuestionId,$arResult,$arrClearParam,false,false,false);
            }
        }






        if(count($arResult["ERROR_Q"])<=0)
        {
            $arResult["NEXT_LIST"]=0;
            $arResult["STEP"]=AelitaTestTools::GetStepQuestion($arResult["QUESTIONING"],$arResult["TEST"]);
            $arResult["STEP"]["GLASSES"]=AelitaTestTools::GetStepList($arResult["STEP"]["GLASSES_LIST"],$Question,$arResult["QUESTIONING"]);

            if($arResult["ALLOW_PASSED_BACK"]=="Y")
            {
                if($back)
                    AelitaTestTools::SetQuestion($arResult["QUESTIONING"]["ID"],$arResult["TEST"]["ID"],(int)$_REQUEST["setprevtest"]);
                elseif(isset($_REQUEST["testsubmit"]))
                {

                    if(count($arResult["STEP"]["STEP_GLASSES_LIST"])>0)
                    {
                        $arResult["NEXT_LIST"]=$arResult["STEP"]["STEP_GLASSES_LIST"][1]["ID"];
                        for($i=1;$i<count($arResult["STEP"]["STEP_GLASSES_LIST"]);$i++)
                        {
                            if($arResult["STEP"]["STEP_GLASSES_LIST"][$i-1]["ID"]==$arResult["QUESTIONING"]["GLASSES_ID"])
                            {
                                $arResult["NEXT_LIST"]=$arResult["STEP"]["STEP_GLASSES_LIST"][$i]["ID"];
                                //break;
                            }
                        }

                        AelitaTestTools::SetQuestion($arResult["QUESTIONING"]["ID"],$arResult["TEST"]["ID"],$arResult["NEXT_LIST"]);
                    }

                }else
                    AelitaTestTools::SetQuestion($arResult["QUESTIONING"]["ID"],$arResult["TEST"]["ID"],$arResult["QUESTIONING"]["GLASSES_ID"]);
            }else{
                if(isset($_REQUEST["testsubmit"]))
                {
                    if(count($arResult["STEP"]["STEP_GLASSES_LIST"])>0)
                    {
                        $arResult["NEXT_LIST"]=$arResult["STEP"]["STEP_GLASSES_LIST"][1]["ID"];
                        for($i=1;$i<count($arResult["STEP"]["STEP_GLASSES_LIST"]);$i++)
                        {
                            if($arResult["STEP"]["STEP_GLASSES_LIST"][$i-1]["ID"]==$arResult["QUESTIONING"]["GLASSES_ID"])
                            {
                                $arResult["NEXT_LIST"]=$arResult["STEP"]["STEP_GLASSES_LIST"][$i]["ID"];
                                //break;
                            }
                        }
                        AelitaTestTools::SetQuestion($arResult["QUESTIONING"]["ID"],$arResult["TEST"]["ID"],$arResult["NEXT_LIST"]);
                    }

                }else
                    AelitaTestTools::SetQuestion($arResult["QUESTIONING"]["ID"],$arResult["TEST"]["ID"],$arResult["QUESTIONING"]["GLASSES_ID"]);
            }

            if($arResult["QUESTIONING"]["GLASSES_ID"]<=0)
                $arResult["QUESTIONING"]["GLASSES_ID"]=$arResult["STEP"]["STEP_GLASSES_LIST"][0]["ID"];

            $g=new AelitaTestStep();
            $Fields = array(
                "OTV"=>"Y",
            );
            $g->Update($arResult["QUESTIONING"]["GLASSES_ID"],$Fields);

			if($_REQUEST["testlast"]=="Y" && !isset($_REQUEST["prevtest"]))
			{
				AelitaTestTools::InitResult($arResult["QUESTIONING"]);
				//if($arResult["TEST"]["AUTO_START_OVER"]=="Y")
				//	AelitaTestTools::CloseQuestioning($arResult["PROFAIL_ID"]["ID"],$arResult["TEST"]["ID"]);
			}

            if($redir)
                LocalRedirect($APPLICATION->GetCurPageParam("",$arrClearParam));
        }


    }

	function CheckResponse($questionid,&$arResult,&$arrClearParam,$back=false,$redir=true,$single=true)
	{

		global $APPLICATION;

        $UseCorrect=AelitaTestEditToolEx::GetUseCorrect($arResult["TEST"]["ID"]);

		$el=new AelitaTestQuestion();
		$resQuestion=$el->GetByID($questionid);
		if($Question=$resQuestion->GetNext())
		{
			$Filter = array(
					"QUESTIONING_ID"=>$arResult["QUESTIONING"]["ID"],
					"QUESTION_ID"=>$Question["ID"],
					"OTV"=>"Y",
			);
			$arSort=array("ID"=>"ASC");
			$gel=new AelitaTestGlasses();
			$gres=$gel->GetList($arSort,$Filter,false,array("nPageSize"=>1));
			if($garr=$gres->GetNext() && $arResult["ALLOW_PASSED_BACK"]!="Y")
			{

				unset($Question);
			}else{

				$Scores=0;
				$SerializedResult=array();
                $SerializedResultText=array();
                $answer=false;
 				switch($Question["TEST_TYPE"]){

					case "input":
                        if($arResult["QUESTIONING"]["STEP_MULTIPLE"]=="step")
                            $answer=trim($_REQUEST["answer"][$questionid]);
                        else
						    $answer=trim($_REQUEST["answer"]);
						if(strlen($answer)>0)
						{
							$SerializedResult[]=$answer;
							if($answer==$Question["CORRECT_ANSWER"])
                            {
                                if ($UseCorrect){
                                    $Scores=$Scores+1;
                                }else{
                                    $Scores=$Scores+$Question["SCORES"];
                                }
                            }

						}else {
                            if($arResult["QUESTIONING"]["STEP_MULTIPLE"]=="step")
                                $arResult["ERROR_Q"][$questionid][] = GetMessage("ERR_NO_ANSWER");
                            else
                                $arResult["ERROR"][] = GetMessage("ERR_NO_ANSWER");
                        }
						break;
					case "check":
                        if($arResult["QUESTIONING"]["STEP_MULTIPLE"]=="step")
						    $answer=$_REQUEST["answer"][$questionid];
                        else
                            $answer=$_REQUEST["answer"];


						if(is_array($answer))
						{
							$arrAnswer=array();
							foreach($answer as $value)
							{
								$value=(int)$value;
								if($value>0)
									$arrAnswer[]=$value;
							}

							if(count($arrAnswer)>0)
							{
                                $CorrectAnswer=array();
                                $CorrectCount=0;
                                $a=new AelitaTestAnswer();
                                if ($UseCorrect){
                                    $resAns=$a->GetList(
                                        array("ID"=>"ASC"),
                                        array(
                                            "QUESTION_ID"=>$Question["ID"],
                                            "ACTIVE"=>"Y",
                                            "CORRECT"=>"Y",
                                        ),
                                        false);
                                    while($arrAns=$resAns->GetNext())
                                        $CorrectAnswer[]=$arrAns["ID"];

                                }

                                foreach($arrAnswer as $value)
                                {
                                    $resAnswer=$a->GetByID($value);
                                    if($Answer=$resAnswer->GetNext())
                                    {
                                        if ($UseCorrect){
                                            if(in_array($Answer["ID"],$CorrectAnswer))
                                                $CorrectCount++;
                                        }else{
                                            $Scores=$Scores+$Answer["SCORES"];
                                        }

                                        $SerializedResult[]=$Answer["NAME"];
                                        $SerializedResultText[]=$Answer["DESCRIPTION"];
                                    }
                                }

                                if ($UseCorrect){
                                    if(count($CorrectAnswer)==$CorrectCount && count($CorrectAnswer)>0)
                                        $Scores=$Scores+1;
                                }




							}else{
                                if($arResult["QUESTIONING"]["STEP_MULTIPLE"]=="step")
                                    $arResult["ERROR_Q"][$questionid][] = GetMessage("ERR_NO_ANSWER");
                                else
                                    $arResult["ERROR"][] = GetMessage("ERR_NO_ANSWER");
                            }
						}else{
                            if($arResult["QUESTIONING"]["STEP_MULTIPLE"]=="step")
                                $arResult["ERROR_Q"][$questionid][] = GetMessage("ERR_NO_ANSWER");
                            else
                                $arResult["ERROR"][] = GetMessage("ERR_NO_ANSWER");
                        }
						break;
					case "radio":
                        if($arResult["QUESTIONING"]["STEP_MULTIPLE"]=="step")
						    $answer=(int)$_REQUEST["answer"][$questionid];
                        else
                            $answer=(int)$_REQUEST["answer"];
						if($answer>0)
						{
							$a=new AelitaTestAnswer();
							$resAnswer=$a->GetByID($answer);
							if($Answer=$resAnswer->GetNext())
							{
                                if ($UseCorrect){
                                    if ($Answer["CORRECT"]=="Y")
                                        $Scores=$Scores+1;
                                }else{
                                    $Scores=$Scores+$Answer["SCORES"];
                                }

								$SerializedResult[]=$Answer["NAME"];
                                $SerializedResultText[]=$Answer["DESCRIPTION"];
							}
						}else{
                            if($arResult["QUESTIONING"]["STEP_MULTIPLE"]=="step")
                                $arResult["ERROR_Q"][$questionid][] = GetMessage("ERR_NO_ANSWER");
                            else
                                $arResult["ERROR"][] = GetMessage("ERR_NO_ANSWER");
                        }
						break;
				}

				if(($arResult["QUESTIONING"]["STEP_MULTIPLE"]=="step" && count($arResult["ERROR_Q"][$questionid])<=0) || ($arResult["QUESTIONING"]["STEP_MULTIPLE"]!="step" && count($arResult["ERROR"])<=0))
				{
					$g=new AelitaTestGlasses();
					$FieldsGlasses=array(
							"QUESTIONING_ID"=>$arResult["QUESTIONING"]["ID"],
							"QUESTION_ID"=>$Question["ID"],
					);
					$SortGlasses=array("ID"=>"ASC");
					$resGlasses = $g->GetList($SortGlasses,$FieldsGlasses,false,array("nPageSize"=>1));
					if($arrGlasses=$resGlasses->GetNext())
					{
						$Fields = array(
								"SCORES"=>$Scores,
								"OTV"=>"Y",
						);
						if(count($SerializedResult)>0)
							$Fields["SERIALIZED_RESULT"]=$SerializedResult;

                        if(count($SerializedResultText)>0)
                            $Fields["SERIALIZED_RESULT_TEXT"]=$SerializedResultText;
						
						if($arResult["TEST"]["SHOW_COMMENTS"]=="Y" || $Question["SHOW_COMMENTS"]=="Y")
						{
                            if($arResult["QUESTIONING"]["STEP_MULTIPLE"]=="step")
							    $comments=trim($_REQUEST["comments"][$questionid]);
                            else
                                $comments=trim($_REQUEST["comments"]);
							if(strlen($comments)>0)
								$Fields["COMMENTS"]=$comments;
							else
								$Fields["COMMENTS"]="";
						}



						$g->Update($arrGlasses["ID"],$Fields);



						if($arResult["ALLOW_PASSED_BACK"]=="Y" && $arResult["QUESTIONING"]["STEP_MULTIPLE"]!="step")
						{
							if($back)
								AelitaTestTools::SetQuestion($arResult["QUESTIONING"]["ID"],$arResult["TEST"]["ID"],(int)$_REQUEST["setprevtest"]);
							elseif(isset($_REQUEST["testsubmit"]))
							{
								$arResult["NEXT_LIST"]=0;
								$arResult["STEP"]=AelitaTestTools::GetStepQuestion($arResult["QUESTIONING"],$arResult["TEST"]);
								$arResult["STEP"]["GLASSES"]=AelitaTestTools::GetStepList($arResult["STEP"]["GLASSES_LIST"],$Question,$arResult["QUESTIONING"]);
								if($Question["ID"]>0 && count($arResult["STEP"]["GLASSES_LIST"])>0)
								{

									for($i=1;$i<count($arResult["STEP"]["GLASSES_LIST"]);$i++)
									{
										if($arResult["STEP"]["GLASSES_LIST"][$i-1]["QUESTION_ID"]==$Question["ID"])
										{
											$arResult["NEXT_LIST"]=$arResult["STEP"]["GLASSES_LIST"][$i]["QUESTION_ID"];
											//break;
										}
									}

									AelitaTestTools::SetQuestion($arResult["QUESTIONING"]["ID"],$arResult["TEST"]["ID"],$arResult["NEXT_LIST"]);
								}
								
							}else
								AelitaTestTools::SetQuestion($arResult["QUESTIONING"]["ID"],$arResult["TEST"]["ID"],$Question["ID"]);
						}
					}

					if($_REQUEST["testlast"]=="Y" && !isset($_REQUEST["prevtest"]) && $single)
					{
						AelitaTestTools::InitResult($arResult["QUESTIONING"]);
						//if($arResult["TEST"]["AUTO_START_OVER"]=="Y")
						//	AelitaTestTools::CloseQuestioning($arResult["PROFAIL_ID"]["ID"],$arResult["TEST"]["ID"]);
					}

					if($redir)
						LocalRedirect($APPLICATION->GetCurPageParam("",$arrClearParam));
				}
			}
		}
		return $Question;
	}
	
	function GetJsUrl($NameField,$arrCodes)
	{
		$result="";
		$result.="['mnu_".$NameField."','5000',[";
		$arr=array();
		foreach($arrCodes as $Code)
			$arr[]="{'TEXT': '".GetMessage($Code)."','TITLE':'#".$Code."# - ".GetMessage($Code)."','ONCLICK':'window.IBlockComponentPropertiesObj.Action(\'#".$Code."#\', \'mnu_".$NameField."\', \'\')'}";
		$result.=implode(",", $arr);
		$result.="]]";
		return $result;
	}
	
	function GetTxtTime($time)
	{
		if($time>0)
		{
			$hours = floor($time/3600);
			$minutes=floor(($time/3600 - $hours)*60);
			$seconds = ceil((($time/3600 - $hours)*60 - floor($minutes))*60);
			if($hours<10)
				$hours="0".$hours;
			if($minutes<10)
				$minutes="0".$minutes;
			if($seconds<10)
				$seconds="0".$seconds;
			$txtTime=$hours.":".$minutes.":".$seconds;
		}else{
			$txtTime="";
		}
		return $txtTime;
	}

	function GetTestGroup($ShowNullItem=false)
	{
		$arrResult=array();
		if($ShowNullItem)
			$arrResult[]=GetMessage("AT_NON");
		$ListGroups = new AelitaTestGroup();
		$dbGroups = $ListGroups->GetList(array("SORT"=>"ASC"),array("ACTIVE"=>"Y"),false,false,array("ID","NAME"));
		while($Group=$dbGroups->GetNext())
			$arrResult[$Group["ID"]]=$Group["NAME"];
		return $arrResult;
	}
	
	function GetTestTest($Group=0)
	{
		$arrResult=array();
		$Group=(int)$Group;
		$ListGroups = new AelitaTestTest();
		$arFilter=array("ACTIVE"=>"Y");
		if($Group>0)
			$arFilter["GROUP_ID"]=$Group;
		$dbGroups = $ListGroups->GetList(array("SORT"=>"ASC"),$arFilter,false,false,array("ID","NAME"));
		while($Group=$dbGroups->GetNext())
			$arrResult[$Group["ID"]]=$Group["NAME"];
		return $arrResult;
	}

	function GetIDProfail()
	{
		global $USER;
		/*if (!array_key_exists("TEST_PROFAIL_ID", $_SESSION))
		{
			$_SESSION["TEST_PROFAIL_ID"]=AelitaTestTools::InitProfail();
		}elseif(($_SESSION["TEST_PROFAIL_ID"]["USER_ID"]<=0 && $USER->GetID()>0) || $_SESSION["TEST_PROFAIL_ID"]["USER_ID"]!=$USER->GetID()){
			$_SESSION["TEST_PROFAIL_ID"]=AelitaTestTools::InitProfail();
		}elseif($_SESSION["TEST_PROFAIL_ID"]["SESS_ID"]!=bitrix_sessid()){
			$_SESSION["TEST_PROFAIL_ID"]=AelitaTestTools::InitProfail();
		}*/

        $_SESSION["TEST_PROFAIL_ID"]=AelitaTestTools::InitProfail();

		if (!array_key_exists("TEST_PROFAIL_ID", $_SESSION))
			$_SESSION["TEST_PROFAIL_ID"]=array();
		return $_SESSION["TEST_PROFAIL_ID"];
	}
	
	function InitProfail()
	{
		$ID=array(
			"ID"=>0,
			"SESS_ID"=>0,
			"USER_ID"=>"",
			);
		global $USER;
		
		$SessionUserID=(int)$_SESSION["TEST_PROFAIL_ID"]["USER_ID"];
		
		if($USER->GetID()>0)
		{
			$el=new AelitaTestProfile();
			$arFields=array("USER_ID"=>$USER->GetID());
			$res = $el->GetList(array("ID"=>"DESC"),$arFields,false,array("nPageSize"=>1));
			if($arr=$res->GetNext())
			{
				$ID=$arr;
				/*if($ID["SESS_ID"]!=bitrix_sessid() && ($SessionUserID==0 || $SessionUserID==$USER->GetID()))
				{
					$sesel=new AelitaTestProfile();
					$arFields=array("SESS_ID"=>bitrix_sessid());
					$resA = $sesel->GetList(array("ID"=>"DESC"),$arFields,false,array("nPageSize"=>1));
					if($arrA=$resA->GetNext())
					{
						$el=new AelitaTestQuestioning();
						$arFields=array(
							"PROFILE_ID"=>$ID["ID"],
							);
						$el->GroupUpdate("PROFILE_ID", $arrA["ID"], $arFields);
					}
				}*/
				$ID["SESS_ID"]=bitrix_sessid();
				$newel=new AelitaTestProfile();
				$arFields=array("SESS_ID"=>bitrix_sessid());
				$newel->Update($ID["ID"],$arFields);
			}else{
				/*$sesel=new AelitaTestProfile();
				$arFields=array("SESS_ID"=>bitrix_sessid());
				$resA = $sesel->GetList(array("ID"=>"DESC"),$arFields,false,array("nPageSize"=>1));
				if($arrA=$resA->GetNext())
				{
					$ID=$arrA;
					$ID["USER_ID"]=$USER->GetID();
					$newel=new AelitaTestProfile();
					$arFields=array("USER_ID"=>$USER->GetID());
					$newel->Update($ID["ID"],$arFields);
				}else{*/
					$ID=array(
						"USER_ID"=>$USER->GetID(),
						"SESS_ID"=>bitrix_sessid(),
						);
					$newel=new AelitaTestProfile();
					$ID["ID"]=$newel->Add($ID);
				/*}*/
			}
		}
		
		
		if($ID["ID"]<=0)
		{
			$el=new AelitaTestProfile();
			$arFields=array("SESS_ID"=>bitrix_sessid());
			$res = $el->GetList(array("ID"=>"DESC"),$arFields,false,array("nPageSize"=>1));
			if($arr=$res->GetNext())
			{
				$ID=$arr;
				unset($ID["USER_ID"]);
				unset($ID["~USER_ID"]);
			}else{
				$ID=array(
					"SESS_ID"=>bitrix_sessid(),
					);
				$newel=new AelitaTestProfile();
				$ID["ID"]=$newel->Add($ID);
			}
		}
		
		return $ID;
	}
	
	function GetQuestioning($ProfileID,&$Test)
	{
        //echo "<pre>";print_r($Test);echo "</pre>";
		$ProfileID=(int)$ProfileID;
		$TestID=(int)$Test["ID"];
		$Result=array();
		$Select=array(
			"ID",
			"PROFILE_ID",
			"TEST_ID",
			"RESULT_ID",
			"CLOSED",
			"FINAL",
			"DATE_START",
			"DATE_STOP",
			"DURATION",
			"AVER_DURATION",
			"GLASSES_ID",
			"TEST_TYPE_RESULT",
            "STEP_MULTIPLE",
		);
		if($ProfileID>0 && $TestID>0)
		{
			$el=new AelitaTestQuestioning();
			$arFields=array(
				"CLOSED"=>"N",
				"TEST_ID"=>$TestID,
				"PROFILE_ID"=>$ProfileID,
				);

			$res = $el->GetList(array("ID"=>"DESC"),$arFields,false,array("nPageSize"=>1),$Select);
			if($arr=$res->GetNext())
			{
				$Result=$arr;
				$arFields["<>ID"]=$arr["ID"];
				$res = $el->GetList(array("ID"=>"DESC"),$arFields);
				$arUpdate=array("CLOSED"=>"Y");
				while($arr=$res->GetNext())
				{
					$el->Update($arr["ID"],$arUpdate);
				}
			}
		}
		return $Result;
	}
	
	function CloseQuestioning($ProfileID,$TestID)
	{
		$ProfileID=(int)$ProfileID;
		$TestID=(int)$TestID;
		if($ProfileID>0 && $TestID>0)
		{
			$el=new AelitaTestQuestioning();
			$arFields=array(
				"CLOSED"=>"N",
				"TEST_ID"=>$TestID,
				"PROFILE_ID"=>$ProfileID,
				);
			$res = $el->GetList(array("ID"=>"DESC"),$arFields);
			$arUpdate=array("CLOSED"=>"Y");
			while($arr=$res->GetNext())
			{
				$el->Update($arr["ID"],$arUpdate);
			}
		}
	}
	
	function initQuestioning($ProfileID,&$Test)
	{
		$ProfileID=(int)$ProfileID;
		$TestID=(int)$Test["ID"];
		if($ProfileID>0 && $TestID>0)
		{
			
			$elt=new AelitaTestTest();
			$rest=$elt->GetList(array(),array("ID"=>$TestID),false,array("nPageSize"=>1));
			if($test=$rest->GetNext())
				$MIX=$test["MIX_QUESTION"];
			
			$el=new AelitaTestQuestioning();
			$arFields=array(
				"CLOSED"=>"N",
				"FINAL"=>"N",
				"TEST_ID"=>$TestID,
				"PROFILE_ID"=>$ProfileID,
				);
            if(($Test["MULTIPLE_QUESTION"]!="none" && $Test["MULTIPLE_QUESTION"]!="anum") || ($Test["MULTIPLE_QUESTION"]=="anum" && $Test["MULTIPLE_QUESTION_COUNT"]>0))
                $arFields["STEP_MULTIPLE"]="step";
			$res = $el->Add($arFields);
			$QuestioningID=(int)$res;
			if($QuestioningID>0)
			{
				$el=new AelitaTestQuestion();
				$qg=new AelitaTestQuestionGroup();
				
				$arrqg=array(false,0);
				
				$arqgFields=array(
						"ACTIVE"=>"Y",
						"TEST_ID"=>$TestID,
				);
				$arqgSort=array(
						"SORT"=>"ASC",
						"ID"=>"ASC",
				);
				
				$resqg=$qg->GetList($arqgSort,$arqgFields,false);
				while($arrqgel=$resqg->GetNext())
					$arrqg[]=$arrqgel;
				$arrId=array();
				foreach($arrqg as $qkey=>$q)
				{
					$arFields=array(
						"ACTIVE"=>"Y",
						"TEST_ID"=>$TestID,
						);
					$arSort=array(
						"SORT"=>"ASC",
						"ID"=>"ASC",
						);

					$Nav=false;
					if(is_array($q))
					{
						$arFields["TEST_GROUP_ID"]=$q["ID"];
						if((int)$q["COUNT"]>0)
						{
							$Nav=array("nPageSize"=>$q["COUNT"]);
							$arSort=array("ID"=>"RAND");
						}
					}else{
						$arFields["TEST_GROUP_ID"]=$q;
					}
					$res = $el->GetList($arSort,$arFields,false,$Nav);
					$arrG=array();
					while($arr=$res->GetNext())
					{
                        $arrG[]=$arr["ID"];
					}
					if($MIX=="Y")
					{
                        shuffle($arrG);
					}
                    if($Test["MULTIPLE_QUESTION"]=="gnum" || $Test["MULTIPLE_QUESTION"]=="clst")
                        $arrId[$qkey]=$arrG;
                    else
                        $arrId = array_merge($arrId,$arrG);
				}
				//shuffle($arrId);

                switch ($Test["MULTIPLE_QUESTION"]) {
                    case "none":
                        self::AddAllGlasses($QuestioningID,$arrId);
                        break;
                    case "anum":
                        self::AddAllStepGlasses($QuestioningID,$arrId,$Test["MULTIPLE_QUESTION_COUNT"]);
                        break;
                    case "gnum":
                        self::AddGroupStepGlasses($QuestioningID,$arrId,$arrqg);
                        break;
                    case "allq":
                        self::AddAllStepGlasses($QuestioningID,$arrId,0);
                        break;
                    case "clst":
                        self::AddGroupStepGlasses($QuestioningID,$arrId,$arrqg,true);
                        break;
                }
			}
		}
	}

    function AddStep($QuestioningID)
    {
        $s=new AelitaTestStep();
        $Fields = array(
            "QUESTIONING_ID"=>$QuestioningID,
            "OTV"=>"N",
        );
        $StepID=(int)$s->Add($Fields);
        return $StepID;
    }

    function AddAllStepGlasses($QuestioningID,$arrId,$step)
    {
        $g=new AelitaTestGlasses();
        $StepID=self::AddStep($QuestioningID);
        $i=0;
        foreach($arrId as $id)
        {
            $i++;

            if($i>$step && $step>0)
            {
                $i=1;
                $StepID=self::AddStep($QuestioningID);
            }

            $Fields = array(
                "QUESTIONING_ID"=>$QuestioningID,
                "QUESTION_ID"=>$id,
                "STEP"=>$StepID,
                "OTV"=>"N",
            );
            $g->Add($Fields);
        }
    }

    function AddGroupStepGlasses($QuestioningID,$arrId,$arrqg,$clast=false)
    {
        $g=new AelitaTestGlasses();
        foreach($arrqg as $key=>$ag)
        {


            $i=0;
            $step=0;
            if($ag["MULTIPLE_QUESTION_COUNT"] && !$clast)
                $step=(int)$ag["MULTIPLE_QUESTION_COUNT"];

            if(is_array($arrId[$key]) && count($arrId[$key])>0)
            {
                $StepID=self::AddStep($QuestioningID);

                foreach($arrId[$key] as $id)
                {
                    $i++;

                    if($i>$step && $step>0)
                    {
                        $i=1;
                        $StepID=self::AddStep($QuestioningID);
                    }

                    $Fields = array(
                        "QUESTIONING_ID"=>$QuestioningID,
                        "QUESTION_ID"=>$id,
                        "STEP"=>$StepID,
                        "OTV"=>"N",
                    );

                    $g->Add($Fields);
                }
            }

        }

    }

    function AddAllGlasses($QuestioningID,$arrId)
    {
        $g=new AelitaTestGlasses();
        foreach($arrId as $id)
        {
            $Fields = array(
                "QUESTIONING_ID"=>$QuestioningID,
                "QUESTION_ID"=>$id,
                "OTV"=>"N",
            );
            $g->Add($Fields);
        }
    }
	
	function GetCountQuestioning($ProfileID,$TestID,$Period=0)
	{
		$ProfileID=(int)$ProfileID;$TestID=(int)$TestID;$result=0;$Period=(int)$Period;
		if($ProfileID>0 && $TestID>0)
		{
			$el=new AelitaTestQuestioning();
			$arFields=array(
				"TEST_ID"=>$TestID,
				"PROFILE_ID"=>$ProfileID,
				);
			if($Period>0)
				$arFields[">=DATE_START"]=ConvertDateTime(GetTime(time()-($Period*60),"FULL"), "YYYY-MM-DD HH:MI:SS");
			$res = $el->GetList(false,$arFields,false,false,array("COUNT"));
			if($arr=$res->GetNext())
				$result=$arr["COUNT"];
		}
		return $result;
	}
	
	function GetShowAnswers($TestID,$QuestionID)
	{
		$result=array();
		$QuestionID=(int)$QuestionID;
		$TestID=(int)$TestID;
		if($QuestionID>0 && $TestID>0)
		{
			$el=new AelitaTestQuestion();
			$arFields=array(
				"TEST_ID"=>$TestID,
				"ID"=>$QuestionID,
				);
			$res = $el->GetList(false,$arFields,false,array("nPageSize"=>1));
			if($arr=$res->GetNext())
			{
				if($arr["TEST_TYPE"]=="input")
				{
					$result[]=$arr["CORRECT_ANSWER"];
				}else{
					$elAnswer=new AelitaTestAnswer();
					$arSortAnswer=array(
						"SCORES"=>"DESC",
						"ID"=>"ASC",
						);
					$arFieldsAnswer=array(
						"ACTIVE"=>"Y",
						"QUESTION_ID"=>$QuestionID,
						);
					$NavAnswer=array("nPageSize"=>1);
					if($arr["TEST_TYPE"]=="check")
						$NavAnswer=false;
						$resAnswer = $elAnswer->GetList($arSortAnswer,$arFieldsAnswer,false,$NavAnswer);
						while($arrAnswer=$resAnswer->GetNext())
							if($arrAnswer["SCORES"]>0)
								$result[]=$arrAnswer["NAME"];
				}
			}
		}
		return $result;
	}

    function ChekQuestion(&$Question,&$Questioning)
    {
        switch ($Questioning["STEP_MULTIPLE"]) {
            case "none":
                if($Question["ID"]>0)
                    return true;
                else
                    return false;
                break;
            case "step":
                if(count($Question)>0)
                    return true;
                else
                    return false;
                break;
        }
    }

    function ChekComment(&$Question,&$Questioning,&$Test)
    {
        switch ($Questioning["STEP_MULTIPLE"]) {
            case "none":

                if($Question["SHOW_COMMENTS"]=="Y" && $Test["SHOW_COMMENTS"]=="N")
                    $Test["SHOW_COMMENTS"]="Y";

                break;
            case "step":
                if($Test["SHOW_COMMENTS"]=="Y"){
                    foreach($Question as &$arrQuestion)
                    {
                        $arrQuestion["SHOW_COMMENTS"]="Y";
                    }unset($arrQuestion);
                }
                break;
        }
    }

    function PictureQuestion(&$Question,&$Questioning)
    {
        switch ($Questioning["STEP_MULTIPLE"]) {
            case "none":
                if($Question["PICTURE"])
                    $Question["PICTURE"] = AelitaTestTools::GetWatermarkPicture($Question["PICTURE"],$Question["ALT"]);
                break;
            case "step":
                foreach($Question as &$arrQuestion)
                {
                    if($arrQuestion["PICTURE"])
                        $arrQuestion["PICTURE"] = AelitaTestTools::GetWatermarkPicture($arrQuestion["PICTURE"],$Question["ALT"]);
                }unset($arrQuestion);
                break;
        }
    }

    function GetStep(&$AllQuestioning,&$AllTest,$OTV=true,$revers=false,$glasses=0)
    {
        $Result=array();



        switch ($AllQuestioning["STEP_MULTIPLE"]) {
            case "none":

                $elGlasses=new AelitaTestGlasses();
                $arFieldsGlasses=array("QUESTIONING_ID"=>$AllQuestioning["ID"]);
                if($OTV)
                    $arFieldsGlasses["OTV"]="N";
                $arSortGlasses=array("ID"=>"ASC");
                if($revers)
                    $arSortGlasses=array("ID"=>"DESC");

                $page=array("nPageSize"=>1);

                if($glasses>0)
                    $page=false;

                $resGlasses=$elGlasses->GetList($arSortGlasses,$arFieldsGlasses,false,$page);

                if($glasses>0)
                {
                    while($arrGlasses=$resGlasses->GetNext())
                    {
                        if($arrGlasses["QUESTION_ID"]==$glasses)
                        {
                            $el=new AelitaTestQuestion();
                            $res = $el->GetByID($arrGlasses["QUESTION_ID"]);
                            if($arr=$res->GetNext())
                                $Result=$arr;
                            break;
                        }
                    }
                }else{
                    if($arrGlasses=$resGlasses->GetNext())
                    {
                        $el=new AelitaTestQuestion();
                        $res = $el->GetByID($arrGlasses["QUESTION_ID"]);
                        if($arr=$res->GetNext())
                            $Result=$arr;
                    }
                }








                break;
            case "step":


                $page=array("nPageSize"=>1);

                $el=new AelitaTestQuestion();

                $elGlasses=new AelitaTestGlasses();
                $arSortGlasses=array("ID"=>"ASC");

                if($revers)
                    $arSortGlasses=array("ID"=>"DESC");

                $elStep=new AelitaTestStep();
                $arFieldsStep=array("QUESTIONING_ID"=>$AllQuestioning["ID"]);
                if($OTV)
                    $arFieldsStep["OTV"]="N";
                $arSortStep=array("ID"=>"ASC");

                if($glasses>0)
                    $page=false;

                $resStep=$elStep->GetList($arSortStep,$arFieldsStep,false,$page);

                if($glasses>0)
                {
                    while($arrStep=$resStep->GetNext())
                    {
                        if($arrStep["ID"]==$glasses)
                        {
                            $AllQuestioning["NUMBER_STEP"]=$arrStep["ID"];


                            $arFieldsGlasses=array("QUESTIONING_ID"=>$AllQuestioning["ID"],"STEP"=>$arrStep["ID"]);
                            $resGlasses=$elGlasses->GetList($arSortGlasses,$arFieldsGlasses,false);
                            while($arrGlasses=$resGlasses->GetNext())
                            {

                                $res = $el->GetByID($arrGlasses["QUESTION_ID"]);
                                if($arr=$res->GetNext())
                                    $Result[$arr["ID"]]=$arr;
                            }

                            break;
                        }
                    }
                }else{
                    if($arrStep=$resStep->GetNext())
                    {

                        $AllQuestioning["NUMBER_STEP"]=$arrStep["ID"];

                        $arFieldsGlasses=array("QUESTIONING_ID"=>$AllQuestioning["ID"],"STEP"=>$arrStep["ID"]);
                        $resGlasses=$elGlasses->GetList($arSortGlasses,$arFieldsGlasses,false);
                        while($arrGlasses=$resGlasses->GetNext())
                        {

                            $res = $el->GetByID($arrGlasses["QUESTION_ID"]);
                            if($arr=$res->GetNext())
                                $Result[$arr["ID"]]=$arr;
                        }
                    }
                }



                break;
        }



        return $Result;
    }
	
	function GetQuestion(&$AllQuestioning,&$AllTest)
	{
		$Questioning=(int)$AllQuestioning["ID"];
		$Test=(int)$AllTest["ID"];
		$Result=array();

		if($Questioning>0 && $Test>0)
		{
			$AllowPassedBack=COption::GetOptionString("aelita.test","aelita_test_allow_passed_back","N");

			$elGlasses=new AelitaTestGlasses();


			
			$Count=0;
			$resGlasses=$elGlasses->GetList(false,array("QUESTIONING_ID"=>$Questioning,"OTV"=>"N"),false,false,array("COUNT"));
			if($arrGlasses=$resGlasses->GetNext())
				$Count=(int)$arrGlasses["COUNT"];
				

			if($Count>0){
				if($AllowPassedBack=="Y")
				{
					$q=new AelitaTestQuestioning();
					$res = $q->GetByID($Questioning);
					if($arrQuestioning=$res->GetNext())
					{
						if($arrQuestioning["GLASSES_ID"]>0)
						{
                            $Result=self::GetStep($AllQuestioning,$AllTest,false,false,$arrQuestioning["GLASSES_ID"]);


							if($Result==array())
							{
                                $Result=self::GetStep($AllQuestioning,$AllTest);
							}
						}elseif($arrQuestioning["GLASSES_ID"]==0){


                            $Result=self::GetStep($AllQuestioning,$AllTest,false);


						}
					}

				}else{
                    $Result=self::GetStep($AllQuestioning,$AllTest);
				}
			}else{

				if($AllowPassedBack=="Y")
				{
					$q=new AelitaTestQuestioning();
					$res = $q->GetByID($Questioning);
					if($arrQuestioning=$res->GetNext())
					{
						if($arrQuestioning["GLASSES_ID"]>0)
						{
                            $Result=self::GetStep($AllQuestioning,$AllTest,false,false,$arrQuestioning["GLASSES_ID"]);


							if($Result==array())
							{
                                $Result=self::GetStep($AllQuestioning,$AllTest,false,true);


							}
						}elseif($arrQuestioning["GLASSES_ID"]==0){

                            $Result=self::GetStep($AllQuestioning,$AllTest,false,true);
						}
					}

				}else{
                    $Result=self::GetStep($AllQuestioning,$AllTest,true,true);
				}
			}
		}
		return $Result;
	}
	
	function SetQuestion($QuestioningID,$TestID,$QuestionID)
	{
		$QuestioningID=(int)$QuestioningID;
		$TestID=(int)$TestID;
		$QuestionID=(int)$QuestionID;
		if($QuestioningID>0 && $TestID>0 && $QuestionID>0)
		{
			$el=new AelitaTestQuestion();
			$res = $el->GetByID($QuestionID);
			if($arr=$res->GetNext())
				$Question=$arr;
			$q=new AelitaTestQuestioning();
			$res = $q->GetByID($QuestioningID);
			if($arr=$res->GetNext())
				$Questioning=$arr;
            if($Questioning && $Questioning["STEP_MULTIPLE"]=="step")
            {

                $el=new AelitaTestStep();
                $res = $el->GetByID($QuestionID);
                if($arr=$res->GetNext())
                    $Step=$arr;

                if($Step && $Questioning["TEST_ID"]==$TestID && $Step["QUESTIONING_ID"]==$QuestioningID)
                    $q->Update($Questioning["ID"],array("GLASSES_ID"=>$QuestionID));

            }else{
                if($Questioning && $Question>0)
                    if($Questioning["TEST_ID"]==$TestID && $Question["TEST_ID"]==$TestID)
                        $q->Update($Questioning["ID"],array("GLASSES_ID"=>$QuestionID));
            }

		}elseif($QuestioningID>0 && $TestID>0){
			$q=new AelitaTestQuestioning();
			$res = $q->GetByID($QuestioningID);
			if($Questioning=$res->GetNext())
				if($Questioning["TEST_ID"]==$TestID)
					$q->Update($Questioning["ID"],array("GLASSES_ID"=>0));
		}
	}
	
	
	function GetStepQuestion(&$AllQuestioning,&$AllTest)
	{
        $Questioning=(int)$AllQuestioning["ID"];
        $Test=(int)$AllTest["ID"];

        if($Questioning>0 && $Test>0)
        {

            $Result=array(
                "GLASSES"=>0,
                "TESTS"=>0,
                "GLASSES_LIST"=>array(),
                "QUESTION"=>array(),
                "STEP_GLASSES_LIST"=>array(),
            );

            $elGlasses=new AelitaTestGlasses();
            $arFieldsGlasses=array("QUESTIONING_ID"=>$Questioning);
            $resGlasses=$elGlasses->GetList(array("ID"=>"ASC"),$arFieldsGlasses,false,false);
            while($arrGlasses=$resGlasses->GetNext())
            {
                if($arrGlasses["SERIALIZED_RESULT"])
                    $arrGlasses["SERIALIZED_RESULT"]=unserialize(base64_decode($arrGlasses["SERIALIZED_RESULT"]));
                if($AllQuestioning["STEP_MULTIPLE"]=="step")
                    $Result["GLASSES_LIST"][$arrGlasses["QUESTION_ID"]]=$arrGlasses;
                else
                    $Result["GLASSES_LIST"][]=$arrGlasses;
                if($arrGlasses["OTV"]=="Y")
                    $Result["QUESTION"][$arrGlasses["QUESTION_ID"]]=$arrGlasses["SERIALIZED_RESULT"];
            }


            if($AllQuestioning["STEP_MULTIPLE"]=="step")
            {
                $elStep=new AelitaTestStep();
                $Result["TESTS"]=$elStep->GetList(false,array("QUESTIONING_ID"=>$Questioning),false,false,array("COUNT"))->GetNext();
                $Result["TESTS"]=$Result["TESTS"]["COUNT"];

                $resStep=$elStep->GetList(array("ID"=>"ASC"),array("QUESTIONING_ID"=>$Questioning),false,false);
                while($Step=$resStep->GetNext())
                    $Result["STEP_GLASSES_LIST"][]=$Step;
            }else{


                $Result["TESTS"]=count($Result["GLASSES_LIST"]);


            }
        }




		return $Result;
	}
	
	function GetStepList(&$List,&$Question,&$Questioning)
	{
		$result=0;

        if($Questioning["STEP_MULTIPLE"]=="step")
        {



            $elStep=new AelitaTestStep();
            $arFieldsStep=array("QUESTIONING_ID"=>$Questioning["ID"]);
            $arSortStep=array("ID"=>"ASC");
            $resStep=$elStep->GetList($arSortStep,$arFieldsStep,false);
            $i=0;
            while($Step=$resStep->GetNext())
            {
                $i++;
                if($Step["ID"]==$Questioning["NUMBER_STEP"])
                    $result=$i;

            }
        }else{
            $q=$Question["ID"];
            foreach($List as $v)
            {
                $result++;
                if($q==$v["QUESTION_ID"])
                    break;
            }
        }



		return $result;
	}
	
	function GetAnswer(&$AllQuestion,$mix="N",&$Questioning)
	{

		$Result=array();
        if($Questioning["STEP_MULTIPLE"]=="step")
        {
            if(count($AllQuestion)>0)
            {
                foreach($AllQuestion as $Question)
                {
                    $el=new AelitaTestAnswer();

                    $Glasses=new AelitaTestGlasses();
                    $count_test=$Glasses->GetList(array(),array(
                        "QUESTION_ID"=>$Question["ID"],
                        "OTV"=>"Y",
                    ),false,false,array("COUNT"));
                    $count_test=$count_test->GetNext();
                    $count_test=(int)$count_test["COUNT"];

                    $arFields=array(
                        "QUESTION_ID"=>$Question["ID"],
                        "ACTIVE"=>"Y",
                    );
                    $arSort=array(
                        "SORT"=>"ASC",
                        "ID"=>"ASC",
                    );
                    if($mix=="Y")
                    {
                        $arSort=array("ID"=>"RAND");
                    }

                    $res = $el->GetList($arSort,$arFields);
                    while($arr=$res->GetNext())
                    {
                        if($arr["PICTURE"])
                            $arr["PICTURE"] = AelitaTestTools::GetWatermarkPicture($arr["PICTURE"],$arr["ALT"]);


                        $arr["COUNT_TEST"]=$count_test;
                        $count=$Glasses->GetList(array(),array(
                            "QUESTION_ID"=>$Question["ID"],
                            "SERIALIZED_RESULT"=>base64_encode(serialize(array($arr["NAME"]))),
                        ),false,false,array("COUNT"));
                        $count=$count->GetNext();
                        $count=(int)$count["COUNT"];
                        $arr["COUNT_ANSWER"]=$count;
                        if($count_test>0)
                            $arr["PRECENT_ANSWER"]=round(100*($count/$count_test));
                        else
                            $arr["PRECENT_ANSWER"]=0;


                        $Result[$Question["ID"]][]=$arr;
                    }
                }
            }
        }else{
            $Question=(int)$AllQuestion["ID"];
            if($Question>0)
            {
                $el=new AelitaTestAnswer();

                $Glasses=new AelitaTestGlasses();
                $count_test=$Glasses->GetList(array(),array(
                    "QUESTION_ID"=>$Question,
                    "OTV"=>"Y",
                ),false,false,array("COUNT"));
                $count_test=$count_test->GetNext();
                $count_test=(int)$count_test["COUNT"];

                $arFields=array(
                    "QUESTION_ID"=>$Question,
                    "ACTIVE"=>"Y",
                );
                $arSort=array(
                    "SORT"=>"ASC",
                    "ID"=>"ASC",
                );
                if($mix=="Y")
                {
                    $arSort=array("ID"=>"RAND");
                }

                $res = $el->GetList($arSort,$arFields);
                while($arr=$res->GetNext())
                {
                    if($arr["PICTURE"])
                        $arr["PICTURE"] = AelitaTestTools::GetWatermarkPicture($arr["PICTURE"],$arr["ALT"]);

                    $arr["COUNT_TEST"]=$count_test;
                    $count=$Glasses->GetList(array(),array(
                       "QUESTION_ID"=>$Question,
                        "SERIALIZED_RESULT"=>base64_encode(serialize(array($arr["NAME"]))),
                    ),false,false,array("COUNT"));
                    $count=$count->GetNext();
                    $count=(int)$count["COUNT"];
                    $arr["COUNT_ANSWER"]=$count;
                    if($count_test>0)
                        $arr["PRECENT_ANSWER"]=round(100*($count/$count_test));
                    else
                        $arr["PRECENT_ANSWER"]=0;

                    $Result[]=$arr;
                }
            }
        }
		return $Result;
	}
	
	function GetAverResult($QUESTIONING_ID)
	{
		$result=0;
		$module_id="aelita.test";
			$i=0;
			$el=new AelitaTestGlasses();
			$arFields=array(
				"QUESTIONING_ID"=>$QUESTIONING_ID,
				);
			$arSort=array();
			$res = $el->GetList($arSort,$arFields,false,false);
			while($arr=$res->GetNext())
			{
				if((int)$arr["SCORES"]>0)
				{
					$result+=(int)$arr["SCORES"];
					$i++;
				}
			}
			if($i>0)
				$result=$result/$i;
		$result=round($result, 2);
		return $result;
	}
	
	
	function InitResult(&$Questioning)
	{
		$module_id="aelita.test";
		if($Questioning["RESULT_ID"]<=0)
		{
			$result=array();
			$el=new AelitaTestGlasses();
			$arFields=array(
				"QUESTIONING_ID"=>$Questioning["ID"],
				);
			$arSort=array();
			$res = $el->GetList($arSort,$arFields,false,false,array("SUM_SCORES"));
			if($arr=$res->GetNext())
			{
				$Scores=(int)$arr["SUM_SCORES"];
				$ex_type_result = COption::GetOptionString($module_id, "aelita_test_ex_type_result","N");
				if($ex_type_result=="Y"){
					$AverScores=AelitaTestTools::GetAverResult($Questioning["ID"]);
				}
				//if($Scores>0)
				//{
					$elResult=new AelitaTestResult();
					
					$arFieldsResult=array(
						"TEST_ID"=>$Questioning["TEST_ID"],
						"ACTIVE"=>"Y",
						);
					
					if($ex_type_result=="Y" && $Questioning["TEST_TYPE_RESULT"]=="aver"){
						$arFieldsResult["<=MIN_SCORES"]=$AverScores;
						$arFieldsResult[">=MAX_SCORES"]=$AverScores;
					}else{
						$arFieldsResult["<=MIN_SCORES"]=$Scores;
						$arFieldsResult[">=MAX_SCORES"]=$Scores;
					}

					$arSort=array(
						"ID"=>"RAND",
						);
					$resResult = $elResult->GetList($arSort,$arFieldsResult,false,array("nPageSize"=>1),array("ID"));
					if($arrResult=$resResult->GetNext())
					{
						$Questioning["RESULT_ID"]=$arrResult["ID"];
						$el=new AelitaTestQuestioning();
						$DateStop=ConvertDateTime(GetTime(time(),"FULL"), "YYYY-MM-DD HH:MI:SS");
						$UnixDateStop=MakeTimeStamp($DateStop, "YYYY-MM-DD HH:MI:SS");
						$UnixDateStart=MakeTimeStamp($Questioning["DATE_START"], "YYYY-MM-DD HH:MI:SS");
						$UnixDuration=$UnixDateStop-$UnixDateStart;
						$DateStopb=date("d-m-Y H:i:s",$UnixDateStop);
						global $DB;
						$DateStopb=$DB->FormatDate($DateStop,"YYYY-MM-DD HH:MI:SS",CSite::GetDateFormat("FULL"));
						$arFields=array(
							"RESULT_ID"=>$arrResult["ID"],
							"FINAL"=>"Y",
							"DATE_STOP"=>$DateStopb,
							"DURATION"=>$UnixDuration,
							);
						if($el->Update($Questioning["ID"],$arFields))
						{
							$arQuestioningEvents=array(
								"TEST_ID"=>$Questioning["TEST_ID"],
								"RESULT_ID"=>$Questioning["RESULT_ID"],
								"DATE_STOP"=>$DateStop,
								"DURATION"=>$UnixDuration,
								"FINAL"=>"Y",
								"CLOSED"=>$Questioning["CLOSED"],
								"PROFILE_ID"=>$Questioning["PROFILE_ID"],
								);
								
							if($ex_type_result=="Y"){
								switch ($Questioning["TEST_TYPE_RESULT"]){
									case 'summ':
										$arQuestioningEvents["SCORES"]=$Scores;
										break;
									case 'aver':
										$arQuestioningEvents["AVER_SCORES"]=$AverScores;
										break;
									case 'suer':
										$arQuestioningEvents["SCORES"]=$Scores;
										$arQuestioningEvents["AVER_SCORES"]=$AverScores;
										break;
								}; 
								$arQuestioningEvents["<=MIN_SCORES"]=$AverScores;
								$arFieldsResult["<=MIN_SCORES"]=$AverScores;
							}else{
								$arQuestioningEvents["SCORES"]=$Scores;
							}
					
							$rsEvents = GetModuleEvents($module_id, "OnAfterPassingTest");
							while ($arEvent = $rsEvents->Fetch())
								ExecuteModuleEvent($arEvent,$arQuestioningEvents);
							AelitaTestTools::EmailResponsible($Questioning["ID"]);
						}
					}
				//}
			}
		}
	}
	
	function EmailResponsible($ID)
	{
		$module_id="aelita.test";
        $userId=0;
        $TestId=0;
        $userEmail=false;
		$ex_type_result = COption::GetOptionString($module_id, "aelita_test_ex_type_result","N");
		$Message=array();
		
		$Message[]=GetMessage("REPORT_SEPARATOR");
		$Message[]=GetMessage("REPORT_RESULT");
		$Message[]=GetMessage("REPORT_SEPARATOR");
		
		$el=new AelitaTestQuestioning();
		$arSelect=array("ID","PROFILE_ID","TEST_ID","RESULT_ID","CLOSED","TEST_NAME","RESULT_NAME","SCORES","USER_ID","DATE_START","DATE_STOP","FINAL","DURATION","TEST_TYPE_RESULT","DESCRIPTION");
		$arGroup=array("ID");
		$arFilter=array("ID"=>$ID);
		$arExtra=$el->GetList(array("ID"=>"ASC"),$arFilter,$arGroup,array("nPageSize"=>1),$arSelect);
		if ($arExtra=$arExtra->GetNext())
		{

            $TestId=$arExtra["TEST_ID"];

			if($ex_type_result=="Y"){
				$AverScores=AelitaTestTools::GetAverResult($arExtra["ID"]);
			}
			
			if($arExtra["USER_ID"]>0)
			{
                $userId=$arExtra["USER_ID"];
				$arrName="";
				$rsUser = CUser::GetByID($arExtra["USER_ID"]);
				if($arUser=$rsUser->Fetch()){
					$arrName=array();
					//$arrName[]="[".$arUser["ID"]."]";
					$arrName[]="(".$arUser["LOGIN"].")";
					$arrName[]=$arUser["NAME"];
					$arrName[]=$arUser["LAST_NAME"];
					$arrName=implode(" ",$arrName);
                    $userEmail=$arUser["EMAIL"];
				}
				$Message[]=GetMessage("REPORT_TXT_USER",array("#ID#"=>$arExtra["USER_ID"],"#NAME#"=>$arrName));
			}else{
				$Message[]=GetMessage("REPORT_TXT_NO_USER");
			}
			$Message[]=GetMessage("REPORT_TXT_TEST",array("#ID#"=>$arExtra["TEST_ID"],"#NAME#"=>$arExtra["TEST_NAME"]));
			if($arExtra["RESULT_ID"])
				$Message[]=GetMessage("REPORT_TXT_RESULT",array("#ID#"=>$arExtra["RESULT_ID"],"#NAME#"=>$arExtra["RESULT_NAME"]));
			else
				$Message[]=GetMessage("REPORT_TXT_NO_RESULT");
				
			if($ex_type_result=="Y"){
				switch ($arExtra["TEST_TYPE_RESULT"]){
					case 'summ':
						$Message[]=GetMessage("REPORT_TXT_SCORES",array("#SCORES#"=>$arExtra["SCORES"]));
						break;
					case 'aver':
						$Message[]=GetMessage("REPORT_TXT_AVERS_CORES",array("#AVERS_CORES#"=>$AverScores));
						break;
					case 'suer':
						$Message[]=GetMessage("REPORT_TXT_SCORES",array("#SCORES#"=>$arExtra["SCORES"]));
						$Message[]=GetMessage("REPORT_TXT_AVERS_CORES",array("#AVERS_CORES#"=>$AverScores));
						break;
				}; 
			}else{
				$Message[]=GetMessage("REPORT_TXT_SCORES",array("#SCORES#"=>$arExtra["SCORES"]));
			}
				
			
			$Message[]=GetMessage("REPORT_TXT_DATE_START",array("#DATE#"=>$arExtra["DATE_START"]));
			$Message[]=GetMessage("REPORT_TXT_DATE_STOP",array("#DATE#"=>$arExtra["DATE_STOP"]));
			$Message[]=GetMessage("REPORT_TXT_DURATION",array("#DURATION#"=>AelitaTestTools::GetTxtTime($arExtra["DURATION"])));

			$Message[]="";
			$Message[]=GetMessage("REPORT_SEPARATOR");
			$Message[]=GetMessage("REPORT_ANSWERS");
			$Message[]=GetMessage("REPORT_SEPARATOR");
			$Message[]="";
			
			$arFilter=array("QUESTIONING_ID"=>$ID);

			$gl=new AelitaTestGlasses();
			$arSelect=array("ID","QUESTION_ID","QUESTION_NAME","SCORES","SERIALIZED_RESULT","COMMENTS","QUESTION_DESCRIPTION");
			$arGroup=array("ID");
			$dbResultList = $gl->GetList(array("ID"=>"ASC"),$arFilter,$arGroup,false,$arSelect);
			while ($arElement = $dbResultList->GetNext())
			{
				$SerializedResult=$arElement["SERIALIZED_RESULT"];
				if($SerializedResult)
				{
					$SerializedResult=unserialize(base64_decode($SerializedResult));
					$SerializedResult=implode(",",$SerializedResult);
				}
				
				$Message[]=GetMessage("REPORT_TXT_ANSWERS_QUESTION",array("#QUESTION#"=>$arElement["~QUESTION_NAME"]));
				if(strlen($arElement["~QUESTION_DESCRIPTION"])>0)
					$Message[]=$arElement["~QUESTION_DESCRIPTION"];
				$Message[]=GetMessage("REPORT_TXT_ANSWERS_COMMENTS",array("#COMMENTS#"=>$arElement["COMMENTS"]));
				$Message[]=GetMessage("REPORT_TXT_ANSWERS_ANSWER",array("#ANSWER#"=>$SerializedResult));
				$Message[]=GetMessage("REPORT_TXT_ANSWERS_SCORES",array("#SCORES#"=>$arElement["SCORES"]));

				$Message[]="";
			}
			
			$Message[]=GetMessage("REPORT_SEPARATOR");
		}

		foreach($Message as &$m){
			$m=HTMLToTxt($m,"",array(),false);
		}unset($m);

		$Message=implode("\r\n",$Message);
		
		$elr=new AelitaTestResponsible();
		$props = $elr->GetList(array(), array("TEST_ID"=>$arExtra["TEST_ID"]));
		while($p=$props->GetNext())
		{
			if($p["USER_ID"]>0)
			{
				$rsUser = CUser::GetByID($p["USER_ID"]);
				if($arUser=$rsUser->Fetch()){
					$arEventFields = array(
						"EMAIL"=>$arUser["EMAIL"],
						"TEXT_REPORT"=>$Message,
					);
					CEvent::Send("AELITA_TEST_WIN_TEST",SITE_ID,$arEventFields);
				}
			}
		}
        if($userId>0 && $TestId>0 && $userEmail)
        {
            $el=new AelitaTestTest();
            $arFilterTest=array("ID"=>$TestId);
            $resTest=$el->GetList(array("ID"=>"ASC"),$arFilterTest);
            if ($arTest=$resTest->GetNext())
            {
                if($arTest["COUNT_USER_AUTOR"]=="Y")
                {
                    CEvent::Send("AELITA_TEST_WIN_TEST",SITE_ID,array(
                        "EMAIL"=>$userEmail,
                        "TEXT_REPORT"=>$Message,
                    ));
                }
            }
        }
	}
	
	function ChekAnswer(&$AllQuestion,&$Answer,&$Step,&$Questioning)
	{
        if($Questioning["STEP_MULTIPLE"]=="step")
        {
            foreach($AllQuestion as &$Question)
            {
                if($Question["TEST_TYPE"]=="check" || $Question["TEST_TYPE"]=="radio")
                {
                    foreach($Answer[$Question["ID"]] as &$val)
                    {
                        if($Step["QUESTION"][$Question["ID"]] && in_array($val["NAME"],$Step["QUESTION"][$Question["ID"]]))
                            $val["CHECKED"]="Y";
                    }unset($val);
                }elseif($Question["TEST_TYPE"]=="input"){
                    if($Step["QUESTION"][$Question["ID"]])
                        $Question["VAL"]=implode("",$Step["QUESTION"][$Question["ID"]]);
                    else
                        $Question["VAL"]="";
                }
            }unset($Question);
        }else{
            if($AllQuestion["TEST_TYPE"]=="check" || $AllQuestion["TEST_TYPE"]=="radio")
            {
                foreach($Answer as &$val)
                {
                    if($Step["QUESTION"][$AllQuestion["ID"]] && in_array($val["NAME"],$Step["QUESTION"][$AllQuestion["ID"]]))
                        $val["CHECKED"]="Y";
                }unset($val);
            }elseif($AllQuestion["TEST_TYPE"]=="input"){
                if($Step["QUESTION"][$AllQuestion["ID"]])
                    $AllQuestion["VAL"]=implode("",$Step["QUESTION"][$AllQuestion["ID"]]);
                else
                    $AllQuestion["VAL"]="";
            }
        }

	}
	
}




