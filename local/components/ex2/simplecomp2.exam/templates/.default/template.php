<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

echo '---<br/><br/>';
echo '<b>'.GetMessage('CATALOG').'</b></br>';
echo '<ul>';
foreach($arResult['ITEMS'] as $item) {
    echo '<li><b>'.$item['NAME'].'</b>';
    echo '<ul>';
    foreach ($item['PRODUCTS'] as $prodId) {
        $arProduct = $arResult['ALL_PRODUCTS'][$prodId];
        echo '<li>'.$arProduct['NAME']. ' - '.$arProduct['PRICE']. ' - '.$arProduct['MATERIAL']. ' - '.$arProduct['ARTNUMBER'].' <a href="'.$arProduct['LINK'].'">'.GetMessage('DETAIL').'</a>'.'</li>';
    }
    echo '</ul>';
    echo '</li>';
}
echo '</ul>';
