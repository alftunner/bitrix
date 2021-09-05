<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
foreach ($arResult['ITEMS'] as $item) {
    echo '<p>'.$item['ACTIVE_FROM'].'<br><b>'.$item['PROPERTIES']['NAME_EN']['VALUE'].'</b><br>'
        .$item['PROPERTIES']['PREVIEW_TEXT_EN']['VALUE']['TEXT'].'</p>';
}
