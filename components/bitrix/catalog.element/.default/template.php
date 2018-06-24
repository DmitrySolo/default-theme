<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc;

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CatalogSectionComponent $component
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $componentPath
 * @var string $templateFolder
 */

$this->setFrameMode(true);
//$this->addExternalCss('/bitrix/css/main/bootstrap.css');

$templateLibrary = array('popup', 'fx');
$currencyList = '';

if (!empty($arResult['CURRENCIES']))
{
	$templateLibrary[] = 'currency';
	$currencyList = CUtil::PhpToJSObject($arResult['CURRENCIES'], false, true, true);
}

$templateData = array(
	'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'],
	'TEMPLATE_LIBRARY' => $templateLibrary,
	'CURRENCIES' => $currencyList,
	'ITEM' => array(
		'ID' => $arResult['ID'],
		'IBLOCK_ID' => $arResult['IBLOCK_ID'],
		'OFFERS_SELECTED' => $arResult['OFFERS_SELECTED'],
		'JS_OFFERS' => $arResult['JS_OFFERS']
	)
);
unset($currencyList, $templateLibrary);

$mainId = $this->GetEditAreaId($arResult['ID']);
$itemIds = array(
	'ID' => $mainId,
	'DISCOUNT_PERCENT_ID' => $mainId.'_dsc_pict',
	'STICKER_ID' => $mainId.'_sticker',
	'BIG_SLIDER_ID' => $mainId.'_big_slider',
	'BIG_IMG_CONT_ID' => $mainId.'_bigimg_cont',
	'SLIDER_CONT_ID' => $mainId.'_slider_cont',
	'OLD_PRICE_ID' => $mainId.'_old_price',
	'PRICE_ID' => $mainId.'_price',
	'DISCOUNT_PRICE_ID' => $mainId.'_price_discount',
	'PRICE_TOTAL' => $mainId.'_price_total',
	'SLIDER_CONT_OF_ID' => $mainId.'_slider_cont_',
	'QUANTITY_ID' => $mainId.'_quantity',
	'QUANTITY_DOWN_ID' => $mainId.'_quant_down',
	'QUANTITY_UP_ID' => $mainId.'_quant_up',
	'QUANTITY_MEASURE' => $mainId.'_quant_measure',
	'QUANTITY_LIMIT' => $mainId.'_quant_limit',
	'BUY_LINK' => $mainId.'_buy_link',
	'ADD_BASKET_LINK' => $mainId.'_add_basket_link',
	'BASKET_ACTIONS_ID' => $mainId.'_basket_actions',
	'NOT_AVAILABLE_MESS' => $mainId.'_not_avail',
	'COMPARE_LINK' => $mainId.'_compare_link',
	'TREE_ID' => $mainId.'_skudiv',
	'DISPLAY_PROP_DIV' => $mainId.'_sku_prop',
	'DISPLAY_MAIN_PROP_DIV' => $mainId.'_main_sku_prop',
	'OFFER_GROUP' => $mainId.'_set_group_',
	'BASKET_PROP_DIV' => $mainId.'_basket_prop',
	'SUBSCRIBE_LINK' => $mainId.'_subscribe',
	'TABS_ID' => $mainId.'_tabs',
	'TAB_CONTAINERS_ID' => $mainId.'_tab_containers',
	'SMALL_CARD_PANEL_ID' => $mainId.'_small_card_panel',
	'TABS_PANEL_ID' => $mainId.'_tabs_panel'
);
$obName = $templateData['JS_OBJ'] = 'ob'.preg_replace('/[^a-zA-Z0-9_]/', 'x', $mainId);
$name = !empty($arResult['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'])
	? $arResult['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']
	: $arResult['NAME'];
$title = !empty($arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_TITLE'])
	? $arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_TITLE']
	: $arResult['NAME'];
$alt = !empty($arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_ALT'])
	? $arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_ALT']
	: $arResult['NAME'];

$haveOffers = !empty($arResult['OFFERS']);
if ($haveOffers)
{
	$actualItem = isset($arResult['OFFERS'][$arResult['OFFERS_SELECTED']])
		? $arResult['OFFERS'][$arResult['OFFERS_SELECTED']]
		: reset($arResult['OFFERS']);
	$showSliderControls = false;

	foreach ($arResult['OFFERS'] as $offer)
	{
		if ($offer['MORE_PHOTO_COUNT'] > 1)
		{
			$showSliderControls = true;
			break;
		}
	}
}
else
{
	$actualItem = $arResult;
	$showSliderControls = $arResult['MORE_PHOTO_COUNT'] > 1;
}

$skuProps = array();
$price = $actualItem['ITEM_PRICES'][$actualItem['ITEM_PRICE_SELECTED']];
$measureRatio = $actualItem['ITEM_MEASURE_RATIOS'][$actualItem['ITEM_MEASURE_RATIO_SELECTED']]['RATIO'];
$showDiscount = $price['PERCENT'] > 0;

$showDescription = !empty($arResult['PREVIEW_TEXT']) || !empty($arResult['DETAIL_TEXT']);
$showBuyBtn = in_array('BUY', $arParams['ADD_TO_BASKET_ACTION']);
$buyButtonClassName = in_array('BUY', $arParams['ADD_TO_BASKET_ACTION_PRIMARY']) ? 'btn-default' : 'btn-link';
$showAddBtn = in_array('ADD', $arParams['ADD_TO_BASKET_ACTION']);
$showButtonClassName = in_array('ADD', $arParams['ADD_TO_BASKET_ACTION_PRIMARY']) ? 'btn-default' : 'btn-link';
$showSubscribe = $arParams['PRODUCT_SUBSCRIPTION'] === 'Y' && ($arResult['CATALOG_SUBSCRIBE'] === 'Y' || $haveOffers);

$arParams['MESS_BTN_BUY'] = $arParams['MESS_BTN_BUY'] ?: Loc::getMessage('CT_BCE_CATALOG_BUY');
$arParams['MESS_BTN_ADD_TO_BASKET'] = $arParams['MESS_BTN_ADD_TO_BASKET'] ?: Loc::getMessage('CT_BCE_CATALOG_ADD');
$arParams['MESS_NOT_AVAILABLE'] = $arParams['MESS_NOT_AVAILABLE'] ?: Loc::getMessage('CT_BCE_CATALOG_NOT_AVAILABLE');
$arParams['MESS_BTN_COMPARE'] = $arParams['MESS_BTN_COMPARE'] ?: Loc::getMessage('CT_BCE_CATALOG_COMPARE');
$arParams['MESS_PRICE_RANGES_TITLE'] = $arParams['MESS_PRICE_RANGES_TITLE'] ?: Loc::getMessage('CT_BCE_CATALOG_PRICE_RANGES_TITLE');
$arParams['MESS_DESCRIPTION_TAB'] = $arParams['MESS_DESCRIPTION_TAB'] ?: Loc::getMessage('CT_BCE_CATALOG_DESCRIPTION_TAB');
$arParams['MESS_PROPERTIES_TAB'] = $arParams['MESS_PROPERTIES_TAB'] ?: Loc::getMessage('CT_BCE_CATALOG_PROPERTIES_TAB');
$arParams['MESS_COMMENTS_TAB'] = $arParams['MESS_COMMENTS_TAB'] ?: Loc::getMessage('CT_BCE_CATALOG_COMMENTS_TAB');
$arParams['MESS_SHOW_MAX_QUANTITY'] = $arParams['MESS_SHOW_MAX_QUANTITY'] ?: Loc::getMessage('CT_BCE_CATALOG_SHOW_MAX_QUANTITY');
$arParams['MESS_RELATIVE_QUANTITY_MANY'] = $arParams['MESS_RELATIVE_QUANTITY_MANY'] ?: Loc::getMessage('CT_BCE_CATALOG_RELATIVE_QUANTITY_MANY');
$arParams['MESS_RELATIVE_QUANTITY_FEW'] = $arParams['MESS_RELATIVE_QUANTITY_FEW'] ?: Loc::getMessage('CT_BCE_CATALOG_RELATIVE_QUANTITY_FEW');

$positionClassMap = array(
	'left' => 'product-item-label-left',
	'center' => 'product-item-label-center',
	'right' => 'product-item-label-right',
	'bottom' => 'product-item-label-bottom',
	'middle' => 'product-item-label-middle',
	'top' => 'product-item-label-top'
);

$discountPositionClass = 'product-item-label-big';
if ($arParams['SHOW_DISCOUNT_PERCENT'] === 'Y' && !empty($arParams['DISCOUNT_PERCENT_POSITION']))
{
	foreach (explode('-', $arParams['DISCOUNT_PERCENT_POSITION']) as $pos)
	{
		$discountPositionClass .= isset($positionClassMap[$pos]) ? ' '.$positionClassMap[$pos] : '';
	}
}

$labelPositionClass = 'product-item-label-big';
if (!empty($arParams['LABEL_PROP_POSITION']))
{
	foreach (explode('-', $arParams['LABEL_PROP_POSITION']) as $pos)
	{
		$labelPositionClass .= isset($positionClassMap[$pos]) ? ' '.$positionClassMap[$pos] : '';
	}
}

$product_reserv = $arResult['PROPERTIES']['stock_reserved']['VALUE'];

$partnerId = isPartnerClient();

$morz_bonus = 0;
if(!$partnerId) {
    $morz_bonus = (($arResult['PROPERTIES']['OLD_PRICE']['VALUE'] > 1) ? 0 : round($arResult['PROPERTIES']['morz']['VALUE'] * 0.1 / 10) * 10);
    $morz_bonus = ($arResult['PRICES']['BASE']['DISCOUNT_DIFF']) ? $morz_bonus / 2 : $morz_bonus;
}

$arItemData = array();

foreach ($arParams['PRODUCT_PAY_BLOCK_ORDER'] as $blockName)
{
    switch ($blockName)
    {
        case 'price':
            $arPrices = array();
            if ($arParams['SHOW_OLD_PRICE'] === 'Y') {
                $arOldPrice = array();
                $arOldPrice['ID'] = $itemIds['OLD_PRICE_ID'];
                if ($arResult['PRICES']['BASE']['DISCOUNT_VALUE'] < $arResult['PRICES']['BASE']['VALUE']) {
                    $arOldPrice['PRINT_VALUE'] = number_format($arResult['PRICES']['BASE']['VALUE'], 0, '', ' ');
                    $arPrices['OLD_PRICE'] = $arOldPrice;
                }
                if($arResult['PROPERTIES']['OLD_PRICE']['VALUE'] > 0){
                    $arOldPrice['PRINT_VALUE'] = number_format($arResult['PROPERTIES']['OLD_PRICE']['VALUE'], 0, '', ' ');
                    $arPrices['OLD_PRICE'] = $arOldPrice;
                }
            }
            if (!empty($price)) {
                $arCurrentPrice = array();
                $arCurrentPrice['ID'] = $itemIds['PRICE_ID'];

                $arCurrentPrice['PRINT_VALUE'] = number_format($arResult['PRICES']['BASE']['DISCOUNT_VALUE'], 0, '', ' ');
                $arPrices['RETAIL_PRICE']['PRINT_VALUE'] = number_format($arResult['PRICES']['BASE']['VALUE'], 0, '', ' ');

                if($partnerId){
                    $arPrice = CCatalogIBlockParameters::getPriceTypesList();
                    if(isset($arPrice['purchasePrice_'.$partnerId])){
                        $arCurrentPrice['PRINT_VALUE'] = number_format($arResult['PRICES']['purchasePrice_'.$partnerId]['VALUE'], 0, '', ' ');
                    }
                    if(isset($arPrice['retailPrice_'.$partnerId])){
                        $arPrices['RETAIL_PRICE']['PRINT_VALUE'] = number_format($arResult['PRICES']['retailPrice_'.$partnerId]['VALUE'], 0, '', ' ');
                    }
                }

                $arPrices['CURRENT_PRICE'] = $arCurrentPrice;
            }
            $arItemData['PRICES'] = $arPrices;
            break;

        case 'quantityLimit':
            if ($arParams['SHOW_MAX_QUANTITY'] !== 'N') {
                $arQuantityLimit = array();
                $arQuantityLimit['ID'] = $itemIds['QUANTITY_LIMIT'];
                $arQuantityLimit['MESSAGE'] = $arParams['MESS_SHOW_MAX_QUANTITY'];

                if (
                    $measureRatio
                    && (float)$actualItem['CATALOG_QUANTITY'] > 0
                    && $actualItem['CATALOG_QUANTITY_TRACE'] === 'Y'
                    && $actualItem['CATALOG_CAN_BUY_ZERO'] === 'N'
                ) {
                    if ($arParams['SHOW_MAX_QUANTITY'] === 'M') {
                        if ((float)$actualItem['CATALOG_QUANTITY'] / $measureRatio >= $arParams['RELATIVE_QUANTITY_FACTOR']) {
                            $arQuantityLimit['VALUE'] = $arParams['MESS_RELATIVE_QUANTITY_MANY'];
                        } else {
                            $arQuantityLimit['VALUE'] = $arParams['MESS_RELATIVE_QUANTITY_FEW'];
                        }
                    } else {
                        $arQuantityLimit['VALUE'] = $actualItem['CATALOG_QUANTITY'] . ' ' . $actualItem['ITEM_MEASURE']['TITLE'];
                    }
                    $arItemData['QUANTITY_LIMIT'] = $arQuantityLimit;
                }
            }

            break;

        case 'quantity':
            if ($arParams['USE_PRODUCT_QUANTITY']) {
                $arQuantity = array();
                $arQuantity['ENTITY'] = "quantity-block";
                $arQuantity['MINUS']['ID'] = $itemIds['QUANTITY_DOWN_ID'];
                $arQuantity['QUANTITY']['ID'] = $itemIds['QUANTITY_ID'];
                $arQuantity['QUANTITY']['NAME'] = $arParams['PRODUCT_QUANTITY_VARIABLE'];
                $arQuantity['QUANTITY']['VALUE'] = $measureRatio;
                $arQuantity['PLUS']['ID'] = $itemIds['QUANTITY_UP_ID'];
                $arItemData['QUANTITY'] = $arQuantity;
            }

            break;

        case 'buttons':
            $arButtons = array();
            if ($actualItem['CAN_BUY']) {
                $arButtons['BUY']['ID_CONTAINER'] = $itemIds['BASKET_ACTIONS_ID'];
                $arButtons['BUY']['ID'] = $itemIds['BUY_LINK'];
                if($partnerId) $arButtons['BUY']['MESSAGE'] = 'В заявку';
                else $arButtons['BUY']['MESSAGE'] = ($arParams['ADD_TO_BASKET_ACTION'] === 'BUY' ? $arParams['MESS_BTN_BUY'] : $arParams['MESS_BTN_ADD_TO_BASKET']);
            } else {
                $arButtons['SUBSCRIBE']['ID'] = $itemIds['SUBSCRIBE_LINK'];
                $arButtons['SUBSCRIBE']['MESSAGE'] = $arParams['MESS_NOT_AVAILABLE'];
                if ($showSubscribe) {
                    $arButtons['SUBSCRIBE']['PRODUCT_ID'] = $actualItem['ID'];
                    $arButtons['SUBSCRIBE']['BUTTON_ID'] = 'btn btn-default ' . $buttonSizeClass; // todo change it!
                }
            }
            if (
                $arParams['DISPLAY_COMPARE']
                && (!$haveOffers || $arParams['PRODUCT_DISPLAY_MODE'] === 'Y')
            ) {
                $arButtons['COMPARE']['ID'] = $itemIds['COMPARE_LINK'];
                $arButtons['COMPARE']['MESSAGE'] = $arParams['MESS_BTN_COMPARE'];
            }
            $arItemData['BUTTONS'] = $arButtons;
            break;
    }
}

$arTabs = array();

$arTabs["SET_CONSTRUCTOR"] = CCatalogProductSet::isProductHaveSet($arResult["ID"]);
$arTabs["PRODUCT_INFO"] = true;
$arTabs["COMPLECTATION"] = $arResult['PROPERTIES']['komplekt_postavki']['VALUE'] > 0;

$arTabs["COLLECTION"] = false;
$vendor = $arResult['PROPERTIES']['vendor']['VALUE'];
$collection = $arResult['PROPERTIES']['collection']['VALUE'];
if (!empty($collection)) {
    $arOrder = Array("SORT" => "ASC");
    $arFilter = Array(
        "!ID" => "{$arResult['ID']}",
        "IBLOCK_ID" => "10",
        "PROPERTY_collection_VALUE" => "$collection",
        "PROPERTY_vendor" => "$vendor"
    );
    $selectedRows = Array("XML_ID", "CATALOG_GROUP_1", "PREVIEW_PICTURE", 'DETAIL_PAGE_URL', 'NAME', 'IBLOCK_ID', 'ID');
    $resCollection = CIBlockElement::GetList(
        $arOrder,
        $arFilter,
        false,
        false,
        $selectedRows
    );

    $collectionFields = $resCollection->FieldsCount();
    //echo "FIELDS = ", $collectionFields;
    if ($collectionFields > 0) {
        $arTabs["COLLECTION"] = true;
        $arItemData["RES_COLLECTION"] = $resCollection;
    }
}

$arTabs["DELIVERY_POINTS"] = true;

$arTabs["PRODUCT_INFO_TAB_NUMBER"] = 0;
$arTabs["PRODUCT_INFO_TAB_NUMBER"] += $arTabs["SET_CONSTRUCTOR"]?1:0;

$arTabs["DELIVERY_POINTS_TAB_NUMBER"] = 0;
$arTabs["DELIVERY_POINTS_TAB_NUMBER"] += $arTabs["SET_CONSTRUCTOR"]?1:0;
$arTabs["DELIVERY_POINTS_TAB_NUMBER"] += $arTabs["PRODUCT_INFO"]?1:0;
$arTabs["DELIVERY_POINTS_TAB_NUMBER"] += $arTabs["COMPLECTATION"]?1:0;
$arTabs["DELIVERY_POINTS_TAB_NUMBER"] += $arTabs["COLLECTION"]?1:0;

$arItemData["TABS"] = $arTabs;

?>

    <div class="page page--product bx-<?=$arParams['TEMPLATE_THEME']?>" id="<?=$itemIds['ID']?>">
        <div class="page__content page__contentundefined" data-qcontent="level__page">
            <?
            if ($arParams['DISPLAY_NAME'] === 'Y')
            {
                ?>
                <div class="group">
                    <div class="col col-12-tl">
                        <h1 class="page__title"><?=$name?> <span class="page_productSku">(Код товара: <?=$arResult['PROPERTIES']['product_sku']['VALUE']?>)</span></h1>
                    </div>
                </div>
                <?
            }
            ?>
            <main>
                <div class="productInfo">
                    <input type="hidden" value="<?=$arResult['MASS'];?>" id="js_prod_mass">
                    <div class="productInfo__content block--inner productInfo__contentundefined" data-qcontent="level__productInfo">
                        <div class="productMain">
                            <div class="group group--md">
                                <div class="col col-5-tl"><!-- split modules/productImageSlider -->
                                    <div class="productImageSlider productImageSlider__content" data-qcontent="module__productImageSlider">
                                        <div class="productImageSlider__mainCarousel" id="js-productImageSlider">
                                            <ul class="productImageSlider__imageList owl-carousel"><?
                                                if (!empty($actualItem['MORE_PHOTO'])) {
                                                    foreach ($actualItem['MORE_PHOTO'] as $key => $photo) {
                                                        ?><img class="productImageSlider__image" src="<?=$photo['SRC']?>"/><?
                                                    }
                                                }
                                                ?>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="productImageSlider__fullWindow">
                                        <svg class="icon icon--slider-close" data-qcontent="element__ICONS__MAIN-SVG-use" id="js-slider-close">
                                            <use xlink:href="#close">              </use>
                                        </svg>
                                        <ul class="productImageSlider__imageListFull owl-carousel"><?
                                            if (!empty($actualItem['MORE_PHOTO'])) {
                                                foreach ($actualItem['MORE_PHOTO'] as $key => $photo) {
                                                    ?><img class="productImageSlider__image" src="<?=$photo['SRC']?>"/><?
                                                }
                                            }
                                            ?>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col col-3-tl col-6-tp">
                                    <div class="productInfo__section">
                                        <? include_once '/var/www/west/data/www/santehsmart.ru/bitrix/templates/STS2/includes/productCoupon.php';?>
                                        <? if ($arResult['PROPERTIES']['OLD_PRICE']['VALUE'] > 1): ?>
                                            <span class="productBadge productBadge--sale"
                                                  data-qcontent="element__buttons__productBadge">Распродажа</span><?
                                        elseif (isset($arItemData['PRICES']['OLD_PRICE'])): ?>
                                            <span class="productBadge productBadge--discount"
                                                  data-qcontent="element__buttons__productBadge">Акция</span><? endif ?>
                                        <div class="productInfo__priceStock">
                                            <?if($partnerId):?>
                                                <?if($_COOKIE['shopState'] == 'true'):?>
                                                    <div class="priceAndStock priceAndStock--part" data-qcontent="component__priceAndStock"><span
                                                                class="productCol__price" id="<?= $itemIds['PRICE'] ?>"><?= $arItemData['PRICES']['RETAIL_PRICE']['PRINT_VALUE'] ?> <span
                                                                    class="icon-ruble"></span></span><span
                                                                class="partStock"><? if ($arResult['CATALOG_QUANTITY'] > 0): ?><span
                                                                    class="productCol productCol__stock icon-tick" data-quantity="<?= $arResult['CATALOG_QUANTITY'] ?>">Доступно: <?= $arResult['CATALOG_QUANTITY'] ?> шт</span><? else: ?><span
                                                                    class="productCol__stock productCol__stock--out" data-quantity="<?= $arResult['CATALOG_QUANTITY'] ?>">Нет в наличии</span><? endif ?><? if ($product_reserv): ?><span
                                                                    class="productCol productCol__lock icon-padlock">Резерв: <?= $product_reserv ?> шт</span><? endif ?></span>
                                                    </div>
                                                <?else:?>
                                                    <div class="priceAndStock priceAndStock--part" data-qcontent="component__priceAndStock"><span
                                                                class="productCol__price" id="<?= $itemIds['PRICE_ID'] ?>"><span
                                                                    class="productCol__mrcPrice"><?= $arItemData['PRICES']['RETAIL_PRICE']['PRINT_VALUE'] ?>
                                                                <span class="icon-ruble"></span></span><span class="mop_price"><?= $arItemData['PRICES']['CURRENT_PRICE']['PRINT_VALUE'] ?></span>
                                                        <span class="icon-ruble"></span></span><span
                                                                class="partStock"><? if ($arResult['CATALOG_QUANTITY'] > 0): ?><span
                                                                    class="productCol productCol__stock icon-tick" data-quantity="<?= $arResult['CATALOG_QUANTITY'] ?>">Доступно: <?= $arResult['CATALOG_QUANTITY'] ?> шт</span><? else: ?><span
                                                                    class="productCol__stock productCol__stock--out" data-quantity="<?= $arResult['CATALOG_QUANTITY'] ?>">Нет в наличии</span><? endif ?><? if ($product_reserv): ?><span
                                                                    class="productCol productCol__lock icon-padlock">Резерв: <?= $product_reserv ?> шт</span><? endif ?></span>
                                                    </div>
                                                <?endif?>
                                            <?else:?>
                                                <div class="priceAndStock priceAndStock--sale" data-qcontent="component__priceAndStock"><span
                                                            class="productCol__price" id="<?= $itemIds['PRICE_ID'] ?>"><? if (isset($arItemData['PRICES']['OLD_PRICE'])): ?><span
                                                                class="productCol__oldPrice"><?= $arItemData['PRICES']['OLD_PRICE']['PRINT_VALUE'] ?>
                                                            <span class="icon-ruble"></span>
                                                            </span><? endif ?><span class="item_current_price"><?= $arItemData['PRICES']['CURRENT_PRICE']['PRINT_VALUE'] ?></span> <span
                                                                class="icon-ruble"></span></span><?if($arResult['CATALOG_QUANTITY']>0):?><span
                                                            class="productCol__stock icon-tick">В наличии</span><?else:?><span
                                                            class="productCol__stock productCol__stock--out">Нет в наличии</span><?endif?>
                                                </div>
                                            <?endif?>
                                        </div>
                                        <?if($morz_bonus):?>
                                            <div class="smartBonus" data-qcontent="component__smartBonus">
                                                <svg class="icon icon--smartBonus" data-qcontent="element__ICONS__MAIN-SVG-use">
                                                    <use xlink:href="#smartbonus">  </use>
                                                </svg><span class="smartBonus__value">+ <?=$morz_bonus?>  </span>CмартБонусов
                                            </div>
                                        <?endif;?>
                                        <? if (isset($arItemData['QUANTITY'])): ?>
                                            <div class="counter__ctn" style="display: none">
                                                <input class="counter " type="text"
                                                       value="<?= $arItemData['QUANTITY']['QUANTITY']['VALUE'] ?>"
                                                       id="<?= $arItemData['QUANTITY']['QUANTITY']['ID'] ?>"
                                                       name="<?= $arItemData['QUANTITY']['QUANTITY']['NAME'] ?>"
                                                       data-qcontent="element__INPUTS__counter"/>
                                            </div>
                                        <? endif ?>
                                        <? if (isset($arItemData['BUTTONS']['BUY'])): ?>
                                            <div id="<?= $arItemData['BUTTONS']['BUY']['ID_CONTAINER'] ?>"><button
                                                        class="toOrder active notEdit"
                                                        data-qcontent="element__buttons__toOrder"
                                                        id="<?= $arItemData['BUTTONS']['BUY']['ID'] ?>"><?= $arItemData['BUTTONS']['BUY']['MESSAGE'] ?></button></div>
                                        <? endif ?>
                                        <? if (isset($arItemData['BUTTONS']['COMPARE'])): ?>
                                            <div class="checkBox col col-12-tl col-6-tp col-6-m checkBox--compare" data-qcontent="element__buttons__checkBox">
                                                <label class="checkBox__label" type="checkbox" id="<?= $arItemData['BUTTONS']['COMPARE']['ID'] ?>">
                                                <input class="checkBox__input productFilter__checkbox" type="checkbox" data-entity="compare-checkbox"/>
                                                <svg class="icon " data-qcontent="element__ICONS__MAIN-SVG-use">
                                                    <use xlink:href="#compare">    </use>
                                                </svg>
                                                <?= $arItemData['BUTTONS']['COMPARE']['MESSAGE'] ?>
                                                </label>
                                            </div>
<!--                                            <label id="--><?//= $arItemData['BUTTONS']['COMPARE']['ID'] ?><!--">-->
<!--                                                <input type="checkbox" data-entity="compare-checkbox">-->
<!--                                                <span data-entity="compare-title">--><?//= $arItemData['BUTTONS']['COMPARE']['MESSAGE'] ?><!--</span>-->
<!--                                            </label>-->
                                        <? endif ?>
                                        <button class="button button--fastBuy" data-qcontent="element__buttons__button">Оформить без регистрации</button><!-- split modules/productChar -->
                                        <?
                                        if (!empty($arResult['DISPLAY_PROPERTIES']) || $arResult['SHOW_OFFERS_PROPS'])
                                        {
                                            ?>
                                            <div class="productChar__content" data-qcontent="module__productChar">
                                                <?
                                                if (!empty($arResult['DISPLAY_PROPERTIES']))
                                                {
                                                    ?>
                                                    <h5 class="productChar__title">Основные Характеристики:</h5>
                                                    <dl class="productChar">
                                                        <?
                                                        foreach ($arResult['DISPLAY_PROPERTIES'] as $property)
                                                        {
                                                            if (isset($arParams['MAIN_BLOCK_PROPERTY_CODE'][$property['CODE']]))
                                                            {
                                                                ?>
                                                                <div class="productChar__terminDesc">
                                                                    <dt><?=$property['NAME']?>:</dt>
                                                                    <dd><?=(is_array($property['DISPLAY_VALUE'])
                                                                            ? implode(', ', $property['DISPLAY_VALUE'])
                                                                            : $property['DISPLAY_VALUE'])?></dd>
                                                                </div>
                                                                <?
                                                            }
                                                        }
                                                        unset($property);
                                                        ?>
                                                        <div class="deliverySection__more">
                                                            <a href="#delPoints" class="actionLink  "
                                                               onclick="window.openDeliveryPointsTab(<?=$arItemData["TABS"]["PRODUCT_INFO_TAB_NUMBER"]?>);"
                                                               data-qcontent="element__LINKS__actionLink">Все характеристики</a>
                                                        </div>
                                                    </dl>
                                                    <?
                                                }
                                                ?>
                                            </div>
                                            <?
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="col col-4-tl col-6-tp">
                                    <div class="productInfo__section productInfo__section--delivery"><!-- split modules/deliverySection -->
                                        <?$frame = $this->createFrame()->begin();?>
                                        <?$APPLICATION->IncludeComponent(
                                            "sts2:when_and_how_much",
                                            "",
                                            Array(
                                                "ELEMENT_ID" => $arResult['ID'],
                                                "MASS" => $arResult['MASS'],
                                                "MORZ"=>$arResult['PROPERTIES']['morz']['VALUE'],
                                                "QUANTITY" => $arResult['CATALOG_QUANTITY'],
                                                "ELEMENT_PRICE" => $arResult['PRICES']['BASE']['DISCOUNT_VALUE'],
                                                "DELIV_POINTS_TAB_INDEX" => $arItemData["TABS"]["DELIVERY_POINTS_TAB_NUMBER"],
                                                "IBLOC_ID"=>18,
                                                "CACHE_TYPE" => "N",
                                            )
                                        );?>
                                        <?$frame->beginStub()?>
                                        <img style="position:absolute; top:50%; left:45%;" src='/bitrix/templates/STS2/source/images/compozit.svg'>
                                        <?$frame->end()?>
                                    </div><a name="delPoints"></a>
                                </div>
                            </div>
                        </div>
                        <?$APPLICATION->IncludeComponent("bitrix:catalog.brandblock",
                            "sts2", array(
                                "IBLOCK_TYPE" => $arParams['IBLOCK_TYPE'],
                                "IBLOCK_ID" => $arParams['IBLOCK_ID'],
                                "ELEMENT_ID" => $arResult['ID'],
                                "ELEMENT_CODE" => "",
                                "PROP_CODE" => $arParams['BRAND_PROP_CODE'],
                                "CACHE_TYPE" => $arParams['CACHE_TYPE'],
                                "CACHE_TIME" => $arParams['CACHE_TIME'],
                                "CACHE_GROUPS" => $arParams['CACHE_GROUPS'],
                                "WIDTH" => "119",
                                "HEIGHT" => "57"
                            ),
                            $component,
                            array('HIDE_ICONS' => 'Y')
                        );?>
                    </div>
                </div>




                <div class="subProductInfo">
                    <div class="subProductInfo__content block--inner subProductInfo__contentundefined" data-qcontent="level__subProductInfo">
                        <div class="subProductInfo__tabber">
                            <ul class="subProductInfo__tabber-list">
                                <?if($arItemData["TABS"]["SET_CONSTRUCTOR"]):?>
                                <li class="subProductInfo__tabber-item subProductInfo__tabber-item--active"><span>Дополнительное Оборудование</span></li>
                                <?endif?>
                                <?if($arItemData["TABS"]["PRODUCT_INFO"]):?>
                                <li class="subProductInfo__tabber-item <?
                                    if($arItemData["TABS"]["SET_CONSTRUCTOR"]):?>ibb<?
                                else:?>subProductInfo__tabber-item--active<?endif;?>"><span>Характеристики и Описание</span></li>
                                <?endif?>
                                <?if($arItemData["TABS"]["COMPLECTATION"]):?>
                                <li class="subProductInfo__tabber-item ibb"><span>Состав комплекта поставки</span></li>
                                <?endif?>
                                <?if($arItemData["TABS"]["COLLECTION"]):?>
                                <li class="subProductInfo__tabber-item ibb"><span>Товары из этой серии коллекции</span></li>
                                <?endif?>
                                <?if($arItemData["TABS"]["DELIVERY_POINTS"]):?>
                                <li class="subProductInfo__tabber-item ibb"><span>Пункты выдачи в вашем городе</span></li>
                                <?endif?>
                            </ul>
                            <div class="subProductInfo__contents">
                                <?if($arItemData["TABS"]["SET_CONSTRUCTOR"]):?>
                                    <div class="subProductInfo__tabContent subProductInfo__hided-item"><!-- split modules/additionalProducts -->
                                        <?$APPLICATION->IncludeComponent("sts2:catalog.set.constructor.mod",
                                            "",
                                            array(
                                                "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                                                "ELEMENT_ID" => $arResult["ID"],
                                                "PRICE_CODE" => $arParams["PRICE_CODE"],
                                                "BASKET_URL" => $arParams["BASKET_URL"],
                                                "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                                                "CACHE_TIME" => $arParams["CACHE_TIME"],
                                                "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                                                "BX_ID" => $itemIds['ID'],
                                                "AVAILABLE" => $arResult['CATALOG_QUANTITY'] > 0,
                                            ),
                                            $component,
                                            array()
                                        );?>
                                    </div>
                                <?endif?>
                                <?if($arItemData["TABS"]["PRODUCT_INFO"]):?>
                                <div class="subProductInfo__tabContent" style="<?=($arItemData["TABS"]["SET_CONSTRUCTOR"])?"display: none":""?>">
                                    <div class="group group--md">
                                        <div class="col col-8-tl">
                                        <?
                                        if (!empty($arResult['DISPLAY_PROPERTIES']) || $arResult['SHOW_OFFERS_PROPS'])
                                        {
                                            ?>
                                            <div class="productChar__content" data-qcontent="module__productChar">
                                                <?
                                                if (!empty($arResult['DISPLAY_PROPERTIES']))
                                                {
                                                    ?>
                                                    <dl class="productChar">
                                                        <?
                                                        foreach ($arResult['DISPLAY_PROPERTIES'] as $property)
                                                        {
                                                            ?>
                                                            <div class="productChar__terminDesc">
                                                                <dt><?=$property['NAME']?>:</dt>
                                                                <dd><?=(is_array($property['DISPLAY_VALUE'])
                                                                        ? implode(', ', $property['DISPLAY_VALUE'])
                                                                        : $property['DISPLAY_VALUE'])?></dd>
                                                            </div>
                                                            <?
                                                        }
                                                        unset($property);
                                                        ?>
                                                    </dl>
                                                    <?
                                                }
                                                ?>
                                            </div>
                                            <?
                                        }
                                        ?>
                                        </div>
                                        <div class="col col-12-tl">
                                            <?
                                            if ($showDescription)
                                            {
                                                if ($arResult['DETAIL_TEXT'] != '')
                                                {
                                                    ?>
                                                    <section class="subProductInfo__description">
                                                        <h5>Описание товара</h5>
                                                        <?
                                                        echo $arResult['DETAIL_TEXT_TYPE'] === 'html' ? $arResult['DETAIL_TEXT'] : '<p>'.$arResult['DETAIL_TEXT'].'</p>';
                                                        ?>
                                                    </section>
                                                    <?
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <?endif?>
                                <?if($arItemData["TABS"]["COMPLECTATION"]):?>
                                <div class="subProductInfo__tabContent" style="<?=($arItemData["TABS"]["PRODUCT_INFO"] || $arItemData["TABS"]["SET_CONSTRUCTOR"])?"display: none":""?>">
                                    <div class="group group--md">
                                        <div class="col col-8-tl">
                                           <? $APPLICATION->IncludeComponent("sts2:deliveri_package",
                                                "",
                                                array(
                                                    "IBLOCK_ID" => 19,
                                                    "ELEMENT_ID" => $arResult['PROPERTIES']['komplekt_postavki']['VALUE'],
                                                )
                                            );?>
                                        </div>
                                    </div>
                                </div>
                                <?endif?>
                                <?if($arItemData["TABS"]["COLLECTION"]):?>
                                <div class="subProductInfo__tabContent" style="<?=($arItemData["TABS"]["PRODUCT_INFO"] || $arItemData["TABS"]["SET_CONSTRUCTOR"])?"display: none":""?>">
                                    <? /////COLLECTIONS
                                    $collectId = 'or_collection';
                                    $collectClass = 'or_collect_class';
                                    ?>

                                    <div id="<?= $collectId ?>" class="<?= $collectClass ?>">
                                        <h4>Товары из этой серии, коллекции:</h4>
                                        <div id='coll_carousel' class='owl-carousel owl-theme'>
                                        <?
                                        while ($supres = $arItemData["RES_COLLECTION"]->getNext()) {
                                            $price = number_format($supres['CATALOG_PRICE_1'], 0, '', ' ');
                                            $img = CFile::GetFileArray($supres["PREVIEW_PICTURE"]);
                                            if ($img) $img = $img['SRC'];
                                            ?>
                                            <div class="productCol__content item coll-item" data-qcontent="module__productCol">
                                                <div class="productCol__card"><span class="productSection__subTitle">Код: </span><span
                                                            class="productCol__sku"><?= $supres['XML_ID'] ?></span><a
                                                            class="productCol__link" href="<?= $supres['DETAIL_PAGE_URL'] ?>">
                                                        <div class="productCol__img coll-item-img" style="background-image: url(<?= $img ?>)" data-entity="image-wrapper"></div>
                                                        <h6 class="productCol__name"><?= $supres['NAME'] ?></h6>
                                                        <div class="priceAndStock priceAndStock--sale" data-qcontent="component__priceAndStock"><span
                                                                    class="productCol__price"
                                                                    id="<?= $itemIds['PRICE'] ?>"><?= $price ?> <span
                                                                        class="icon-ruble"></span></span><? if ($supres['CATALOG_QUANTITY'] > 0): ?><span
                                                                    class="productCol__stock icon-tick">В наличии</span><? else: ?><span
                                                                    class="productCol__stock productCol__stock--out">Нет в наличии</span><? endif ?>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>


                                            <?
                                        }
                                        ?>
                                            <div style='clear: both'></div>
                                        </div>
                                    <script src='/libraries/owl.carousel/owl-carousel/owl.carousel.js'></script>
                                <link rel='stylesheet' href='/libraries/owl.carousel/owl-carousel/owl.carousel.css'>
                                <link rel='stylesheet' href='/libraries/owl.carousel/owl-carousel/owl.theme.css'>
                                    <script>

                                        $(document).ready(function () {

                                            //Sort random function
                                            function random(owlSelector) {
                                                owlSelector.children().sort(function () {
                                                    return Math.round(Math.random()) - 0.5;
                                                }).each(function () {
                                                    $(this).appendTo(owlSelector);
                                                });
                                            }

                                            $("#coll_carousel").owlCarousel({
                                                navigation: true,
                                                navigationText: [
                                                    "<i class='icon-chevron-left icon-white'></i>",
                                                    "<i class='icon-chevron-right icon-white'></i>"
                                                ],
                                                beforeInit: function (elem) {
                                                    //Parameter elem pointing to $("#coll_carousel")
                                                    random(elem);
                                                }

                                            });

                                        });
                                    </script>
                                    <style>
                                        #coll_carousel .item {
                                            display: block;
                                            padding: 30px 0px;
                                            margin: 5px;
                                            /*color: #FFF;*/
                                            -webkit-border-radius: 3px;
                                            -moz-border-radius: 3px;
                                            border-radius: 3px;
                                            text-align: center;
                                        }

                                        .owl-theme .owl-controls .owl-buttons div {
                                            padding: 20px;
                                        }

                                        .owl-theme .owl-buttons i {
                                            margin-top: 2px;
                                        }

                                        /*To move navigation buttons outside use these settings:*/
                                        .owl-theme .owl-controls .owl-buttons div {
                                            position: absolute;
                                        }

                                        .owl-theme .owl-controls .owl-buttons .owl-prev {
                                            left: 10px;
                                            top: 120px;
                                        }

                                        .owl-theme .owl-controls .owl-buttons .owl-next {
                                            right: 10px;
                                            top: 120px;
                                        }

                                        .owl-prev {
                                            background-image: url('/libraries/owl.carousel/assets/img/coll_sli_btn_l.png') !important;
                                            background-repeat: no-repeat !important;
                                            background-position: 45% 50% !important;

                                        }

                                        .owl-next {
                                            background-image: url('/libraries/owl.carousel/assets/img/coll_sli_btn_r.png') !important;
                                            background-repeat: no-repeat !important;
                                            background-position: 55% 50% !important;

                                        }
                                    </style>
                                        <? /////TOGETHER MORE INEXPENSIVE
                                        /*$APPLICATION->IncludeComponent("orangerocket:TogetherMoreInexpensive", "template1", Array(
                                            "ELEMENT_PRICE" => $arResult["PRICES"]["BASE"]["DISCOUNT_VALUE"],
                                            "PARENT_ID" => $arResult["ID"]
                                        ),
                                            false
                                        );*/
                                        ?>
                                    </div>
                                </div>
                                    <div id="partnerPVZ" >
                                        <?
                                        $Logistic = Logistic::getInstance($_SESSION['TF_LOCATION_SELECTED_CITY_NAME']);
                                        $Logistic->setPartnerPvzHtml();?>
                                    </div>
                                <?endif?>
                                <?if($arItemData["TABS"]["DELIVERY_POINTS"]):?>

                                <div class="subProductInfo__tabContent order__form--delivery" style="<?=($arItemData["TABS"]["PRODUCT_INFO"] || $arItemData["TABS"]["SET_CONSTRUCTOR"])?"display: none":""?>">

                                    <div class="group group--md">
                                        <div class="col col-12-tl">
                                            <div id ='pvzWidjet' style="width=100%; height:450px"></div>
                                        </div>

                                    <?
//                                    $APPLICATION->IncludeComponent(
//                                        "orangerocket:delivery_points_forOrder",
//                                        "",
//                                        Array(
//                                            "IBLOC_ID" => 18,
//                                            "CHECKED" => "Y",
//                                            "FOR_ELEMENT" => "Y"
//                                        ),
//                                        false
//                                    );
                                    ?>
                                    </div>
                                </div>
                                <?endif?>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

<?
$emptyProductProperties = empty($arResult['PRODUCT_PROPERTIES']);
if ($arParams['ADD_PROPERTIES_TO_BASKET'] === 'Y' && !$emptyProductProperties)
{
    ?>
    <div id="<?=$itemIds['BASKET_PROP_DIV']?>" style="display: none;">
        <?
        if (!empty($arResult['PRODUCT_PROPERTIES_FILL']))
        {
            foreach ($arResult['PRODUCT_PROPERTIES_FILL'] as $propId => $propInfo)
            {
                ?>
                <input type="hidden" name="<?=$arParams['PRODUCT_PROPS_VARIABLE']?>[<?=$propId?>]" value="<?=htmlspecialcharsbx($propInfo['ID'])?>">
                <?
                unset($arResult['PRODUCT_PROPERTIES'][$propId]);
            }
        }

        $emptyProductProperties = empty($arResult['PRODUCT_PROPERTIES']);
        if (!$emptyProductProperties)
        {
            ?>
            <table>
                <?
                foreach ($arResult['PRODUCT_PROPERTIES'] as $propId => $propInfo)
                {
                    ?>
                    <tr>
                        <td><?=$arResult['PROPERTIES'][$propId]['NAME']?></td>
                        <td>
                            <?
                            if (
                                $arResult['PROPERTIES'][$propId]['PROPERTY_TYPE'] === 'L'
                                && $arResult['PROPERTIES'][$propId]['LIST_TYPE'] === 'C'
                            )
                            {
                                foreach ($propInfo['VALUES'] as $valueId => $value)
                                {
                                    ?>
                                    <label>
                                        <input type="radio" name="<?=$arParams['PRODUCT_PROPS_VARIABLE']?>[<?=$propId?>]"
                                            value="<?=$valueId?>" <?=($valueId == $propInfo['SELECTED'] ? '"checked"' : '')?>>
                                        <?=$value?>
                                    </label>
                                    <br>
                                    <?
                                }
                            }
                            else
                            {
                                ?>
                                <select name="<?=$arParams['PRODUCT_PROPS_VARIABLE']?>[<?=$propId?>]">
                                    <?
                                    foreach ($propInfo['VALUES'] as $valueId => $value)
                                    {
                                        ?>
                                        <option value="<?=$valueId?>" <?=($valueId == $propInfo['SELECTED'] ? '"selected"' : '')?>>
                                            <?=$value?>
                                        </option>
                                        <?
                                    }
                                    ?>
                                </select>
                                <?
                            }
                            ?>
                        </td>
                    </tr>
                    <?
                }
                ?>
            </table>
            <?
        }
        ?>
    </div>
    <?
}

$jsParams = array(
    'CONFIG' => array(
        'USE_CATALOG' => $arResult['CATALOG'],
        'PARTNER_ID' => isPartnerClient(),
        'SHOW_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
        'SHOW_PRICE' => !empty($arResult['ITEM_PRICES']),
        'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'] === 'Y',
        'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'] === 'Y',
        'USE_PRICE_COUNT' => $arParams['USE_PRICE_COUNT'],
        'DISPLAY_COMPARE' => $arParams['DISPLAY_COMPARE'],
        'MAIN_PICTURE_MODE' => $arParams['DETAIL_PICTURE_MODE'],
        'ADD_TO_BASKET_ACTION' => $arParams['ADD_TO_BASKET_ACTION'],
        'SHOW_CLOSE_POPUP' => $arParams['SHOW_CLOSE_POPUP'] === 'Y',
        'SHOW_MAX_QUANTITY' => $arParams['SHOW_MAX_QUANTITY'],
        'RELATIVE_QUANTITY_FACTOR' => $arParams['RELATIVE_QUANTITY_FACTOR'],
        'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'],
        'USE_STICKERS' => true,
        'USE_SUBSCRIBE' => $showSubscribe,
        'SHOW_SLIDER' => $arParams['SHOW_SLIDER'],
        'SLIDER_INTERVAL' => $arParams['SLIDER_INTERVAL'],
        'ALT' => $alt,
        'TITLE' => $title,
        'MAGNIFIER_ZOOM_PERCENT' => 200,
        'USE_ENHANCED_ECOMMERCE' => $arParams['USE_ENHANCED_ECOMMERCE'],
        'DATA_LAYER_NAME' => $arParams['DATA_LAYER_NAME'],
        'BRAND_PROPERTY' => !empty($arResult['DISPLAY_PROPERTIES'][$arParams['BRAND_PROPERTY']])
            ? $arResult['DISPLAY_PROPERTIES'][$arParams['BRAND_PROPERTY']]['DISPLAY_VALUE']
            : null
    ),
    'VISUAL' => $itemIds,
    'PRODUCT_TYPE' => $arResult['CATALOG_TYPE'],
    'PRODUCT' => array(
        'ID' => $arResult['ID'],
        'ACTIVE' => $arResult['ACTIVE'],
        'PICT' => reset($arResult['MORE_PHOTO']),
        'NAME' => $arResult['~NAME'],
        'SUBSCRIPTION' => true,
        'ITEM_PRICE_MODE' => $arResult['ITEM_PRICE_MODE'],
        'ITEM_PRICES' => $arResult['ITEM_PRICES'],
        'ITEM_PRICE_SELECTED' => $arResult['ITEM_PRICE_SELECTED'],
        'ITEM_QUANTITY_RANGES' => $arResult['ITEM_QUANTITY_RANGES'],
        'ITEM_QUANTITY_RANGE_SELECTED' => $arResult['ITEM_QUANTITY_RANGE_SELECTED'],
        'ITEM_MEASURE_RATIOS' => $arResult['ITEM_MEASURE_RATIOS'],
        'ITEM_MEASURE_RATIO_SELECTED' => $arResult['ITEM_MEASURE_RATIO_SELECTED'],
        'SLIDER_COUNT' => $arResult['MORE_PHOTO_COUNT'],
        'SLIDER' => $arResult['MORE_PHOTO'],
        'CAN_BUY' => $arResult['CAN_BUY'],
        'CHECK_QUANTITY' => $arResult['CHECK_QUANTITY'],
        'QUANTITY_FLOAT' => is_float($arResult['ITEM_MEASURE_RATIOS'][$arResult['ITEM_MEASURE_RATIO_SELECTED']]['RATIO']),
        'MAX_QUANTITY' => $arResult['CATALOG_QUANTITY'],
        'STEP_QUANTITY' => $arResult['ITEM_MEASURE_RATIOS'][$arResult['ITEM_MEASURE_RATIO_SELECTED']]['RATIO'],
        'CATEGORY' => $arResult['CATEGORY_PATH']
    ),
    'BASKET' => array(
        'ADD_PROPS' => $arParams['ADD_PROPERTIES_TO_BASKET'] === 'Y',
        'QUANTITY' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
        'PROPS' => $arParams['PRODUCT_PROPS_VARIABLE'],
        'EMPTY_PROPS' => $emptyProductProperties,
        'BASKET_URL' => $arParams['BASKET_URL'],
        'ADD_URL_TEMPLATE' => $arResult['~ADD_URL_TEMPLATE'],
        'BUY_URL_TEMPLATE' => $arResult['~BUY_URL_TEMPLATE']
    )
);
unset($emptyProductProperties);

if ($arParams['DISPLAY_COMPARE'])
{
	$jsParams['COMPARE'] = array(
		'COMPARE_URL_TEMPLATE' => $arResult['~COMPARE_URL_TEMPLATE'],
		'COMPARE_DELETE_URL_TEMPLATE' => $arResult['~COMPARE_DELETE_URL_TEMPLATE'],
		'COMPARE_PATH' => $arParams['COMPARE_PATH']
	);
}
?>
<script>
	BX.message({
		ECONOMY_INFO_MESSAGE: '<?=GetMessageJS('CT_BCE_CATALOG_ECONOMY_INFO2')?>',
		TITLE_ERROR: '<?=GetMessageJS('CT_BCE_CATALOG_TITLE_ERROR')?>',
		TITLE_BASKET_PROPS: '<?=GetMessageJS('CT_BCE_CATALOG_TITLE_BASKET_PROPS')?>',
		BASKET_UNKNOWN_ERROR: '<?=GetMessageJS('CT_BCE_CATALOG_BASKET_UNKNOWN_ERROR')?>',
		BTN_SEND_PROPS: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_SEND_PROPS')?>',
		BTN_MESSAGE_BASKET_REDIRECT: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_BASKET_REDIRECT')?>',
		BTN_MESSAGE_CLOSE: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_CLOSE')?>',
		BTN_MESSAGE_CLOSE_POPUP: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_CLOSE_POPUP')?>',
		TITLE_SUCCESSFUL: '<?=GetMessageJS('CT_BCE_CATALOG_ADD_TO_BASKET_OK')?>',
		COMPARE_MESSAGE_OK: '<?=GetMessageJS('CT_BCE_CATALOG_MESS_COMPARE_OK')?>',
		COMPARE_UNKNOWN_ERROR: '<?=GetMessageJS('CT_BCE_CATALOG_MESS_COMPARE_UNKNOWN_ERROR')?>',
		COMPARE_TITLE: '<?=GetMessageJS('CT_BCE_CATALOG_MESS_COMPARE_TITLE')?>',
		BTN_MESSAGE_COMPARE_REDIRECT: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_COMPARE_REDIRECT')?>',
		PRODUCT_GIFT_LABEL: '<?=GetMessageJS('CT_BCE_CATALOG_PRODUCT_GIFT_LABEL')?>',
		PRICE_TOTAL_PREFIX: '<?=GetMessageJS('CT_BCE_CATALOG_MESS_PRICE_TOTAL_PREFIX')?>',
		RELATIVE_QUANTITY_MANY: '<?=CUtil::JSEscape($arParams['MESS_RELATIVE_QUANTITY_MANY'])?>',
		RELATIVE_QUANTITY_FEW: '<?=CUtil::JSEscape($arParams['MESS_RELATIVE_QUANTITY_FEW'])?>',
		SITE_ID: '<?=SITE_ID?>'
	});

	var <?=$obName?> = new JCCatalogElement(<?=CUtil::PhpToJSObject($jsParams, false, true)?>);
</script>
<?
unset($actualItem, $itemIds, $jsParams);