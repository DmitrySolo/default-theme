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
$templateData = array(
	'TEMPLATE_THEME' => $this->GetFolder().'/themes/'.$arParams['TEMPLATE_THEME'].'/style.css',
	'TEMPLATE_CLASS' => 'bx_'.$arParams['TEMPLATE_THEME']
);

$strMainID = $this->GetEditAreaId($arResult['ID']);
$arItemIDs = array(
	'ID' => $strMainID,
	'PICT' => $strMainID.'_pict',
	'DISCOUNT_PICT_ID' => $strMainID.'_dsc_pict',
	'STICKER_ID' => $strMainID.'_sticker',
	'BIG_SLIDER_ID' => $strMainID.'_big_slider',
	'BIG_IMG_CONT_ID' => $strMainID.'_bigimg_cont',
	'SLIDER_CONT_ID' => $strMainID.'_slider_cont',
	'SLIDER_LIST' => $strMainID.'_slider_list',
	'SLIDER_LEFT' => $strMainID.'_slider_left',
	'SLIDER_RIGHT' => $strMainID.'_slider_right',
	'OLD_PRICE' => $strMainID.'_old_price',
	'PRICE' => $strMainID.'_price',
    'BASE_PRICE' => $strMainID.'_base_price',
	'DISCOUNT_PRICE' => $strMainID.'_price_discount',
	'SLIDER_CONT_OF_ID' => $strMainID.'_slider_cont_',
	'SLIDER_LIST_OF_ID' => $strMainID.'_slider_list_',
	'SLIDER_LEFT_OF_ID' => $strMainID.'_slider_left_',
	'SLIDER_RIGHT_OF_ID' => $strMainID.'_slider_right_',
	'QUANTITY' => $strMainID.'_quantity',
	'QUANTITY_DOWN' => $strMainID.'_quant_down',
	'QUANTITY_UP' => $strMainID.'_quant_up',
	'QUANTITY_MEASURE' => $strMainID.'_quant_measure',
	'QUANTITY_LIMIT' => $strMainID.'_quant_limit',
	'BUY_LINK' => $strMainID.'_buy_link',
	'ADD_BASKET_LINK' => $strMainID.'_add_basket_link',
	'COMPARE_LINK' => $strMainID.'_compare_link',
	'PROP' => $strMainID.'_prop_',
	'PROP_DIV' => $strMainID.'_skudiv',
	'DISPLAY_PROP_DIV' => $strMainID.'_sku_prop',
	'OFFER_GROUP' => $strMainID.'_set_group_',
	'BASKET_PROP_DIV' => $strMainID.'_basket_prop',
);
$strObName = 'ob'.preg_replace("/[^a-zA-Z0-9_]/", "x", $strMainID);
$templateData['JS_OBJ'] = $strObName;

$strTitle = (
	isset($arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_TITLE"]) && '' != $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_TITLE"]
	? $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_TITLE"]
	: $arResult['NAME']
);
$strAlt = (
	isset($arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_ALT"]) && '' != $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_ALT"]
	? $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_ALT"]
	: $arResult['NAME']
);
?><div class="bx_item_detail <? echo $templateData['TEMPLATE_CLASS']; ?>" id="<? echo $arItemIDs['ID']; ?>">
<?
if ('Y' == $arParams['DISPLAY_NAME'])
{
?>
<div class="bx_item_title">
	<h1>
		<span><? echo (
			isset($arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"]) && '' != $arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"]
			? $arResult["IPROPERTY_VALUES"]["ELEMENT_PAGE_TITLE"]
			: $arResult["NAME"]
		); ?></span>
	</h1>
</div>
<?
}
reset($arResult['MORE_PHOTO']);
$arFirstPhoto = current($arResult['MORE_PHOTO']);
?>
	<div class="bx_item_container">
		<div class="bx_lt">
<div class="bx_item_slider" id="<? echo $arItemIDs['BIG_SLIDER_ID']; ?>">

	        <?if($arResult['PROPERTIES']['OLD_PRICE']['VALUE']>1):?>
            <div class="sale_marker">Распродажа</div>
            <div class="sale_old_price"><?=$arResult['PROPERTIES']['OLD_PRICE']['VALUE']?> руб.</div>
        <?endif?>
	<div class="bx_bigimages" id="<? echo $arItemIDs['BIG_IMG_CONT_ID']; ?>">
		<div class="bx_bigimages_imgcontainer">
			<span class="bx_bigimages_aligner"><img
				id="<? echo $arItemIDs['PICT']; ?>"
				src="<? echo $arFirstPhoto['SRC']; ?>"
				alt="<? echo $strAlt; ?>"
				title="<? echo $strTitle; ?>"
			></span>
<?
if ('Y' == $arParams['SHOW_DISCOUNT_PERCENT'])
{
?>
			<div class="bx_stick_disc" id="<? echo $arItemIDs['DISCOUNT_PICT_ID'] ?>" style="display: none;"></div>
<?
}
if ($arResult['LABEL'])
{
?>
			<div class="bx_stick new" id="<? echo $arItemIDs['STICKER_ID'] ?>"><? echo $arResult['LABEL_VALUE']; ?></div>
<?
}
?>
		</div>
	</div>
<?
if ($arResult['SHOW_SLIDER'])
{
	if (!isset($arResult['OFFERS']) || empty($arResult['OFFERS']))
	{
		if (5 < $arResult['MORE_PHOTO_COUNT'])
		{
			$strClass = 'bx_slider_conteiner full';
			$strOneWidth = (100/$arResult['MORE_PHOTO_COUNT']).'%';
			$strWidth = (20*$arResult['MORE_PHOTO_COUNT']).'%';
			$strSlideStyle = '';
		}
		else
		{
			$strClass = 'bx_slider_conteiner';
			$strOneWidth = '20%';
			$strWidth = '100%';
			$strSlideStyle = 'display: none;';
		}
?>
	<div class="<? echo $strClass; ?>" id="<? echo $arItemIDs['SLIDER_CONT_ID']; ?>">
		<div class="bx_slider_scroller_container">
			<div class="bx_slide">
				<ul style="width: <? echo $strWidth; ?>;" id="<? echo $arItemIDs['SLIDER_LIST']; ?>">
<?
		foreach ($arResult['MORE_PHOTO'] as &$arOnePhoto)
		{
?>
					<li data-value="<? echo $arOnePhoto['ID']; ?>" style="width: <? echo $strOneWidth; ?>; padding-top: <? echo $strOneWidth; ?>;"><span class="cnt"><span class="cnt_item" style="background-image:url('<? echo $arOnePhoto['SRC']; ?>');"></span></span></li>
<?
		}
		unset($arOnePhoto);
?>
				</ul>
			</div>
			<div class="bx_slide_left" id="<? echo $arItemIDs['SLIDER_LEFT']; ?>" style="<? echo $strSlideStyle; ?>"></div>
			<div class="bx_slide_right" id="<? echo $arItemIDs['SLIDER_RIGHT']; ?>" style="<? echo $strSlideStyle; ?>"></div>
		</div>
	</div>
<?
	}
	else
	{
		foreach ($arResult['OFFERS'] as $key => $arOneOffer)
		{
			if (!isset($arOneOffer['MORE_PHOTO_COUNT']) || 0 >= $arOneOffer['MORE_PHOTO_COUNT'])
				continue;
			$strVisible = ($key == $arResult['OFFERS_SELECTED'] ? '' : 'none');
			if (5 < $arOneOffer['MORE_PHOTO_COUNT'])
			{
				$strClass = 'bx_slider_conteiner full';
				$strOneWidth = (100/$arOneOffer['MORE_PHOTO_COUNT']).'%';
				$strWidth = (20*$arOneOffer['MORE_PHOTO_COUNT']).'%';
				$strSlideStyle = '';
			}
			else
			{
				$strClass = 'bx_slider_conteiner';
				$strOneWidth = '20%';
				$strWidth = '100%';
				$strSlideStyle = 'display: none;';
			}
?>
	<div class="<? echo $strClass; ?>" id="<? echo $arItemIDs['SLIDER_CONT_OF_ID'].$arOneOffer['ID']; ?>" style="display: <? echo $strVisible; ?>;">
		<div class="bx_slider_scroller_container">
			<div class="bx_slide">
				<ul style="width: <? echo $strWidth; ?>;" id="<? echo $arItemIDs['SLIDER_LIST_OF_ID'].$arOneOffer['ID']; ?>">
<?
			foreach ($arOneOffer['MORE_PHOTO'] as &$arOnePhoto)
			{
?>
					<li data-value="<? echo $arOneOffer['ID'].'_'.$arOnePhoto['ID']; ?>" style="width: <? echo $strOneWidth; ?>; padding-top: <? echo $strOneWidth; ?>"><span class="cnt"><span class="cnt_item" style="background-image:url('<? echo $arOnePhoto['SRC']; ?>');"></span></span></li>
<?
			}
			unset($arOnePhoto);
?>
				</ul>
			</div>
			<div class="bx_slide_left" id="<? echo $arItemIDs['SLIDER_LEFT_OF_ID'].$arOneOffer['ID'] ?>" style="<? echo $strSlideStyle; ?>" data-value="<? echo $arOneOffer['ID']; ?>"></div>
			<div class="bx_slide_right" id="<? echo $arItemIDs['SLIDER_RIGHT_OF_ID'].$arOneOffer['ID'] ?>" style="<? echo $strSlideStyle; ?>" data-value="<? echo $arOneOffer['ID']; ?>"></div>
		</div>
	</div>
<?
		}
	}
}
?>
</div>
		</div>

<?if($arResult['PARTNER_ID']){?>
		<div class="bx_rt">
<div class="flyPageFragment__content" data-qcontent="module__flyPageFragment">
<div class="flyPageFragment">
  <table>
	<tr>
		<td class="fragmentTitle">Артикул:</td>
<?
$db_props = CIBlockElement::GetProperty(10, $arResult['ID'], array("sort" => "asc"), Array("CODE"=>"stock_reserved"));
if($ar_props = $db_props->Fetch())
    $reserv = intval($ar_props["VALUE"]);
else $reserv = 0;
$quantity_full = $arResult['CATALOG_QUANTITY']+$reserv;
$q_full_style = '';
if($quantity_full > 0 && $arResult['CATALOG_QUANTITY']<=0) $q_full_style = ' style="color:#379774;font-weight:700"';

$mop_row_style = '';
if($_COOKIE['shopState']) $mop_row_style = ' style="display: none;"';

	    $product_sku = '';
		foreach ($arResult['DISPLAY_PROPERTIES'] as &$arOneProp)
		{if($arOneProp['CODE']!="product_sku")continue;
?>      <? if($arOneProp['CODE']=="vendor")$vendor=$arOneProp['DISPLAY_VALUE'];?>
		<?
			$product_sku = (
				is_array($arOneProp['DISPLAY_VALUE'])
				? implode(', ', $arOneProp['DISPLAY_VALUE'])
				: $arOneProp['DISPLAY_VALUE']
			);
		}
		unset($arOneProp);
?>
		<td class="sku"><?=$product_sku?></td>
		<td rowspan="4" class="basketButton">
			<button class="toOrder <? echo $buyBtnClass; ?> active" id="<? echo $arItemIDs['BUY_LINK']; ?>"><?=($arResult['NALICH'])?'Добавить в заявку':'Уточнить'?></button>
		</td>
	</tr>
	<tr>
	  <td class="fragmentTitle">МРЦ:</td>
	  <td class="rrc_price"><?=$arResult['MIN_PRICE_RETAIL']['PRINT_DISCOUNT_VALUE_VAT']?></td>
	</tr>
	<tr class="mop_row"<?=$mop_row_style?>>
	  <td class="fragmentTitle">МОЦ:</td>
	  <td class="mop_price<?=($arResult['NALICH'])?'':' not-avail'?>" id="<? echo $arItemIDs['PRICE']; ?>"><?=$arResult['MIN_PRICE_EXTRA']['PRINT_DISCOUNT_VALUE_VAT']?></td>
	</tr>
	<tr>
	  <td class="fragmentTitle">Доступно:</td>
        <td class="stock<?=($arResult['NALICH'])?'':' not-avail'?>"><span>Доступно <b><?=$arResult['CATALOG_QUANTITY']?></b> <span<?=$q_full_style?>>из <?=$quantity_full?></span></span></td>
	</tr>
  </table>
</div>
</div>
<div class="item_info_section" style="padding-right:150px;" id="<? echo $arItemIDs['PROP_DIV']; ?>">
<?
	foreach ($arResult['SKU_PROPS'] as &$arProp)
	{
		if (!isset($arResult['OFFERS_PROP'][$arProp['CODE']]))
			continue;
		$arSkuProps[] = array(
			'ID' => $arProp['ID'],
			'SHOW_MODE' => $arProp['SHOW_MODE'],
			'VALUES_COUNT' => $arProp['VALUES_COUNT']
		);
		if ('TEXT' == $arProp['SHOW_MODE'])
		{
			if (5 < $arProp['VALUES_COUNT'])
			{
				$strClass = 'bx_item_detail_size full';
				$strOneWidth = (100/$arProp['VALUES_COUNT']).'%';
				$strWidth = (20*$arProp['VALUES_COUNT']).'%';
				$strSlideStyle = '';
			}
			else
			{
				$strClass = 'bx_item_detail_size';
				$strOneWidth = '20%';
				$strWidth = '100%';
				$strSlideStyle = 'display: none;';
			}
?>
	<div class="<? echo $strClass; ?>" id="<? echo $arItemIDs['PROP'].$arProp['ID']; ?>_cont">
		<span class="bx_item_section_name_gray"><? echo htmlspecialcharsex($arProp['NAME']); ?></span>
		<div class="bx_size_scroller_container"><div class="bx_size">
			<ul id="<? echo $arItemIDs['PROP'].$arProp['ID']; ?>_list" style="width: <? echo $strWidth; ?>;margin-left:0%;">
<?
			foreach ($arProp['VALUES'] as $arOneValue)
			{
?>
				<li
					data-treevalue="<? echo $arProp['ID'].'_'.$arOneValue['ID']; ?>"
					data-onevalue="<? echo $arOneValue['ID']; ?>"
					style="width: <? echo $strOneWidth; ?>; display: none;"
				><i></i><span class="cnt"><? echo htmlspecialcharsex($arOneValue['NAME']); ?></span></li>
<?
			}
?>
			</ul>
			</div>
			<div class="bx_slide_left" style="<? echo $strSlideStyle; ?>" id="<? echo $arItemIDs['PROP'].$arProp['ID']; ?>_left" data-treevalue="<? echo $arProp['ID']; ?>"></div>
			<div class="bx_slide_right" style="<? echo $strSlideStyle; ?>" id="<? echo $arItemIDs['PROP'].$arProp['ID']; ?>_right" data-treevalue="<? echo $arProp['ID']; ?>"></div>
		</div>
	</div>
<?
		}
		elseif ('PICT' == $arProp['SHOW_MODE'])
		{
			if (5 < $arProp['VALUES_COUNT'])
			{
				$strClass = 'bx_item_detail_scu full';
				$strOneWidth = (100/$arProp['VALUES_COUNT']).'%';
				$strWidth = (20*$arProp['VALUES_COUNT']).'%';
				$strSlideStyle = '';
			}
			else
			{
				$strClass = 'bx_item_detail_scu';
				$strOneWidth = '20%';
				$strWidth = '100%';
				$strSlideStyle = 'display: none;';
			}
?>
	<div class="<? echo $strClass; ?>" id="<? echo $arItemIDs['PROP'].$arProp['ID']; ?>_cont">
		<span class="bx_item_section_name_gray"><? echo htmlspecialcharsex($arProp['NAME']); ?></span>
		<div class="bx_scu_scroller_container"><div class="bx_scu">
			<ul id="<? echo $arItemIDs['PROP'].$arProp['ID']; ?>_list" style="width: <? echo $strWidth; ?>;margin-left:0%;">
<?
			foreach ($arProp['VALUES'] as $arOneValue)
			{
?>
				<li
					data-treevalue="<? echo $arProp['ID'].'_'.$arOneValue['ID'] ?>"
					data-onevalue="<? echo $arOneValue['ID']; ?>"
					style="width: <? echo $strOneWidth; ?>; padding-top: <? echo $strOneWidth; ?>; display: none;"
				><i title="<? echo htmlspecialcharsbx($arOneValue['NAME']); ?>"></i>
				<span class="cnt"><span class="cnt_item"
					style="background-image:url('<? echo $arOneValue['PICT']['SRC']; ?>');"
					title="<? echo htmlspecialcharsbx($arOneValue['NAME']); ?>"
				></span></span></li>
<?
			}
?>
			</ul>
			</div>
			<div class="bx_slide_left" style="<? echo $strSlideStyle; ?>" id="<? echo $arItemIDs['PROP'].$arProp['ID']; ?>_left" data-treevalue="<? echo $arProp['ID']; ?>"></div>
			<div class="bx_slide_right" style="<? echo $strSlideStyle; ?>" id="<? echo $arItemIDs['PROP'].$arProp['ID']; ?>_right" data-treevalue="<? echo $arProp['ID']; ?>"></div>
		</div>
	</div>
<?
		}
	}
	unset($arProp);
?>
</div>
	<div class="item_buttons vam">
		<span class="item_buttons_counter_block">
			<a href="javascript:void(0)" class="bx_bt_button_type_2 bx_small bx_fwb" id="<? echo $arItemIDs['QUANTITY_DOWN']; ?>">-</a>
			<input id="<? echo $arItemIDs['QUANTITY']; ?>" type="text" class="tac transparent_input" value="<? echo (isset($arResult['OFFERS']) && !empty($arResult['OFFERS'])
					? 1
					: $arResult['CATALOG_MEASURE_RATIO']
				); ?>">
			<a href="javascript:void(0)" class="bx_bt_button_type_2 bx_small bx_fwb" id="<? echo $arItemIDs['QUANTITY_UP']; ?>">+</a>
			<span class="bx_cnt_desc" id="<? echo $arItemIDs['QUANTITY_MEASURE']; ?>"><? echo (isset($arResult['CATALOG_MEASURE_NAME']) ? $arResult['CATALOG_MEASURE_NAME'] : ''); ?></span>
		</span>
	</div>
		</div>
<?}else{?>

		<div class="bx_rt">
<?
$useBrands = ('Y' == $arParams['BRAND_USE']);
$useVoteRating = ('Y' == $arParams['USE_VOTE_RATING']);
if ($useBrands || $useVoteRating)
{
?>
	<div class="bx_optionblock">
<?
	if ($useVoteRating)
	{
		?><?$APPLICATION->IncludeComponent(
			"bitrix:iblock.vote",
			"stars",
			array(
				"IBLOCK_TYPE" => $arParams['IBLOCK_TYPE'],
				"IBLOCK_ID" => $arParams['IBLOCK_ID'],
				"ELEMENT_ID" => $arResult['ID'],
				"ELEMENT_CODE" => "",
				"MAX_VOTE" => "5",
				"VOTE_NAMES" => array("1", "2", "3", "4", "5"),
				"SET_STATUS_404" => "N",
				"DISPLAY_AS_RATING" => $arParams['VOTE_DISPLAY_AS_RATING'],
				"CACHE_TYPE" => $arParams['CACHE_TYPE'],
				"CACHE_TIME" => $arParams['CACHE_TIME']
			),
			$component,
			array("HIDE_ICONS" => "Y")
		);?><?
	}
	if ($useBrands)
	{
		?><?$APPLICATION->IncludeComponent("bitrix:catalog.brandblock", ".default", array(
			"IBLOCK_TYPE" => $arParams['IBLOCK_TYPE'],
			"IBLOCK_ID" => $arParams['IBLOCK_ID'],
			"ELEMENT_ID" => $arResult['ID'],
			"ELEMENT_CODE" => "",
			"PROP_CODE" => $arParams['BRAND_PROP_CODE'],
			"CACHE_TYPE" => $arParams['CACHE_TYPE'],
			"CACHE_TIME" => $arParams['CACHE_TIME'],
			"CACHE_GROUPS" => $arParams['CACHE_GROUPS'],
			"WIDTH" => "",
			"HEIGHT" => "40"
			),
			$component,
			array()
		);?><?
	}
?>
	</div>
<?
}
unset($useVoteRating);
unset($useBrands);
?>
<div class="io-wrapper item_info_section  ">
<?
	if (!empty($arResult['DISPLAY_PROPERTIES']))
	{
?>
	<dl class="sku">
<?
		foreach ($arResult['DISPLAY_PROPERTIES'] as &$arOneProp)
		{if($arOneProp['CODE']!="product_sku")continue;
?>      <? if($arOneProp['CODE']=="vendor")$vendor=$arOneProp['DISPLAY_VALUE'];?>
		<dt><? echo $arOneProp['NAME']; ?>:</dt><?
			echo '<dd>', (
				is_array($arOneProp['DISPLAY_VALUE'])
				? implode(', ', $arOneProp['DISPLAY_VALUE'])
				: $arOneProp['DISPLAY_VALUE']
			), '</dd>';
		}
		unset($arOneProp);
?>
	</dl>
<?
	}?>	
<?$frame = $this->createFrame()->begin();?>
<div class="item_price">
<?
$boolDiscountShow = 0;//(0 < $arResult['MIN_PRICE']['DISCOUNT_DIFF']);
$boolBaseShow=(isset($arResult['PRICES']['SPEC'])&&$arResult['PRICES']['SPEC']['MIN_PRICE']=='Y');
//print_r($_SESSION["SESS_AUTH"]["GROUPS"]);
$boolAdminPartner = $GLOBALS["USER"]->IsAdmin()||CSite::InGroup(array(9,10));
if($boolAdminPartner){?>

    <div class="item_current_price" id="<? echo $arItemIDs['PRICE']; ?>">
	    
	    <? echo $arResult['PRICES']['BASE']['PRINT_DISCOUNT_VALUE_VAT']; ?></div>
    <div class="item_base_price" id="<? echo $arItemIDs['BASE_PRICE']; ?>" style="display: none;position: absolute;left: 39px;top: 85px;z-index: 10;font-size:15px">
	    <? echo GetMessage("CT_SPEC_PRICE").': ',$arResult['PRICES']['SPEC']['PRINT_DISCOUNT_VALUE_VAT']; ?>
	    <span><? if(strlen($arResult['PROPERTIES']['ym_code']['VALUE'])!=0)
		    echo "<br/><a  target=_blank style='background-color:lightblue;  padding: 2px;
													  border-radius: 35%;
													  border: solid 2px white;
													  margin-left: 4px;
													  font-size: 11px;' href='https://market.yandex.ru/product/".$arResult['PROPERTIES']['ym_code']['VALUE']."'>YM</a>";
	    ?></span>
	    </div>
    <div class="switcher">
	<input id="sw" type="checkbox" class="switcher-value" onclick="switch_price()">
    <label for="sw" class="sw_btn"></label>
    <div class="bg"></div>
    </div>
<script>
	var name="showSpecPrice";
	var sw_price=document.getElementById("<? echo $arItemIDs['BASE_PRICE']; ?>");
	var sw=document.getElementById('sw');
		function getCookie(name) {
  var matches = document.cookie.match(new RegExp(
    "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
  ));
  return matches ? decodeURIComponent(matches[1]) : undefined;
  
}
	$( document ).ready(function() {
    if(getCookie(name)=="1"){
	   	$(sw_price).css("display","block");
	    sw.checked=true;
    }
});
	function switch_price(){
	    if(sw.checked==false) document.cookie = "showSpecPrice=0";
	    else document.cookie = "showSpecPrice=1";
	    $(sw_price).slideToggle();
	}
</script>
<?}else{
?><!--<?//print_r($_SESSION["BASKETFIELDS"])?>-->
	<div class="item_old_price" id="<? echo $arItemIDs['OLD_PRICE']; ?>" style="display: <? echo ($boolDiscountShow ? '' : 'none'); ?>"><? echo ($boolDiscountShow ? $arResult['MIN_PRICE']['PRINT_VALUE'] : ''); ?></div>
	<div class="item_current_price" id="<? echo $arItemIDs['PRICE']; ?>"><? echo $arResult['MIN_PRICE_EXTRA']['PRINT_DISCOUNT_VALUE_VAT'];//$arResult['MIN_PRICE']['PRINT_DISCOUNT_VALUE']; ?></div>
    <div class="item_base_price" id="<? echo $arItemIDs['BASE_PRICE']; ?>" style="display: <? echo ($boolBaseShow ? '' : 'none'); ?>"><? echo GetMessage("CT_BASE_PRICE").': ',$arResult['PRICES']['BASE']['PRINT_DISCOUNT_VALUE_VAT']; ?></div>
    
    <div class="item_economy_price" id="<? echo $arItemIDs['DISCOUNT_PRICE']; ?>" style="display: <? echo ($boolDiscountShow ? '' : 'none'); ?>"><? echo ($boolDiscountShow ? GetMessage('ECONOMY_INFO', array('#ECONOMY#' => $arResult['MIN_PRICE']['PRINT_DISCOUNT_DIFF'])) : ''); ?></div>
<?}?>
</div>
<?
if (!empty($arResult['DISPLAY_PROPERTIES']) || $arResult['SHOW_OFFERS_PROPS'])
{
?><div class="item_info_section">

	<? if ($arResult['SHOW_OFFERS_PROPS'])
	{
?>
	<dl id="<? echo $arItemIDs['DISPLAY_PROP_DIV'] ?>" style="display: none;"></dl>
<?
	}
?>
<?
	$sId=$arResult['IBLOCK_SECTION_ID'];
    $arOrder = Array("SORT"=>"ASC");
    $arFilter = Array('IBLOCK_ID'=>'10','ID'=>$sId);
    $select = Array('UF_LOGISTYC_INDEX');

    $resSec=CIBlockSection::GetList(
        $arOrder,
        $arFilter,false,
        $select,false
    );
    $resSec=$resSec->getNext();
    $UF_LOGISTYC_INDEX = intval($resSec['UF_LOGISTYC_INDEX']);

    $qLog = array(1=>5,3,2,2);
    $qCol = array(0=>'gray','red','orange','#46a036');
    $quantCategory = 0;
    if($arResult['CATALOG_QUANTITY'] > $qLog[$UF_LOGISTYC_INDEX])
        $quantCategory = 3;//Достаточно
    elseif($arResult['CATALOG_QUANTITY'] > 1)
        $quantCategory = 2;//Мало
    elseif($arResult['CATALOG_QUANTITY'] > 0)
        $quantCategory = 1;//Последний
    ?>
<div class="nonebx_catalog_quantity" style="color:<?=$qCol[$quantCategory]?>">
    <?
    if($quantCategory == 3){?>
        <span style="color:<?=$qCol[3]?>">В наличии:</span><br>достаточно<?if($boolAdminPartner){?> - <?=$arResult['CATALOG_QUANTITY']?> шт.<?}?>
    <?}elseif($quantCategory == 2){?>
        <span style="color:<?=$qCol[3]?>">В наличии:</span><br>мало<?if($boolAdminPartner){?> - <?=$arResult['CATALOG_QUANTITY']?> шт.<?}?>
    <?}elseif($quantCategory == 1){?>
        <span style="color:<?=$qCol[3]?>">В наличии:</span><br>последний
    <?}else{?>
        <?=GetMessage("CT_QUANTITY_NO")?>
    <?}
    ?>
</div>
</div><?$frame->beginStub()?>
	<div class="item_price"><div class="item_current_price" id="<? echo $arItemIDs['PRICE']; ?>"><? echo $arResult['PRICES']['BASE']['PRINT_DISCOUNT_VALUE_VAT']; ?></div></div>
<?$frame->end()?>

<?
}
if ('' != $arResult['PREVIEW_TEXT'])
{
	if (
		'S' == $arParams['DISPLAY_PREVIEW_TEXT_MODE']
		|| ('E' == $arParams['DISPLAY_PREVIEW_TEXT_MODE'] && '' == $arResult['DETAIL_TEXT'])
	)
	{
?>
<div class="item_info_section">
<?
		echo ('html' == $arResult['PREVIEW_TEXT_TYPE'] ? $arResult['PREVIEW_TEXT'] : '<p>'.$arResult['PREVIEW_TEXT'].'</p>');
?>
</div>
<?
	}
}
if (isset($arResult['OFFERS']) && !empty($arResult['OFFERS']) && !empty($arResult['OFFERS_PROP']))
{
	$arSkuProps = array();
?>
<div class="item_info_section" style="padding-right:150px;" id="<? echo $arItemIDs['PROP_DIV']; ?>">
<?
	foreach ($arResult['SKU_PROPS'] as &$arProp)
	{
		if (!isset($arResult['OFFERS_PROP'][$arProp['CODE']]))
			continue;
		$arSkuProps[] = array(
			'ID' => $arProp['ID'],
			'SHOW_MODE' => $arProp['SHOW_MODE'],
			'VALUES_COUNT' => $arProp['VALUES_COUNT']
		);
		if ('TEXT' == $arProp['SHOW_MODE'])
		{
			if (5 < $arProp['VALUES_COUNT'])
			{
				$strClass = 'bx_item_detail_size full';
				$strOneWidth = (100/$arProp['VALUES_COUNT']).'%';
				$strWidth = (20*$arProp['VALUES_COUNT']).'%';
				$strSlideStyle = '';
			}
			else
			{
				$strClass = 'bx_item_detail_size';
				$strOneWidth = '20%';
				$strWidth = '100%';
				$strSlideStyle = 'display: none;';
			}
?>
	<div class="<? echo $strClass; ?>" id="<? echo $arItemIDs['PROP'].$arProp['ID']; ?>_cont">
		<span class="bx_item_section_name_gray"><? echo htmlspecialcharsex($arProp['NAME']); ?></span>
		<div class="bx_size_scroller_container"><div class="bx_size">
			<ul id="<? echo $arItemIDs['PROP'].$arProp['ID']; ?>_list" style="width: <? echo $strWidth; ?>;margin-left:0%;">
<?
			foreach ($arProp['VALUES'] as $arOneValue)
			{
?>
				<li
					data-treevalue="<? echo $arProp['ID'].'_'.$arOneValue['ID']; ?>"
					data-onevalue="<? echo $arOneValue['ID']; ?>"
					style="width: <? echo $strOneWidth; ?>; display: none;"
				><i></i><span class="cnt"><? echo htmlspecialcharsex($arOneValue['NAME']); ?></span></li>
<?
			}
?>
			</ul>
			</div>
			<div class="bx_slide_left" style="<? echo $strSlideStyle; ?>" id="<? echo $arItemIDs['PROP'].$arProp['ID']; ?>_left" data-treevalue="<? echo $arProp['ID']; ?>"></div>
			<div class="bx_slide_right" style="<? echo $strSlideStyle; ?>" id="<? echo $arItemIDs['PROP'].$arProp['ID']; ?>_right" data-treevalue="<? echo $arProp['ID']; ?>"></div>
		</div>
	</div>
<?
		}
		elseif ('PICT' == $arProp['SHOW_MODE'])
		{
			if (5 < $arProp['VALUES_COUNT'])
			{
				$strClass = 'bx_item_detail_scu full';
				$strOneWidth = (100/$arProp['VALUES_COUNT']).'%';
				$strWidth = (20*$arProp['VALUES_COUNT']).'%';
				$strSlideStyle = '';
			}
			else
			{
				$strClass = 'bx_item_detail_scu';
				$strOneWidth = '20%';
				$strWidth = '100%';
				$strSlideStyle = 'display: none;';
			}
?>
	<div class="<? echo $strClass; ?>" id="<? echo $arItemIDs['PROP'].$arProp['ID']; ?>_cont">
		<span class="bx_item_section_name_gray"><? echo htmlspecialcharsex($arProp['NAME']); ?></span>
		<div class="bx_scu_scroller_container"><div class="bx_scu">
			<ul id="<? echo $arItemIDs['PROP'].$arProp['ID']; ?>_list" style="width: <? echo $strWidth; ?>;margin-left:0%;">
<?
			foreach ($arProp['VALUES'] as $arOneValue)
			{
?>
				<li
					data-treevalue="<? echo $arProp['ID'].'_'.$arOneValue['ID'] ?>"
					data-onevalue="<? echo $arOneValue['ID']; ?>"
					style="width: <? echo $strOneWidth; ?>; padding-top: <? echo $strOneWidth; ?>; display: none;"
				><i title="<? echo htmlspecialcharsbx($arOneValue['NAME']); ?>"></i>
				<span class="cnt"><span class="cnt_item"
					style="background-image:url('<? echo $arOneValue['PICT']['SRC']; ?>');"
					title="<? echo htmlspecialcharsbx($arOneValue['NAME']); ?>"
				></span></span></li>
<?
			}
?>
			</ul>
			</div>
			<div class="bx_slide_left" style="<? echo $strSlideStyle; ?>" id="<? echo $arItemIDs['PROP'].$arProp['ID']; ?>_left" data-treevalue="<? echo $arProp['ID']; ?>"></div>
			<div class="bx_slide_right" style="<? echo $strSlideStyle; ?>" id="<? echo $arItemIDs['PROP'].$arProp['ID']; ?>_right" data-treevalue="<? echo $arProp['ID']; ?>"></div>
		</div>
	</div>
<?
		}
	}
	unset($arProp);
?>
</div>
<?
}
?>
<div class="item_info_section">
<?
if (isset($arResult['OFFERS']) && !empty($arResult['OFFERS']))
{
	$canBuy = $arResult['OFFERS'][$arResult['OFFERS_SELECTED']]['CAN_BUY'];
}
else
{
	$canBuy = $arResult['CAN_BUY'];
}
if ($canBuy)
{
	$buyBtnMessage = ('' != $arParams['MESS_BTN_BUY'] ? $arParams['MESS_BTN_BUY'] : GetMessage('CT_BCE_CATALOG_BUY'));
	$buyBtnClass = 'bx_big bx_bt_button bx_cart';
}
else
{
	$buyBtnMessage = ('' != $arParams['MESS_NOT_AVAILABLE'] ? $arParams['MESS_NOT_AVAILABLE'] : GetMessageJS('CT_BCE_CATALOG_NOT_AVAILABLE'));
	$buyBtnClass = 'bx_big bx_bt_button_type_2 bx_cart';
}
if (false)//'Y' == $arParams['USE_PRODUCT_QUANTITY'])
{
?>
	<span class="item_section_name_gray"><? echo GetMessage('CATALOG_QUANTITY'); ?></span>
	<div class="item_buttons vam">
		<span class="item_buttons_counter_block">
			<a href="javascript:void(0)" class="bx_bt_button_type_2 bx_small bx_fwb" id="<? echo $arItemIDs['QUANTITY_DOWN']; ?>">-</a>
			<input id="<? echo $arItemIDs['QUANTITY']; ?>" type="text" class="tac transparent_input" value="<? echo (isset($arResult['OFFERS']) && !empty($arResult['OFFERS'])
					? 1
					: $arResult['CATALOG_MEASURE_RATIO']
				); ?>">
			<a href="javascript:void(0)" class="bx_bt_button_type_2 bx_small bx_fwb" id="<? echo $arItemIDs['QUANTITY_UP']; ?>">+</a>
			<span class="bx_cnt_desc" id="<? echo $arItemIDs['QUANTITY_MEASURE']; ?>"><? echo (isset($arResult['CATALOG_MEASURE_NAME']) ? $arResult['CATALOG_MEASURE_NAME'] : ''); ?></span>
		</span>
		<span class="item_buttons_counter_block">
			<a href="javascript:void(0);" class="<? echo $buyBtnClass; ?>" id="<? echo $arItemIDs['BUY_LINK']; ?>"><span></span><? echo $buyBtnMessage; ?></a>
			<a class="bx_big bx_bt_button_type_2 bx_cart" href="?action=ADD_TO_COMPARE_LIST&id=<?=$arResult['ID']?>">
					<? echo ('' != $arParams['MESS_BTN_COMPARE']
					? $arParams['MESS_BTN_COMPARE']
					: GetMessage('CT_BCE_CATALOG_COMPARE'));
					?></a>
<?
	if ('Y' == $arParams['DISPLAY_COMPARE'])
	{
?>
			<a href="javascript:void(0)" class="bx_big bx_bt_button_type_2 bx_cart" style="margin-left: 10px"><? echo ('' != $arParams['MESS_BTN_COMPARE']
					? $arParams['MESS_BTN_COMPARE']
					: GetMessage('CT_BCE_CATALOG_COMPARE')
				); ?></a>
<?
	}
?>
		</span>
	</div>
<?
	if ('Y' == $arParams['SHOW_MAX_QUANTITY'])
	{
		if (isset($arResult['OFFERS']) && !empty($arResult['OFFERS']))
		{
?>
	<p id="<? echo $arItemIDs['QUANTITY_LIMIT']; ?>" style="display: none;"><? echo GetMessage('OSTATOK'); ?>: <span></span></p>
<?
		}
		else
		{
			if ('Y' == $arResult['CATALOG_QUANTITY_TRACE'] && 'N' == $arResult['CATALOG_CAN_BUY_ZERO'])
			{
?>
	<p id="<? echo $arItemIDs['QUANTITY_LIMIT']; ?>"><? echo GetMessage('OSTATOK'); ?>: <span><? echo $arResult['CATALOG_QUANTITY']; ?></span></p>
<?
			}
		}
	}
}
else
{
?>
	<div class="item_buttons vam">
		<span class="item_buttons_counter_block">
			<a href="javascript:void(0);" class="<? echo $buyBtnClass; ?>" id="<? echo $arItemIDs['BUY_LINK']; ?>"><span></span><? echo $buyBtnMessage; ?></a>
<?
	if ('Y' == $arParams['DISPLAY_COMPARE'])
	{
?>
			<a id="<? echo $arItemIDs['COMPARE_LINK']; ?>" href="javascript:void(0)" class="bx_big bx_bt_button_type_2 bx_cart" style="margin-left: 10px"><? echo ('' != $arParams['MESS_BTN_COMPARE']
					? $arParams['MESS_BTN_COMPARE']
					: GetMessage('CT_BCE_CATALOG_COMPARE')
				); ?></a>
<?
	}
?>
		</span>
	</div>
<?
}
?>
</div></div>
<!--Delivery section-->
<div class="io-wrapper fly_delivery">
		<?$frame = $this->createFrame()->begin();?>
	        <?$APPLICATION->IncludeComponent(
				"orangerocket:when_and_how_much",
				"",
				Array(
					"ELEMENT_ID" => $arResult['ID'],
					"MORZ"=>$arResult['PROPERTIES']['morz']['VALUE'],
					"QUANTITY" => $arResult['CATALOG_QUANTITY'],
					"ELEMENT_PRICE" => $arResult['PRICES']['BASE']['DISCOUNT_VALUE'],
					"IBLOC_ID"=>18,
					"CACHE_TYPE" => "N",
				)
			)	;?>
			<?$frame->beginStub()?>
		        <img src='//www.santehsmart.ru.images.1c-bitrix-cdn.ru/bitrix/images/preloader/712.GIF?14278238378461'>
	        <?$frame->end()?>
</div>
		<!--Fast buy section-->
		<div class="io-wrapper fast-buy">
		<?$frame = $this->createFrame()->begin();?>
		<?if($arResult['CATALOG_QUANTITY']){?>
		<?$APPLICATION->IncludeComponent(
				"orangerocket:fast_buy",
				"",
				Array(
					"ELEMENT_ID" => $arResult['ID'],
					"ELEMENT_NAME" => $arResult['NAME'],
					"ELEMENT_PRICE" => $arResult['PRICES']['BASE']['DISCOUNT_VALUE']
				)
			);?>
	        
        <?}else{?>
            <div style='padding:59px 25px 70px;background-color:#EEE;overflow:hidden'>
                Товара нет в наличии. Оставьте запрос менеджеру.
            </div>
        <?}?>
			<?$frame->beginStub()?>
		        <img src='//www.santehsmart.ru.images.1c-bitrix-cdn.ru/bitrix/images/preloader/712.GIF?14278238378461'>
	        <?$frame->end()?>
		</div>
		<!--End fast buy section-->

					</div>
<div class="wrnt-w">
		<div class="wrnt-s wrnt"><a href="/about/howto/">Гарантия и контроль качества</a></div>
	    <div class="wrnt-s mnbck"><a href="/about/howto/">Оплата после осмотра товара</a></div>
	    <div class="wrnt-s"><a href="//clck.yandex.ru/redir/dtype=stred/pid=47/cid=1248/*//market.yandex.ru/shop/276025/reviews/add"><img src="//clck.yandex.ru/redir/dtype=stred/pid=47/cid=1248/*//img.yandex.ru/market/informer12.png" border="0" alt="Оцените качество магазина на Яндекс.Маркете." /></a></div>
</div>
<?}?>
		<div class="bx_md">
<div class="item_info_section">
<?
if (isset($arResult['OFFERS']) && !empty($arResult['OFFERS']))
{
	if ($arResult['OFFER_GROUP'])
	{
		foreach ($arResult['OFFERS'] as $arOffer)
		{
			if (!$arOffer['OFFER_GROUP'])
				continue;
?>
	<span id="<? echo $arItemIDs['OFFER_GROUP'].$arOffer['ID']; ?>" style="display: none;">
<?$APPLICATION->IncludeComponent("bitrix:catalog.set.constructor",
	".default",
	array(
		"IBLOCK_ID" => $arResult["OFFERS_IBLOCK"],
		"ELEMENT_ID" => $arOffer['ID'],
		"PRICE_CODE" => $arParams["PRICE_CODE"],
		"BASKET_URL" => $arParams["BASKET_URL"],
		"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
	),
	$component,
	array()
);?><?
?>
	</span>
<?
		}
	}
}
else
{
	if ($arResult['MODULES']['catalog'])
	{
?><?/*$APPLICATION->IncludeComponent("bitrix:catalog.set.constructor",
	".default",
	array(
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"ELEMENT_ID" => $arResult["ID"],
		"PRICE_CODE" => $arParams["PRICE_CODE"],
		"BASKET_URL" => $arParams["BASKET_URL"],
		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
		"CACHE_TIME" => $arParams["CACHE_TIME"],
		"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
	),
	$component,
	array()
);*/?><?
	}
}
?>
</div>
		</div>
		<div class="bx_rb">
<div class="item_info_section">

<?
	if (!empty($arResult['DISPLAY_PROPERTIES']))
	{
?>
	<dl>
<?
		foreach ($arResult['DISPLAY_PROPERTIES'] as &$arOneProp)
		{if($arOneProp['CODE']=="product_sku")continue;
?>
		<dt><? echo $arOneProp['NAME']; ?>:</dt><?
			echo '<dd>', (
				is_array($arOneProp['DISPLAY_VALUE'])
				? implode(', ', $arOneProp['DISPLAY_VALUE'])
				: ($arOneProp['CODE']=="site"?"<a href='http://{$arOneProp['DISPLAY_VALUE']}' target='_blank'>{$arOneProp['DISPLAY_VALUE']}</a>":$arOneProp['DISPLAY_VALUE'])
			), $arOneProp['PROPERTY_TYPE']=='N'?' '.$arOneProp['HINT']:'', '</dd>';
		}
		unset($arOneProp);
?>
	</dl>
		<?//////////////////////////////DELIVERY PACKAGE
		$APPLICATION->IncludeComponent("orangerocket:deliveri_package",
		"",
		array(
			"IBLOCK_ID" => 19,
			"ELEMENT_ID" => $arResult['PROPERTIES']['komplekt_postavki']['VALUE'],)
			);?>
<?
	}
	if ($arResult['SHOW_OFFERS_PROPS'])
	{
?>
	<dl id="<? echo $arItemIDs['DISPLAY_PROP_DIV'] ?>" style="display: none;"></dl>
<?
	}
?>

<?$APPLICATION->IncludeComponent("orangerocket:catalog.set.constructor.mod",
        "",
        array(
            "IBLOCK_ID" => $arParams["IBLOCK_ID"],
            "ELEMENT_ID" => $arResult["ID"],
            "PRICE_CODE" => $arParams["PRICE_CODE"],
            "BASKET_URL" => $arParams["BASKET_URL"],
            "CACHE_TYPE" => $arParams["CACHE_TYPE"],
            "CACHE_TIME" => $arParams["CACHE_TIME"],
            "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
            "BX_ID" => $arItemIDs['ID'],
            "AVAILABLE" => $arResult['NALICH']
        ),
        $component,
        array()
    );?>

<?
if ('' != $arResult['DETAIL_TEXT'])
{
?>
	<div class="bx_item_description">
		<div class="bx_item_section_name_gray" style="border-bottom: 1px solid #f2f2f2;"><? echo GetMessage('FULL_DESCRIPTION'); ?></div>
<?
	if ('html' == $arResult['DETAIL_TEXT_TYPE'])
	{
		echo $arResult['DETAIL_TEXT'];
	}
	else
	{
		?><p><? echo $arResult['DETAIL_TEXT']; ?></p><?
	}
?>
	</div>
<?
}
?>
</div>

		</div>
		<div class="bx_lb">
<div class="tac ovh">
</div>
<div class="tab-section-container">
<?
if ('Y' == $arParams['USE_COMMENTS'])
{
?>
<?$APPLICATION->IncludeComponent(
	"bitrix:catalog.comments",
	"",
	array(
		"ELEMENT_ID" => $arResult['ID'],
		"ELEMENT_CODE" => "",
		"IBLOCK_ID" => $arParams['IBLOCK_ID'],
		"URL_TO_COMMENT" => "",
		"WIDTH" => "",
		"COMMENTS_COUNT" => "5",
		"BLOG_USE" => $arParams['BLOG_USE'],
		"FB_USE" => $arParams['FB_USE'],
		"FB_APP_ID" => $arParams['FB_APP_ID'],
		"VK_USE" => $arParams['VK_USE'],
		"VK_API_ID" => $arParams['VK_API_ID'],
		"CACHE_TYPE" => $arParams['CACHE_TYPE'],
		"CACHE_TIME" => $arParams['CACHE_TIME'],
		"BLOG_TITLE" => "",
		"BLOG_URL" => $arParams['BLOG_URL'],
		"PATH_TO_SMILE" => "",
		"EMAIL_NOTIFY" => "N",
		"AJAX_POST" => "Y",
		"SHOW_SPAM" => "Y",
		"SHOW_RATING" => "N",
		"FB_TITLE" => "",
		"FB_USER_ADMIN_ID" => "",
		"FB_COLORSCHEME" => "light",
		"FB_ORDER_BY" => "reverse_time",
		"VK_TITLE" => "",
		"TEMPLATE_THEME" => $arParams['~TEMPLATE_THEME']
	),
	$component,
	array("HIDE_ICONS" => "N")
);?>
<?
}
?>
</div>
		</div>
			<div style="clear: both;"></div>
	</div>
	<div class="clb"></div>
</div><?
if (isset($arResult['OFFERS']) && !empty($arResult['OFFERS']))
{
	foreach ($arResult['JS_OFFERS'] as &$arOneJS)
	{
		if ($arOneJS['PRICE']['DISCOUNT_VALUE'] != $arOneJS['PRICE']['VALUE'])
		{
			$arOneJS['PRICE']['PRINT_DISCOUNT_DIFF'] = GetMessage('ECONOMY_INFO', array('#ECONOMY#' => $arOneJS['PRICE']['PRINT_DISCOUNT_DIFF']));
			$arOneJS['PRICE']['DISCOUNT_DIFF_PERCENT'] = -$arOneJS['PRICE']['DISCOUNT_DIFF_PERCENT'];
		}
		$strProps = '';
		if ($arResult['SHOW_OFFERS_PROPS'])
		{
			if (!empty($arOneJS['DISPLAY_PROPERTIES']))
			{
				foreach ($arOneJS['DISPLAY_PROPERTIES'] as $arOneProp)
				{
					$strProps .= '<dt>'.$arOneProp['NAME'].'</dt><dd>'.(
						is_array($arOneProp['VALUE'])
						? implode(' / ', $arOneProp['VALUE'])
						: $arOneProp['VALUE']
					).'</dd>';
				}
			}
		}
		$arOneJS['DISPLAY_PROPERTIES'] = $strProps;
	}
	if (isset($arOneJS))
		unset($arOneJS);
	$arJSParams = array(
		'CONFIG' => array(
			'PARTNER_ID' => $arResult['PARTNER_ID'],
			'USE_CATALOG' => $arResult['CATALOG'],
			'SHOW_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
			'SHOW_PRICE' => true,
			'SHOW_DISCOUNT_PERCENT' => ('Y' == $arParams['SHOW_DISCOUNT_PERCENT']),
			'SHOW_OLD_PRICE' => ('Y' == $arParams['SHOW_OLD_PRICE']),
			'DISPLAY_COMPARE' => ('Y' == $arParams['DISPLAY_COMPARE']),
			'SHOW_SKU_PROPS' => $arResult['SHOW_OFFERS_PROPS'],
			'OFFER_GROUP' => $arResult['OFFER_GROUP'],
			'MAIN_PICTURE_MODE' => $arParams['DETAIL_PICTURE_MODE']
		),
		'PRODUCT_TYPE' => $arResult['CATALOG_TYPE'],
		'VISUAL' => array(
			'ID' => $arItemIDs['ID'],
		),
		'DEFAULT_PICTURE' => array(
			'PREVIEW_PICTURE' => $arResult['DEFAULT_PICTURE'],
			'DETAIL_PICTURE' => $arResult['DEFAULT_PICTURE']
		),
		'PRODUCT' => array(
			'ID' => $arResult['ID'],
			'NAME' => $arResult['~NAME']
		),
		'BASKET' => array(
			'QUANTITY' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
			'BASKET_URL' => $arParams['BASKET_URL'],
			'SKU_PROPS' => $arResult['OFFERS_PROP_CODES']
		),
		'OFFERS' => $arResult['JS_OFFERS'],
		'OFFER_SELECTED' => $arResult['OFFERS_SELECTED'],
		'TREE_PROPS' => $arSkuProps
	);
}
else
{
	$emptyProductProperties = empty($arResult['PRODUCT_PROPERTIES']);
	if ('Y' == $arParams['ADD_PROPERTIES_TO_BASKET'] && !$emptyProductProperties)
	{
?>
<div id="<? echo $arItemIDs['BASKET_PROP_DIV']; ?>" style="display: none;">
<?
		if (!empty($arResult['PRODUCT_PROPERTIES_FILL']))
		{
			foreach ($arResult['PRODUCT_PROPERTIES_FILL'] as $propID => $propInfo)
			{
?>
	<input
		type="hidden"
		name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]"
		value="<? echo htmlspecialcharsbx($propInfo['ID']); ?>"
	>
<?
				if (isset($arResult['PRODUCT_PROPERTIES'][$propID]))
					unset($arResult['PRODUCT_PROPERTIES'][$propID]);
			}
		}
		$emptyProductProperties = empty($arResult['PRODUCT_PROPERTIES']);
		if (!$emptyProductProperties)
		{
?>
	<table>
<?
			foreach ($arResult['PRODUCT_PROPERTIES'] as $propID => $propInfo)
			{
?>
	<tr><td><? echo $arResult['PROPERTIES'][$propID]['NAME']; ?></td>
	<td>
<?
				if(
					'L' == $arResult['PROPERTIES'][$propID]['PROPERTY_TYPE']
					&& 'C' == $arResult['PROPERTIES'][$propID]['LIST_TYPE']
				)
				{
					foreach($propInfo['VALUES'] as $valueID => $value)
					{
						?><label><input
							type="radio"
							name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]"
							value="<? echo $valueID; ?>"
							<? echo ($valueID == $propInfo['SELECTED'] ? '"checked"' : ''); ?>
						><? echo $value; ?></label><br><?
					}
				}
				else
				{
					?><select name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]"><?
					foreach($propInfo['VALUES'] as $valueID => $value)
					{
						?><option
							value="<? echo $valueID; ?>"
							<? echo ($valueID == $propInfo['SELECTED'] ? '"selected"' : ''); ?>
						><? echo $value; ?></option><?
					}
					?></select><?
				}
?>
	</td></tr>
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
	$arJSParams = array(
		'CONFIG' => array(
			'PARTNER_ID' => $arResult['PARTNER_ID'],
			'USE_CATALOG' => $arResult['CATALOG'],
			'SHOW_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
			'SHOW_PRICE' => (isset($arResult['MIN_PRICE']) && !empty($arResult['MIN_PRICE']) && is_array($arResult['MIN_PRICE'])),
			'SHOW_DISCOUNT_PERCENT' => ('Y' == $arParams['SHOW_DISCOUNT_PERCENT']),
			'SHOW_OLD_PRICE' => ('Y' == $arParams['SHOW_OLD_PRICE']),
			'DISPLAY_COMPARE' => ('Y' == $arParams['DISPLAY_COMPARE']),
			'MAIN_PICTURE_MODE' => $arParams['DETAIL_PICTURE_MODE']
		),
		'VISUAL' => array(
			'ID' => $arItemIDs['ID'],
		),
		'PRODUCT_TYPE' => $arResult['CATALOG_TYPE'],
		'PRODUCT' => array(
			'ID' => $arResult['ID'],
			'PICT' => $arFirstPhoto,
			'NAME' => $arResult['~NAME'],
			'SUBSCRIPTION' => true,
			'PRICE' => $arResult['MIN_PRICE'],
			'SLIDER_COUNT' => $arResult['MORE_PHOTO_COUNT'],
			'SLIDER' => $arResult['MORE_PHOTO'],
			'CAN_BUY' => $arResult['CAN_BUY'],
			'CHECK_QUANTITY' => $arResult['CHECK_QUANTITY'],
			'QUANTITY_FLOAT' => is_double($arResult['CATALOG_MEASURE_RATIO']),
			'MAX_QUANTITY' => $arResult['CATALOG_QUANTITY'],
			'STEP_QUANTITY' => $arResult['CATALOG_MEASURE_RATIO'],
			'BUY_URL' => $arResult['~BUY_URL'],
		),
		'BASKET' => array(
			'ADD_PROPS' => ('Y' == $arParams['ADD_PROPERTIES_TO_BASKET']),
			'QUANTITY' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
			'PROPS' => $arParams['PRODUCT_PROPS_VARIABLE'],
			'EMPTY_PROPS' => $emptyProductProperties,
			'BASKET_URL' => $arParams['BASKET_URL']
		)
	);
	unset($emptyProductProperties);
}
?>
<script type="text/javascript">
var <? echo $strObName; ?> = new JCCatalogElement(<? echo CUtil::PhpToJSObject($arJSParams, false, true); ?>);
BX.message({
	MESS_BTN_BUY: '<? echo ('' != $arParams['MESS_BTN_BUY'] ? CUtil::JSEscape($arParams['MESS_BTN_BUY']) : GetMessageJS('CT_BCE_CATALOG_BUY')); ?>',
	MESS_BTN_ADD_TO_BASKET: '<? echo ('' != $arParams['MESS_BTN_ADD_TO_BASKET'] ? CUtil::JSEscape($arParams['MESS_BTN_ADD_TO_BASKET']) : GetMessageJS('CT_BCE_CATALOG_ADD')); ?>',
	MESS_NOT_AVAILABLE: '<? echo ('' != $arParams['MESS_NOT_AVAILABLE'] ? CUtil::JSEscape($arParams['MESS_NOT_AVAILABLE']) : GetMessageJS('CT_BCE_CATALOG_NOT_AVAILABLE')); ?>',
	TITLE_ERROR: '<? echo GetMessageJS('CT_BCE_CATALOG_TITLE_ERROR') ?>',
	TITLE_BASKET_PROPS: '<? echo GetMessageJS('CT_BCE_CATALOG_TITLE_BASKET_PROPS') ?>',
	BASKET_UNKNOWN_ERROR: '<? echo GetMessageJS('CT_BCE_CATALOG_BASKET_UNKNOWN_ERROR') ?>',
	BTN_SEND_PROPS: '<? echo GetMessageJS('CT_BCE_CATALOG_BTN_SEND_PROPS'); ?>',
	BTN_MESSAGE_CLOSE: '<? echo GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_CLOSE') ?>',
	SITE_ID: '<? echo SITE_ID; ?>'
});
</script>

<?/////COLLECTIONS
$collectId='or_collection';
$collectClass='or_collect_class';
?>

<div id="<?=$collectId?>" class="<?=$collectClass?>">
	<?$frame  =  new  \Bitrix\Main\Page\FrameHelper( $collectClass ); 
	$frame->begin()?>
		<?///////////////////////////////////////////////COLLECTION
           $vendor=$arResult['DISPLAY_PROPERTIES']['vendor']['DISPLAY_VALUE'];
           $collection=$arResult['DISPLAY_PROPERTIES']['collection']['DISPLAY_VALUE'];
           if(!empty($collection)){
	        $arOrder = Array("SORT"=>"ASC");
	        $arFilter = Array(
	        "!ID"=>"{$arResult['ID']}",
	        "IBLOCK_ID" => "10",
	        "PROPERTY_collection_VALUE"=>"$collection",
	        "PROPERTY_vendor"=>"$vendor");
	        $selectedRows=Array("CATALOG_GROUP_1","PREVIEW_PICTURE",'DETAIL_PAGE_URL','NAME','IBLOCK_ID','ID');
	        $res=CIBlockElement::GetList(
            $arOrder,
            $arFilter,
            false,
            false,
            $selectedRows);
            if(count($res>1)){
                echo "<h4>Товары из этой серии, коллекции:</h4>".
                "<div id='coll_carousel' class='owl-carousel owl-theme'>";
                while($supres=$res->getNext()){
	                $price=CurrencyFormat($supres['CATALOG_PRICE_1'],"RUB");
	                $quantity=($supres['CATALOG_QUANTITY']>0)?"<span style='color:green;font-size:14px'> В наличии</span>":"<span style='color:silver;font-size:14px'> Нет в наличии</span>";
	                 $img = CFile::ShowImage($supres["PREVIEW_PICTURE"],170,170);
	                 echo"<div class='item coll-item'><a href='".$supres['DETAIL_PAGE_URL']."'><div class='coll-item-img'>$img</div><div style='border-bottom:solid 1px #dadada;height: 34px;overflow: hidden;'>".$supres['NAME']."</div></a>".
	                 "<div style='padding-top:7px'><span style='font-size:18px;font-weight:700;color:#474747'>".$price."</span>".
	                 "$quantity</div></div>";
	                 //print_r($supres);
                }
            }

            echo 
            "<div style='clear: both'></div></div><script src='/libraries/owl.carousel/owl-carousel/owl.carousel.js'></script>".
            "<link rel='stylesheet' href='/libraries/owl.carousel/owl-carousel/owl.carousel.css'>".
            "<link rel='stylesheet' href='/libraries/owl.carousel/owl-carousel/owl.theme.css'>";
            echo"<script>
            
            $(document).ready(function() {
 
           //Sort random function
  function random(owlSelector){
    owlSelector.children().sort(function(){
        return Math.round(Math.random()) - 0.5;
    }).each(function(){
      $(this).appendTo(owlSelector);
    });
  }
 
  $(\"#coll_carousel\").owlCarousel({
    navigation: true,
    navigationText: [
      \"<i class='icon-chevron-left icon-white'></i>\",
      \"<i class='icon-chevron-right icon-white'></i>\"
      ],
    beforeInit : function(elem){
      //Parameter elem pointing to $(\"#coll_carousel\")
      random(elem);
    }
 
  });
 
});
            </script>";
            echo "<style>
            #coll_carousel .item{
  display: block;
  padding: 30px 0px;
  margin: 5px;
  color: #FFF;
  -webkit-border-radius: 3px;
  -moz-border-radius: 3px;
  border-radius: 3px;
  text-align: center;
}
.owl-theme .owl-controls .owl-buttons div {
  padding:20px;
}
 
.owl-theme .owl-buttons i{
  margin-top: 2px;
}
 
//To move navigation buttons outside use these settings:
 
.owl-theme .owl-controls .owl-buttons div {
  position: absolute;
}
 
.owl-theme .owl-controls .owl-buttons .owl-prev{
  left: 10px;
  top: 120px; 
}
 
.owl-theme .owl-controls .owl-buttons .owl-next{
  right: 10px;
  top: 120px;
}
.owl-prev{
	background-image:url('/libraries/owl.carousel/assets/img/coll_sli_btn_l.png')!important;
	background-repeat:no-repeat!important;
	background-position:45% 50%!important;
	
}
.owl-next{
	background-image:url('/libraries/owl.carousel/assets/img/coll_sli_btn_r.png')!important;
	background-repeat:no-repeat!important;
	background-position:55% 50%!important;
	
}
            
            
            
            </style>";
            
            }?>
	<?/////TOGETHER MORE INEXPENSIVE

	$APPLICATION->IncludeComponent("orangerocket:TogetherMoreInexpensive", "template1", Array(
	"ELEMENT_PRICE" => $arResult["PRICES"]["BASE"]["DISCOUNT_VALUE"],
		"PARENT_ID" => $arResult["ID"]
	),
	false
);
	?>
	<?$frame->beginStub()?>
	<?$frame->end()?>
</div>

