<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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
if (empty($arResult))
	return;
?>
<b>Каталог:</b>
        <ul>
			<?foreach($arResult["ALL_ITEMS_ID"] as $itemIdLevel_1=>$arItemsLevel_2):?> <!-- first level-->
            <li>
				<a href="<?=$arResult["ALL_ITEMS"][$itemIdLevel_1]["LINK"]?>"><?=$arResult["ALL_ITEMS"][$itemIdLevel_1]["TEXT"]?></a></li>
<?endforeach?>


