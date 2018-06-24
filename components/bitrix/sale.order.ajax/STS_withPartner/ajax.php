<?php
include_once $_SERVER["DOCUMENT_ROOT"].'/testzone/util/platform.php';
include_once getPlatformPath();
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
use Bitrix\Main\Mail\Event;

/*function mail_utf8($to, $from, $subj, $message)
{
    $subject = '=?UTF-8?B?' . base64_encode($subj) . '?=';
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=utf-8\r\n";
    $headers .= "Content-Transfer-Encoding: 8bit \r\n";
    $headers .= "From: $from\r\n";

    return mail($to, $subject, $message, $headers);
}*/

$arResult = array();//todo убрать лишние модули
if ((CModule::IncludeModule("iblock")&&CModule::IncludeModule("catalog")&&CModule::IncludeModule("sale")&&CModule::IncludeModule("highloadblock"))){
	if(isset($_POST['cart_content'])){
		$pl = new Platform();
		$partner_id = $_POST['partner_id'];
        $alt_partner_id = $_POST['alt_partner_id'];
		$comment = $_POST['comment'];
		$arProducts = $_POST['cart_content'];
		$arNames = $_POST['cart_names'];
		$arPrices = $_POST['cart_prices'];
        $isSecond = $_POST['is_second'];
		$arResult = array();

        if($isSecond=='Y' || $pl->getCartContent($arProducts,$arNames)) {
            $resp = $pl->getLastResponse();
            if($isSecond=='Y' || $resp['STRING']=='OK') {
                if ($pl->makeNewBill($partner_id, $alt_partner_id, $comment, $arProducts, $arPrices, $arNames, $isSecond)) {
                    $arResult['status'] = 'ok';
                    $arResult['response'] = $pl->getLastResponse();
                    $arResult['data'] = $pl->getExtraData();
                    $arMail = $arResult['data']['mail'];
                    $arResult['data'] = $arResult['data']['json']; //todo скрыть ненужные данные

                    Event::send(array(
                        "EVENT_NAME" => "PARTNER_ORDER_FOR_MANAGER",
                        "LID" => "s1",
                        "C_FIELDS" => $arMail,
                    ));

                    /*$to=$arManager['UF_MAIL'];
                    $from='sale@santehsmart.ru';
                    $subj="Заявка Атаманенко тест";

                    $message="Была сформирована заявка N <ТЕСТ> на сумму <ТЕСТ>, кол-во позиций: <ТЕСТ>.";*/

                    //mail_utf8($to, $from, $subj, $message);
                } else {
                    /*$arResult['status'] = 'error';
                    $arResult['response'] = $pl->getLastError();
                    $arResult['data'] = $pl->getExtraData();*/
                    $arResult = $pl->getCartContentPlus($arProducts, $arNames);
                    $arResult['data']['bill1'] = array();
                    $arResult['data']['bill2'] = array();
                    $arTemp['partner_id'] = $partner_id;
                    $arTemp['alt_partner_id'] = $alt_partner_id;
                    $arTemp['comment'] = $comment;
                    $arTemp['cart_names'] = $arNames;
                    $arTemp['cart_content'] = array();
                    $arTemp['cart_prices'] = $arPrices;
                    $arTemp['is_second'] = 'Y';
                    $arResult['data']['bill2'] = $arTemp; //второй заказ с излишками (пока нет товаров)
                    $arTemp['cart_content'] = $arProducts;
                    $arTemp['is_second'] = 'N';
                    $arResult['data']['bill1'] = $arTemp; //первый заказ с доступными (здесь пока еще все)
                    foreach ($arProducts as $sku => $quantity) {
                        if (isset($arResult['data']['errDesc'][$sku]['diff_count'])) {
                            $diff_count = $arResult['data']['errDesc'][$sku]['diff_count'];
                            if ($diff_count < 0) {
                                if (($quantity + $diff_count) == 0) unset($arResult['data']['bill1']['cart_content'][$sku]);
                                else $arResult['data']['bill1']['cart_content'][$sku] = $quantity + $diff_count; //убираем недостающие товары
                                $arResult['data']['bill2']['cart_content'][$sku] = abs($diff_count); //как раз их и переводим в плюс
                            }
                        }
                    }
                    if (empty($arResult['data']['bill1']['cart_content'])) $arResult['data']['bill1'] = 0;
                    if (empty($arResult['data']['bill2']['cart_content'])) $arResult['data']['bill2'] = 0;
                }
            }else{
                $arResult['status'] = 'ok';
                $arResult['response'] = $resp;
                $arResult['data'] = $pl->getExtraData();

                //REPEATING!!!
                //////////////
                $arResult['data']['bill1'] = array();
                $arResult['data']['bill2'] = array();
                $arTemp['partner_id'] = $partner_id;
                $arTemp['alt_partner_id'] = $alt_partner_id;
                $arTemp['comment'] = $comment;
                $arTemp['cart_names'] = $arNames;
                $arTemp['cart_content'] = array();
                $arTemp['cart_prices'] = $arPrices;
                $arTemp['is_second'] = 'Y';
                $arResult['data']['bill2'] = $arTemp; //второй заказ с излишками (пока нет товаров)
                $arTemp['cart_content'] = $arProducts;
                $arTemp['is_second'] = 'N';
                $arResult['data']['bill1'] = $arTemp; //первый заказ с доступными (здесь пока еще все)
                foreach ($arProducts as $sku => $quantity) {
                    if (isset($arResult['data']['errDesc'][$sku]['diff_count'])) {
                        $diff_count = $arResult['data']['errDesc'][$sku]['diff_count'];
                        if ($diff_count < 0) {
                            if (($quantity + $diff_count) == 0) unset($arResult['data']['bill1']['cart_content'][$sku]);
                            else $arResult['data']['bill1']['cart_content'][$sku] = $quantity + $diff_count; //убираем недостающие товары
                            $arResult['data']['bill2']['cart_content'][$sku] = abs($diff_count); //как раз их и переводим в плюс
                        }
                    }
                }
                if (empty($arResult['data']['bill1']['cart_content'])) $arResult['data']['bill1'] = 0;
                if (empty($arResult['data']['bill2']['cart_content'])) $arResult['data']['bill2'] = 0;
                //////////////
            }
        }
	}else{
		$arResult['status'] = 'error';
	}
}else{
	$arResult['status'] = 'error';
}

echo json_encode($arResult);