<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans+Condensed:300"/>
<div class="test">
<?if (count($arResult["ERROR"])):?>
	<?=ShowError(implode("<br />", $arResult["ERROR"]))?>
<?endif?>
	<h3><?=$arResult["TEST"]["NAME"]?></h3>
	<?if($arResult["TEST"]["NUMBER_ATTEMPTS"]>0){?>
	<p class="step">
		<?=GetMessage("MAX_COUNT",array("#NUM#"=>$arResult["COUNT_QUESTIONING"],"#MAX#"=>$arResult["TEST"]["NUMBER_ATTEMPTS"]))?>
	</p>
	<?}?>
	<?if($arResult["TIME_CLOSE"]){?>
		<input type="hidden" name="next_time" value="<?=$arResult["TIME_ACTUAL"]?>" />
		<div id="countdown"></div>
	<?}?>	
	<p class="step">
		<?=GetMessage("STEP_QUESTIONING",array("#STEP#"=>$arResult["STEP"]["GLASSES"],"#NUMBER#"=>$arResult["STEP"]["TESTS"]))?>
	</p>
	<?if($arResult["ALLOW_PASSED_BACK"]=="Y"){?>
		<ul class="line_step">
			<?foreach($arResult["ARR_PREW_LIST"] as $item){?>
				<li class=" <?if($item["OTV"]=="Y"){?>otv<?}?> <?if($item["SELECT"]=="Y"){?>select<?}?>">
					<?if($item["SHOW_LINK"]=="Y"){?>
						<a href="<?=$item["LINK"]?>" data-set="<?=$item["SET"]?>"><?=$item["N"]?></a>
					<?}else{?>
						<span><?=$item["N"]?></span>
					<?}?>
				</li>
			<?}?>
		</ul>
	<?}?>
	<h4>
		<?=$arResult["QUESTION"]["NAME"]?>
	</h4>
	<?if(is_array($arResult["QUESTION"]["PICTURE"])):?>
		<img class="detail_picture" border="0" src="<?=$arResult["QUESTION"]["PICTURE"]["SRC"]?>" width="<?=$arResult["QUESTION"]["PICTURE"]["WIDTH"]?>" height="<?=$arResult["QUESTION"]["PICTURE"]["HEIGHT"]?>" alt="<?=$arResult["QUESTION"]["NAME"]?>"  title="<?=$arResult["QUESTION"]["NAME"]?>" />
	<?endif?>
	<p><?echo $arResult["QUESTION"]["DESCRIPTION"];?></p>
	<form action="<?=POST_FORM_ACTION_URI?>?testaction=Y" method="post" enctype="multipart/form-data">
		<?if(count($arResult["ANSWER"])>0){?>
			<div class="list_answer">
			<?foreach($arResult["ANSWER"] as $Answer){?>
				<?if($arResult["QUESTION"]["TEST_TYPE"]=="radio" || $arResult["QUESTION"]["TEST_TYPE"]=="check"){?>
				<label>
					<?if($arResult["QUESTION"]["TEST_TYPE"]=="radio"){?>
						<input type="radio" <?if($Answer["CHECKED"]=="Y"){?>checked="checked"<?}?> name="answer" value="<?=$Answer["ID"]?>">
					<?}else{?>
						<input type="checkbox" <?if($Answer["CHECKED"]=="Y"){?>checked="checked"<?}?> name="answer[]" value="<?=$Answer["ID"]?>">
					<?}?>
				<div class="right">
				<?}?>
				
				<div class="item_answer">
					<?if(is_array($Answer["PICTURE"])):?>
						<img class="detail_picture" border="0" src="<?=$Answer["PICTURE"]["SRC"]?>" width="<?=$Answer["PICTURE"]["WIDTH"]?>" height="<?=$Answer["PICTURE"]["HEIGHT"]?>" alt="<?=$Answer["NAME"]?>"  title="<?=$Answer["NAME"]?>" />
					<?endif?>
					<h5><?=$Answer["NAME"]?></h5>
					<?=$Answer["DESCRIPTION"]?>
				</div>
				<?if($arResult["QUESTION"]["TEST_TYPE"]=="radio" || $arResult["QUESTION"]["TEST_TYPE"]=="check"){?>
				</div>
				</label>
				<?}?>
			<?}?>
			</div>
		<?}?>
		<input type="hidden" name="stepquestioning" value="Y">
		<input type="hidden" name="questionid" value="<?=$arResult["QUESTION"]["ID"]?>">
		<?if($arResult["QUESTION"]["TEST_TYPE"]=="input"){?>
			<?echo GetMessage("CORRECT_ANSWER")?> <input type="text" name="answer" value="<?=$arResult["QUESTION"]["VAL"]?>">
		<?}?>
		<?if($arResult["ALLOW_PASSED_BACK"]=="Y" && $arResult["STEP"]["GLASSES"]>1){?>
			<input type="submit" name="prevtest" value="<?echo GetMessage("PREV_TEST")?>">
			<input type="hidden" name="setprevtest" value="<?=$arResult["PREW_LIST"]?>">
		<?}?>
		<?if($arResult["STEP"]["TESTS"]<=$arResult["STEP"]["GLASSES"] && $arResult["ALLOW_PASSED_BACK"]=="Y"){?>
			<input type="submit" name="closequestioning" value="<?echo GetMessage("INIT_QUESTIONING")?>">
		<?}else{?>
			<input type="submit" name="testsubmit" value="<?echo GetMessage("INIT_QUESTIONING")?>">
		<?}?>
		<input type="submit" class="closequestioning" name="closequestioning" value="<?echo GetMessage("CLOSE_QUESTIONING")?>">
	</form>
</div>
<?if(strlen($arParams["LIST_PAGE_URL"])>0){?>
	<div class="list_page">
		<a href="<?=$arParams["LIST_PAGE_URL"]?>"><?=GetMessage("LIST_PAGE")?></a>
	</div>
<?}?>
