<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$module_id="aelita.test";
if(!CModule::IncludeModule($module_id))
	return;

$arDefaultUrlTemplates404 = array(
	"groups" => "",
	"group" => "#GROUP_CODE#/",
	"test" => "#GROUP_CODE#/#TEST_CODE#/",
);

$arDefaultVariableAliases404 = array();

$arDefaultVariableAliases = array();

$arComponentVariables = array(
	"GROUP_CODE",
	"TEST_CODE",
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
		$componentPage = "groups";
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

	if(isset($arVariables["GROUP_CODE"]) && strlen($arVariables["GROUP_CODE"])>0 && isset($arVariables["TEST_CODE"]) && strlen($arVariables["TEST_CODE"])>0)
		$componentPage = "test";
	elseif(isset($arVariables["GROUP_CODE"]) && strlen($arVariables["GROUP_CODE"])>0)
		$componentPage = "group";
	else
		$componentPage = "groups";

	$arResult = array(
		"FOLDER" => "",
		"URL_TEMPLATES" => Array(
			"groups" => htmlspecialcharsbx($APPLICATION->GetCurPage()),
			"group" => htmlspecialcharsbx($APPLICATION->GetCurPage()."?".$arVariableAliases["GROUP_CODE"]."=#GROUP_CODE#"),
			"test" => htmlspecialcharsbx($APPLICATION->GetCurPage()."?".$arVariableAliases["TEST_CODE"]."=#TEST_CODE#"."&".$arVariableAliases["GROUP_CODE"]."=#GROUP_CODE#"),
		),
		"VARIABLES" => $arVariables,
		"ALIASES" => $arVariableAliases
	);
}
$this->IncludeComponentTemplate($componentPage);
?>