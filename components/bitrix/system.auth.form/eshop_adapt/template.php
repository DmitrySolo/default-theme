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

CJSCore::Init(array("popup"));
?>
<!--<a class="actionLink login js_modal_trigger icon-login" href="" data-qcontent="element__LINKS__actionLink" id="js_modal_trigger__login">Вход</a><a class="actionLink signUp js_modal_trigger " href="" data-qcontent="element__LINKS__actionLink" id="js_modal_trigger__signUp">Регистрация</a>-->

	<?
	$frame = $this->createFrame("login-line", false)->begin();
		if ($arResult["FORM_TYPE"] == "login")
		{
		?>
			<a class="actionLink login js_modal_trigger icon-login" id="js_modal_trigger__login" href="javascript:void(0)" onclick="$('#js_modal__login').removeClass('modalWindow--hide');"><?=GetMessage("AUTH_LOGIN")?></a>
			<?if($arResult["NEW_USER_REGISTRATION"] == "Y"):?>
			<a class="actionLink signUp js_modal_trigger" href="<?=$arResult["AUTH_REGISTER_URL"]?>" ><?=GetMessage("AUTH_REGISTER")?></a>
			<?endif;
		}
		else
		{
			$name = trim($USER->GetFullName());
			if (strlen($name) <= 0)
				$name = $USER->GetLogin();
		?>
			<a class="actionLink  icon-login" href="<?=$arResult['PROFILE_URL']?>"><?=htmlspecialcharsEx($name);?></a>
			<a class="actionLink" href="<?=$APPLICATION->GetCurPageParam("logout=yes", Array("logout"))?>"><?=GetMessage("AUTH_LOGOUT")?></a>
		<?
		}
	$frame->beginStub();
		?>
        <a class="actionLink login js_modal_trigger icon-login" id="js_modal_trigger__login" href="javascript:void(0)" onclick="openAuthorizePopup()"><?=GetMessage("AUTH_LOGIN")?></a>
        <?if($arResult["NEW_USER_REGISTRATION"] == "Y"):?>
            <a class="actionLink signUp js_modal_trigger" href="<?=$arResult["AUTH_REGISTER_URL"]?>" ><?=GetMessage("AUTH_REGISTER")?></a>
        <?endif;
	$frame->end();
	?>



<?if ($arResult["FORM_TYPE"] == "login"):?>
	<div  class="modalWindow overlay modalWindow--hide modalWindow--signUp" id="js_modal__login">
	<?$APPLICATION->IncludeComponent("bitrix:system.auth.form", "eshop_adapt_auth",
		array(
			"BACKURL" => $arResult["BACKURL"],
			"AUTH_FORGOT_PASSWORD_URL" => $arResult["AUTH_FORGOT_PASSWORD_URL"],
		),
		false
	);
	?>
	</div>

<?endif?>
