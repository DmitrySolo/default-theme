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
		<div class="support__content" data-qcontent="module__support">
		  <section class="support__section">
			<h3 class="support__sectionTitle">Ваш менеджер:</h3>
			  <ul>
			  <li><p><b>ФИО:</b> <?=$arResult['MANAGER']['UF_FIO']?>
			</p></li>
				  <li><p><b>Эл. почта:</b> <?=$arResult['MANAGER']['UF_EMAIL']?></p></li>
			  <li><p><b>Телефон:</b> +7(473) 233-48-17 доб. <?=$arResult['MANAGER']['UF_PHONE_INNER']?></p></li>

		  </section>
		  <section class="support__section">
			  <h3 class="support__sectionTitle">Техподдержка:</h3>
			  <li><p><b>Телефон: +7(473) 233-48-17 доб. 151</b></p></li>
              <li><p><b>Эл. почта: sale@santehsmart.ru</b></p></li>
		  </section>
		  <section class="support__section">
			<h3 class="support__sectionTitle">Настройки:</h3>
			  <?if($arResult['CSV_LINK']):?>
                  <li><p>Ссылка на файл с остатками и ценами (CSV) <b><a href="<?=$arResult['CSV_LINK']?>"> <br/> [Скачать] </a></b></p></li>
			  <?endif?>
			  </ul>
		  </section>
		</div>