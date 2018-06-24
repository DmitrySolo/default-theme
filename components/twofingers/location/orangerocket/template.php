<?$this->setFrameMode(true);?>
<?$frame  =  new  \Bitrix\Main\Page\FrameHelper( 'tf_location_link' ); 
	$frame->begin()?>
<?if ($arParams['ORDER_TEMPLATE'] == 'Y'):?>
<a href="#tfLocationPopup" class="<?=$arResult['SETTINGS']['TF_LOCATION_ORDERLINK_CLASS']?> tf_location_link in_order" onclick="tfLocationPopupOpen('<?=$arResult['COMPONENT_PATH']?>', <?=str_replace('()', '', $arParams['PARAMS']['ONCITYCHANGE'])?>); return false;"><span><?=$arResult['CITY_NAME']?></span></a>
<input type="hidden" name="<?=$arParams['PARAMS']['CITY_INPUT_NAME']?>" class="tf_location_city_input" value="<?=$arResult['CITY_ID']?>">
<?else:?>
<a href="#tfLocationPopup" class="<?=$arResult['SETTINGS']['TF_LOCATION_HEADLINK_CLASS']?> tf_location_link" onclick="tfLocationPopupOpen('<?=$arResult['COMPONENT_PATH']?>', '<?=$arResult['SETTINGS']['TF_LOCATION_CALLBACK']?>'); return false;">
<?	if (strlen($arResult['SETTINGS']['TF_LOCATION_HEADLINK_TEXT']) > 0):?>
	<?=$arResult['SETTINGS']['TF_LOCATION_HEADLINK_TEXT']?>:
<?	endif?>
	<span><?=$arResult['CITY_NAME']?></span> 
</a>
<?endif?>
<?if ($arResult['CALL_POPUP'] == 'Y'):?>
<script>$().ready(function() {tfLocationPopupOpen('<?=$arResult['COMPONENT_PATH']?>', '<?=$arResult['SETTINGS']['TF_LOCATION_CALLBACK']?>');});</script>
<?endif?>
<?if ($GLOBALS['TF_LOCATION_TEMPLATE_LOADED'] != 'Y'):?>
<div class="custom-popup-2014-overlay" style="display:none;"></div>
<div class="custom-popup-2014" style="display:none; border-radius:<?=intval($arResult['SETTINGS']['TF_LOCATION_POPUP_RADIUS'])?>px"><div class="custom-popup-2014-content">
	<div class="popup-title"><?=GetMessage("TF_LOCATION_CHECK_CITY")?></div>

	<ul class="current-list"></ul>
	
</div><div class="custom-popup-2014-close"></div></div>
<? if($_COOKIE['chekedCity']!=='Y'):?>
<div class="sure-or-not-wrap">
	<div class="sure-or-not">
		<span>Ваш город <?=$arResult['CITY_NAME']?> - 	Мы угадали?</span>
		<div>
			<input type="button" value="Да" onclick="close_sure_or_not()"onclick="close_sure_or_not()">
			<a href="#tfLocationPopup" class="<?=$arResult['SETTINGS']['TF_LOCATION_ORDERLINK_CLASS']?> tf_location_link in_order" onclick="tfLocationPopupOpen('<?=$arResult['COMPONENT_PATH']?>', ''); return false;"><input type="button" value="Нет" onclick="close_sure_or_not()"></a>
			
			</div>
		</div>
</div>
	
<?endif;?>

<?$GLOBALS['TF_LOCATION_TEMPLATE_LOADED'] = 'Y'; endif?>
<?$frame->beginStub()?>
		Секундочку
<?$frame->end()?>