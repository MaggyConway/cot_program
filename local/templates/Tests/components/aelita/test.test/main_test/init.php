<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? //echo '<pre>'; var_dump($arResult["TEST"]["TEST_TIME"]); echo "</pre>"; ?>

<section class="test_init">
	<?if (count($arResult["ERROR"])):?>
		<?=ShowError(implode("<br />", $arResult["ERROR"]))?>
	<?endif?>
	<div class="test__picture" style="background: url(<?=$arResult["TEST"]["PICTURE"]["SRC"]?>);"></div>
	<div class="test__wrap"></div>
	<div class="test_content">
		<h1><?=$arResult["TEST"]["NAME"]?></h1>
		<div class="clock">
			<div class="clock__image"></div>
			<div class="clock__title">Длительность теста <? echo $arResult["TEST"]["TEST_TIME"] ?> мин</div>
		</div>
		
		<form action="<?=POST_FORM_ACTION_URI?>?testaction=Y" method="post" enctype="multipart/form-data">
			<input type="hidden" name="initquestioning" value="Y">
			<button type="submit" name="testsubmit" class="btn"><?echo GetMessage("INIT_QUESTIONING")?></button>
		</form>
	</div>
</section>