<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if($arResult["QUESTIONING"]){?>

<section class="summary"><div class="wrapper">
	<h1><?=$arResult["TEST"]["NAME"]?></h1>

	<div class="all_info">
		<div class="item_info"><?=GetMessage("DATE_START")?>: 
			<span><?=$arResult["QUESTIONING"]["DATE_START"];?></span>
		</div>
		<?if($arResult["QUESTIONING"]["DATE_STOP"]){?>
			<div class="item_info"><?=GetMessage("DATE_STOP")?>: 
				<span><?=$arResult["QUESTIONING"]["DATE_STOP"];?></span>
			</div>
		<?}?>
		<?if($arResult["QUESTIONING"]["DURATION"]){?>
			<div class="item_info"><?=GetMessage("DURATION")?>: 
				<span><?=$arResult["QUESTIONING"]["DURATION"];?></span>
			</div>
		<?}?>
		<div class="item_info"><?=GetMessage("MAXIMUM_POINTS")?>: 
			<span><?=$arResult["QUESTIONING"]["SCORES"];?></span>
		</div>
		<div class="item_info"><?=GetMessage("FINAL")?>: 
			<span><?=GetMessage("AT_ACTIVE_".$arResult["QUESTIONING"]["FINAL"]);?></span>
		</div>
		<?if($arResult["QUESTIONING"]["RESULT_NAME"]){?>
			<div class="item_info"><?=GetMessage("RESULT_NAME")?>: 
				<span><?=$arResult["QUESTIONING"]["RESULT_NAME"];?></span>
			</div>
		<?}?>
	</div>

	<?if(count($arResult["ITEMS"])>0){?>
		<div class="summary__table">
			<table class="summary__table">
				<thead>
					<td><?=GetMessage("TABLE_QUESTION")?></td>
					<td><?=GetMessage("TABLE_SCORE")?></th></td>
					<td><?=GetMessage("TABLE_ANSWER")?></td>
					<?if($arResult["TEST"]["SHOW_ANSWERS"]=="Y"){?>
						<td><?=GetMessage("SHOW_ANSWERS")?></td>
						<?}?>
					</thead>
					<?foreach($arResult["ITEMS"] as $item){?>
						<tr>
							<td><?=$item["QUESTION_NAME"]?></td>
							<td style="text-align: center"><?=$item["SCORES"]?></td>
							<td><?=$item["SERIALIZED_RESULT"]?></td>
								<td>
									<?if($arResult["TEST"]["SHOW_ANSWERS"]=="Y" && $arResult["SHOW"] == "Y"){?>
									<?=$item["ANSWERS"]?>
									<?}?>
								</td>
								
							</tr>
							<?}?>
						</table>
					</div>
	<?}else{?>
		<div class="empty_item">
			<?=GetMessage("NO_TEST")?>
		</div>
	<?}?>

	<div class="summary__btns">
		<?if($arResult["TEST"]["SHOW_REPEATED"]=="Y"){?>
			<div class="test_repeat btn">
				<a href="/tests/ekzamen-po-okhrane-truda/" title="<?=GetMessage("TEST_REPEATED")?>"><?=GetMessage("TEST_REPEATED")?></a>
			</div>
		<?}?>
		<?if($arResult["TEST"]["TEST_URL"]){?>
			<div class="btn_back">
				<a href="<?=$arResult["TEST"]["TEST_URL"]?>" title="<?=GetMessage("TEST_URL")?>"><?=GetMessage("TEST_URL")?></a>
			</div>
		<?}?>
	</div>
</div></section>
<?}?>