<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$APPLICATION->IncludeComponent(
    "aelita:test.profile.questioning",
    "quiz",
    array(
        "TEST_GROUP" => "",
        "TEST_ID" => $arResult["TEST"]["ID"],
        "QUESTIONING_ID" => $arResult["QUESTIONING"]["ID"],
        "ADD_TEST_CHAIN" => "N",
        "SET_TITLE_TEST" => "N",
        "ADD_QUESTIONING_CHAIN" => "N",
        "SET_TITLE_QUESTIONING" => "N",
        "TEST_URL" => "",
        "DETAIL_URL" => $arParams["QUESTIONING_URL"],
        "REPEATED_URL" => "",
        "NON_CHECK_USER" => "Y",
        "SHOW_LINK" => "Y"
    ),
    false
);?>

    <form action="<?//=POST_FORM_ACTION_URI?>?testaction=Y" method="post" enctype="multipart/form-data">
        <input type="hidden" name="reinitquestioning" value="Y">
        <input type="submit" name="testsubmit" value="<?echo GetMessage("INIT_QUESTIONING")?>">
    </form>
