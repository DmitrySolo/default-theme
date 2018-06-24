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

$ClientID = 'navigation_'.$arResult['NavNum'];

if(!$arResult["NavShowAlways"])
{
	if ($arResult["NavRecordCount"] == 0 || ($arResult["NavPageCount"] == 1 && $arResult["NavShowAll"] == false))
		return;
}

$strNavQueryString = ($arResult["NavQueryString"] != "" ? $arResult["NavQueryString"]."&amp;" : "");
$strNavQueryStringFull = ($arResult["NavQueryString"] != "" ? "?".$arResult["NavQueryString"] : "");
?>

<div class="breadCrumb__content" data-qcontent="module__breadcrumbs">
    <nav class="breadCrumb visible-l">
        <?
        if($arResult["bDescPageNumbering"] === true)
        {
            // to show always first and last pages
            $arResult["nStartPage"] = $arResult["NavPageCount"];
            $arResult["nEndPage"] = 1;

            $sPrevHref = '';
            if ($arResult["NavPageNomer"] < $arResult["NavPageCount"])
            {
                $bPrevDisabled = false;
                if ($arResult["bSavePage"])
                {
                    $sPrevHref = $arResult["sUrlPath"].'?'.$strNavQueryString.'PAGEN_'.$arResult["NavNum"].'='.($arResult["NavPageNomer"]+1);
                }
                else
                {
                    if ($arResult["NavPageCount"] == ($arResult["NavPageNomer"]+1))
                    {
                        $sPrevHref = $arResult["sUrlPath"].$strNavQueryStringFull;
                    }
                    else
                    {
                        $sPrevHref = $arResult["sUrlPath"].'?'.$strNavQueryString.'PAGEN_'.$arResult["NavNum"].'='.($arResult["NavPageNomer"]+1);
                    }
                }
            }
            else
            {
                $bPrevDisabled = true;
            }

            $sNextHref = '';
            if ($arResult["NavPageNomer"] > 1)
            {
                $bNextDisabled = false;
                $sNextHref = $arResult["sUrlPath"].'?'.$strNavQueryString.'PAGEN_'.$arResult["NavNum"].'='.($arResult["NavPageNomer"]-1);
            }
            else
            {
                $bNextDisabled = true;
            }
            ?>
            <?if (!$bPrevDisabled):?>
            <a class="not breadcrumb__section" id="<?=$ClientID?>_previous_page" href="<?=$sPrevHref;?>">&larr;</a>
        <?endif?>

            <?
            $bFirst = true;
            $bPoints = false;
            do
            {
                $NavRecordGroupPrint = $arResult["NavPageCount"] - $arResult["nStartPage"] + 1;
                if ($arResult["nStartPage"] <= 2 || $arResult["NavPageCount"]-$arResult["nStartPage"] <= 1 || abs($arResult['nStartPage']-$arResult["NavPageNomer"])<=1)
                {
                    if ($arResult["nStartPage"] == $arResult["NavPageNomer"]):?>
                        <span class="breadcrumb__section breadCrumb__section--current"><?=$NavRecordGroupPrint?></span>
                    <?elseif($arResult["nStartPage"] == $arResult["NavPageCount"] && $arResult["bSavePage"] == false):?>
                        <a class="not breadcrumb__section" href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>"><?=$NavRecordGroupPrint?></a>
                    <?else:?>
                        <a class="not breadcrumb__section" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["nStartPage"]?>"><?=$NavRecordGroupPrint?></a>
                    <?endif;
                    $bFirst = false;
                    $bPoints = true;
                }
                else
                {
                    if ($bPoints)
                    {
                        ?><span class="not breadcrumb__section breadcrumb__section--points">...</span><?
                        $bPoints = false;
                    }
                }
                $arResult["nStartPage"]--;
            } while($arResult["nStartPage"] >= $arResult["nEndPage"]);?>
            <?if (!$bNextDisabled):?>
            <a class="not breadcrumb__section" id="<?=$ClientID?>_next_page" href="<?=$sNextHref;?>">&rarr;</a>
        <?endif;
        }
        else
        {
            // to show always first and last pages
            $arResult["nStartPage"] = 1;
            $arResult["nEndPage"] = $arResult["NavPageCount"];

            $sPrevHref = '';
            if ($arResult["NavPageNomer"] > 1)
            {
                $bPrevDisabled = false;

                if ($arResult["bSavePage"] || $arResult["NavPageNomer"] > 2)
                {
                    $sPrevHref = $arResult["sUrlPath"].'?'.$strNavQueryString.'PAGEN_'.$arResult["NavNum"].'='.($arResult["NavPageNomer"]-1);
                }
                else
                {
                    $sPrevHref = $arResult["sUrlPath"].$strNavQueryStringFull;
                }
            }
            else
            {
                $bPrevDisabled = true;
            }

            $sNextHref = '';
            if ($arResult["NavPageNomer"] < $arResult["NavPageCount"])
            {
                $bNextDisabled = false;
                $sNextHref = $arResult["sUrlPath"].'?'.$strNavQueryString.'PAGEN_'.$arResult["NavNum"].'='.($arResult["NavPageNomer"]+1);
            }
            else
            {
                $bNextDisabled = true;
            }
            ?>
            <?if (!$bPrevDisabled):?>
            <a class="not breadcrumb__section" id="<?=$ClientID?>_previous_page" href="<?=$sPrevHref;?>">&larr;</a>
        <?endif?>
            <?
            $bFirst = true;
            $bPoints = false;
            do
            {
                if ($arResult["nStartPage"] <= 3 || $arResult["nEndPage"]-$arResult["nStartPage"] <= 1 || abs($arResult['nStartPage']-$arResult["NavPageNomer"])<=1)
                {
                    if ($arResult["nStartPage"] == $arResult["NavPageNomer"]):?>
                        <span class="breadcrumb__section breadCrumb__section--current"><?=$arResult["nStartPage"]?></span>
                    <?elseif($arResult["nStartPage"] == 1 && $arResult["bSavePage"] == false):?>
                        <a class="not breadcrumb__section" href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>"><?=$arResult["nStartPage"]?></a>
                    <?else:?>
                        <a class="not breadcrumb__section" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["nStartPage"]?>"><?=$arResult["nStartPage"]?></a>
                    <?endif;
                    $bFirst = false;
                    $bPoints = true;
                }
                else
                {
                    if ($bPoints)
                    {
                        ?><span class="not breadcrumb__section breadcrumb__section--points">...</span><?
                        $bPoints = false;
                    }
                }
                $arResult["nStartPage"]++;
            } while($arResult["nStartPage"] <= $arResult["nEndPage"]);
            ?>
            <?if (!$bNextDisabled):?>
            <a class="not breadcrumb__section" id="<?=$ClientID?>_next_page" href="<?=$sNextHref;?>">&rarr;</a>
        <?endif;
        }
        ?>
        <?if ($arResult["bShowAll"]):
            if ($arResult["NavShowAll"]):?>
                <a class="not breadcrumb__section breadCrumb__section--all" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>SHOWALL_<?=$arResult["NavNum"]?>=0"><?=GetMessage("nav_paged")?></a>
            <?else:?>
                <a class="not breadcrumb__section breadCrumb__section--all" href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>SHOWALL_<?=$arResult["NavNum"]?>=1"><?=GetMessage("nav_all")?></a>
            <?endif;
        endif;?>
    </nav>
</div>

<?CJSCore::Init();?>
<script type="text/javascript">
	BX.bind(document, "keydown", function (event) {

		event = event || window.event;
		if (!event.ctrlKey)
			return;

		var target = event.target || event.srcElement;
		if (target && target.nodeName && (target.nodeName.toUpperCase() == "INPUT" || target.nodeName.toUpperCase() == "TEXTAREA"))
			return;

		var key = (event.keyCode ? event.keyCode : (event.which ? event.which : null));
		if (!key)
			return;

		var link = null;
		if (key == 39)
			link = BX('<?=$ClientID?>_next_page');
		else if (key == 37)
			link = BX('<?=$ClientID?>_previous_page');

		if (link && link.href)
			document.location = link.href;
	});
</script>