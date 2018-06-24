<?php
include_once $_SERVER["DOCUMENT_ROOT"].'/testzone/util/platform.php';
include_once getPlatformPath();
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$arResult = array();
if ((CModule::IncludeModule("iblock")&&CModule::IncludeModule("catalog")&&CModule::IncludeModule("sale")&&CModule::IncludeModule("highloadblock"))){
	if(isset($_POST['cart_content'])){
		$pl = new Platform();
		$arProducts = $_POST['cart_content'];
		$arNames = $_POST['cart_names'];
		if($pl->getCartContent($arProducts,$arNames)){
			$arResult['status'] = 'ok';
			$arResult['response'] = $pl->getLastResponse();
			$arResult['data'] = $pl->getExtraData();
		}
		else {
			sleep(5);
			if($pl->getCartContent($arProducts,$arNames)){
				$arResult['status'] = 'ok';
				$arResult['response'] = $pl->getLastResponse();
				$arResult['data'] = $pl->getExtraData();
			}
			else {
				$arResult['status'] = 'error';
				$arResult['response'] = $pl->getLastError();
			}
		}
	}else{
		$arResult['status'] = 'error';
	}
}else{
	$arResult['status'] = 'error';
}

echo json_encode($arResult);