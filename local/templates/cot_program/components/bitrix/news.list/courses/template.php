<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>

<? //echo '<pre>'; var_dump($arResult); echo "</pre>"; ?>
<section id="courses">
	<h2>Курсы для обучения</h2>
	<div class="box">
	    <? foreach ($arResult["ITEMS"] as $arItem): ?>
	        <?
	        $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	        $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	        ?>
	        <div class="courses__item" data-content="<?=htmlspecialchars ($arItem["DETAIL_TEXT"]); ?>">
	        	<? //echo '<pre>'; var_dump($arItem["DISPLAY_PROPERTIES"]["MAIN_ICON"]["FILE_VALUE"]["SRC"]); echo "</pre>"; ?>
	            <div class="item__icon"></div>
				<div class="item__title">
					 <?= $arItem["NAME"]?>
				</div>
				<p>
					 <?=$arItem["PREVIEW_TEXT"]?>
				</p>
				<div class="btn modal_docs_btn">
					 ПЕРЕЧЕНЬ ДОКУМЕНТОВ
				</div>
	        </div>
	    <? endforeach; ?>
	</div>
 </section>