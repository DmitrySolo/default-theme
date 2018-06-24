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

/**
* noticeList styles
*/



$this->setFrameMode(true);
?>
<?if($arResult['NOTICES']){?>
	<div class="noticeList">
		<ul class="noticeList__content" data-qcontent="module__noticeList"> 
    	<?foreach($arResult['NOTICES'] as $notice){?>
			<li class="noticeList__element">
				<time class="noticeList__publicTime"> <b><?=$notice['DATETIME']?></b></time>
				<h5 class="noticeList__elementHeader"><?=$notice['HEADER']?></h5>
				<p class="noticeList__noticeContent text"><?=$notice['NOTICE_CONTENT']?></p>
			</li>
	<?}?>
		</ul>
	</div>
<?}?>