<?
if(!CModule::IncludeModule("iblock"))
    return;
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$arComponentParameters = array(
	"PARAMETERS" => array(
		"PRODUCTS_IBLOCK_ID" => array(
			"NAME" => GetMessage("SIMPLECOMP_EXAM2_CAT_IBLOCK_ID"),
			"TYPE" => "STRING"
		),
        "NEWS_IBLOCK_ID" => array(
            "NAME" => GetMessage("NAME_NEWS_IBLOCK_ID"),
            "TYPE" => "STRING"
        ),
        "PROPERTY_CODE" => array(
            "NAME" => GetMessage("NAME_PROPERTY_CODE"),
            "TYPE" => "STRING"
        ),
        "DETAIL_TEMPLATE" => array(
            "NAME" => GetMessage("NAME_DETAIL_TEMPLATE"),
            "TYPE" => "STRING"
        ),
        "NEWS_COUNT" => array(
            "NAME" => GetMessage("NAME_NEWS_COUNT"),
            "TYPE" => "STRING",
            "DEFAULT" => "2"
        ),
        "CACHE_TIME" => ["DEFAULT" => 3600]
	),
);

CIBlockParameters::AddPagerSettings(
    $arComponentParameters,
    GetMessage("T_IBLOCK_DESC_PAGER_NEWS"), //$pager_title
    true, //$bDescNumbering
    true, //$bShowAllParam
    true, //$bBaseLink
    $arCurrentValues["PAGER_BASE_LINK_ENABLE"]==="Y" //$bBaseLinkEnabled
);