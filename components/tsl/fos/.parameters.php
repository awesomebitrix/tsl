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

use Bitrix\Main\Loader;
use Bitrix\Currency;
use Bitrix\Iblock;

if (!Loader::includeModule("iblock"))
	return;

$boolCatalog = Loader::includeModule("catalog");

$iblockExists = (!empty($arCurrentValues['IBLOCK_ID']) && (int)$arCurrentValues['IBLOCK_ID'] > 0);

$arProperty = array();
if ($iblockExists){
	$arOrder = Array("SORT"=>"ASC");
	$arFilter = Array();
	$res = CIBlock::GetProperties($arCurrentValues['IBLOCK_ID'], $arOrder, $arFilter);
	while ($property = $res->Fetch()){
		$propertyCode = (string)$property['CODE'];
			if ($propertyCode == '')
				$propertyCode = $property['ID'];
			$propertyName = $property['NAME'].' ['.$propertyCode.']';

			$arProperty[$propertyCode] = $propertyName;
	}
}



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
			"FORM_NAME" => array(
				"PARENT" => "PARAMS",
				"NAME" => GetMessage("FORM_NAME"),
				"TYPE" => "TEXT",
			),
			"BTN" => array(
				"PARENT" => "PARAMS",
				"NAME" => GetMessage("BTN"),
				"TYPE" => "TEXT",
			),
			"SHOW_FIELDS" => array(
				"PARENT" => "PARAMS",
				"NAME" => GetMessage("SHOW_FIELDS"),
				"TYPE" => "LIST",
				"MULTIPLE" => "Y",
				"VALUES" => $arProperty,
				"ADDITIONAL_VALUES" => "Y",
			),
	),
);

?>