<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="test_list">
<?if(count($arResult["ITEMS"])>0){?>
	<?if($arParams["DISPLAY_TOP_PAGER"]):?>
		<?=$arResult["NAV_STRING"]?><br />
	<?endif;?>
	<?foreach($arResult["ITEMS"] as $item){?>
		<div class="test_item">
			<div class="test_item_name"><a href="<?=$item["DETAIL_URL"]?>" title="<?=$item["TEST_NAME"]?>"><?=$item["TEST_NAME"]?></div></a>
			<?if(is_array($item["TEST_PICTURE"])):?>
				<img class="detail_picture" border="0" src="<?=$item["TEST_PICTURE"]["SRC"]?>" width="<?=$item["TEST_PICTURE"]["WIDTH"]?>" height="<?=$item["TEST_PICTURE"]["HEIGHT"]?>" alt="<?=$item["TEST_NAME"]?>"  title="<?=$item["TEST_NAME"]?>" />
			<?endif?>
			<div class="test_item_maximum_points"><b><?=GetMessage("MAXIMUM_POINTS")?>: <?=$item["SCORES"];?></b></div>
			<div class="test_item_description"><?echo $item["TEST_DESCRIPTION"];?></div>
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
</div>