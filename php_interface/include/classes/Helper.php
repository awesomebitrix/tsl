<?
CModule::IncludeModule("iblock");
class TSL_Helper{

	//Преобразуем массив множественных свойств в строку
	function arrPropToStr($array, $separator = ','){
		$count = count($array);
		$i = 0; foreach ($array as $key => $value) { $i++;
			$string .= $value;
			if ($i != $count){
				$string .=  $separator.' ';
			}
		}
		return $string;
	}
	

	//получает все свойства свойства типа список 
	function GetListPropInfo($iblock_id, $xml_id, $prop_name){
		$property_enums = CIBlockPropertyEnum::GetList(Array("SORT"=>"ASC"), Array("IBLOCK_ID"=>$iblock_id, "XML_ID"=>$xml_id));
		if($enum_fields = $property_enums->GetNext());
		return $enum_fields[$prop_name];
	}

	//получаем IBLOCK_ID по символьному коду
	function GetIblockIdByCode($code)
	{
		$id = null;
		$res = CIBlock::GetList(array(), array('CODE' => $code), false);
		if ($ob = $res->GetNext()) {
			$id = (int) $ob['ID'];
		}
		return $id;
	}

//возвращает id инфоблока по его коду
	function GetElemIdByCode($code, $iblock_id)
	{
		$id = null;
		$res = CIBlockElement::GetList(array(), array('IBLOCK_id'=>$iblock_id,'CODE' => $code), false);
		if ($ob = $res->GetNext()) {
			$id = (int) $ob['ID'];
		}
		return $id;
	}

	// возвращает id свойства
	function GetPropIdByCode($code, $iblock_id){
	$properties = CIBlockProperty::GetList(Array("sort"=>"asc", "name"=>"asc"), Array("ACTIVE"=>"Y", "IBLOCK_ID"=>$iblock_id, "CODE"=>$code));
		while ($prop_fields = $properties->GetNext()){
			return $prop_fields["ID"];
		}
	}

	// поиск минимума и максимума у элементов инфоблока
	function GetMinMax($IBLOCK_ID, $PROP_NAME){
		if (!is_numeric($IBLOCK_ID)){
			$IBLOCK_ID = self::GetIblockIdByCode($IBLOCK_ID);
		}
		$res = CIBlockElement::GetList(
		 $arOrder = Array("PROPERTY_".$PROP_NAME=>"ASC"),
		 $rFilter = Array("IBLOCK_ID"=>$IBLOCK_ID, "ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y"),
		 $arGroupBy = false,
		 $arNavStartParams = false,
		 $arSelectFields = Array("ID", "IBLOCK_ID", "PROPERTY_".$PROP_NAME)
		);
		while($ob = $res->GetNextElement()){
		 $arInnerFields = $ob->GetFields();
		 	$arMinMax[] = $arInnerFields["PROPERTY_".$PROP_NAME."_VALUE"];
		}
		$arMinMaxResult["MIN"]= array_shift($arMinMax);//искомое значение
		$arMinMaxResult["MAX"] = array_pop($arMinMax);//искомое значение
		return $arMinMaxResult;
	}

}//class
?>