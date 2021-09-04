<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (isset($arResult['FILTER_LINK'])) {
    echo GetMessage('FILTER').$arResult['FILTER_LINK'].'<br/>';
}

echo '---<br/><br/>';
echo '<b>'.GetMessage('CATALOG').'</b></br>';
echo '<ul>';
foreach($arResult['ITEMS'] as $item) {
    $str = '';
    foreach ($item['SECTIONS'] as $sectionID) {
        $str .= ', '.$arResult['ALL_SECTIONS'][$sectionID]['NAME'];
    }
    echo '<li><b>'.$item['NAME'].'</b> - '.$item['ACTIVE_FROM'].' ('.substr($str, 2).')';
    echo '<ul>';
    foreach ($item['PRODUCTS'] as $prodId) {
        $arProduct = $arResult['ALL_PRODUCTS'][$prodId];
        echo '<li>'.$arProduct['NAME']. ' - '.$arProduct['PRICE']. ' - '.$arProduct['MATERIAL']. ' - '.$arProduct['ARTNUMBER'].' ('.$arProduct['LINK'].')</li>';
    }
    echo '</ul>';
    echo '</li>';
}
echo '</ul>';
