<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc;

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $item
 * @var array $actualItem
 * @var array $minOffer
 * @var array $itemIds
 * @var array $price
 * @var array $measureRatio
 * @var bool $haveOffers
 * @var bool $showSubscribe
 * @var array $morePhoto
 * @var bool $showSlider
 * @var string $imgTitle
 * @var string $productTitle
 * @var string $buttonSizeClass
 * @var CatalogSectionComponent $component
 */
?>

<?
$product_sku = '';
$product_reserv = 0;
foreach ($item['DISPLAY_PROPERTIES'] as $code => $displayProperty) {
    if ($code == "product_sku") {
        $product_sku = $displayProperty['DISPLAY_VALUE'];
        unset($item['DISPLAY_PROPERTIES'][$code]);
    }
    if ($code == "stock_reserved") {
        $product_reserv = intval($displayProperty['DISPLAY_VALUE']);
        unset($item['DISPLAY_PROPERTIES'][$code]);
    }
}
//$quantity_full = $item['CATALOG_QUANTITY'] + $product_reserv;

$partnerId = isPartnerClient();

$morz_bonus = (($item['PROPERTIES']['OLD_PRICE']['VALUE'] > 1) ? 0 : round($item['PROPERTIES']['morz']['VALUE']*0.1/10)*10);
$morz_bonus = ($item['PRICES']['BASE']['DISCOUNT_DIFF']) ? $morz_bonus/2 : $morz_bonus;

$arItemData = array();

foreach ($arParams['PRODUCT_BLOCKS_ORDER'] as $blockName) {
    switch ($blockName) {
        case 'price':
            $arPrices = array();
            if ($arParams['SHOW_OLD_PRICE'] === 'Y') {
                $arOldPrice = array();
                $arOldPrice['ID'] = $itemIds['PRICE_OLD'];
                if ($item['PRICES']['BASE']['DISCOUNT_VALUE'] < $item['PRICES']['BASE']['VALUE']) {
                    $arOldPrice['PRINT_VALUE'] = number_format($item['PRICES']['BASE']['VALUE'], 0, '', ' ');
                    $arPrices['OLD_PRICE'] = $arOldPrice;
                }
                if ($item['PROPERTIES']['OLD_PRICE']['VALUE'] > 0) {
                    $arOldPrice['PRINT_VALUE'] = number_format($item['PROPERTIES']['OLD_PRICE']['VALUE'], 0, '', ' ');
                    $arPrices['OLD_PRICE'] = $arOldPrice;
                }
            }
            if (!empty($price)) {
                $arCurrentPrice = array();
                $arCurrentPrice['ID'] = $itemIds['PRICE'];

                $arCurrentPrice['PRINT_VALUE'] = number_format($item['PRICES']['BASE']['DISCOUNT_VALUE'], 0, '', ' ');
                $arPrices['RETAIL_PRICE']['PRINT_VALUE'] = number_format($item['PRICES']['BASE']['VALUE'], 0, '', ' ');

                if ($partnerId) {
                    $arPrice = CCatalogIBlockParameters::getPriceTypesList();
                    if (isset($arPrice['purchasePrice_' . $partnerId])) {
                        $arCurrentPrice['PRINT_VALUE'] = number_format($item['PRICES']['purchasePrice_' . $partnerId]['VALUE'], 0, '', ' ');
                    }
                    if (isset($arPrice['retailPrice_' . $partnerId])) {
                        $arPrices['RETAIL_PRICE']['PRINT_VALUE'] = number_format($item['PRICES']['retailPrice_' . $partnerId]['VALUE'], 0, '', ' ');
                    }
                }

                $arPrices['CURRENT_PRICE'] = $arCurrentPrice;
            }
            $arItemData['PRICES'] = $arPrices;
            break;

        /*case 'quantityLimit':
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

            break;*/

        case 'quantity':
            if ($actualItem['CAN_BUY'] && $arParams['USE_PRODUCT_QUANTITY']) {
                $arQuantity = array();
                $arQuantity['ENTITY'] = "quantity-block";
                $arQuantity['MINUS']['ID'] = $itemIds['QUANTITY_DOWN'];
                $arQuantity['QUANTITY']['ID'] = $itemIds['QUANTITY'];
                $arQuantity['QUANTITY']['NAME'] = $arParams['PRODUCT_QUANTITY_VARIABLE'];
                $arQuantity['QUANTITY']['VALUE'] = $measureRatio;
                $arQuantity['PLUS']['ID'] = $itemIds['QUANTITY_UP'];
                $arItemData['QUANTITY'] = $arQuantity;
            }

            break;

        case 'buttons':
        case 'compare':
            $arButtons = array();
            if ($actualItem['CAN_BUY']) {
                $arButtons['BUY']['ID_CONTAINER'] = $itemIds['BASKET_ACTIONS'];
                $arButtons['BUY']['ID'] = $itemIds['BUY_LINK'];
                if($partnerId) $arButtons['BUY']['MESSAGE'] = 'В заявку';
                else $arButtons['BUY']['MESSAGE'] = ($arParams['ADD_TO_BASKET_ACTION'] === 'BUY' ? $arParams['MESS_BTN_BUY'] : $arParams['MESS_BTN_ADD_TO_BASKET']);
            } else {
                $arButtons['SUBSCRIBE']['ID'] = $itemIds['NOT_AVAILABLE_MESS'];
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
?>

<div class="productCol__content" data-qcontent="module__productCol">
    <div class="productCol__card"><span class="productSection__subTitle">Код: </span><span
                class="productCol__sku"><?= $product_sku ?></span>
            <? if ($item['PROPERTIES']['OLD_PRICE']['VALUE'] > 1): ?>
                <span class="productBadge productBadge--sale"
                      data-qcontent="element__buttons__productBadge">Распродажа</span><?
            elseif (isset($arItemData['PRICES']['OLD_PRICE'])): ?>
            <span class="productBadge productBadge--discount"
                  data-qcontent="element__buttons__productBadge">Акция</span><? endif ?><a
                class="productCol__link" href="<?= $item['DETAIL_PAGE_URL'] ?>">
            <div class="productCol__img" style="background-image: url(<?= $item['PREVIEW_PICTURE']['SRC'] ?>)"
                 title="<?= $imgTitle ?>" data-entity="image-wrapper"
                 id="<?= $item['SECOND_PICT'] ? $itemIds['SECOND_PICT'] : $itemIds['PICT'] ?>"></div>
            <h6 class="productCol__name"><?= $item['NAME'] ?></h6>
            <? if ($partnerId): ?>
                <?if($_COOKIE['shopState'] == 'true'):?>
                    <div class="priceAndStock priceAndStock--sale" data-qcontent="component__priceAndStock"><span
                                class="productCol__price" id="<?= $itemIds['PRICE'] ?>"><?= $arItemData['PRICES']['RETAIL_PRICE']['PRINT_VALUE'] ?> <span
                                    class="icon-ruble"></span></span><? if ($item['CATALOG_QUANTITY'] > 0): ?><span
                                    class="productCol productCol__stock icon-tick" style="font-size:0.8em">Доступно: <?= $item['CATALOG_QUANTITY'] ?> шт<? if ($product_reserv): ?><br><span
                                class="productCol productCol__lock icon-padlock">Резерв: <?= $product_reserv ?> шт</span><? endif ?></span><? else: ?><span
                                    class="productCol__stock productCol__stock--out" style="font-size:0.8em">Нет в наличии<? if ($product_reserv): ?><br><span
                                class="productCol productCol__lock icon-padlock">Резерв: <?= $product_reserv ?> шт</span><? endif ?></span><? endif ?>
                    </div>
                <?else:?>
                    <div class="priceAndStock priceAndStock--part" data-qcontent="component__priceAndStock"><span
                                class="productCol__price" id="<?= $itemIds['PRICE'] ?>"><span
                                    class="productCol__mrcPrice"><?= $arItemData['PRICES']['RETAIL_PRICE']['PRINT_VALUE'] ?>
                                <span class="icon-ruble"></span></span><?= $arItemData['PRICES']['CURRENT_PRICE']['PRINT_VALUE'] ?>
                            <span class="icon-ruble"></span></span><span
                                class="partStock"><? if ($item['CATALOG_QUANTITY'] > 0): ?><span
                                    class="productCol productCol__stock icon-tick">Доступно: <?= $item['CATALOG_QUANTITY'] ?> шт</span><? else: ?><span
                                    class="productCol__stock productCol__stock--out">Нет в наличии</span><? endif ?><? if ($product_reserv): ?>
                                <span
                                        class="productCol productCol__lock icon-padlock">Резерв: <?= $product_reserv ?> шт</span><? endif ?></span>
                    </div>
                <?endif?>
            <? else: ?>
                <div class="priceAndStock priceAndStock--sale" data-qcontent="component__priceAndStock"><span
                            class="productCol__price"
                            id="<?= $itemIds['PRICE'] ?>"><? if (isset($arItemData['PRICES']['OLD_PRICE'])): ?><span
                                class="productCol__oldPrice"><?= $arItemData['PRICES']['OLD_PRICE']['PRINT_VALUE'] ?>
                            <span class="icon-ruble"></span>
                            </span><? endif ?><?= $arItemData['PRICES']['CURRENT_PRICE']['PRINT_VALUE'] ?> <span
                                class="icon-ruble"></span></span><? if ($item['CATALOG_QUANTITY'] > 0): ?><span
                            class="productCol__stock icon-tick">В наличии</span><? else: ?><span
                            class="productCol__stock productCol__stock--out">Нет в наличии</span><? endif ?>
                    <?if($morz_bonus):?>
                        <div class="smartBonus" data-qcontent="component__smartBonus">
                            <svg class="icon icon--smartBonus" data-qcontent="element__ICONS__MAIN-SVG-use">
                                <use xlink:href="#smartbonus">  </use>
                            </svg><span class="smartBonus__value">+ <?=$morz_bonus?>  </span>CмартБонусов
                        </div>
                    <?endif;?>
                </div>
            <? endif ?>
        </a>
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
            <div id="<?= $arItemData['BUTTONS']['BUY']['ID_CONTAINER'] ?>"><button type="button"
                        class="toOrder active notEdit"
                        data-qcontent="element__buttons__toOrder"
                        id="<?= $arItemData['BUTTONS']['BUY']['ID'] ?>"><?= $arItemData['BUTTONS']['BUY']['MESSAGE'] ?></button></div>
        <? endif ?>
        <? if (isset($arItemData['BUTTONS']['SUBSCRIBE'])): ?>
            <button type="button" class="toOrder active notEdit"
                    data-qcontent="element__buttons__toOrder"
                    id="<?= $arItemData['BUTTONS']['SUBSCRIBE']['ID'] ?>"><?= $arItemData['BUTTONS']['SUBSCRIBE']['MESSAGE'] ?></button>
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
        <? endif ?>
    </div>
</div>