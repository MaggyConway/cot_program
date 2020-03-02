<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>


<?$APPLICATION->AddHeadScript($templateFolder.'/jquery.min.js');?>
<?$APPLICATION->AddHeadScript($templateFolder.'/scripttest.js');?>



<div class="test">

<?if (count($arResult["ERROR"])):?>
	<?=ShowError(implode("<br />", $arResult["ERROR"]))?>
<?endif?>
	<h3>
		<?=$arResult["TEST"]["NAME"]?>
	</h3>
	<?if($arResult["TEST"]["NUMBER_ATTEMPTS"]>0){?>
	<p class="step">
		<?=GetMessage("MAX_COUNT",array("#NUM#"=>$arResult["COUNT_QUESTIONING"],"#MAX#"=>$arResult["TEST"]["NUMBER_ATTEMPTS"]))?>
	</p>
	<?}?>
	<?if($arResult["TIME_CLOSE"]){?>
	<p class="step">
		<?=GetMessage("TIME_CLOSE",array("#TIME#"=>date("H:i:s d.m.Y",$arResult["TIME_CLOSE"])))?>
	</p>
	<?}?>	
	<p class="step">
        <?if($arResult["QUESTIONING"]["STEP_MULTIPLE"]=="step"){?>
            <?=GetMessage("STEP_STEP",array("#STEP#"=>$arResult["STEP"]["GLASSES"],"#NUMBER#"=>$arResult["STEP"]["TESTS"]))?>
        <?}else{?>
		    <?=GetMessage("STEP_QUESTIONING",array("#STEP#"=>$arResult["STEP"]["GLASSES"],"#NUMBER#"=>$arResult["STEP"]["TESTS"]))?>
	    <?}?>
    </p>
	<?if($arResult["ALLOW_PASSED_BACK"]=="Y"){?>
		<ul class="line_step">
			<?foreach($arResult["ARR_PREW_LIST"] as $item){?>
				<li class=" <?if($item["OTV"]=="Y"){?>otv<?}?> <?if($item["SELECT"]=="Y"){?>select<?}?>">
					<?if($item["SHOW_LINK"]=="Y"){?>
						<a href="<?=$item["LINK"]?>"><?=$item["N"]?></a>
					<?}else{?>
						<span><?=$item["N"]?></span>
					<?}?>
				</li>
			<?}?>
		</ul>
	<?}?>

<?if($arResult["QUESTIONING"]["STEP_MULTIPLE"]=="step"){?>
    <form action="<?=POST_FORM_ACTION_URI?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="testaction" value="Y">
        <?foreach($arResult["QUESTION"] as $key=>$Question){?>
            <?if (count($arResult["ERROR_Q"][$Question["ID"]])):?>
                <?=ShowError(implode("<br />", $arResult["ERROR_Q"][$Question["ID"]]))?>
            <?endif?>
            <h4>
                <?=$Question["NAME"]?>
            </h4>

            <?if(is_array($Question["PICTURE"])):?>
                <img class="detail_picture" border="0" src="<?=$Question["PICTURE"]["SRC"]?>" width="<?=$Question["PICTURE"]["WIDTH"]?>" height="<?=$Question["PICTURE"]["HEIGHT"]?>" alt="<?=$Question["NAME"]?>"  title="<?=$Question["NAME"]?>" />
            <?endif?>
            <p><?echo $Question["DESCRIPTION"];?></p>
            <?if(count($arResult["ANSWER"][$Question["ID"]])>0){?>
                <div class="list_answer">
                    <?foreach($arResult["ANSWER"][$Question["ID"]] as $Answer){?>

                        <?if($Question["TEST_TYPE"]=="radio" || $Question["TEST_TYPE"]=="check"){?>
                            <div class="label">
                            <?if($Question["TEST_TYPE"]=="radio"){?>
                                <?if($Answer["CORRECT"]=="Y"){?><div class="simbol">&radic;</div><?}else{?><div class="simbol">&times;</div><?}?>
                                <input type="radio" <?if($Answer["CORRECT"]=="Y"){?>data-correct="Y"<?}?> <?if($Answer["CHECKED"]=="Y"){?>checked="checked"<?}?> name="answer[<?=$Question["ID"]?>]" value="<?=$Answer["ID"]?>">
                            <?}else{?>
                                <?if($Answer["CORRECT"]=="Y"){?><div class="simbol">&radic;</div><?}else{?><div class="simbol">&times;</div><?}?>
                                <input type="checkbox" <?if($Answer["CORRECT"]=="Y"){?>data-correct="Y"<?}?> <?if($Answer["CHECKED"]=="Y"){?>checked="checked"<?}?> name="answer[<?=$Question["ID"]?>][]" value="<?=$Answer["ID"]?>">
                            <?}?>
                            <div class="right">
                        <?}?>

                        <div class="item_answer">
                            <?if(is_array($Answer["PICTURE"])):?>
                                <img class="detail_picture" border="0" src="<?=$Answer["PICTURE"]["SRC"]?>" width="<?=$Answer["PICTURE"]["WIDTH"]?>" height="<?=$Answer["PICTURE"]["HEIGHT"]?>" alt="<?=$Answer["NAME"]?>"  title="<?=$Answer["NAME"]?>" />
                            <?endif?>
                            <h5><?=$Answer["NAME"]?> <span class="precent_answer"><?=$Answer["PRECENT_ANSWER"]?>%</span></h5>
                            <?=$Answer["DESCRIPTION"]?>
                        </div>
                        <?if($Question["TEST_TYPE"]=="radio" || $Question["TEST_TYPE"]=="check"){?>
                            </div>
                            </div>
                        <?}?>
                    <?}?>
                </div>
            <?}?>

            <input type="hidden" name="stepquestioning" value="Y">
            <input type="hidden" name="questionid[]" value="<?=$Question["ID"]?>">
            <?if($Question["TEST_TYPE"]=="input"){?>
                <?echo GetMessage("CORRECT_ANSWER")?> <input type="text" name="answer[<?=$Question["ID"]?>]" value="<?=$Question["VAL"]?>">
            <?}?>

            <?if($Question["SHOW_COMMENTS"]=="Y"){?>
                <br /> <br />
                <div class="show_comments">
                    <b><?=GetMessage("COMMENTS")?></b>:<br /><br /><textarea rows="10" cols="45" name="comments[<?=$Question["ID"]?>]"><?=$arResult["STEP"]["GLASSES_LIST"][$Question["ID"]]["COMMENTS"]?></textarea><br /><br />
                </div>
            <?}?>

            <br />
        <?}?>

        <?if($arResult["ALLOW_PASSED_BACK"]=="Y" && $arResult["STEP"]["GLASSES"]>1){?>
            <input type="submit" name="prevtest" value="<?echo GetMessage("PREV_TEST")?>">
            <input type="hidden" name="setprevtest" value="<?=$arResult["PREW_LIST"]?>">
        <?}?>
        <?if($arResult["STEP"]["TESTS"]<=$arResult["STEP"]["GLASSES"] && $arResult["ALLOW_PASSED_BACK"]=="Y"){?>
            <input type="submit" name="testsubmit" value="<?echo GetMessage("INIT_QUESTIONING")?>">
            <input type="hidden" name="testlast" value="Y">
        <?}else{?>
            <input type="submit" name="testsubmit" value="<?echo GetMessage("INIT_QUESTIONING")?>">
        <?}?>
        <input type="submit" name="closequestioning" value="<?echo GetMessage("CLOSE_QUESTIONING")?>">
    </form>
<?}else{?>

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
				<div class="label">
					<?if($arResult["QUESTION"]["TEST_TYPE"]=="radio"){?>
                        <?if($Answer["CORRECT"]=="Y"){?><div class="simbol">&radic;</div><?}else{?><div class="simbol">&times;</div><?}?>
						<input type="radio" <?if($Answer["CORRECT"]=="Y"){?>data-correct="Y"<?}?> <?if($Answer["CHECKED"]=="Y"){?>checked="checked"<?}?> name="answer" value="<?=$Answer["ID"]?>">
					<?}else{?>
                        <?if($Answer["CORRECT"]=="Y"){?><div class="simbol">&radic;</div><?}else{?><div class="simbol">&times;</div><?}?>
						<input type="checkbox" <?if($Answer["CORRECT"]=="Y"){?>data-correct="Y"<?}?> <?if($Answer["CHECKED"]=="Y"){?>checked="checked"<?}?> name="answer[]" value="<?=$Answer["ID"]?>">
					<?}?>
				<div class="right">
				<?}?>
				
				<div class="item_answer">
					<?if(is_array($Answer["PICTURE"])):?>
						<img class="detail_picture" border="0" src="<?=$Answer["PICTURE"]["SRC"]?>" width="<?=$Answer["PICTURE"]["WIDTH"]?>" height="<?=$Answer["PICTURE"]["HEIGHT"]?>" alt="<?=$Answer["NAME"]?>"  title="<?=$Answer["NAME"]?>" />
					<?endif?>
					<h5><?=$Answer["NAME"]?> <span class="precent_answer"><?=$Answer["PRECENT_ANSWER"]?>%</span></h5>

					<?=$Answer["DESCRIPTION"]?>
				</div>



				<?if($arResult["QUESTION"]["TEST_TYPE"]=="radio" || $arResult["QUESTION"]["TEST_TYPE"]=="check"){?>
				</div>
                    <?if($Answer["CORRECT_DESCRIPTION"]){?>
                        <div class="correct_description"><?=$Answer["CORRECT_DESCRIPTION"]?></div>
                    <?}?>

                    <?if($Answer["ERROR_DESCRIPTION"]){?>
                        <div class="error_description"><?=$Answer["ERROR_DESCRIPTION"]?></div>
                    <?}?>
				</div>
				<?}?>
			<?}?>
			</div>
		<?}?>

		<input type="hidden" name="stepquestioning" value="Y">
		<input type="hidden" name="questionid" value="<?=$arResult["QUESTION"]["ID"]?>">
		<?if($arResult["QUESTION"]["TEST_TYPE"]=="input"){?>
			<?echo GetMessage("CORRECT_ANSWER")?> <input type="text" name="answer" value="<?=$arResult["QUESTION"]["VAL"]?>">
		<?}?>
		
        <?if($arResult["TEST"]["SHOW_COMMENTS"]=="Y"){?>
            <div class="show_comments">
                <b><?=GetMessage("COMMENTS")?></b>:<br /><br /><textarea rows="10" cols="45" name="comments"><?=$arResult["STEP"]["GLASSES_LIST"][$arResult["STEP"]["GLASSES"]-1]["COMMENTS"]?></textarea><br /><br />
            </div>
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
		<input type="submit" name="closequestioning" value="<?echo GetMessage("CLOSE_QUESTIONING")?>">
	</form>

<?}?>

</div>
<?if(strlen($arParams["LIST_PAGE_URL"])>0){?>
	<div class="list_page">
		<a href="<?=$arParams["LIST_PAGE_URL"]?>"><?=GetMessage("LIST_PAGE")?></a>
	</div>
<?}?>