<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?
//Подключаем модуль инфоблоки
if (!CModule::IncludeModule("iblock"))
	return;

//Получаем типы инфоблоков
$arIBlockTypes = CIBlockParameters::GetIBlockTypes();

$arIBlocks=array();
$db_iblock = CIBlock::GetList(array("SORT"=>"ASC"), array("SITE_ID"=>$_REQUEST["site"], "TYPE" => ($arCurrentValues["IBLOCK_TYPE"]!="-"?$arCurrentValues["IBLOCK_TYPE"]:"")));
while($arRes = $db_iblock->Fetch())
	$arIBlocks[$arRes["ID"]] = $arRes["NAME"];


//Создаем параметры для компоненты
$arComponentParameters = array(
	"GROUPS" => array(
	  "PARAMS" => array(
	     "NAME" => GetMessage("PARAMETRS")
		),
	),
	"PARAMETERS" => array(
			"IBLOCK_TYPE" => array(
				"PARENT" => "PARAMS",
				"NAME" => GetMessage("IBLOCK_TYPE"),
				"TYPE" => "LIST",
				"VALUES" => $arIBlockTypes,
				"DEFAULT" => "news",
				"REFRESH" => "Y",
			),
			"IBLOCK_ID" => array(
				"PARENT" => "PARAMS",
				"NAME" => GetMessage("IBLOCK_ID"),
				"TYPE" => "LIST",
				"VALUES" => $arIBlocks,
				"DEFAULT" => '',
				"ADDITIONAL_VALUES" => "Y",
				"REFRESH" => "Y",
			),
			"SECTION_ID" => array(
				"PARENT" => "PARAMS",
				"NAME" => GetMessage("SECTION_ID"),
				"TYPE" => "STRING"
			),
	),
);

?>