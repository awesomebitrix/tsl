<?

IncludeModuleLangFile(__FILE__);

global $APPLICATION;

$aMenu = 
	array(
			"parent_menu" => "global_menu_settings",
			"section" => "",
			"sort" => 10000,
			"text" => "Простой слайдер",
			"title" => "Простой слайдер",
			"icon" => "sys_menu_icon",
			"page_icon" => "sys_menu_icon",
			"items_id" => "simple_slider",
			"items" => Array(
			/*	array(
					"text" => GetMessage(""),
					"url" => "",
					"more_url" => Array(
						"",
						""
					),
					"title" => GetMessage("")
					),*/
				),
		);

	return $aMenu;
?>