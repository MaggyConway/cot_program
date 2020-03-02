<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if($arResult["QUESTIONING"]){?>
<div class="test">
	<h3>
		<?=$arResult["TEST"]["NAME"]?>
	</h3>
	<?if(is_array($arResult["TEST"]["PICTURE"])):?>
		<img class="detail_picture" border="0" src="<?=$arResult["TEST"]["PICTURE"]["SRC"]?>" width="<?=$arResult["TEST"]["PICTURE"]["WIDTH"]?>" height="<?=$arResult["TEST"]["PICTURE"]["HEIGHT"]?>" alt="<?=$arResult["TEST"]["NAME"]?>"  title="<?=$arResult["TEST"]["NAME"]?>" />
	<?endif?>
	<p><?echo $arResult["TEST"]["DESCRIPTION"];?></p>
	<div class="test_item_maximum_points"><b><?=GetMessage("DATE_START")?>: <?=$arResult["QUESTIONING"]["DATE_START"];?></b></div>
	<?if($arResult["QUESTIONING"]["DATE_STOP"]){?>
		<div class="test_item_maximum_points"><b><?=GetMessage("DATE_STOP")?>: <?=$arResult["QUESTIONING"]["DATE_STOP"];?></b></div>
	<?}?>
	<?if($arResult["QUESTIONING"]["DURATION"]){?>
		<div class="test_item_maximum_points"><b><?=GetMessage("DURATION")?>: <?=$arResult["QUESTIONING"]["DURATION"];?></b></div>
	<?}?>
	<div class="test_item_maximum_points"><b><?=GetMessage("MAXIMUM_POINTS")?>: <?=$arResult["QUESTIONING"]["SCORES"];?></b></div>
	<div class="test_item_maximum_points"><b><?=GetMessage("FINAL")?>: <?=GetMessage("AT_ACTIVE_".$arResult["QUESTIONING"]["FINAL"]);?></b></div>
	<?if($arResult["QUESTIONING"]["RESULT_NAME"]){?>
		<div class="test_item_maximum_points"><b><?=GetMessage("RESULT_NAME")?>: <?=$arResult["QUESTIONING"]["RESULT_NAME"];?></b></div>
	<?}?>
</div>
<?}?>
<div class="test_list">
<?if(count($arResult["ITEMS"])>0){?>
	<table>
		<tr class="test_item">
			<th><?=GetMessage("TABLE_QUESTION")?></th>
			<th><?=GetMessage("TABLE_SCORE")?></th>
			<th><?=GetMessage("TABLE_ANSWER")?></th>
			<th><?=GetMessage("TABLE_ANSWER_TEST")?></th>
			<?if($arResult["TEST"]["SHOW_ANSWERS"]=="Y"){?>
				<th><?=GetMessage("SHOW_ANSWERS")?></th>
			<?}?>
		</tr>
	<?foreach($arResult["ITEMS"] as $item){?>
		<tr class="test_item">
			<td><?=$item["QUESTION_NAME"]?></td>
			<td align="center"><?=$item["SCORES"]?></td>
			<td><?=$item["SERIALIZED_RESULT"]?></td>
			<td><?=$item["SERIALIZED_RESULT_TEXT"]?></td>
			<?if($arResult["TEST"]["SHOW_ANSWERS"]=="Y"){?>
				<td><?=$item["ANSWERS"]?></td>
			<?}?>
		</tr>
	<?}?>
	</table>
<?}else{?>
	<div class="test_item">
		<?=GetMessage("NO_TEST")?>
	</div>
<?}?>
	<?if($arResult["TEST"]["TEST_URL"]){?>
	<div class="test_repeated">
		<a href="<?=$arResult["TEST"]["TEST_URL"]?>" title="<?=GetMessage("TEST_URL")?>"><?=GetMessage("TEST_URL")?></a>
	</div>
	<?}?>
	<?if($arResult["TEST"]["SHOW_REPEATED"]=="Y"){?>
	<div class="test_repeated">
		<a href="<?=$arResult["TEST"]["REPEATED_URL"]?>" title="<?=GetMessage("TEST_REPEATED")?>"><?=GetMessage("TEST_REPEATED")?></a>
	</div>
	<?}?>
</div>