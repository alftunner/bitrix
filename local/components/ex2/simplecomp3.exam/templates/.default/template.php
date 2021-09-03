<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

echo '<b>'.GetMessage('AUTHORS_AND_NEWS').'</b></br>';
echo '<ul>';
foreach($arResult['ITEMS'] as $userId => $item) {
    echo '<li>['.$userId.'] - '.$item['LOGIN'];
    echo '<ul>';
    foreach ($item['NEWS'] as $newsId) {
        $arNews = $arResult['ALL_NEWS'][$newsId];
        echo '<li>'.$arNews['NAME'].'</li>';
    }
    echo '</ul>';
    echo '</li>';
}
echo '</ul>';
