<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? //echo '<pre>'; var_dump($arResult); echo "</pre>"; ?>
<section class="result_after_test"><h1><?=$arResult["TEST"]["NAME"]?></h1>
	<div class="wrapper">
		<!-- ПОПЫТКА И НАБРАННЫЙ БАЛЛ -->
		<div><?if($arResult["TEST"]["NUMBER_ATTEMPTS"]>0){?>
			<p>
				<?=GetMessage("MAX_COUNT");?>
				<span><?=$arResult["COUNT_QUESTIONING"]?> из <?=$arResult["TEST"]["NUMBER_ATTEMPTS"]?></span>
				<?/*=GetMessage("MAX_COUNT",array("#NUM#"=>$arResult["COUNT_QUESTIONING"],"#MAX#"=>$arResult["TEST"]["NUMBER_ATTEMPTS"]))*/?>
			</p>
			<p><?=GetMessage("MAXIMUM_POINTS");?> <span><?= $arResult["RESULT"]["MAX_SCORES"] ?></span></p>
			<p><?=GetMessage("CURRENT_RESULT");?> <span><?= $arResult["RESULT"]["NAME"] ?></span></p>
		<?}?>
		</div>
	</div>
	<div class="result_btns">
	<? if ($arResult["COUNT_QUESTIONING"] < 3): ?>
		<form class="result_form_btn" action="<?=POST_FORM_ACTION_URI?>?testaction=Y" method="post" enctype="multipart/form-data">
			<input type="hidden" name="reinitquestioning" value="Y">
			<input type="submit" name="testsubmit" class="btn" value="<?echo GetMessage("INIT_QUESTIONING")?>">
		</form>
	<? endif; ?>
	<?if(strlen($arParams["LIST_PAGE_URL"])>0){?>
		<div class="list_page">
			<a href="/tests/#tests_page"><?=GetMessage("LIST_PAGE")?></a>
		</div>
	<?}?></div>
</section>


