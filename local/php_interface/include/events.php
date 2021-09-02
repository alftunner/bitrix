<?php
const PRODUCT_IBLOCK = 2;
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