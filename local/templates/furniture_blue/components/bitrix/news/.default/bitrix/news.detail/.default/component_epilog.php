<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

const IBLOCK_ZALOB = 8;

if (isset($arResult['CANONICAL_NAME'])) {
    $APPLICATION->SetPageProperty('canonical', $arResult['CANONICAL_NAME']);
}

if ($_REQUEST['zal'] == 1 && ($newId = (int)$_REQUEST['id'])) {
    $name = session_id().'_'.$newId;
    $resNews = CIBlockElement::GetList(
        false,
        ['IBLOCK_ID'=>IBLOCK_ZALOB, 'ACTIVE'=>'Y', 'NAME'=>$name],
        false,
        ['nTopCount' => 1],
        ['ID']
    );
    if ($resNews->SelectedRowsCount()) {
        $result = GetMessage('ISSET');
    } else {
        if ($userId = $USER->GetId()) {
            $rsUser = CUser::GetByID($userId);
            $arUser = $rsUser->Fetch();
            $propUser = $userId.', '.$arUser['LOGIN'].', '.$arUser['LAST_NAME'].' '.$arUser['NAME'].' '.$arUser['SECOND_NAME'];
        } else {
            $propUser = GetMessage('NOT_AUTH');
        }

        $el = new CIBlockElement;
        if ($newZalob = $el->Add([
            'IBLOCK_ID'=>IBLOCK_ZALOB,
            'NAME' => $name,
            'ACTIVE_FROM' => date('d.m.Y H:i:s', time()),
            'PROPERTY_VALUES' => [
                'USER' => $propUser,
                'NEW' => $newId
            ]
        ])) {
            $result = GetMessage('FINISH').$newZalob;
        } else {
            $result = GetMessage('ERROR');
        }
    }

    if (isset($_REQUEST['ajax'])) {
        $APPLICATION->RestartBuffer();
        die($result);
    } else {
        ?>
        <script>
            $(function (){
                $('#result_zalob').html('<?=$result?>').show();
            });
        </script>
        <?
    }
}