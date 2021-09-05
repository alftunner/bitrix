<?php
const MAIL_TEMPLATE = 32;
const GROUP_ADMIN = 1;

function CheckUserCount() {
    $last_user_id = COption::GetOptionInt("main", "last_user_id", 0);
    $rsUsers = CUser::GetList(
        ($by="id"),
        ($order="desc"),
        ['>ID' => $last_user_id],
        ['FIELDS' => ['ID']]
    );
    if ($count_user = $rsUsers->SelectedRowsCount()) {
        $arUser = $rsUsers->Fetch();
        $new_last_id = $arUser['ID'];

        if ($time_check_user = COption::GetOptionInt("main", "time_check_user", 0)) {
            $days = round((time() - $time_check_user)/86400);
            if (!$days) {
                $days = 1;
            }
        } else {
            $days = 1;
        }

        if ($days == 1) {
            $days .= GetMessage('DAY_1');
        } elseif ($days > 4) {
            $days .= GetMessage('DAY_5');
        } else {
            $days .= GetMessage('DAY_2');
        }

        $arFields = [
            'COUNT' => $count_user,
            'DAYS' => $days
        ];
        $rsUsers = CUser::GetList(
            ($by="id"),
            ($order="desc"),
            ['GROUPS_ID' => GROUP_ADMIN],
            ['FIELDS' => ['ID', 'EMAIL']]
        );
        while ($arUser = $rsUsers->Fetch()) {
            $arFields['EMAIL'] = $arUser['EMAIL'];
            CEvent::SendImmediate('NEW_REGISTRATION', 's1', $arFields, 'N', MAIL_TEMPLATE);
        }
        COption::SetOptionInt("main", "last_user_id", $new_last_id);
    }
    COption::SetOptionInt("main", "time_check_user", time());
    return 'CheckUserCount()';
}