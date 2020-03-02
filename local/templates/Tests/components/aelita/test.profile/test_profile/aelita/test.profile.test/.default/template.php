<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<section class="repeats">
	<? //echo '<pre>'; var_dump($arResult); echo "</pre>"; ?>
	<h1><?=$arResult["TEST"]["NAME"]?></h1>
	<div class="wrapper">
		<div class="repeats__list">
			<?if(count($arResult["ITEMS"])>0){?>

				<?foreach($arResult["ITEMS"] as $item){?>
					<div class="item">
						<div class="item__name">
							<a href="<?=$item["DETAIL_URL"]?>" title="<?=GetMessage("ATTEMPT_NUMBER")?><?=$item["DATE_START"]?>">
								<?=GetMessage("ATTEMPT_NUMBER")?><?=$item["DATE_START"]?>
							</a>
						</div>
						<div class="all_info">
							<?if($item["DATE_STOP"]){?>
								<div class="item__info">
									<?=GetMessage("DATE_STOP")?>: <span><?=$item["DATE_STOP"];?></span>
								</div>
							<?}?>
							<?if($item["DURATION"]){?>
								<div class="item__info">
									<?=GetMessage("DURATION")?>: <span><?=$item["DURATION"];?></span>
								</div>
							<?}?>
							<div class="item__info">
								<?=GetMessage("MAXIMUM_POINTS")?>: <span><?=$item["SCORES"];?></span>
							</div>
							<div class="item__info">
								<?=GetMessage("FINAL")?>: <span><?=GetMessage("AT_ACTIVE_".$item["FINAL"]);?></span>
							</div>
						</div>
					</div>
				<?}?>
			<?}else{?>
				<div class="test_item">
					<?=GetMessage("NO_TEST")?>
				</div>
			<?}?>
		</div>
		<div class="repeats_btns">
			<? //echo '<pre>'; var_dump($arResult); echo "</pre>"; ?>
			<?if($arResult["TEST"]["SHOW_REPEATED"]=="Y"){?>
				<div class="btn_again btn">
					<a href="/tests/ekzamen-po-okhrane-truda/" title="<?=GetMessage("TEST_REPEATED")?>"><?=GetMessage("TEST_REPEATED")?></a>
				</div>
			<?}?>
			<?if($arParams["TESTS_URL"]){?>
				<div class="btn_back">
					<a href="<?=$arParams["TESTS_URL"]?>" title="<?=GetMessage("TESTS_URL")?>"><?=GetMessage("TESTS_URL")?></a>
				</div>
			<?}?>
			
		</div>
	</div>
</section>