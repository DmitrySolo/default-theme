<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$frame = $this->createFrame()->begin();
?>
<!--<div class="login_page">-->
<!--	--><?//
//	ShowMessage($arParams["~AUTH_RESULT"]);
//	ShowMessage($arResult['ERROR_MESSAGE']);
//	?>
<!--	--><?//if($arResult["AUTH_SERVICES"]):?>
<!--	<h2>--><?//ecyyyyyyyho GetMessage("AUTH_TITLE")?><!--</h2>-->
<!--	--><?//endif?>
<!--	--><?///*if($arResult["AUTH_SERVICES"]):
//		$APPLICATION->IncludeComponent("bitrix:socserv.auth.form", "",
//			array(
//				"AUTH_SERVICES"=>$arResult["AUTH_SERVICES"],
//				"CURRENT_SERVICE"=>$arResult["CURRENT_SERVICE"],
//				"AUTH_URL"=> ($arParams["BACKURL"] ? $arParams["BACKURL"] : $arResult["BACKURL"]),
//				"POST"=>$arResult["POST"],
//				"SUFFIX" => RandString(8),
//			),
//			$component,
//			array("HIDE_ICONS"=>"Y")
//		);
//	endif;*/?>
<!---->
<!---->
<!--	<form name="system_auth_form--><?//=$arResult["RND"]?><!--" method="post" target="_top" action="--><?//=SITE_DIR?><!--auth/--><?////=$arResult["AUTH_URL"]?><!--" class="bx_auth_form">-->
<!--		<input type="hidden" name="AUTH_FORM" value="Y" />-->
<!--		<input type="hidden" name="TYPE" value="AUTH" />-->
<!--		--><?//if (strlen($arParams["BACKURL"]) > 0 || strlen($arResult["BACKURL"]) > 0):?>
<!--		<input type="hidden" name="backurl" value="--><?//=($arParams["BACKURL"] ? $arParams["BACKURL"] : $arResult["BACKURL"])?><!--" />-->
<!--		--><?//endif?>
<!--		--><?//foreach ($arResult["POST"] as $key => $value):?>
<!--		<input type="hidden" name="--><?//=$key?><!--" value="--><?//=$value?><!--" />-->
<!--		--><?//endforeach?>
<!---->
<!--		<strong>--><?//=GetMessage("AUTH_LOGIN")?><!--</strong><br>-->
<!--		<input class="input_text_style" type="text" name="USER_LOGIN" maxlength="255" value="--><?//=$arResult["LAST_LOGIN"]?><!--" /><br><br>-->
<!--		<strong>--><?//=GetMessage("AUTH_PASSWORD")?><!--</strong><br>-->
<!--		<input class="input_text_style" type="password" name="USER_PASSWORD" maxlength="255" /><br>-->
<!---->
<!--		--><?//if($arResult["CAPTCHA_CODE"]):?>
<!--			<input type="hidden" name="captcha_sid" value="--><?//echo $arResult["CAPTCHA_CODE"]?><!--" />-->
<!--			<img src="/bitrix/tools/captcha.php?captcha_sid=--><?//echo $arResult["CAPTCHA_CODE"]?><!--" width="180" height="40" alt="CAPTCHA" />-->
<!--			--><?//echo GetMessage("AUTH_CAPTCHA_PROMT")?><!--:-->
<!--			<input class="bx-auth-input" type="text" name="captcha_word" maxlength="50" value="" size="15" />-->
<!--		--><?//endif;?>
<!---->
<!--		--><?//if ($arResult["STORE_PASSWORD"] == "Y"):?>
<!--			<span class="rememberme"><input type="checkbox" id="USER_REMEMBER" name="USER_REMEMBER" value="Y" checked/>--><?//=GetMessage("AUTH_REMEMBER_ME")?><!--</span>-->
<!--		--><?//endif?>
<!---->
<!--		--><?//if ($arParams["NOT_SHOW_LINKS"] != "Y"):?>
<!--		<noindex>-->
<!--			<span class="forgotpassword" style="padding-left:75px;"><a href="--><?//=$arParams["AUTH_FORGOT_PASSWORD_URL"] ? $arParams["AUTH_FORGOT_PASSWORD_URL"] : $arResult["AUTH_FORGOT_PASSWORD_URL"]?><!--" rel="nofollow">--><?//=GetMessage("AUTH_FORGOT_PASSWORD_2")?><!--</a></span>-->
<!--		</noindex>-->
<!--		--><?//endif?>
<!--		<br><br><input type="submit" name="Login" class="bt_blue big shadow" value="--><?//=GetMessage("AUTH_AUTHORIZE")?><!--" />-->
<!--	</form>-->
<!--</div>-->
//

    <div class="modalWindow__content modalWindow__content" data-qcontent="level__modalWindow"><a class="modalWindow__close">
            <svg class="icon icon--close" data-qcontent="element__ICONS__MAIN-SVG-use">
                <use xlink:href="#close">              </use>
            </svg></a>
        <h3 class="modalWindow__title"><?=GetMessage("AUTH_LOGIN")?></h3>
        <div class="modalWindow__body">
            <form name="system_auth_form<?=$arResult["RND"]?>" method="post" target="_top" action="<?=SITE_DIR?>auth/<?//=$arResult["AUTH_URL"]?>" >
                <input type="hidden" name="AUTH_FORM" value="Y" />
                <input type="hidden" name="TYPE" value="AUTH" />
                <?if (strlen($arParams["BACKURL"]) > 0 || strlen($arResult["BACKURL"]) > 0):?>
                    <input type="hidden" name="backurl" value="<?=($arParams["BACKURL"] ? $arParams["BACKURL"] : $arResult["BACKURL"])?>" />
                <?endif?>
                <?foreach ($arResult["POST"] as $key => $value):?>
                    <input type="hidden" name="<?=$key?>" value="<?=$value?>" />
                <?endforeach?>
                <div class="input__ctn">
                    <label class="textInput__label">Электронная почта:</label>
                    <input class="textInput "  placeholder="Электронная почта" type="text" name="USER_LOGIN" maxlength="255" value="<?=$arResult["LAST_LOGIN"]?>" data-qcontent="element__INPUTS__textInput"/>
                </div>
                <div class="input__ctn">
                    <label class="textInput__label">Пароль:</label>
                    <input type="password" name="USER_PASSWORD" maxlength="255" class="textInput " placeholder="Пароль" data-qcontent="element__INPUTS__textInput"/>
                </div>
                <?if($arResult["CAPTCHA_CODE"]):?>
                    <input type="hidden" name="captcha_sid" value="<?echo $arResult["CAPTCHA_CODE"]?>" />
                    <img src="/bitrix/tools/captcha.php?captcha_sid=<?echo $arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA" />
                    <?echo GetMessage("AUTH_CAPTCHA_PROMT")?>:
                    <input class="bx-auth-input" type="text" name="captcha_word" maxlength="50" value="" size="15" />
                <?endif;?>
                <?if ($arResult["STORE_PASSWORD"] == "Y"):?>
                    <div class="checkBox col col-12-tl col-6-tp col-6-m checked" data-qcontent="element__buttons__checkBox">
                        <input class="checkBox__input productFilter__checkbox" type="checkbox" id="USER_REMEMBER" value="Y" checked/>
                        <label class="checkBox__label" type="checkbox" for="USER_REMEMBER"><?=GetMessage("AUTH_REMEMBER_ME")?></label>
                    </div>
                <?endif?>
                <button type="submit" class="toOrder active" name="Login" data-qcontent="element__buttons__toOrder" type="<?=GetMessage("AUTH_AUTHORIZE")?>" ><?=GetMessage("AUTH_AUTHORIZE")?></button>
                <?if ($arParams["NOT_SHOW_LINKS"] != "Y"):?>
                    <noindex>
                        <a href="<?=$arParams["AUTH_FORGOT_PASSWORD_URL"] ? $arParams["AUTH_FORGOT_PASSWORD_URL"] : $arResult["AUTH_FORGOT_PASSWORD_URL"]?>" rel="nofollow">
                            <button type="button" class="button button--fastBuy" data-qcontent="element__buttons__button"><?=GetMessage("AUTH_FORGOT_PASSWORD_2")?></button>
                        </a>
                    </noindex>
                <?endif?>
            </form>
        </div>
    </div>

<script type="text/javascript">
<?if (strlen($arResult["LAST_LOGIN"])>0):?>
try{document.form_auth.USER_PASSWORD.focus();}catch(e){}
<?else:?>
try{document.form_auth.USER_LOGIN.focus();}catch(e){}
<?endif?>
</script>

