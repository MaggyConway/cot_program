<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="test">
	<h3>
		<?=$arResult["TEST"]["NAME"]?>
	</h3>
	<?if(is_array($arResult["TEST"]["PICTURE"])):?>
		<img class="detail_picture" border="0" src="<?=$arResult["TEST"]["PICTURE"]["SRC"]?>" width="<?=$arResult["TEST"]["PICTURE"]["WIDTH"]?>" height="<?=$arResult["TEST"]["PICTURE"]["HEIGHT"]?>" alt="<?=$arResult["TEST"]["NAME"]?>"  title="<?=$arResult["TEST"]["NAME"]?>" />
	<?endif?>
	<p><?echo $arResult["TEST"]["DESCRIPTION"];?></p>
	<?if($arParams["TESTS_URL"]){?>
	<div class="test_repeated">
		<a href="<?=$arParams["TESTS_URL"]?>" title="<?=GetMessage("TESTS_URL")?>"><?=GetMessage("TESTS_URL")?></a>
	</div>
	<?}?>
	<?if($arResult["TEST"]["SHOW_REPEATED"]=="Y"){?>
	<div class="test_repeated">
		<a href="<?=$arResult["TEST"]["REPEATED_URL"]?>" title="<?=GetMessage("TEST_REPEATED")?>"><?=GetMessage("TEST_REPEATED")?></a>
	</div>
	<?}?>
</div>
<div class="test_list">
<?if(count($arResult["ITEMS"])>0){?>
	<?if($arParams["DISPLAY_TOP_PAGER"]):?>
		<?=$arResult["NAV_STRING"]?><br />
	<?endif;?>
	<?foreach($arResult["ITEMS"] as $item){?>
		<div class="test_item">
			<div class="test_item_name"><a href="<?=$item["DETAIL_URL"]?>" title="<?=GetMessage("ATTEMPT_NUMBER")?><?=$item["DATE_START"]?>"><?=GetMessage("ATTEMPT_NUMBER")?><?=$item["DATE_START"]?></div></a>
			<?if($item["DATE_STOP"]){?>
				<div class="test_item_maximum_points"><b><?=GetMessage("DATE_STOP")?>: <?=$item["DATE_STOP"];?></b></div>
			<?}?>
			<?if($item["DURATION"]){?>
				<div class="test_item_maximum_points"><b><?=GetMessage("DURATION")?>: <?=$item["DURATION"];?></b></div>
			<?}?>
			<div class="test_item_maximum_points"><b><?=GetMessage("MAXIMUM_POINTS")?>: <?=$item["SCORES"];?></b></div>
			<div class="test_item_maximum_points"><b><?=GetMessage("FINAL")?>: <?=GetMessage("AT_ACTIVE_".$item["FINAL"]);?></b></div>
			<div class="test_item_description"><?echo $item["TEST_DESCRIPTION"];?></div>

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