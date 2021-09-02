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