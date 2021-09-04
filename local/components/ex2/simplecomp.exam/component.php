<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader,
	Bitrix\Iblock;

if(!Loader::includeModule("iblock"))
{
	ShowError(GetMessage("SIMPLECOMP_EXAM2_IBLOCK_MODULE_NONE"));
	return;
}
if ($this->StartResultCache(false, [isset($_GET['F'])])) {
    if (isset($_GET['F'])) {
        $this->AbortResultCache();
    }

    if (!$iblockProd = (int) $arParams['PRODUCTS_IBLOCK_ID']) {
        return false;
    }
    if (!$iblockNews = (int) $arParams['NEWS_IBLOCK_ID']) {
        return false;
    }
    if (!$propCode = trim($arParams['PROPERTY_CODE'])) {
        return false;
    }
    if (!$detailTemp = trim($arParams['DETAIL_TEMPLATE'])) {
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

    $filter = ['IBLOCK_ID'=>$iblockProd, 'ACTIVE'=>'Y', 'SECTION_ID'=>array_keys($arAllSections)];
    if (isset($_GET['F'])) {
        $filter[] = [
            'LOGIC' => 'OR',
            ['<=PROPERTY_PRICE' => 1700, 'PROPERTY_MATERIAL' => 'Дерево, ткань'],
            ['<PROPERTY_PRICE' => 1500, 'PROPERTY_MATERIAL' => 'Металл, пластик']
        ];
    }

    $arAllProducts = [];
    $resProducts = CIBlockElement::GetList(
        ['NAME' => 'ASC', 'SORT' => 'ASC'],
        $filter,
        false,
        false,
        ['ID', 'NAME', 'CODE', 'IBLOCK_SECTION_ID', 'PROPERTY_PRICE', 'PROPERTY_MATERIAL', 'PROPERTY_ARTNUMBER']
    );
    while ($arProduct = $resProducts->GetNext()) {
        $prodId = $arProduct['ID'];
        $arAllProducts[$prodId] = [
            'NAME' => $arProduct['NAME'],
            'PRICE' => $arProduct['PROPERTY_PRICE_VALUE'],
            'MATERIAL' => $arProduct['PROPERTY_MATERIAL_VALUE'],
            'ARTNUMBER' => $arProduct['PROPERTY_ARTNUMBER_VALUE'],
            'LINK' => str_replace(
                ['#SECTION_ID#', '#ELEMENT_CODE#', '#ELEMENT_ID#'],
                [$arProduct['IBLOCK_SECTION_ID'], $arProduct['CODE'], $prodId],
                $detailTemp
            )
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

    if (!isset($_GET['F'])) {
        $url = $APPLICATION->GetCurPage().'?F=Y';
        $arResult['FILTER_LINK'] = '<a href="'.$url.'">'.$url.'</a>';
    }

    $this->setResultCacheKeys(['COUNT_PRODUCTS']);

    $this->includeComponentTemplate();
}

$APPLICATION->SetTitle(GetMessage('SET_TITLE').$arResult['COUNT_PRODUCTS']);
?>