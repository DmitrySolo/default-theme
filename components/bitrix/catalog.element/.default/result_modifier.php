<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var CBitrixComponentTemplate $this
 * @var CatalogElementComponent $component
 */

$component = $this->getComponent();
$arParams = $component->applyTemplateModifications();


//GET MASS OF ELEMENT

$wer = CIBlockSection::GetList(
    false,
    Array("IBLOCK_ID" => 10, "ID" => $arResult ['SECTION']['ID'], "ACTIVE" => "Y", "GLOBAL_ACTIVE" => "Y", "SECTION_ACTIVE" => "Y"),
    false,
    Array("UF_MASS"),
    false
);
 $arResult['MASS'] = $wer->fetch()['UF_MASS'];




