<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader,
	Bitrix\Iblock;

if(!Loader::includeModule("iblock"))
{
	ShowError(GetMessage("SIMPLECOMP_EXAM2_IBLOCK_MODULE_NONE"));
	return;
}

$currentUser = $USER->GetId();
if (!$currentUser) {
    return false;
}

if ($this->StartResultCache(false, [$currentUser])) {
    if (!$iblockNew = (int) $arParams['NEWS_IBLOCK_ID']) {
        return false;
    }
    if (!$iblockProp = trim($arParams['IBLOCK_PROPERTY'])) {
        return false;
    }
    if (!$userProp = trim($arParams['USER_PROPERTY'])) {
        return false;
    }
    $iblockProp = 'PROPERTY_'.$iblockProp;

    $rsUser = CUser::GetByID($currentUser);
    $arUser = $rsUser->Fetch();
    $currentType = $arUser[$userProp];

    $arItems = [];
    $rsUsers = CUser::GetList(
        ($by = "personal_country"),
        ($order = "desc"),
        ['ACTIVE' => 'Y', $userProp => $currentType],
        ['FIELDS' => ['ID', 'LOGIN']]
    );
    while ($arUser = $rsUsers->Fetch()) {
        $arItems[$arUser['ID']] = [
            'LOGIN' => $arUser['LOGIN'],
            'NEWS' => []
        ];
    }

    $arAllNews = [];
    $resNews = CIBlockElement::GetList(
        false,
        ['IBLOCK_ID'=>$iblockNew, 'ACTIVE'=>'Y', $iblockProp=>array_keys($arItems)],
        false,
        false,
        ['ID', 'NAME','ACTIVE_FROM', $iblockProp]
    );
    while ($arNew = $resNews->GetNext()) {
        $newsId = $arNew['ID'];
        if (!isset($arAllNews[$newsId])) {
            $arAllNews[$newsId] = [
                'NAME' => $arNew['NAME'],
                'ACTIVE_FROM' => $arNew['ACTIVE_FROM'],
                'AUTHORS' => [$arNew[$iblockProp.'_VALUE']]
            ];
        } else {
            $arAllNews[$newsId]['AUTHORS'][] = $arNew[$iblockProp.'_VALUE'];
        }
    }
    foreach ($arAllNews as $newsId => $arNew) {
        if (in_array($currentUser, $arNew['AUTHORS'])) {
            unset($arAllNews[$newsId]);
        } else {
            foreach ($arNew['AUTHORS'] as $authorId) {
                $arItems[$authorId]['NEWS'][] = $newsId;
            }
        }
    }

    unset($arItems[$currentUser]);
    foreach ($arItems as $key=>$value) {
        if(!count($value['NEWS'])) {
            unset($arItems[$key]);
        }
    }

    $arResult['ITEMS'] = $arItems;
    $arResult['ALL_NEWS'] = $arAllNews;
    $arResult['COUNT_NEWS'] = count($arAllNews);

    $this->setResultCacheKeys(['COUNT_NEWS']);

    $this->includeComponentTemplate();
}

$APPLICATION->SetTitle(GetMessage('SET_TITLE').$arResult['COUNT_NEWS']);
?>