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
<?
if (!empty($arResult['ITEMS']))
{
    $templateLibrary = array('popup');
    $currencyList = '';
    if (!empty($arResult['CURRENCIES']))
    {
        $templateLibrary[] = 'currency';
        $currencyList = CUtil::PhpToJSObject($arResult['CURRENCIES'], false, true, true);
    }
    $templateData = array(
        'TEMPLATE_THEME' => $this->GetFolder().'/themes/'.$arParams['TEMPLATE_THEME'].'/style.css',
        'TEMPLATE_CLASS' => 'bx_'.$arParams['TEMPLATE_THEME'],
        'TEMPLATE_LIBRARY' => $templateLibrary,
        'CURRENCIES' => $currencyList
    );
    unset($currencyList, $templateLibrary);

    $arSkuTemplate = array();
    if (!empty($arResult['SKU_PROPS']))
    {
        foreach ($arResult['SKU_PROPS'] as &$arProp)
        {
            $templateRow = '';
            if ('TEXT' == $arProp['SHOW_MODE'])
            {
                if (5 < $arProp['VALUES_COUNT'])
                {
                    $strClass = 'bx_item_detail_size full';
                    $strWidth = ($arProp['VALUES_COUNT']*20).'%';
                    $strOneWidth = (100/$arProp['VALUES_COUNT']).'%';
                    $strSlideStyle = '';
                }
                else
                {
                    $strClass = 'bx_item_detail_size';
                    $strWidth = '100%';
                    $strOneWidth = '20%';
                    $strSlideStyle = 'display: none;';
                }
                $templateRow .= '<div class="'.$strClass.'" id="#ITEM#_prop_'.$arProp['ID'].'_cont">'.
                    '<span class="bx_item_section_name_gray">'.htmlspecialcharsex($arProp['NAME']).'</span>'.
                    '<div class="bx_size_scroller_container"><div class="bx_size"><ul id="#ITEM#_prop_'.$arProp['ID'].'_list" style="width: '.$strWidth.';">';
                foreach ($arProp['VALUES'] as $arOneValue)
                {
                    $arOneValue['NAME'] = htmlspecialcharsbx($arOneValue['NAME']);
                    $templateRow .= '<li data-treevalue="'.$arProp['ID'].'_'.$arOneValue['ID'].'" data-onevalue="'.$arOneValue['ID'].'" style="width: '.$strOneWidth.';" title="'.$arOneValue['NAME'].'"><i></i><span class="cnt">'.$arOneValue['NAME'].'</span></li>';
                }
                $templateRow .= '</ul></div>'.
                    '<div class="bx_slide_left" id="#ITEM#_prop_'.$arProp['ID'].'_left" data-treevalue="'.$arProp['ID'].'" style="'.$strSlideStyle.'"></div>'.
                    '<div class="bx_slide_right" id="#ITEM#_prop_'.$arProp['ID'].'_right" data-treevalue="'.$arProp['ID'].'" style="'.$strSlideStyle.'"></div>'.
                    '</div></div>';
            }
            elseif ('PICT' == $arProp['SHOW_MODE'])
            {
                if (5 < $arProp['VALUES_COUNT'])
                {
                    $strClass = 'bx_item_detail_scu full';
                    $strWidth = ($arProp['VALUES_COUNT']*20).'%';
                    $strOneWidth = (100/$arProp['VALUES_COUNT']).'%';
                    $strSlideStyle = '';
                }
                else
                {
                    $strClass = 'bx_item_detail_scu';
                    $strWidth = '100%';
                    $strOneWidth = '20%';
                    $strSlideStyle = 'display: none;';
                }
                $templateRow .= '<div class="'.$strClass.'" id="#ITEM#_prop_'.$arProp['ID'].'_cont">'.
                    '<span class="bx_item_section_name_gray">'.htmlspecialcharsex($arProp['NAME']).'</span>'.
                    '<div class="bx_scu_scroller_container"><div class="bx_scu"><ul id="#ITEM#_prop_'.$arProp['ID'].'_list" style="width: '.$strWidth.';">';
                foreach ($arProp['VALUES'] as $arOneValue)
                {
                    $arOneValue['NAME'] = htmlspecialcharsbx($arOneValue['NAME']);
                    $templateRow .= '<li data-treevalue="'.$arProp['ID'].'_'.$arOneValue['ID'].'" data-onevalue="'.$arOneValue['ID'].'" style="width: '.$strOneWidth.'; padding-top: '.$strOneWidth.';"><i title="'.$arOneValue['NAME'].'"></i>'.
                        '<span class="cnt"><span class="cnt_item" style="background-image:url(\''.$arOneValue['PICT']['SRC'].'\');" title="'.$arOneValue['NAME'].'"></span></span></li>';
                }
                $templateRow .= '</ul></div>'.
                    '<div class="bx_slide_left" id="#ITEM#_prop_'.$arProp['ID'].'_left" data-treevalue="'.$arProp['ID'].'" style="'.$strSlideStyle.'"></div>'.
                    '<div class="bx_slide_right" id="#ITEM#_prop_'.$arProp['ID'].'_right" data-treevalue="'.$arProp['ID'].'" style="'.$strSlideStyle.'"></div>'.
                    '</div></div>';
            }
            $arSkuTemplate[$arProp['CODE']] = $templateRow;
        }
        unset($templateRow, $arProp);
    }

    if ($arParams["DISPLAY_TOP_PAGER"])
    {
        ?><?
        echo $arResult["SORTING_STRING"];
        echo $arResult["NAV_STRING"];
        ?><?
    }

    $strElementEdit = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT");
    $strElementDelete = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE");
    $arElementDeleteParams = array("CONFIRM" => GetMessage('CT_BCS_TPL_ELEMENT_DELETE_CONFIRM'));
    ?>
    <div class="bx-section-desc <? echo $templateData['TEMPLATE_CLASS']; ?>">
        <p class="bx-section-desc-post"><?=$arResult["DESCRIPTION"]?></p>
    </div>
    <div class="bx_catalog_list_home container col<? echo $arParams['LINE_ELEMENT_COUNT']; ?>">
    <div class="productSection__content">
        <ul class="productCol__list clearfix">
        <?
        foreach ($arResult['ITEMS'] as $key => $arItem)
        {
            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], $strElementEdit);
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], $strElementDelete, $arElementDeleteParams);
            $strMainID = $this->GetEditAreaId($arItem['ID']);

            $arItemIDs = array(
                'ID' => $strMainID,
                'PICT' => $strMainID.'_pict',
                'SECOND_PICT' => $strMainID.'_secondpict',
                'STICKER_ID' => $strMainID.'_sticker',
                'SECOND_STICKER_ID' => $strMainID.'_secondsticker',
                'QUANTITY' => $strMainID.'_quantity',
                'QUANTITY_DOWN' => $strMainID.'_quant_down',
                'QUANTITY_UP' => $strMainID.'_quant_up',
                'QUANTITY_MEASURE' => $strMainID.'_quant_measure',
                'BUY_LINK' => $strMainID.'_buy_link',
                'BASKET_ACTIONS' => $strMainID.'_basket_actions',
                'NOT_AVAILABLE_MESS' => $strMainID.'_not_avail',
                'SUBSCRIBE_LINK' => $strMainID.'_subscribe',
                'COMPARE_LINK' => $strMainID.'_compare_link',

                'PRICE' => $strMainID.'_price',
                'DSC_PERC' => $strMainID.'_dsc_perc',
                'SECOND_DSC_PERC' => $strMainID.'_second_dsc_perc',
                'PROP_DIV' => $strMainID.'_sku_tree',
                'PROP' => $strMainID.'_prop_',
                'DISPLAY_PROP_DIV' => $strMainID.'_sku_prop',
                'BASKET_PROP_DIV' => $strMainID.'_basket_prop',
            );

            $strObName = 'ob'.preg_replace("/[^a-zA-Z0-9_]/", "x", $strMainID);

            $productTitle = (
            isset($arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'])&& $arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'] != ''
                ? $arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']
                : $arItem['NAME']
            );
            $imgTitle = (
            isset($arItem['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE']) && $arItem['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE'] != ''
                ? $arItem['IPROPERTY_VALUES']['ELEMENT_PREVIEW_PICTURE_FILE_TITLE']
                : $arItem['NAME']
            );

            ?>
            <li class="productCol__content col-3-d">
                <div class="productCol__card" id="<? echo $strMainID; ?>"> <span class="productSection__subTitle">Артикул: </span><span class="productCol__sku"><?=$arItem['XML_ID']?></span><a class="productCol__link" href="<?=$arItem['DETAIL_PAGE_URL']?>">
                    <div class="productCol__img" style="background-image: url('<? echo $arItem['PREVIEW_PICTURE']['SRC']; ?>')" title="<? echo $imgTitle; ?>" id="<?=('Y' == $arItem['SECOND_PICT'] ? $arItemIDs['SECOND_PICT'] : $arItemIDs['PICT'])?>"></div>
                    <h6 class="productCol__name" title="<? echo $arItem['LABEL_VALUE']; ?>"><? echo $productTitle; ?></h6>
                    <?if ('Y' == $arParams['SHOW_DISCOUNT_PERCENT']):?>
                        <div
                                id="<? echo $arItemIDs['DSC_PERC']; ?>"
                                class="bx_stick_disc right top"
                                style="display:<? echo (0 < $arItem['MIN_PRICE']['DISCOUNT_DIFF_PERCENT'] ? '' : 'none'); ?>;">-<? echo $arItem['MIN_PRICE']['DISCOUNT_DIFF_PERCENT']; ?>%</div>

                    <?endif?></a>
                <div id="<?=$arItemIDs['BASKET_ACTIONS']?>">
                    <div class="clearfix productCol__priceStock" id="<? echo $arItemIDs['PRICE']; ?>">
                        <?if($arResult['PARTNER_ID']):?>
                            <?
                            $mop_row_style = '';
                            if($_COOKIE['shopState']) $mop_row_style = ' style="display: none;"';

                            $quantity_full = $arItem['CATALOG_QUANTITY']+$arItem['RESERV'];
                            $q_full_style = '';
                            if($quantity_full > 0 && $arItem['CATALOG_QUANTITY']<=0) $q_full_style = ' style="color:#379774;font-weight:700"';
                            ?>
                            <span class="productCol__price productCol__price--opt productCol__price--opt">МРЦ: <?=$arItem['MIN_PRICE_RETAIL']['PRINT_DISCOUNT_VALUE_VAT']?><br><span class="productCol__price--opt productCol__price--opt-moc mop_row"<?=$mop_row_style?>>МОЦ: <?=$arItem['MIN_PRICE_EXTRA']['PRINT_DISCOUNT_VALUE_VAT']?></span></span><span class="productCol__stock f-right">Доступно:<br><b><?=$arItem['CATALOG_QUANTITY']?></b> <span<?=$q_full_style?>>из <?=$quantity_full?></span>
                        <?else:?>
                            <span class="productCol__price f-left"><?
                                if (!empty($arItem['MIN_PRICE']))
                                {
                                    if ('N' == $arParams['PRODUCT_DISPLAY_MODE'] && isset($arItem['OFFERS']) && !empty($arItem['OFFERS']))
                                    {
                                        echo GetMessage(
                                            'CT_BCS_TPL_MESS_PRICE_SIMPLE_MODE',
                                            array(
                                                '#PRICE#' => $arItem['PRICES']['BASE']['PRINT_DISCOUNT_VALUE_VAT'],
                                                '#MEASURE#' => GetMessage(
                                                    'CT_BCS_TPL_MESS_MEASURE_SIMPLE_MODE',
                                                    array(
                                                        '#VALUE#' => $arItem['PRICES']['BASE']['CATALOG_MEASURE_RATIO'],
                                                        '#UNIT#' => $arItem['PRICES']['BASE']['CATALOG_MEASURE_NAME']
                                                    )
                                                )
                                            )
                                        );
                                    }
                                    else
                                    {
                                        echo $arItem['MIN_PRICE_EXTRA']['PRINT_DISCOUNT_VALUE_VAT'];
                                    }
                                    if ('Y' == $arParams['SHOW_OLD_PRICE'] && $arItem['PRICES']['BASE']['DISCOUNT_VALUE'] < $arItem['PRICES']['BASE']['VALUE'])
                                    {
                                        echo $arItem['PRICES']['BASE']['PRINT_DISCOUNT_VALUE_VAT'];
                                    }
                                }
                                ?></span><span class="productCol__stock f-right">В наличии:<br> <span class="productCol__stock--last">Скоро закончится</span></span>
                        <?endif?>
                    </div>
                    <label class="productInline__quantityLabel">
                        <input class="productInline__quantitySelector" id="<? echo $arItemIDs['QUANTITY']; ?>" name="<? echo $arParams["PRODUCT_QUANTITY_VARIABLE"]; ?>" value="<? echo $arItem['CATALOG_MEASURE_RATIO']; ?>" type="text" size="3">
                        <a id="<? echo $arItemIDs['QUANTITY_DOWN']; ?>" href="javascript:void(0)" class="productInline__quantityButton productInline__quantityButton--minus" rel="nofollow"></a>
                        <a id="<? echo $arItemIDs['QUANTITY_UP']; ?>" href="javascript:void(0)" class="productInline__quantityButton productInline__quantityButton--plus" rel="nofollow"></a>
                    </label>
                    <button id="<? echo $arItemIDs['BUY_LINK']; ?>" class="toOrder active notEdit colList">В корзину</button>
                </div>
                </div>
            </li>
            <?$arJSParams = array(
            'PRODUCT_TYPE' => $arItem['CATALOG_TYPE'],
            'SHOW_QUANTITY' => ($arParams['USE_PRODUCT_QUANTITY'] == 'Y'),
            'SHOW_ADD_BASKET_BTN' => false,
            'SHOW_BUY_BTN' => true,
            'SHOW_ABSENT' => true,
            'SHOW_OLD_PRICE' => ('Y' == $arParams['SHOW_OLD_PRICE']),
            'ADD_TO_BASKET_ACTION' => $arParams['ADD_TO_BASKET_ACTION'],
            'SHOW_CLOSE_POPUP' => ($arParams['SHOW_CLOSE_POPUP'] == 'Y'),
            'SHOW_DISCOUNT_PERCENT' => ('Y' == $arParams['SHOW_DISCOUNT_PERCENT']),
            'DISPLAY_COMPARE' => $arParams['DISPLAY_COMPARE'],
            'PRODUCT' => array(
                'ID' => $arItem['ID'],
                'NAME' => $productTitle,
                'PICT' => ('Y' == $arItem['SECOND_PICT'] ? $arItem['PREVIEW_PICTURE_SECOND'] : $arItem['PREVIEW_PICTURE']),
                'CAN_BUY' => $arItem["CAN_BUY"],
                'SUBSCRIPTION' => ('Y' == $arItem['CATALOG_SUBSCRIPTION']),
                'CHECK_QUANTITY' => $arItem['CHECK_QUANTITY'],
                'MAX_QUANTITY' => $arItem['CATALOG_QUANTITY'],
                'STEP_QUANTITY' => $arItem['CATALOG_MEASURE_RATIO'],
                'QUANTITY_FLOAT' => is_double($arItem['CATALOG_MEASURE_RATIO']),
                'SUBSCRIBE_URL' => $arItem['~SUBSCRIBE_URL'],
                'BASIS_PRICE' => $arItem['MIN_BASIS_PRICE']
            ),
            'BASKET' => array(
                'ADD_PROPS' => ('Y' == $arParams['ADD_PROPERTIES_TO_BASKET']),
                'QUANTITY' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
                'PROPS' => $arParams['PRODUCT_PROPS_VARIABLE'],
                'EMPTY_PROPS' => empty($arItem['PRODUCT_PROPERTIES']),
                'ADD_URL_TEMPLATE' => $arResult['~ADD_URL_TEMPLATE'],
                'BUY_URL_TEMPLATE' => $arResult['~BUY_URL_TEMPLATE']
            ),
            'VISUAL' => array(
                'ID' => $arItemIDs['ID'],
                'PICT_ID' => ('Y' == $arItem['SECOND_PICT'] ? $arItemIDs['SECOND_PICT'] : $arItemIDs['PICT']),
                'QUANTITY_ID' => $arItemIDs['QUANTITY'],
                'QUANTITY_UP_ID' => $arItemIDs['QUANTITY_UP'],
                'QUANTITY_DOWN_ID' => $arItemIDs['QUANTITY_DOWN'],
                'PRICE_ID' => $arItemIDs['PRICE'],
                'BUY_ID' => $arItemIDs['BUY_LINK'],
                'BASKET_PROP_DIV' => $arItemIDs['BASKET_PROP_DIV'],
                'BASKET_ACTIONS_ID' => $arItemIDs['BASKET_ACTIONS'],
                'NOT_AVAILABLE_MESS' => $arItemIDs['NOT_AVAILABLE_MESS'],
                'COMPARE_LINK_ID' => $arItemIDs['COMPARE_LINK']
            ),
            'LAST_ELEMENT' => $arItem['LAST_ELEMENT']
        );
            if ($arParams['DISPLAY_COMPARE'])
            {
                $arJSParams['COMPARE'] = array(
                    'COMPARE_URL_TEMPLATE' => $arResult['~COMPARE_URL_TEMPLATE'],
                    'COMPARE_PATH' => $arParams['COMPARE_PATH']
                );
            }
            ?><script type="text/javascript">
            var <? echo $strObName; ?> = new JCCatalogSection(<? echo CUtil::PhpToJSObject($arJSParams, false, true); ?>);
        </script>
            <?
        }
        ?>
        </ul>
    </div>
    </div>
    <script type="text/javascript">
        BX.message({
            BTN_MESSAGE_BASKET_REDIRECT: '<? echo GetMessageJS('CT_BCS_CATALOG_BTN_MESSAGE_BASKET_REDIRECT'); ?>',
            BASKET_URL: '<? echo $arParams["BASKET_URL"]; ?>',
            ADD_TO_BASKET_OK: '<? echo GetMessageJS('ADD_TO_BASKET_OK'); ?>',
            TITLE_ERROR: '<? echo GetMessageJS('CT_BCS_CATALOG_TITLE_ERROR') ?>',
            TITLE_BASKET_PROPS: '<? echo GetMessageJS('CT_BCS_CATALOG_TITLE_BASKET_PROPS') ?>',
            TITLE_SUCCESSFUL: '<? echo GetMessageJS('ADD_TO_BASKET_OK'); ?>',
            BASKET_UNKNOWN_ERROR: '<? echo GetMessageJS('CT_BCS_CATALOG_BASKET_UNKNOWN_ERROR') ?>',
            BTN_MESSAGE_SEND_PROPS: '<? echo GetMessageJS('CT_BCS_CATALOG_BTN_MESSAGE_SEND_PROPS'); ?>',
            BTN_MESSAGE_CLOSE: '<? echo GetMessageJS('CT_BCS_CATALOG_BTN_MESSAGE_CLOSE') ?>',
            BTN_MESSAGE_CLOSE_POPUP: '<? echo GetMessageJS('CT_BCS_CATALOG_BTN_MESSAGE_CLOSE_POPUP'); ?>',
            COMPARE_MESSAGE_OK: '<? echo GetMessageJS('CT_BCS_CATALOG_MESS_COMPARE_OK') ?>',
            COMPARE_UNKNOWN_ERROR: '<? echo GetMessageJS('CT_BCS_CATALOG_MESS_COMPARE_UNKNOWN_ERROR') ?>',
            COMPARE_TITLE: '<? echo GetMessageJS('CT_BCS_CATALOG_MESS_COMPARE_TITLE') ?>',
            BTN_MESSAGE_COMPARE_REDIRECT: '<? echo GetMessageJS('CT_BCS_CATALOG_BTN_MESSAGE_COMPARE_REDIRECT') ?>',
            SITE_ID: '<? echo SITE_ID; ?>'
        });
    </script>
    <?
    if ($arParams["DISPLAY_BOTTOM_PAGER"])
    {
        ?><?
        echo $arResult["NAV_STRING"];
        ?><?
    }
}
