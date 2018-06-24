<?
define("NOT_CHECK_PERMISSIONS", true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
use Bitrix\Main\Mail\Event;

if ($_SERVER["REQUEST_METHOD"]=="POST"){
    if(isset($_POST["mail"])){
        echo 'Товар: ',$_REQUEST['sku'],' ',$_REQUEST['name'],'<br>';
        echo 'Телефон: ',$_REQUEST['var_num'],'<br>';
        echo 'Имя: ',$_REQUEST['var_name'],'<br><br>';

        $city=$_SESSION['TF_LOCATION_SELECTED_CITY_NAME'];
        $arEventFields = array(
            "AUTHOR"         => htmlspecialcharsEx($_REQUEST['var_name']),
            "SKU"            => htmlspecialcharsEx($_REQUEST['sku']),
            "PRODUCT"        => htmlspecialcharsEx($_REQUEST['name']),
            "PHONE"          => htmlspecialcharsEx($_REQUEST['var_num']),
            "CITY"           => htmlspecialcharsEx($city)
        );
        if (CModule::IncludeModule("main")):
           if (CEvent::Send("GET_QUANTITY", "s1", $arEventFields)):
              echo "<b>Благодарим Вас за заявку.<br>Мы свяжемся с Вами по указанным<br>контактным данным.</b>";
           endif;
        endif;
    }elseif(isset($_POST["partnerID"])){
        $partner_id = $_POST["partnerID"];

        include_once $_SERVER["DOCUMENT_ROOT"].'/testzone/util/platform.php';
        include_once getPlatformPath();

        if(CModule::IncludeModule("highloadblock")&&CModule::IncludeModule("iblock")) {
            $pl = new Platform();
            $arManager = $pl->getManagerByPartnerId($partner_id);

            $var_name = "";
            $var_num = "";
            $hldata = Bitrix\Highloadblock\HighloadBlockTable::getById(13)->fetch();
            $hlentity = Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hldata);
            $hlDataClass = $hldata['NAME'] . 'Table';

            $result = $hlDataClass::getList(array(
                'filter' => array('UF_XML_ID' => $partner_id),
                'order' => array('UF_XML_ID' => 'ASC'),
                'select' => array(
                    'UF_SNAME',
                    'UF_NAME',
                    'UF_PHONE'
                )
            ));

            if ($res = $result->fetch()) {
                $var_name = $res['UF_NAME'] . " " . $res['UF_SNAME'];
                $var_num = $res['UF_PHONE'];
            }

            $email = 'admin@ove-cfo.ru';//$arManager['UF_EMAIL'];

            $city = $_SESSION['TF_LOCATION_SELECTED_CITY_NAME'];
            $arEventFields = array(
                "AUTHOR" => $var_name,
                "SKU" => htmlspecialcharsEx($_REQUEST['sku']),
                "PRODUCT" => htmlspecialcharsEx($_REQUEST['name']),
                "PHONE" => $var_num,
                "CITY" => htmlspecialcharsEx($city),
                "EMAIL" => $email
            );
            $result = Event::send(array(
                "EVENT_NAME" => "GET_QUANTITY",
                "LID" => "s1",
                "C_FIELDS" => $arEventFields,
            ));
            if($result) echo "<b>Запрос партнеру отослан.</b>";
        }
    }
}
