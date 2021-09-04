<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (isset($arResult['FILTER_LINK'])) {
    echo GetMessage('FILTER').$arResult['FILTER_LINK'].'<br/>';
}

echo '---<br/><br/>';
echo '<b>'.GetMessage('CATALOG').'</b></br>';
$this->AddEditAction('iblock_'.$arResult["IBLOCK_ID"], $arResult['ADD_ELEMENT_LINK'], CIBlock::GetArrayByID($arResult["IBLOCK_ID"], "ELEMENT_ADD"));
echo '<ul id="'.$this->GetEditAreaId('iblock_'.$arResult["IBLOCK_ID"]).'">';
foreach($arResult['ITEMS'] as $newsId => $item) {
    $str = '';
    foreach ($item['SECTIONS'] as $sectionID) {
        $str .= ', '.$arResult['ALL_SECTIONS'][$sectionID]['NAME'];
    }
    echo '<li><b>'.$item['NAME'].'</b> - '.$item['ACTIVE_FROM'].' ('.substr($str, 2).')';
    echo '<ul>';
    foreach ($item['PRODUCTS'] as $prodId) {
        $ermitId = $newsId.'_'.$prodId;
        $arProduct = $arResult['ALL_PRODUCTS'][$prodId];
        $this->AddEditAction($ermitId, $arProduct['EDIT_LINK'], CIBlock::GetArrayByID($arResult["IBLOCK_ID"], "ELEMENT_EDIT"));
        $this->AddDeleteAction($ermitId, $arProduct['DELETE_LINK'], CIBlock::GetArrayByID($arResult["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
        echo '<li id="'.$this->GetEditAreaId($ermitId).'">'.$arProduct['NAME']. ' - '.$arProduct['PRICE']. ' - '.$arProduct['MATERIAL']. ' - '.$arProduct['ARTNUMBER'].' ('.$arProduct['LINK'].')</li>';
    }
    echo '</ul>';
    echo '</li>';
}
echo '</ul>';
