<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader,
	Bitrix\Iblock;

if(!Loader::includeModule("iblock"))
{
	ShowError(GetMessage("SIMPLECOMP_EXAM2_IBLOCK_MODULE_NONE"));
	return;
}
if ($this->StartResultCache(false, [$USER->GetUserGroupArray()])) {
    if (!$iblockProd = (int) $arParams['PRODUCTS_IBLOCK_ID']) {
        return false;
    }
    if (!$iblockClassif = (int) $arParams['CLASSIF_IBLOCK_ID']) {
        return false;
    }
    if (!$detailTemplate = trim($arParams['DETAIL_TEMPLATE'])) {
        return false;
    }
    if (!$propCode = trim($arParams['PROPERTY_CODE'])) {
        return false;
    }
    $propCode = 'PROPERTY_'.$propCode;

    $resClassif = CIBlockElement::GetList(
        false,
        ['IBLOCK_ID'=>$iblockClassif, 'CHECK_PERMISSIONS'=>'Y'],
        false,
        ['nTopCount' => 1],
        ['ID']
    );
    if (!$resClassif->SelectedRowsCount()) {
        return false;
    }

    $arAllProducts = [];
    $arItems = [];
    $resProducts = CIBlockElement::GetList(
        false,
        ['IBLOCK_ID'=>$iblockProd, 'ACTIVE'=>'Y', '!'.$propCode=>false, 'CHECK_PERMISSIONS'=>'Y'],
        false,
        false,
        ['ID', 'NAME', 'IBLOCK_SECTION_ID', 'CODE', 'PROPERTY_PRICE', 'PROPERTY_MATERIAL', 'PROPERTY_ARTNUMBER', $propCode, $propCode.'.NAME']
    );
    while ($arProduct = $resProducts->GetNext()) {
        $prodId = $arProduct['ID'];
        if (!isset($arAllProducts[$prodId])) {
            $arAllProducts[$prodId] = [
                'NAME' => $arProduct['NAME'],
                'PRICE' => $arProduct['PROPERTY_PRICE_VALUE'],
                'MATERIAL' => $arProduct['PROPERTY_MATERIAL_VALUE'],
                'ARTNUMBER' => $arProduct['PROPERTY_ARTNUMBER_VALUE'],
                'LINK' => str_replace(
                    ['#SECTION_ID#', '#ELEMENT_CODE#', '#ELEMENT_ID#'],
                    [$arProduct['IBLOCK_SECTION_ID'], $arProduct['CODE'], $prodId],
                    $detailTemplate
                )
            ];
        }

        $classifId = $arProduct[$propCode.'_VALUE'];
        if (!isset($arItems[$classifId])) {
            $arItems[$classifId] = [
                'NAME' => $arProduct[$propCode.'_NAME'],
                'PRODUCTS' => [$prodId]
            ];
        } else {
            $arItems[$classifId]['PRODUCTS'][] = $prodId;
        }
    }

    $arResult['ITEMS'] = $arItems;
    $arResult['ALL_PRODUCTS'] = $arAllProducts;
    $arResult['COUNT_SECTIONS'] = count($arItems);

    $this->setResultCacheKeys(['COUNT_SECTIONS']);

    $this->includeComponentTemplate();
}

$APPLICATION->SetTitle(GetMessage('SET_TITLE').$arResult['COUNT_SECTIONS']);
?>