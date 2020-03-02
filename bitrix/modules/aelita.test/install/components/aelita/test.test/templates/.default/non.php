<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="test">
	<h3>
		<?=$arResult["TEST"]["NAME"]?>
	</h3>
	<p>
		<?if($arResult["NO_ACCESS"]=="Y"){
			echo GetMessage("NO_ACCESS");
		}elseif($arResult["NO_DATE"]=="Y"){
			echo GetMessage("NO_DATE");
		}else{
			echo GetMessage("NO_TEST");
		}?>
	</p>
</div>
<?if(strlen($arParams["LIST_PAGE_URL"])>0){?>
	<div class="list_page">
		<a href="<?=$arParams["LIST_PAGE_URL"]?>"><?=GetMessage("LIST_PAGE")?></a>
	</div>
<?}?>