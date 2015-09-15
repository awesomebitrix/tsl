<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
//возвращает id инфоблока по его коду
if (!function_exists('GetIblockIdByCode')) {
	function GetIblockIdByCode($code)
	{
		$id = null;
		$res = CIBlock::GetList(array(), array('CODE' => $code), false);
		if ($ob = $res->GetNext()) {
			$id = (int) $ob['ID'];
		}
		return $id;
	}
}

$IBLOCK_ID = trim($arParams["IBLOCK_ID"]);
if (!is_numeric($IBLOCK_ID)){
	$IBLOCK_ID = GetIblockIdByCode($IBLOCK_ID);
}

//собираем данные свойст инфоблока
$arOrder = Array("SORT"=>"ASC");
$arFilter = Array();
$res = CIBlock::GetProperties($IBLOCK_ID, $arOrder, $arFilter);
while ($prop = $res->Fetch()){
	
	switch ($prop["PROPERTY_TYPE"]) {
		case 'L'://если поле типа список
			$arOrder2 = $arOrder;
			$arFilter2 = Array("PROPERTY_ID" => $prop["ID"]);
			$res2 = CIBlockPropertyEnum::GetList($arOrder2, $arFilter2);
			while ($list = $res2->Fetch()){
				$arList[] = $list;
			}
			$prop["LIST"] = $arList;
			break;
		case 'E'://если поле типа список
			$LINK_IBLOCK_ID = $prop["LINK_IBLOCK_ID"];
			$arOrder3 = Array("SORT"=>"ASC");
			$arFilter3 = Array("IBLOCK_ID"=>$LINK_IBLOCK_ID, "ACTIVE"=>"Y");
			$arGroupBy3 = false;
			$arNavStartParams3 = false;
			$arSelectFields3 = Array("ID", "IBLOCK_ID", "NAME");
			$res3 = CIBlockElement::GetList($arOrder3, $arFilter3, $arGroupBy3, $arNavStartParams3, $arSelectFields3);
			while($ob = $res3->GetNextElement()){ 
			 	$arFields = $ob->GetFields();  
				$prop["LIST"][] = $arFields;
			}

			break;

	}
	$arResult["PROPERTIES"][] = $prop;
}


session_start();
$_SESSION['IBLOCK_ID'] = $IBLOCK_ID;
$_SESSION['PROPERTIES'] = $arResult["PROPERTIES"];
$_SESSION['PARAMS'] = $arParams;

$this->IncludeComponentTemplate();
?>