<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="test">
<?if (count($arResult["ERROR"])):?>
	<?=ShowError(implode("<br />", $arResult["ERROR"]))?>
<?endif?>
	<h3><?=$arResult["TEST"]["NAME"]?></h3>
	<p><?echo GetMessage("TITLE_CLOSE");?></p>
	<form action="<?=POST_FORM_ACTION_URI?>?testaction=Y" method="post" enctype="multipart/form-data">

		<input type="hidden" name="stepquestioning" value="Y">
		<input type="hidden" name="questionid" value="<?=$arResult["QUESTION"]["ID"]?>">
		<input type="hidden" name="closequestioning" value="Y">



		<input type="submit" name="closequestioning_Y" value="<?echo GetMessage("CLOSE_QUESTIONING")?>">
		<input type="submit" name="closequestioning_N" value="<?echo GetMessage("CLOSE_QUESTIONING_N")?>">
	</form>
</div>
