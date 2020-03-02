<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$module_id="aelita.test";
if(!CModule::IncludeModule($module_id))
	return;

$arResult = array();

if(!isset($arParams["CACHE_TIME"]))
	$arParams["CACHE_TIME"] = 36000;

global $USER;
global $APPLICATION;
$USER_ID=$USER->GetID();
$arGroups=CUser::GetUserGroup($USER_ID);

$arParams["PROFILE_DETAIL_URL"]=trim($arParams["PROFILE_DETAIL_URL"]);

$arParams["TEST_GROUP"]=trim($arParams["TEST_GROUP"]);
$arParams["TEST_ID"]=trim($arParams["TEST_ID"]);

$arParams["LIST_PAGE_URL"]=trim($arParams["LIST_PAGE_URL"]);
$arParams["DETAIL_URL"]=trim($arParams["DETAIL_URL"]);

$arParams["ADD_GROUP_CHAIN"] = $arParams["ADD_GROUP_CHAIN"]=="Y";
$arParams["SET_TITLE_GROUP"] = $arParams["SET_TITLE_GROUP"]=="Y";
$arParams["ADD_TEST_CHAIN"] = $arParams["ADD_TEST_CHAIN"]=="Y";
$arParams["SET_TITLE_TEST"] = $arParams["SET_TITLE_TEST"]=="Y";

$arResult["ID_GROUP"]=(int)$arParams["TEST_GROUP"];
if(strlen($arParams["TEST_GROUP"])>0 && $arParams["TEST_GROUP"]<>"0")
	$arResult["CODE_GROUP"]=$arParams["TEST_GROUP"];

$FilterGroup=array();
if(strlen($arResult["CODE_GROUP"])>0)
	$FilterGroup["CODE"]=$arResult["CODE_GROUP"];
elseif($arResult["ID_GROUP"]>0)
	$FilterGroup["ID"]=$arResult["ID_GROUP"];

if(count($FilterGroup)>0)
{
	$FilterGroup["ACTIVE"]="Y";
	$el=new AelitaTestGroup();
	$res=$el->GetList(array(),$FilterGroup,false,array("nPageSize"=>1),[
        "ID",
        "XML_ID",
        "GROUP_ID",
        "NAME",
        "ACTIVE",
        "PICTURE",
        "ALT",
        "DESCRIPTION",
        "DESCRIPTION_TYPE",
        "SORT",
        "CODE",
        "ACCESS_ALL",
        "GROUP_NAME",
        "GROUP_CODE",
    ]);
	if($test=$res->GetNext())
	{
		$arResult["GROUP"]=$test;
	}elseif($arResult["ID_GROUP"]>0){
		unset($FilterGroup["CODE"]);
		$FilterGroup["ID"]=$arResult["ID_GROUP"];
		$res=$el->GetList(array(),$FilterGroup,false,array("nPageSize"=>1),[
            "ID",
            "XML_ID",
            "GROUP_ID",
            "NAME",
            "ACTIVE",
            "PICTURE",
            "ALT",
            "DESCRIPTION",
            "DESCRIPTION_TYPE",
            "SORT",
            "CODE",
            "ACCESS_ALL",
            "GROUP_NAME",
            "GROUP_CODE",
        ]);
		if($test=$res->GetNext())
			$arResult["GROUP"]=$test;
	}
}
	
if($arResult["GROUP"])
{
	$arResult["ID_GROUP"]=$arResult["GROUP"]["ID"];
	$Code="";
	if(strlen($arResult["GROUP"]["CODE"])>0)
		$Code=$arResult["GROUP"]["CODE"];
	else
		$Code=$arResult["GROUP"]["ID"];
	$arResult["GROUP"]["DETAIL_URL"]=str_replace("#GROUP_CODE#", $Code, $arParams["LIST_PAGE_URL"]);
	$arParams["DETAIL_URL"]=str_replace("#GROUP_CODE#", $Code, $arParams["DETAIL_URL"]);
	$arParams["LIST_PAGE_URL"]=str_replace("#GROUP_CODE#", $Code, $arParams["LIST_PAGE_URL"]);
}else{
	$arResult["GROUP"]["DETAIL_URL"]=str_replace("#GROUP_CODE#", 0, $arParams["LIST_PAGE_URL"]);
	$arParams["DETAIL_URL"]=str_replace("#GROUP_CODE#", 0, $arParams["DETAIL_URL"]);
	$arParams["LIST_PAGE_URL"]=str_replace("#GROUP_CODE#", 0, $arParams["LIST_PAGE_URL"]);
}

$arParams["CACHE_TIME"]=(int)$arParams["CACHE_TIME"];

if(is_numeric($arParams["TEST_ID"]))
{
    $arResult["TEST_ID"]=(int)$arParams["TEST_ID"];
}else{
    if(strlen($arParams["TEST_ID"])>0 && $arParams["TEST_ID"]<>"0")
        $arResult["CODE_TEST"]=$arParams["TEST_ID"];
}

$arrClearParam=array(
	"initquestioning",
	"testsubmit",
	"stepquestioning",
	"questionid",
	"answer",
	"testaction",
	"reinitquestioning",
	"prevtest",
	"setprevtest",
	"closequestioning_N",
	"closequestioning_Y",
	"closequestioning",
	);
$templatefile="non";
$arResult["ERROR"] =array();
$arResult["PROFAIL_ID"]=AelitaTestTools::GetIDProfail();

$TestGroup=array();
if(strlen($arResult["CODE_TEST"])>0)
	$TestGroup["CODE"]=$arResult["CODE_TEST"];
elseif($arResult["TEST_ID"]>0)
	$TestGroup["ID"]=$arResult["TEST_ID"];

if(count($TestGroup)>0)
{
	$TestGroup["ACTIVE"]="Y";
	if($arResult["ID_GROUP"]>0)
		$TestGroup["GROUP_ID"]=$arResult["ID_GROUP"];
	$el=new AelitaTestTest();
	$res=$el->GetList(array(),$TestGroup,false,array("nPageSize"=>1));
	if($test=$res->GetNext())
	{
		$arResult["TEST"]=$test;
	}elseif($arResult["TEST_ID"]>0){
		unset($TestGroup["CODE"]);
		$TestGroup["ID"]=$arResult["TEST_ID"];
		$res=$el->GetList(array(),$TestGroup,false,array("nPageSize"=>1));
		if($test=$res->GetNext())
			$arResult["TEST"]=$test;
	}
}
	
if($arResult["TEST"])
{
	$arResult["TEST_ID"]=$arResult["TEST"]["ID"];
	$Code="";
	if(strlen($arResult["TEST"]["CODE"])>0)
		$Code=$arResult["TEST"]["CODE"];
	else
		$Code=$arResult["TEST"]["ID"];
	$arResult["TEST"]["DETAIL_URL"]=str_replace("#TEST_CODE#", $Code, $arParams["DETAIL_URL"]);
}

if($arResult["TEST"])
{
	$Access=array();
	if($arResult["TEST"]["ACCESS_GROUP"]!="Y")
	{
		if($arResult["TEST"]["ACCESS_ALL"]!="Y")
		{
			$resAccess=new AelitaTestAccessTest();
			$props = $resAccess->GetList(array(), array("TEST_ID"=>$arResult["TEST"]["ID"]));
			while($p = $props->GetNext())
				$Access[]=$p["USER_GROUP_ID"];
		}
	}elseif($arResult["GROUP"]){
		if($arResult["GROUP"]["ACCESS_ALL"]!="Y")
		{
			$resAccess=new AelitaTestAccessGroup();
			$props = $resAccess->GetList(array(), array("GROUP_ID"=>$arResult["GROUP"]["ID"]));
			while($p = $props->GetNext())
				$Access[]=$p["USER_GROUP_ID"];
		}
	}
	if(count($Access)>0)
	{
		$Сonvergence = array_intersect($Access, $arGroups);
		if(count($Сonvergence)<=0)
		{
			$Result["HIDE_RESULT"]="Y";
			$arResult["NO_ACCESS"]="Y";
		}
	}
}

if($arResult["TEST"])
{
	$Date=ConvertDateTime(GetTime(time(),"FULL"), "YYYY-MM-DD HH:MI:SS");
	if(
		(!$arResult["TEST"]["DATE_FROM"] && !$arResult["TEST"]["DATE_TO"]) || 
		($arResult["TEST"]["DATE_FROM"]<$Date && !$arResult["TEST"]["DATE_TO"]) || 
		(!$arResult["TEST"]["DATE_FROM"] && $arResult["TEST"]["DATE_TO"]>$Date) || 
		($arResult["TEST"]["DATE_FROM"]<$Date && $arResult["TEST"]["DATE_TO"]>$Date)
	){
		//$Result["HIDE_RESULT"]="N";
	}else{
		$Result["HIDE_RESULT"]="Y";
		$arResult["NO_DATE"]="Y";
	}
}

function Msklonenie($n,$forms){
	return $n%10==1&&$n%100!=11?$forms[0]:($n%10>=2&&$n%10<=4&&($n%100<10||$n%100>=20)?$forms[1]:$forms[2]);
}

$forms=array(GetMessage("TSBS_PRODUCT_1"),GetMessage("TSBS_PRODUCT_2"),GetMessage("TSBS_PRODUCT_3"));
$forms_T=array(GetMessage("TSBS_PRODUCT_1_T"),GetMessage("TSBS_PRODUCT_2_T"),GetMessage("TSBS_PRODUCT_3_T"));

if($arResult["TEST_ID"]>0 && $arResult["PROFAIL_ID"]["ID"]>0)
{
	$arResult["COUNT_QUESTIONING"]=AelitaTestTools::GetCountQuestioning($arResult["PROFAIL_ID"]["ID"],$arResult["TEST"]["ID"],$arResult["TEST"]["PERIOD_ATTEMPTS"]);
	$arResult["COUNT_BY_FINISH"]=COption::GetOptionString($module_id,"count_test_result_by_finish","N");
	$arResult["ALLOW_PASSED_BACK"]=COption::GetOptionString($module_id,"aelita_test_allow_passed_back","N");
	$arResult["REQUIRE_CONFIRMATION_COMPLETION"]=COption::GetOptionString($module_id,"require_confirmation_completion","N");

	$arResult['TEST_RULE']=array();
	$arResult['TEST_RULE'][]=GetMessage("TEST_RULE_NEXT");
	$arResult['TEST_RULE'][]=GetMessage("TEST_RULE_PREW");
	$arResult['TEST_RULE'][]=GetMessage("TEST_RULE_CLOSE");
	if($arResult["TEST"]["TEST_TIME"]>0)
		$arResult['TEST_RULE'][]=GetMessage("TEST_RULE_TIME",array("#TIME#"=>$arResult["TEST"]["TEST_TIME"]." ".Msklonenie($arResult["TEST"]["TEST_TIME"],$forms)));
	if($arResult["TEST"]["NUMBER_ATTEMPTS"]>0)
		$arResult['TEST_RULE'][]=GetMessage("TEST_RULE_MAX",array("#NUM#"=>$arResult["TEST"]["NUMBER_ATTEMPTS"]));
		
	if($arResult["TEST"]["PERIOD_ATTEMPTS"]>0)
		$arResult['TEST_RULE'][]=GetMessage("TEST_RULE_MAZ_T",array("#TIME#"=>$arResult["TEST"]["PERIOD_ATTEMPTS"]." ".Msklonenie($arResult["TEST"]["PERIOD_ATTEMPTS"],$forms_T)));

	if($arParams["PROFILE_DETAIL_URL"])
		$arParams["PROFILE_DETAIL_URL"]=str_replace("#TEST_CODE#", $arResult["TEST"]["ID"], $arParams["PROFILE_DETAIL_URL"]);
	if($arResult["TEST"]["ID"]>0 && $Result["HIDE_RESULT"]!="Y")
	{
		$templatefile="init";
		if($arResult["TEST"]["PICTURE"])
			$arResult["TEST"]["PICTURE"] = AelitaTestTools::GetWatermarkPicture($arResult["TEST"]["PICTURE"],$arResult["TEST"]["ALT"]);

        if($arResult["TEST"]["SPONSOR_PICTURE"])
            $arResult["TEST"]["SPONSOR_PICTURE"] = AelitaTestTools::GetWatermarkPicture($arResult["TEST"]["SPONSOR_PICTURE"],$arResult["TEST"]["SPONSOR_ALT"]);
				
		$arResult["QUESTIONING"]=AelitaTestTools::GetQuestioning($arResult["PROFAIL_ID"]["ID"],$arResult["TEST"]);
		if($arResult["QUESTIONING"]["ID"]>0)
		{
			if($arResult["TEST"]["TEST_TIME"]>0)
			{
				$arResult["TIME_CLOSE"]=MakeTimeStamp($arResult["QUESTIONING"]["DATE_START"], "YYYY-MM-DD HH:MI:SS");
				$arResult["TIME_CLOSE"]=$arResult["TIME_CLOSE"]+$arResult["TEST"]["TEST_TIME"]*60;
				$arResult["TIME_ACTUAL"]=$arResult["TIME_CLOSE"]-time();
				if(time()>=$arResult["TIME_CLOSE"] && $arResult["QUESTIONING"]["FINAL"]!="Y")
				{
					AelitaTestTools::InitResult($arResult["QUESTIONING"]);
					LocalRedirect($APPLICATION->GetCurPageParam("",$arrClearParam));
				}
			}
			
			if($arParams["PROFILE_DETAIL_URL"])
				$arParams["PROFILE_DETAIL_URL"]=str_replace("#QUESTIONING_CODE#", $arResult["QUESTIONING"]["ID"], $arParams["PROFILE_DETAIL_URL"]);
			if(isset($_REQUEST["prevtest"]) && isset($_REQUEST["setprevtest"]))	
			{



				if($arResult["ALLOW_PASSED_BACK"]=="Y"){
					if(isset($_REQUEST["answer"]))
					{

                        if($arResult["QUESTIONING"]["STEP_MULTIPLE"]=="step")
                        {

                            $Question=AelitaTestTools::MultiCheckResponse($arResult,$arrClearParam,true,true);
                            AelitaTestTools::SetQuestion($arResult["QUESTIONING"]["ID"],$arResult["TEST"]["ID"],(int)$_REQUEST["setprevtest"]);

                        }else{
                            $questionid=(int)$_REQUEST["questionid"];
                            if($questionid>0)
                                $Question=AelitaTestTools::CheckResponse($questionid,$arResult,$arrClearParam,true,true);

                        }

					}else{
						AelitaTestTools::SetQuestion($arResult["QUESTIONING"]["ID"],$arResult["TEST"]["ID"],(int)$_REQUEST["setprevtest"]);

					}

				}else{

                }

                LocalRedirect($APPLICATION->GetCurPageParam("",$arrClearParam));
			}elseif(isset($_REQUEST["closequestioning"]) && $_REQUEST["stepquestioning"]==="Y" && isset($_REQUEST["questionid"])){
				
				if(isset($_REQUEST["answer"]) && $arResult["QUESTIONING"]["RESULT_ID"]<=0)
				{

                    if($arResult["QUESTIONING"]["STEP_MULTIPLE"]=="step")
                    {

                        $Question=AelitaTestTools::MultiCheckResponse($arResult,$arrClearParam,false,false);
                    }else{
                        $questionid=(int)$_REQUEST["questionid"];
                        if($questionid>0)
                            $Question=AelitaTestTools::CheckResponse($questionid,$arResult,$arrClearParam,false,false);
                    }

				}
				
				if($arResult["REQUIRE_CONFIRMATION_COMPLETION"]=='Y' && !isset($_REQUEST["closequestioning_Y"]))
				{
					if(isset($_REQUEST["closequestioning_N"]))
						LocalRedirect($APPLICATION->GetCurPageParam("",$arrClearParam));
					$arResult["REQUIRE_CONFIRMATION"]="Y";
				}elseif($arResult["COUNT_BY_FINISH"]=="Y"){
					if($arResult["QUESTIONING"]["RESULT_ID"]<=0)
						AelitaTestTools::InitResult($arResult["QUESTIONING"]);
					LocalRedirect($APPLICATION->GetCurPageParam("",$arrClearParam));
				}else{
					AelitaTestTools::CloseQuestioning($arResult["PROFAIL_ID"]["ID"],$arResult["TEST"]["ID"]);
					LocalRedirect($APPLICATION->GetCurPageParam("",$arrClearParam));
				}

			}elseif(isset($_REQUEST["testsubmit"]) && $_REQUEST["stepquestioning"]==="Y" && isset($_REQUEST["questionid"]) && $arResult["QUESTIONING"]["RESULT_ID"]<=0){



				if(isset($_REQUEST["answer"]))
				{

                    if($arResult["QUESTIONING"]["STEP_MULTIPLE"]=="step")
                    {

                        $Question=AelitaTestTools::MultiCheckResponse($arResult,$arrClearParam,false,true);
                    }else{
                        $questionid=(int)$_REQUEST["questionid"];
                        if($questionid>0)
                            $Question=AelitaTestTools::CheckResponse($questionid,$arResult,$arrClearParam,false,true);
                    }

				}else{
					$arResult["ERROR"][]=GetMessage("ERR_NO_ANSWER");
				}


			}


			if(AelitaTestTools::ChekQuestion($Question,$arResult["QUESTIONING"]))
				$arResult["QUESTION"]=$Question;
			else {

                $arResult["QUESTION"] = AelitaTestTools::GetQuestion(
                    $arResult["QUESTIONING"],
                    $arResult["TEST"]
                );
            }

           // echo "<pre>";print_r($arResult["QUESTION"]);echo "</pre>";


			
			if(AelitaTestTools::ChekQuestion($arResult["QUESTION"],$arResult["QUESTIONING"]) && $arResult["QUESTIONING"]["RESULT_ID"]<=0)
			{

                AelitaTestTools::ChekComment($arResult["QUESTION"],$arResult["QUESTIONING"],$arResult["TEST"]);
				
				$templatefile="test";
				$arResult["STEP"]=AelitaTestTools::GetStepQuestion($arResult["QUESTIONING"],$arResult["TEST"]);
				$arResult["STEP"]["GLASSES"]=AelitaTestTools::GetStepList($arResult["STEP"]["GLASSES_LIST"],$arResult["QUESTION"],$arResult["QUESTIONING"]);
				$arResult["ANSWER"]=AelitaTestTools::GetAnswer($arResult["QUESTION"],$arResult["TEST"]["MIX_QUESTION"],$arResult["QUESTIONING"]);




				AelitaTestTools::ChekAnswer($arResult["QUESTION"],$arResult["ANSWER"],$arResult["STEP"],$arResult["QUESTIONING"]);
			
				if($arResult["ALLOW_PASSED_BACK"]=="Y")
				{
					$arResult["PREW_LIST"]=0;
					$arResult["NEXT_LIST"]=0;
					$arResult["ARR_PREW_LIST"]=array();
					if(AelitaTestTools::ChekQuestion($arResult["QUESTION"],$arResult["QUESTIONING"]) && count($arResult["STEP"]["GLASSES_LIST"])>0)
					{


                        if($arResult["QUESTIONING"]["STEP_MULTIPLE"]=="step")
                        {
                            for($i=0;$i<count($arResult["STEP"]["STEP_GLASSES_LIST"]);$i++)
                            {
                                $arResult["NEXT_LIST"]=$arResult["STEP"]["STEP_GLASSES_LIST"][$i]["ID"];

                                if(
									$arResult["STEP"]["STEP_GLASSES_LIST"][$i]["ID"]==$arResult["QUESTIONING"]["NUMBER_STEP"]
									||
									$arResult["STEP"]["STEP_GLASSES_LIST"][$i]["ID"]==$arResult["QUESTIONING"]["GLASSES_ID"]
								)
								{
									break;
								}else{
									//if($i>0)
										$arResult["PREW_LIST"]=$arResult["STEP"]["STEP_GLASSES_LIST"][$i]["ID"];
								}



                            }

                            $PrewList=0;

                            for($i=0;$i<count($arResult["STEP"]["STEP_GLASSES_LIST"]);$i++)
                            {

                                $PrewList=$arResult["STEP"]["STEP_GLASSES_LIST"][$i]["ID"];
                                $navigation=array(
                                    "LINK"=>$APPLICATION->GetCurPageParam("prevtest=Y&setprevtest=".$PrewList,$arrClearParam),
                                    "N"=>$i+1,
                                    "SET"=>$PrewList,
                                );
                                if($arResult["STEP"]["STEP_GLASSES_LIST"][$i]["OTV"]=="Y" || ($i>0 && $arResult["STEP"]["STEP_GLASSES_LIST"][$i-1]["OTV"]=="Y"))
                                    $navigation["SHOW_LINK"]="Y";
                                if($arResult["STEP"]["STEP_GLASSES_LIST"][$i]["OTV"]=="Y")
                                    $navigation["OTV"]="Y";
                                if($arResult["STEP"]["STEP_GLASSES_LIST"][$i]["QUESTION_ID"]==$arResult["QUESTIONING"]["NUMBER_STEP"])
                                    $navigation["SELECT"]="Y";
                                $arResult["ARR_PREW_LIST"][]=$navigation;
                            }

                        }else{
                            for($i=0;$i<count($arResult["STEP"]["GLASSES_LIST"]);$i++)
                            {
                                $arResult["NEXT_LIST"]=$arResult["STEP"]["GLASSES_LIST"][$i+1]["QUESTION_ID"];

                                if($arResult["STEP"]["GLASSES_LIST"][$i]["QUESTION_ID"]==$arResult["QUESTION"]["ID"])
                                    break;
                                //if($i>0)
                                $arResult["PREW_LIST"]=$arResult["STEP"]["GLASSES_LIST"][$i]["QUESTION_ID"];

                            }
                            $PrewList=0;

                            for($i=0;$i<count($arResult["STEP"]["GLASSES_LIST"]);$i++)
                            {

                                $PrewList=$arResult["STEP"]["GLASSES_LIST"][$i]["QUESTION_ID"];
                                $navigation=array(
                                    "LINK"=>$APPLICATION->GetCurPageParam("prevtest=Y&setprevtest=".$PrewList,$arrClearParam),
                                    "N"=>$i+1,
                                    "SET"=>$PrewList,
                                );
                                if($arResult["STEP"]["GLASSES_LIST"][$i]["OTV"]=="Y" || ($i>0 && $arResult["STEP"]["GLASSES_LIST"][$i-1]["OTV"]=="Y"))
                                    $navigation["SHOW_LINK"]="Y";
                                if($arResult["STEP"]["GLASSES_LIST"][$i]["OTV"]=="Y")
                                    $navigation["OTV"]="Y";
                                if($arResult["STEP"]["GLASSES_LIST"][$i]["QUESTION_ID"]==$arResult["QUESTION"]["ID"])
                                    $navigation["SELECT"]="Y";
                                $arResult["ARR_PREW_LIST"][]=$navigation;
                            }
                        }


					}
				}

                AelitaTestTools::PictureQuestion($arResult["QUESTION"],$arResult["QUESTIONING"]);






				if($arResult["REQUIRE_CONFIRMATION"]=="Y" && $templatefile=="test")
					$templatefile="require";
					
			}else{

				if((isset($_REQUEST["testsubmit"]) && $_REQUEST["reinitquestioning"]==="Y")/* || $arResult["TEST"]["AUTO_START_OVER"]=="Y"*/)
				{
                    // Начать заново
					AelitaTestTools::CloseQuestioning($arResult["PROFAIL_ID"]["ID"],$arResult["TEST"]["ID"]);
					LocalRedirect($APPLICATION->GetCurPageParam("",$arrClearParam));
				}
				
				$templatefile="result";
				
				if($arResult["QUESTIONING"]["RESULT_ID"]<=0)
					AelitaTestTools::InitResult($arResult["QUESTIONING"]);
					
				if($arResult["QUESTIONING"]["RESULT_ID"]<=0)
				{
					$templatefile="no_result";
				}else{
					$elResult=new AelitaTestResult();
					$resResult=$elResult->GetByID($arResult["QUESTIONING"]["RESULT_ID"]);
					if(!$arResult["RESULT"]=$resResult->GetNext())
						$templatefile="no_result";
				}
				if($arResult["RESULT"]["ID"]>0)
					if($arResult["RESULT"]["PICTURE"])
						$arResult["RESULT"]["PICTURE"] = AelitaTestTools::GetWatermarkPicture($arResult["RESULT"]["PICTURE"],$arResult["RESULT"]["ALT"]);

                if($arResult["TEST"]["AUTO_START_OVER"]=="Y")
                {
                    AelitaTestTools::CloseQuestioning($arResult["PROFAIL_ID"]["ID"],$arResult["TEST"]["ID"]);
                }

			}
		}elseif(isset($_REQUEST["testsubmit"]) && $_REQUEST["initquestioning"]==="Y"){
			$MaxCount=false;
			if($arResult["TEST"]["NUMBER_ATTEMPTS"]>0)
				if($arResult["COUNT_QUESTIONING"]>=$arResult["TEST"]["NUMBER_ATTEMPTS"])
					$MaxCount=true;
			if($MaxCount)
			{
				$arResult["ERROR"][]=GetMessage("ERR_MAX_COUNT",array("#NUM#"=>$arResult["TEST"]["NUMBER_ATTEMPTS"]));
			}else{
				AelitaTestTools::initQuestioning($arResult["PROFAIL_ID"]["ID"],$arResult["TEST"]);
				LocalRedirect($APPLICATION->GetCurPageParam("",$arrClearParam));
			}
		}
	}
}



if($arResult["GROUP"]["GROUP_ID"]>0){
    $code=$arResult["GROUP"]["GROUP_CODE"];
    if(strlen($code)<=0)
        $code=$arResult["GROUP"]["GROUP_ID"];
    $url=$arParams["~LIST_PAGE_URL"];
    $url=str_replace('#GROUP_CODE#',$code,$url);
    $url=str_replace('#GROUP_ID#',$arResult["GROUP"]["GROUP_ID"],$url);
    $arResult["GROUP"]["GROUP_PARENT_URL"]=$url;
}

//echo $arResult["TEST"]["MIX_QUESTION"];

$this->IncludeComponentTemplate($templatefile);

if($arParams["ADD_GROUP_CHAIN"] && $arResult["GROUP"]["NAME"]){
    if($arResult["GROUP"]["GROUP_ID"]>0)
        $APPLICATION->AddChainItem($arResult["GROUP"]["GROUP_NAME"],$arResult["GROUP"]["GROUP_PARENT_URL"]);
    $APPLICATION->AddChainItem($arResult["GROUP"]["NAME"],$arParams["LIST_PAGE_URL"]);
}

if($arParams["ADD_TEST_CHAIN"] && $arResult["TEST"]["NAME"])
	$APPLICATION->AddChainItem($arResult["TEST"]["NAME"],$arResult["TEST"]["DETAIL_URL"]);
	
if($arParams["SET_TITLE_GROUP"] && $arResult["GROUP"]["NAME"])
{
	$APPLICATION->SetTitle($arResult["GROUP"]["NAME"]);
	$APPLICATION->SetPageProperty('title',$arResult["GROUP"]["NAME"]);
}	

if($arParams["SET_TITLE_TEST"] && $arResult["TEST"]["NAME"])
{
	$APPLICATION->SetTitle($arResult["TEST"]["NAME"]);
$APPLICATION->SetPageProperty('title',$arResult["TEST"]["NAME"]);	
}

?>
