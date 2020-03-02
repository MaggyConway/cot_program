<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<section class="results">
	<h1>Результаты</h1>
	<div class="wrapper">
		<?if(count($arResult["ITEMS"])>0){?>
			<?foreach($arResult["ITEMS"] as $item){?>
				<div class="test_item">
					<div class="test_item__name"><a href="<?=$item["DETAIL_URL"]?>" title="<?=$item["TEST_NAME"]?>"><?=$item["TEST_NAME"]?></div></a>
					
					<div class="test_item__maximum_points"><?=GetMessage("MAXIMUM_POINTS")?>: <?=$item["SCORES"];?></div>
				</div>
			<?}?>
		<?}else{?>
			<div class="test_item">
				<p><?=GetMessage("NO_TEST")?></p>
			</div>
		<?}?>
	</div>
</section>