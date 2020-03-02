<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="test">
	<h3>
		<?=$arResult["TEST"]["NAME"]?>
	</h3>
	<?if($arResult["TEST"]["NUMBER_ATTEMPTS"]>0){?>
	<p class="step">
		<?=GetMessage("MAX_COUNT",array("#NUM#"=>$arResult["COUNT_QUESTIONING"],"#MAX#"=>$arResult["TEST"]["NUMBER_ATTEMPTS"]))?>
	</p>
	<?}?>
	<h4>
		<?=$arResult["RESULT"]["NAME"]?>
	</h4>
	<?if(is_array($arResult["RESULT"]["PICTURE"])):?>
		<img class="detail_picture" border="0" src="<?=$arResult["RESULT"]["PICTURE"]["SRC"]?>" width="<?=$arResult["RESULT"]["PICTURE"]["WIDTH"]?>" height="<?=$arResult["RESULT"]["PICTURE"]["HEIGHT"]?>" alt="<?=$arResult["RESULT"]["NAME"]?>"  title="<?=$arResult["RESULT"]["NAME"]?>" />
	<?endif?>
	<p><?echo $arResult["RESULT"]["DESCRIPTION"];?></p>
	<form action="<?=POST_FORM_ACTION_URI?>?testaction=Y" method="post" enctype="multipart/form-data">
		<input type="hidden" name="reinitquestioning" value="Y">
		<input type="submit" name="testsubmit" value="<?echo GetMessage("INIT_QUESTIONING")?>">
	</form>
</div>
<?if(strlen($arParams["PROFILE_DETAIL_URL"])>0){?>
	<div class="list_page">
		<a href="<?=$arParams["PROFILE_DETAIL_URL"]?>"><?=GetMessage("PROFILE_DETAIL_URL")?></a>
	</div>
<?}?>
<?if(strlen($arParams["LIST_PAGE_URL"])>0){?>
	<div class="list_page">
		<a href="<?=$arParams["LIST_PAGE_URL"]?>"><?=GetMessage("LIST_PAGE")?></a>
	</div>
<?}?>