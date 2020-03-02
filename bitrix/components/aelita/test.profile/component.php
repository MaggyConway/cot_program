<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$module_id="aelita.test";
if(!CModule::IncludeModule($module_id))
	return;

$arDefaultUrlTemplates404 = array(
	"tests" => "",
	"test" => "#TEST_CODE#/",
	"questioning" => "#TEST_CODE#/#QUESTIONING_CODE#/",
);

$arDefaultVariableAliases404 = array();

$arDefaultVariableAliases = array();

$arComponentVariables = array(
	"TEST_CODE",
	"QUESTIONING_CODE",
);

if($arParams["SEF_MODE"] == "Y")
{
	$arVariables = array();
	$arUrlTemplates = CComponentEngine::MakeComponentUrlTemplates($arDefaultUrlTemplates404, $arParams["SEF_URL_TEMPLATES"]);
	$arVariableAliases = CComponentEngine::MakeComponentVariableAliases($arDefaultVariableAliases404, $arParams["VARIABLE_ALIASES"]);

	$engine = new CComponentEngine($this);

	$componentPage = $engine->guessComponentPath(
		$arParams["SEF_FOLDER"],
		$arUrlTemplates,
		$arVariables
	);

	$b404 = false;
	if(!$componentPage)
	{
		$componentPage = "tests";
		$b404 = true;
	}
	
	if($b404 && $arParams["SET_STATUS_404"]==="Y")
	{
		$folder404 = str_replace("\\", "/", $arParams["SEF_FOLDER"]);
		if ($folder404 != "/")
			$folder404 = "/".trim($folder404, "/ \t\n\r\0\x0B")."/";
		if (substr($folder404, -1) == "/")
			$folder404 .= "index.php";

			if($folder404 != $APPLICATION->GetCurPage(true))
			CHTTP::SetStatus("404 Not Found");
	}

	CComponentEngine::InitComponentVariables($componentPage, $arComponentVariables, $arVariableAliases, $arVariables);

	$arResult = array(
		"FOLDER" => $arParams["SEF_FOLDER"],
		"URL_TEMPLATES" => $arUrlTemplates,
		"VARIABLES" => $arVariables,
		"ALIASES" => $arVariableAliases,
	);
}
else
{
	$arVariableAliases = CComponentEngine::MakeComponentVariableAliases($arDefaultVariableAliases, $arParams["VARIABLE_ALIASES"]);
	
	CComponentEngine::InitComponentVariables(false, $arComponentVariables, $arVariableAliases, $arVariables);

	$componentPage = "";

	if(isset($arVariables["QUESTIONING_CODE"]) && strlen($arVariables["QUESTIONING_CODE"])>0 && isset($arVariables["TEST_CODE"]) && strlen($arVariables["TEST_CODE"])>0)
		$componentPage = "questioning";
	elseif(isset($arVariables["TEST_CODE"]) && strlen($arVariables["TEST_CODE"])>0)
		$componentPage = "test";
	else
		$componentPage = "tests";

	$arResult = array(
		"FOLDER" => "",
		"URL_TEMPLATES" => Array(
			"tests" => htmlspecialcharsbx($APPLICATION->GetCurPage()),
			"test" => htmlspecialcharsbx($APPLICATION->GetCurPage()."?".$arVariableAliases["TEST_CODE"]."=#TEST_CODE#"),
			"questioning" => htmlspecialcharsbx($APPLICATION->GetCurPage()."?".$arVariableAliases["TEST_CODE"]."=#TEST_CODE#"."&".$arVariableAliases["QUESTIONING_CODE"]."=#QUESTIONING_CODE#"),
		),
		"VARIABLES" => $arVariables,
		"ALIASES" => $arVariableAliases
	);
}
$this->IncludeComponentTemplate($componentPage);
?>