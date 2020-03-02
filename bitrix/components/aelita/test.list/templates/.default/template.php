<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="test_list">
<?if(count($arResult["ITEMS"])>0){?>
	<?if($arParams["DISPLAY_TOP_PAGER"]):?>
		<?=$arResult["NAV_STRING"]?><br />
	<?endif;?>
	<?foreach($arResult["ITEMS"] as $item){?>
		<div class="test_item">
			<div class="test_item_name"><a href="<?=$item["DETAIL_URL"]?>" title="<?=$item["NAME"]?>"><?=$item["NAME"]?></div></a>
			<?if(is_array($item["PICTURE"])):?>
				<img class="detail_picture" border="0" src="<?=$item["PICTURE"]["SRC"]?>" width="<?=$item["PICTURE"]["WIDTH"]?>" height="<?=$item["PICTURE"]["HEIGHT"]?>" alt="<?=$item["NAME"]?>"  title="<?=$item["NAME"]?>" />
			<?endif?>
			<div class="test_item_description"><?echo $item["DESCRIPTION"];?></div>
			<div class="test_item_link">
				<a href="<?=$item["DETAIL_URL"]?>" title="<?=GetMessage("DETAIL_URL")?>"><?=GetMessage("DETAIL_URL")?></a>
			</div>
		</div>
	<?}?>
	<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
		<br /><?=$arResult["NAV_STRING"]?>
	<?endif;?>
<?}else{?>
	<div class="test_item">
		<?=GetMessage("NO_TEST")?>
	</div>
<?}?>
<?if($arResult["GROUP"] && $arParams["MAIN_PAGE_URL"]){?>
	<div class="test_item_link">
		<a href="<?=$arParams["MAIN_PAGE_URL"]?>" title="<?=GetMessage("MAIN_URL")?>"><?=GetMessage("MAIN_URL")?></a>
	</div>
<?}?>
</div>