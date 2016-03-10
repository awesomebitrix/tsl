<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
 
$arComponentDescription = array(
    "NAME" => 'Пустой компонент',
    "DESCRIPTION" => 'Для выполнения произвольного кода',
	'ICON' => '/images/icon.gif',
    "CACHE_PATH" => "Y",
	"PATH" => array(
		"ID" => "tsl",
		"CHILD" => array(
			"ID" => "tsl_base",
			"NAME" => "Базовые компоненты"
			)
		)
);
?>