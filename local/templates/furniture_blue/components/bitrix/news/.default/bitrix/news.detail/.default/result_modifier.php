<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
if (isset($arParams['SET_IBLOCK_ID']) && ($bid = (int)$arParams['SET_IBLOCK_ID'])) {
    $res = CIBlockElement::GetList([
        false,
        ['IBLOCK_ID'=>$bid, 'ACTIVE'=>'Y', 'PROPERTY_NEW'=>$arResult['ID']],
        false,
        'nTopCount' => 1,
        ['ID', 'NAME']
    ]);
    if ($field = $res->Fetch()) {
        $this->getComponent()->SetResultCacheKeys(['CANONICAL_NAME']);
        $arResult['CANONICAL_NAME'] = $field['NAME'];
    }
}
