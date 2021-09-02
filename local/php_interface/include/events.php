<?php
const PRODUCT_IBLOCK = 2;
const MANAGER_GROUP = 5;
const METATAGS_IBLOCK = 6;
AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", 'OnBeforeIBlockElementUpdateHandler');
function OnBeforeIBlockElementUpdateHandler(&$arFields)
{
    if($arFields['IBLOCK_ID'] == PRODUCT_IBLOCK && $arFields['ACTIVE'] == 'N')
    {
        $res = CIBlockElement::GetList(
            false,
            ['IBLOCK_ID' => PRODUCT_IBLOCK, 'ACTIVE' => 'Y', 'ID'=>$arFields['ID'], '>SHOW_COUNTER' => 2],
            false,
            ['nTopCount' => 1],
            ['ID', 'SHOW_COUNTER']
        );
        if ($fields = $res->Fetch()) {
            global $APPLICATION;
            $APPLICATION->throwException(str_replace('#COUNT#', $fields['SHOW_COUNTER'] ,GetMessage('STOP_DEACTIVE')));
            return false;
        }
    }
}

AddEventHandler('main', 'onEpilog', 'onEpilogHandler', 1);
function onEpilogHandler() {
    if (defined('ERROR_404') && ERROR_404 == 'Y') {
        CEventLog::Add([
            'SEVERITY' => 'INFO',
            'AUDIT_TYPE_ID' => 'ERROR_404',
            'MODULE_ID' => 'main',
            'DESCRIPTION' => $_SERVER['REQUEST_URI']
        ]);

        global $APPLICATION;
        $APPLICATION->RestartBuffer();
        include $_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/header.php';
        include $_SERVER['DOCUMENT_ROOT'].'/404.php';
        include $_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/footer.php';
    }
}

AddEventHandler("main", "OnBeforeEventAdd", "OnBeforeEventAddHandler");
function OnBeforeEventAddHandler(&$event, &$lid, &$arFields)
{
    if ($event == 'FEEDBACK_FORM') {
        global $USER;
        if ($userID = $USER->getId()) {
            $rsUser = CUser::GetByID($userID);
            $arUser = $rsUser->Fetch();
            $arFields['AUTHOR'] = str_replace(
                    ['#ID#', '#LOGIN#', '#NAME#'],
                    [$arUser['ID'], $arUser['LOGIN'], $arUser['LAST_NAME'].' '.$arUser['NAME'].' '.$arUser['SECOND_NAME']],
                    GetMessage('AUTH')
                ).$arFields['AUTHOR'];
        } else {
            $arFields['AUTHOR'] = GetMessage('NOT_AUTH').$arFields['AUTHOR'];
        }
        CEventLog::Add([
            'SEVERITY' => 'INFO',
            'AUDIT_TYPE_ID' => 'FEEDBACK_FORM',
            'MODULE_ID' => 'main',
            'DESCRIPTION' => GetMessage('DESCRIPTION').$arFields['AUTHOR']
        ]);
    }
}

AddEventHandler("main", "OnBuildGlobalMenu", "OnBuildGlobalMenuHandler");
function OnBuildGlobalMenuHandler(&$aGlobalMenu, &$aModuleMenu) {
    global $USER;
    if (in_array(MANAGER_GROUP, $USER->GetUserGroupArray())) {
        foreach ($aGlobalMenu as $key => $value) {
            if ($key != 'global_menu_content') {
                unset($aGlobalMenu[$key]);
            }
        }
        foreach ($aModuleMenu as $key => $value) {
            if ($value['items_id'] != 'menu_iblock_/news') {
                unset($aModuleMenu[$key]);
            }
        }
    }
}

AddEventHandler("main", "OnPageStart", "OnPageStartHandler");
function OnPageStartHandler() {
    if(!CModule::IncludeModule('iblock')) return true;
    global $APPLICATION;

    $currentPage = $APPLICATION->GetCurPage();
    if (strpos($currentPage, '/bitrix/') === 0) {
        return true;
    }
    if (substr($currentPage, -10) == '/index.php') {
        $currentPage = substr($currentPage, 0, -9);
    }

    $res = CIBlockElement::GetList(
        false,
        ['IBLOCK_ID'=>METATAGS_IBLOCK, 'ACTIVE'=>'Y', '%NAME'=>$currentPage],
        false,
        false,
        ['ID', 'NAME', 'PROPERTY_TITLE', 'PROPERTY_DESCRIPTION']
    );
    while ($fields = $res->Fetch()) {
        if (trim($fields['NAME']) == $currentPage) {
            $APPLICATION->SetPageProperty('title', $fields['PROPERTY_TITLE_VALUE']);
            $APPLICATION->SetPageProperty('description', $fields['PROPERTY_DESCRIPTION_VALUE']);
            break;
        }
    }
}