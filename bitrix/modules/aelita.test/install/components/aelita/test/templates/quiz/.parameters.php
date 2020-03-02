<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$module_id="aelita.test";
if(CModule::IncludeModule($module_id))
{
    $arTemplateParameters  = array(
        "QUESTIONING_URL" => array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("QUESTIONING_URL"),
            "TYPE" => "CUSTOM",
            "DEFAULT" => '',
            "JS_FILE"=>"/bitrix/js/iblock/path_templates.js",
            "JS_EVENT"=>"IBlockComponentProperties",
            "JS_DATA"=>AelitaTestTools::GetJsUrl("DETAIL_URL",array("TEST_CODE","QUESTIONING_CODE")),
        ),
    );
}


?>
