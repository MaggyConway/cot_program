<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="test">
<?if (count($arResult["ERROR"])):?>
	<?=ShowError(implode("<br />", $arResult["ERROR"]))?>
<?endif?>
	<h3>
		<?=$arResult["TEST"]["NAME"]?>
	</h3>
	<?if(is_array($arResult["TEST"]["PICTURE"])):?>
		<img class="detail_picture" border="0" src="<?=$arResult["TEST"]["PICTURE"]["SRC"]?>" width="<?=$arResult["TEST"]["PICTURE"]["WIDTH"]?>" height="<?=$arResult["TEST"]["PICTURE"]["HEIGHT"]?>" alt="<?=$arResult["TEST"]["NAME"]?>"  title="<?=$arResult["TEST"]["NAME"]?>" />
	<?endif?>
	<p><?echo $arResult["TEST"]["DESCRIPTION"];?></p>
	<form action="<?=POST_FORM_ACTION_URI?>?testaction=Y" method="post" enctype="multipart/form-data">
		<input type="hidden" name="initquestioning" value="Y">
		<input type="submit" name="testsubmit" value="<?echo GetMessage("INIT_QUESTIONING")?>">
	</form>
</div>
<?if(strlen($arParams["LIST_PAGE_URL"])>0){?>
	<div class="list_page">
		<a href="<?=$arParams["LIST_PAGE_URL"]?>"><?=GetMessage("LIST_PAGE")?></a>
	</div>
<?}?>