<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$arComponentParameters = array(
	"PARAMETERS" => array(
		"PRODUCTS_IBLOCK_ID" => array(
			"NAME" => GetMessage("SIMPLECOMP_EXAM2_CAT_IBLOCK_ID"),
			"TYPE" => "STRING"
		),
        "CLASSIF_IBLOCK_ID" => array(
            "NAME" => GetMessage("NAME_CLASSIF_IBLOCK_ID"),
            "TYPE" => "STRING"
        ),
        "DETAIL_TEMPLATE" => array(
            "NAME" => GetMessage("NAME_DETAIL_TEMPLATE"),
            "TYPE" => "STRING"
        ),
        "PROPERTY_CODE" => array(
            "NAME" => GetMessage("NAME_PROPERTY_CODE"),
            "TYPE" => "STRING"
        ),
        "CACHE_TIME" => ["DEFAULT" => 3600]
	),
);