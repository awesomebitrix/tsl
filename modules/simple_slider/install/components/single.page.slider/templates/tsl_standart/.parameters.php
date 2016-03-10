<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();


$arTemplateParameters['SHOW_RESIZED'] = array(
	"PARENT" => "PARAMS",
	"NAME" => 'Показывать с измененными размерами',
	"TYPE" => "LIST",
	"VALUES" => Array(
		"Y"=>"Да", 
		"N"=>"Нет"
		),
	"DEFAULT" => 'Y',
);

$arTemplateParameters['PICTURE_WIDTH'] = array(
	"PARENT" => "PARAMS",
	"NAME" => 'Ширина баннера',
	"TYPE" => "STRING",
	"DEFAULT" => "860"
);

$arTemplateParameters['PICTURE_HEIGHT'] = array(
	"PARENT" => "PARAMS",
	"NAME" => 'Высота баннера',
	"TYPE" => "STRING",
	"DEFAULT" => "420"
);
$arTemplateParameters['RESIZE_METHOD'] = array(
	"PARENT" => "PARAMS",
	"NAME" => "Метод ресайза",
	"TYPE" => "LIST",
	"VALUES" => Array(
		"BX_RESIZE_IMAGE_EXACT"=>"BX_RESIZE_IMAGE_EXACT", 
		"BX_RESIZE_IMAGE_PROPORTIONAL"=>"BX_RESIZE_IMAGE_PROPORTIONAL", 
		"BX_RESIZE_IMAGE_PROPORTIONAL_ALT"=>"BX_RESIZE_IMAGE_PROPORTIONAL_ALT", 				
		),
	"DEFAULT" => 'BX_RESIZE_IMAGE_EXACT',
);


?>