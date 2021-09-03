<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Простой компонент");
?><?$APPLICATION->IncludeComponent(
	"ex2:simplecomp2.exam", 
	".default", 
	array(
		"COMPONENT_TEMPLATE" => ".default",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "3600",
		"PRODUCTS_IBLOCK_ID" => "2",
		"CLASSIF_IBLOCK_ID" => "7",
		"DETAIL_TEMPLATE" => "/products/#SECTION_ID#/#ELEMENT_ID#/",
		"PROPERTY_CODE" => "FIRMS"
	),
	false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>