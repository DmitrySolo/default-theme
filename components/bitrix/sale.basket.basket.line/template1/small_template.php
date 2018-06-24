<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<div>



	<?if ($arResult['NUM_PRODUCTS']> 0 && $arParams['SHOW_NUM_PRODUCTS'] == 'Y' && $arParams['SHOW_TOTAL_PRICE'] == 'Y'):?>

	<?else: echo GetMessage('TSB1_EMPTY_CART'); endif?>

	<?if($arParams['SHOW_NUM_PRODUCTS'] == 'Y'):?>
		<?if ($arResult['NUM_PRODUCTS'] > 0):?>
			<a href="<?=$arParams['PATH_TO_BASKET']?>"><?=$arResult['NUM_PRODUCTS'].' '.$arResult['PRODUCT(S)']?></a>
		<?endif?>
	<?endif?>

	<?if($arParams['SHOW_TOTAL_PRICE'] == 'Y'):?>
		

		<?if ($arResult['NUM_PRODUCTS'] > 0):?> <?=GetMessage('TSB1_TOTAL_PRICE')?>
			<a href="<?=$arParams['PATH_TO_BASKET']?>"><?=$arResult['TOTAL_PRICE']?></a>
		<?endif?>
	<?endif?>


	<?if($arParams["SHOW_PERSONAL_LINK"] == "Y"):?>

		<span class="icon_profile"></span>
		<a class="link_profile" href="<?=$arParams["PATH_TO_PERSONAL"]?>"><?=GetMessage("TSB1_PERSONAL")?></a>
	<?endif?>

	<?if ($arParams["SHOW_PRODUCTS"] == "Y" && $arResult['NUM_PRODUCTS'] > 0):?>
		<div class="bx_item_hr" style="margin-bottom:0"></div>
	<?endif?>

</div>