<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arTemplateParameters = [
    'SET_SPECIALDATE' => [
        'NAME' => GetMessage('SET_SPECIALDATE'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ],
    'SET_IBLOCK_ID' => [
        'NAME' => GetMessage('IBLOCK_ID'),
        'TYPE' => 'STRING',
        'DEFAULT' => ''
    ],
    'SET_AJAX_ZALOB' => [
        'NAME' => GetMessage('NAME_SET_AJAX_ZALOB'),
        'TYPE' => 'CHECKBOX',
        'DEFAULT' => 'N'
    ]
];
