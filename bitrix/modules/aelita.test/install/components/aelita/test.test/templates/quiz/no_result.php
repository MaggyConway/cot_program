<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="test">
	<h3>
		<?=$arResult["TEST"]["NAME"]?>
	</h3>
	<p>
		<?=GetMessage("NO_RESULT");?>
	</p>
	<form action="<?=POST_FORM_ACTION_URI?>?testaction=Y" method="post" enctype="multipart/form-data">
		<input type="hidden" name="reinitquestioning" value="Y">
		<input type="submit" name="testsubmit" value="<?echo GetMessage("INIT_QUESTIONING")?>">
	</form>
</div>
<?if(strlen($arParams["LIST_PAGE_URL"])>0){?>
	<div class="list_page">
		<a href="<?=$arParams["LIST_PAGE_URL"]?>"><?=GetMessage("LIST_PAGE")?></a>
	</div>
<?}?>