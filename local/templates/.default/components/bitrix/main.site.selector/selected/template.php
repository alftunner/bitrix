<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
echo '<select onchange="location.href = this.value">';
foreach ($arResult['SITES'] as $item) {
    echo '<option value="'.$item['DIR'].'" '.($item['CURRENT'] == 'Y' ? ' selected ' : '').'>'.$item['LANG'].'</option>';
}
echo '</select>';
