<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader,
	Bitrix\Iblock;

if(!Loader::includeModule("iblock"))
{
	ShowError(GetMessage("SIMPLECOMP_EXAM2_IBLOCK_MODULE_NONE"));
	return;
}
if ($this->StartResultCache()) {
    if (!$iblockProd = (int) $arParams['PRODUCTS_IBLOCK_ID']) {
        return false;
    }
    if (!$iblockNews = (int) $arParams['NEWS_IBLOCK_ID']) {
        return false;
    }
    if (!$propCode = trim($arParams['PROPERTY_CODE'])) {
        return false;
    }

    $arAllSections = [];
    $arIdNews = [];
    $resSections = CIBlockSection::GetList(
        false,
        ['IBLOCK_ID' => $iblockProd, 'ACTIVE' => 'Y', '!'.$propCode => false],
        true,
        ['ID', 'NAME', $propCode]
    );
    while ($arSection = $resSections->GetNext()) {
        if ($arSection['ELEMENT_CNT'] > 0) {
            $arAllSections[$arSection['ID']] = [
                'NAME' => $arSection['NAME'],
                'NEWS' => $arSection[$propCode]
            ];

            foreach ($arSection[$propCode] as $newsId) {
                if (in_array($newsId, $arIdNews)) {
                    $arIdNews[] = $newsId;
                }
            }
        }
    }

    $arAllNews = [];
    $resNews = CIBlockElement::GetList(
        false,
        ['IBLOCK_ID'=>$iblockNews, 'ACTIVE'=>'Y', 'ID'=>$arIdNews],
        false,
        false,
        ['ID', 'NAME', 'ACTIVE_FROM']
    );
    while ($arNew = $resNews->GetNext()) {
        $arAllNews[$arNew['ID']] = [
            'NAME' => $arNew['NAME'],
            'ACTIVE_FROM' => $arNew['ACTIVE_FROM'],
            'SECTIONS' => [],
            'PRODUCTS' => []
        ];
    }

    $arAllProducts = [];
    $resProducts = CIBlockElement::GetList(
        false,
        ['IBLOCK_ID'=>$iblockProd, 'ACTIVE'=>'Y', 'SECTION_ID'=>array_keys($arAllSections)],
        false,
        false,
        ['ID', 'NAME', 'IBLOCK_SECTION_ID', 'PROPERTY_PRICE', 'PROPERTY_MATERIAL', 'PROPERTY_ARTNUMBER']
    );
    while ($arProduct = $resProducts->GetNext()) {
        $prodId = $arProduct['ID'];
        $arAllProducts[$prodId] = [
            'NAME' => $arProduct['NAME'],
            'PRICE' => $arProduct['PROPERTY_PRICE_VALUE'],
            'MATERIAL' => $arProduct['PROPERTY_MATERIAL_VALUE'],
            'ARTNUMBER' => $arProduct['PROPERTY_ARTNUMBER_VALUE']
        ];
        $IBLOCK_SECTION_ID = $arProduct['IBLOCK_SECTION_ID'];
        foreach ($arAllSections[$IBLOCK_SECTION_ID]['NEWS'] as $newsId) {
            $arAllNews[$newsId]['PRODUCTS'][] = $prodId;

            if (!in_array($IBLOCK_SECTION_ID, $arAllNews[$newsId]['SECTIONS'])) {
                $arAllNews[$newsId]['SECTIONS'][] = $IBLOCK_SECTION_ID;
            }
        }
    }
    $arResult['ITEMS'] = $arAllNews;
    $arResult['ALL_PRODUCTS'] = $arAllProducts;
    $arResult['ALL_SECTIONS'] = $arAllSections;
    $arResult['COUNT_PRODUCTS'] = count($arAllProducts);

    $this->setResultCacheKeys(['COUNT_PRODUCTS']);

    $this->includeComponentTemplate();
}

$APPLICATION->SetTitle(GetMessage('SET_TITLE').$arResult['COUNT_PRODUCTS']);
?>