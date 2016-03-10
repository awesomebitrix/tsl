<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
//возвращает id инфоблока по его коду
if (!function_exists('GetIblockIdByCode'))
 {
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
if (!is_numeric($IBLOCK_ID))
{
	$IBLOCK_ID = GetIblockIdByCode($IBLOCK_ID);
}

$SECTION_ID = $arParams["SECTION_ID"];

if (!is_numeric($SECTION_ID))
	{
		$arFilter = Array('IBLOCK_ID'=>	$IBLOCK_ID, 'GLOBAL_ACTIVE'=>'Y', "CODE"=>$SECTION_ID);//фильтр без привязки
		$db_list = CIBlockSection::GetList(Array("NAME"=>"ASC"), $arFilter, true, array("ID"));
		while($ar_result = $db_list->GetNext())
			  {
				  $arResult = $ar_result;
			  }
		$SECTION_ID = $arResult["ID"];
	}

//все свойства инфоблока
$properties = CIBlockProperty::GetList(Array("sort"=>"asc", "name"=>"asc"), Array("ACTIVE"=>"Y", "IBLOCK_ID"=>$IBLOCK_ID));
while ($prop_fields = $properties->GetNext())
	{

		$arPropCodes[] = $prop_fields["CODE"];
	}

$arSelect = Array("ID", "NAME", "CODE");
foreach($arPropCodes as $PropCode)
	{
		$arSelect[] = "PROPERTY_".$PropCode;
	}

$arFilter = Array("IBLOCK_ID"=>$IBLOCK_ID, "SECTION_ID"=>$SECTION_ID);
$res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilter, false,  false, $arSelect);
while($ob = $res->GetNextElement())
	{
		$arRes[] = $ob->GetFields();
	}


foreach ($arRes as $key=>$arItem)
	{
		foreach($arItem as $subkey => $field)
			{
				$subkeyform = str_replace("PROPERTY_","",$subkey);
				if(is_array($field) && substr_count($subkeyform,"~") && substr_count($subkeyform, "_VALUE_ID")==0)
					{
						
						$subkeyform = str_replace("_VALUE_ID","",$subkeyform);				
						$subkeyform = str_replace("_VALUE","",$subkeyform);

						$subkeyform = str_replace("~","",$subkeyform);
						$arResult["ITEMS"][$key][$subkeyform] = $field["TEXT"];
					}
				elseif(substr_count($subkeyform, "~")==0 && substr_count($subkeyform, "_VALUE_ID")==0)
					{
						$subkeyform = str_replace("_VALUE","",$subkeyform);
						$subkeyform = str_replace("~","",$subkeyform);
						$arResult["ITEMS"][$key][$subkeyform] = $field;
					}	
			}
	}
//картинки	
for($i=0;$i<count($arResult["ITEMS"]);$i++)	
	{
		$pic = CFile::GetPath($arResult["ITEMS"][$i]["PICTURE"]);
		$arResult["ITEMS"][$i]["PICTURES"]["ORIGINAL"] = $pic;
		
		//ресайз
		if($arParams["PICTURE_WIDTH"] || $arParams["PICTURE_HEIGHT"])
			{
				$ResizeWidth = intval($arParams["PICTURE_WIDTH"]);
				$ResizeHeight = intval($arParams["PICTURE_HEIGHT"]);
				$ResizeMethod = $arParams["RESIZE_METHOD"];

				$arResult["ITEMS"][$i]["PICTURES"]["RESIZED"] = CFile::ResizeImageGet(
				   $arResult["ITEMS"][$i]["PICTURE"],
				   array(
					  'width'=>$ResizeWidth,
					  'height'=>$ResizeHeight
					   ), 
				   $ResizeMethod,
				   Array(
					  "name" => "sharpen", 
					  "precision" => 0
					   )
					);
				}


/*		$arButtons = CIBlock::GetPanelButtons(
			$arParams["IBLOCK_ID"],
			$arResult["ITEMS"][$i]["ID"],
			$arResult["ID"],
			array()
		);
		$arResult["ITEMS"][$i]["EDIT_LINK"] = $arButtons["edit"]["edit_element"]["ACTION_URL"];
		$arResult["ITEMS"][$i]["DELETE_LINK"] = $arButtons["edit"]["delete_element"]["ACTION_URL"];*/
		$arButtons = CIBlock::GetPanelButtons(
			intval($arParams["IBLOCK_ID"]),
			intval($arResult["ITEMS"][$i]["ID"]),
			$arResult["ID"],
			array("SECTION_BUTTONS"=>false, "SESSID"=>false, "CATALOG"=>false)
		);



		$arResult["ITEMS"][$i]["EDIT_LINK"] = $arButtons["edit"]["edit_element"]["ACTION_URL"];
		$arResult["ITEMS"][$i]["DELETE_LINK"] = $arButtons["edit"]["delete_element"]["ACTION_URL"];
		
//		echo "<pre>"; print_r($arButtons); echo "</pre>";		
	}
//echo "<pre>"; print_r($arResult["ITEMS"]); echo "</pre>";

$this->IncludeComponentTemplate();
?>