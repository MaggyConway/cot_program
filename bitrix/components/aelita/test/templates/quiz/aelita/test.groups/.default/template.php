<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="test_list">
<?if(count($arResult["ITEMS"])>0){?>
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
<?}else{?>
	<div class="test_item">
		<?=GetMessage("NO_TEST")?>
	</div>
<?}?>
</div>