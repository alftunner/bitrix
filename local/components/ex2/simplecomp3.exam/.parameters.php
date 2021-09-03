<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$arComponentParameters = array(
	"PARAMETERS" => array(
		"NEWS_IBLOCK_ID" => array(
			"NAME" => GetMessage("NAME_NEWS_IBLOCK_ID"),
			"TYPE" => "STRING"
		),
        "IBLOCK_PROPERTY" => array(
            "NAME" => GetMessage("NAME_IBLOCK_PROPERTY"),
            "TYPE" => "STRING"
        ),
        "USER_PROPERTY" => array(
            "NAME" => GetMessage("NAME_USER_PROPERTY"),
            "TYPE" => "STRING"
        ),
        "CACHE_TIME" => ["DEFAULT" => 3600]
	),
);