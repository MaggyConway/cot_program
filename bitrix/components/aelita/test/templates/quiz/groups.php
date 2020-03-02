<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if($arParams["SHOW_ALL"]=="Y"){?>
	<?$ListParam=array(
		"SHOW_ALL"=>$arParams["SHOW_ALL"],
		"AJAX_MODE"=>$arParams["AJAX_MODE"],
		"AJAX_OPTION_JUMP"=>$arParams["AJAX_OPTION_JUMP"],
		"AJAX_OPTION_STYLE"=>$arParams["AJAX_OPTION_STYLE"],
		"AJAX_OPTION_HISTORY"=>$arParams["AJAX_OPTION_HISTORY"],
		"CACHE_TYPE"=>$arParams["CACHE_TYPE"],
		"CACHE_TIME"=>$arParams["CACHE_TIME"],
		"CACHE_GROUPS"=>$arParams["CACHE_GROUPS"],
		"COUNT_TEST"=>$arParams["COUNT_TEST"],
		"DISPLAY_TOP_PAGER"=>$arParams["DISPLAY_TOP_PAGER"],
		"DISPLAY_BOTTOM_PAGER"=>$arParams["DISPLAY_BOTTOM_PAGER"],
		"PAGER_TITLE"=>$arParams["PAGER_TITLE"],
		"PAGER_SHOW_ALWAYS"=>$arParams["PAGER_SHOW_ALWAYS"],
		"PAGER_TEMPLATE"=>$arParams["PAGER_TEMPLATE"],
		"PAGER_DESC_NUMBERING"=>$arParams["PAGER_DESC_NUMBERING"],
		"PAGER_DESC_NUMBERING_CACHE_TIME"=>$arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
		"PAGER_SHOW_ALL"=>$arParams["PAGER_SHOW_ALL"],
		"AJAX_OPTION_ADDITIONAL"=>$arParams["AJAX_OPTION_ADDITIONAL"],
		//"TEST_GROUP" => "",
		"LIST_PAGE_URL" => $arResult["FOLDER"],
		"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["test"],
		);?>
	<?$APPLICATION->IncludeComponent(
		"aelita:test.list",
		"",
		$ListParam,
		$component
	);?>
<?}else{?>
	<?$GropParam=array(
		"AJAX_MODE"=>$arParams["AJAX_MODE"],
		"AJAX_OPTION_JUMP"=>$arParams["AJAX_OPTION_JUMP"],
		"AJAX_OPTION_STYLE"=>$arParams["AJAX_OPTION_STYLE"],
		"AJAX_OPTION_HISTORY"=>$arParams["AJAX_OPTION_HISTORY"],
		"CACHE_TYPE"=>$arParams["CACHE_TYPE"],
		"CACHE_TIME"=>$arParams["CACHE_TIME"],
		"CACHE_GROUPS"=>$arParams["CACHE_GROUPS"],
		"AJAX_OPTION_ADDITIONAL"=>$arParams["AJAX_OPTION_ADDITIONAL"],
		"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["group"],
		);?>
	<?$APPLICATION->IncludeComponent(
		"aelita:test.groups",
		"",
		$GropParam,
		$component
		);?>
	<?if($arParams["SHOW_NO_GROUP"]=="Y"){?>
		<?$ListParam=array(
			"SHOW_NO_GROUP"=>$arParams["SHOW_NO_GROUP"],
			"AJAX_MODE"=>$arParams["AJAX_MODE"],
			"AJAX_OPTION_JUMP"=>$arParams["AJAX_OPTION_JUMP"],
			"AJAX_OPTION_STYLE"=>$arParams["AJAX_OPTION_STYLE"],
			"AJAX_OPTION_HISTORY"=>$arParams["AJAX_OPTION_HISTORY"],
			"CACHE_TYPE"=>$arParams["CACHE_TYPE"],
			"CACHE_TIME"=>$arParams["CACHE_TIME"],
			"CACHE_GROUPS"=>$arParams["CACHE_GROUPS"],
			"COUNT_TEST"=>$arParams["COUNT_TEST"],
			"DISPLAY_TOP_PAGER"=>$arParams["DISPLAY_TOP_PAGER"],
			"DISPLAY_BOTTOM_PAGER"=>$arParams["DISPLAY_BOTTOM_PAGER"],
			"PAGER_TITLE"=>$arParams["PAGER_TITLE"],
			"PAGER_SHOW_ALWAYS"=>$arParams["PAGER_SHOW_ALWAYS"],
			"PAGER_TEMPLATE"=>$arParams["PAGER_TEMPLATE"],
			"PAGER_DESC_NUMBERING"=>$arParams["PAGER_DESC_NUMBERING"],
			"PAGER_DESC_NUMBERING_CACHE_TIME"=>$arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
			"PAGER_SHOW_ALL"=>$arParams["PAGER_SHOW_ALL"],
			"AJAX_OPTION_ADDITIONAL"=>$arParams["AJAX_OPTION_ADDITIONAL"],
			//"TEST_GROUP" => "0",
			"LIST_PAGE_URL" => $arResult["FOLDER"],
			"DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["test"],
			);?>
		<br /><br />
		<?$APPLICATION->IncludeComponent(
			"aelita:test.list",
			"",
			$ListParam,
			$component
		);?>
	<?}?>
<?}?>