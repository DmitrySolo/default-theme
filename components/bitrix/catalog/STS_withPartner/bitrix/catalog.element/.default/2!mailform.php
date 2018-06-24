<?if(empty($_REQUEST['partnerID'])||$_REQUEST['partnerID']=='undefined'||$_REQUEST['partnerID']=='false'){?>
    <b>В настоящий момент данный товар отсутствует на наших складах.<br>Мы сообщим Вам о сроках его поставки.</b><br><br>
    <form style="font-size:20px" name="or_mailform" action="/bitrix/templates/santehsmart_inner/components/bitrix/catalog/smart_catalog/bitrix/catalog.element/.default/ajaxmail.php" method="POST">
        <input type="hidden" name="mail" value="true">
        <input type="hidden" name="sku" value="<?=$_REQUEST['sku']?>">
        <input type="hidden" name="name" value="<?=$_REQUEST['name']?>">
        <label for="var_num" style="line-height:30px">Телефон:</label><br>
        <input type="text" name="var_num" id="var_num"><br>
        <label for="var_name" style="line-height:30px">Имя:</label><br>
        <input type="text" name="var_name" id="var_name">
    </form>
    <script>
    $('input[name=var_num]').mask('+7 (999) 999-99-99');
    </script>
<?}else{?>
    <b>Уточнить сроки поставки у менеджера</b>
    <form style="font-size:20px" name="or_mailform" action="/bitrix/templates/santehsmart_inner/components/bitrix/catalog/smart_catalog/bitrix/catalog.element/.default/ajaxmail.php" method="POST">
        <input type="hidden" name="sku" value="<?=$_REQUEST['sku']?>">
        <input type="hidden" name="name" value="<?=$_REQUEST['name']?>">
        <input type="hidden" name="partnerID" value="<?=$_REQUEST['partnerID']?>">
    </form>
<?}?>