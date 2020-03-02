<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="test_list">
<?if(count($arResult["ITEMS"])>0){?>
	<table>
		<tr class="test_item">
			<th <?if($arParams["SHOW_POINTS_TOP"]){?>colspan="3"<?}?>><?=GetMessage("HEADER_TABLE")?></th>
		</tr>
		<tr class="test_item">
			<th><?=GetMessage("TABLE_QUESTION")?></th>
			<?if($arParams["SHOW_POINTS_TOP"]){?>
			<th><?=GetMessage("TABLE_SCORE")?></th>
			<th><?=GetMessage("TABLE_ANSWER")?></th>
			<?}?>
		</tr>
	<?foreach($arResult["ITEMS"] as $item){?>
		<tr class="test_item">
			<td><?=$item["TXT_USER"]?></td>
			<?if($arParams["SHOW_POINTS_TOP"]){?>
			<td align="center"><?=$item["SCORES"]?></td>
			<td align="center"><?=$item["TXT_DURATION"]?></td>
			<?}?>
		</tr>
	<?}?>
	</table>
<?}?>
</div>