<?
AddEventHandler("iblock", "OnAfterIBlockElementAdd", Array("ObjectsPropertiesHandler", "ObjectAddUpdate"));
AddEventHandler("iblock", "OnAfterIBlockElementUpdate", Array("ObjectsPropertiesHandler", "ObjectAddUpdate"));
AddEventHandler("iblock", "OnBeforeIBlockElementDelete", Array("ObjectsPropertiesHandler", "ObjectDelete"));
AddEventHandler("iblock", "OnStartIBlockElementUpdate", Array("ObjectsPropertiesHandler", "ObjectStartUpdate"));

class ObjectsPropertiesHandler{
 
function ObjectAddUpdate(&$arFields, $ID = 0){

$SECTION_ID = array_shift($arFields["IBLOCK_SECTION"]);
$IBLOCK_ID_ROOM = TSL_Helper::GetIblockIdByCode('tsl_room');
$IBLOCK_ID_NEWBUILDINGS = TSL_Helper::GetIblockIdByCode('tsl_newbuildings');
//применяем действия только к нужному нам инфоблоку и элементу у которого есть раздел
if ( ($arFields["IBLOCK_ID"] == $IBLOCK_ID_ROOM) && (!empty($SECTION_ID)) ){

	//Найдем мин и макс в разделе у квартир
	$arMinMaxPrices = self::GetMinMax($IBLOCK_ID_ROOM, $SECTION_ID, "R_PRICE", $ID);
	$arMinMaxArea = self::GetMinMax($IBLOCK_ID_ROOM, $SECTION_ID, "R_AREA", $ID);
	//Найдем id проекта(новостройка) у которого выбранна секция соответствующая секции с квартирой которую мы изменяем
	$res = CIBlockElement::GetList(
	 $arOrder = Array("SORT"=>"ASC"),
	 $rFilter = Array("IBLOCK_ID"=>$IBLOCK_ID_NEWBUILDINGS, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y", "PROPERTY_NB_ROOMS_SECTION"=>$SECTION_ID),//
	 $arGroupBy = false,
	 $arNavStartParams = false,
	 $arSelectFields = Array("ID", "IBLOCK_ID", "PROPERTY_NB_ROOMS_SECTION")
	);
	while($ob = $res->GetNextElement()){
		 $arInnerFields = $ob->GetFields();
		 $newBuildingsID = $arInnerFields["ID"];//искомое значение
	}


	//Изменяем значения максимума и минимума у проекта(новостройки)
	$Props = array();
	$Props["NB_MIN_PRICE"] = $arMinMaxPrices["MIN"];
	$Props["NB_MAX_PRICE"] = $arMinMaxPrices["MAX"];

	$Props["NB_MIN_AREA"] = $arMinMaxArea["MIN"];
	$Props["NB_MAX_AREA"] = $arMinMaxArea["MAX"];


	CIBlockElement::SetPropertyValuesEx($newBuildingsID, false, $Props);

}//if
}

function GetMinMax($IBLOCK_ID_ROOM, $SECTION_ID, $PROP_NAME, $ID){
	$res = CIBlockElement::GetList(
	 $arOrder = Array("PROPERTY_".$PROP_NAME=>"ASC"),
	 $rFilter = Array("IBLOCK_ID"=>$IBLOCK_ID_ROOM, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y", "SECTION_ID"=>$SECTION_ID),
	 $arGroupBy = false,
	 $arNavStartParams = false,
	 $arSelectFields = Array("ID", "IBLOCK_ID", "PROPERTY_".$PROP_NAME)
	);
	while($ob = $res->GetNextElement()){
	 $arInnerFields = $ob->GetFields();
	 if ($arInnerFields["ID"] != $ID){
	 	$arMinMax[] = $arInnerFields["PROPERTY_".$PROP_NAME."_VALUE"];
	 } 
	}
	$arMinMaxResult["MIN"]= array_shift($arMinMax);//искомое значение
	$arMinMaxResult["MAX"] = array_pop($arMinMax);//искомое значение
	return $arMinMaxResult;
}

function ObjectDelete($ID){

	$IBLOCK_ID_ROOM = TSL_Helper::GetIblockIdByCode('tsl_room');
	$IBLOCK_ID_NEWBUILDINGS = TSL_Helper::GetIblockIdByCode('tsl_newbuildings');
	//Найдем id раздела
	$res = CIBlockElement::GetList(
	 $arOrder = Array("SORT"=>"ASC"),
	 $rFilter = Array("IBLOCK_ID"=>$IBLOCK_ID_ROOM, "ID"=>$ID, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y"),
	 $arGroupBy = false,
	 $arNavStartParams = false,
	 $arSelectFields = Array("ID", "IBLOCK_ID", "IBLOCK_SECTION_ID")
	);
	while($ob = $res->GetNextElement()){
		$arInnerFields = $ob->GetFields();
		$SECTION_ID = $arInnerFields["IBLOCK_SECTION_ID"];
		$IBLOCK_ID = $arInnerFields["IBLOCK_ID"];
	}
	//применяем действия только к нужному нам инфоблоку и элементу у которого есть раздел
	if ( ($IBLOCK_ID == $IBLOCK_ID_ROOM) && (!empty($SECTION_ID)) ){
		$arFields["IBLOCK_SECTION"][0] = $SECTION_ID;
		$arFields["IBLOCK_ID"] = $IBLOCK_ID;
		self::ObjectAddUpdate($arFields, $ID);
	}

}

//функция пересчета параметров при перемещении элемента из одного раздела в другой
function ObjectStartUpdate($arFields){
	$IBLOCK_ID_ROOM = TSL_Helper::GetIblockIdByCode('tsl_room');
	$ID = $arFields["ID"];
	$SECTION_ID_TO = array_shift($arFields["IBLOCK_SECTION"]);//раздел в который перемещасется элемент
	//Найдем id раздела
	$res = CIBlockElement::GetList(
	 $arOrder = Array("SORT"=>"ASC"),
	 $rFilter = Array("IBLOCK_ID"=>$IBLOCK_ID_ROOM, "ID"=>$ID, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y"),
	 $arGroupBy = false,
	 $arNavStartParams = false,
	 $arSelectFields = Array("ID", "IBLOCK_ID", "IBLOCK_SECTION_ID")
	);
	while($ob = $res->GetNextElement()){
		$arInnerFields = $ob->GetFields(); 
		$SECTION_ID_FROM = $arInnerFields["IBLOCK_SECTION_ID"];//раздел из которого перемещается элемент
		$IBLOCK_ID = $arInnerFields["IBLOCK_ID"];
	}
	if ( ($IBLOCK_ID == $IBLOCK_ID_ROOM) && (!empty($SECTION_ID_FROM)) ){
		$arInnerFields["IBLOCK_SECTION"][0] = $SECTION_ID_FROM;
		$arInnerFields["IBLOCK_ID"] = $IBLOCK_ID;
		if ($SECTION_ID_FROM != $SECTION_ID_TO){
			self::ObjectAddUpdate($arInnerFields, $ID);
		}
	}
	

}



}//class

// ob_start();
// print_r($IBLOCK_ID);
// echo $prop_fields["ID"]." - ".$prop_fields["NAME"]."<br>";
// $message = ob_get_clean();
// AddMessage2Log($message);  
?>