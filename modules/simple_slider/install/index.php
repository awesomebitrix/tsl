<?
/* dev semartyom@mail.ru */
use Bitrix\Main\Application;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Text\String;
global $MESS;
$PathInstall = str_replace("\\", "/", __FILE__);
$PathInstall = substr($PathInstall, 0, strlen($PathInstall)-strlen("/index.php"));
IncludeModuleLangFile($PathInstall."/install.php");
 
if(class_exists("simple_slider")) return;
 
Class simple_slider extends CModule
	{
		var $MODULE_ID = "simple_slider";
		var $MODULE_VERSION;
		var $MODULE_VERSION_DATE;
		var $MODULE_NAME;
		var $MODULE_DESCRIPTION;
		var $MODULE_GROUP_RIGHTS = "Y";
	 
		function simple_slider()
	{
		$arModuleVersion = array();

		$path = str_replace("\\", "/", __FILE__);
		$path = substr($path, 0, strlen($path) - strlen("/index.php"));
		include($path."/version.php");

		if (is_array($arModuleVersion) && array_key_exists("VERSION", $arModuleVersion))
		{
			$this->MODULE_VERSION = $arModuleVersion["VERSION"];
			$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
		}
		else
		{
			$this->MODULE_VERSION = "0.0.1";
			$this->MODULE_VERSION_DATE = "2015-11-2 23:05:00";
		}

		$this->MODULE_NAME = GetMessage("SLIDER_MODULE_NAME");
		$this->MODULE_DESCRIPTION = GetMessage("SLIDER_MODULE_DESCRIPTION");
	}
			
			
			
	 
		function DoInstall()
			{
				global $DOCUMENT_ROOT, $APPLICATION;
				$this->InstallFiles();
				$this->InstallDb();
				RegisterModule("simple_slider");
				$APPLICATION->IncludeAdminFile("Установка модуля simple_slider", $DOCUMENT_ROOT."/bitrix/modules/simple_slider/install/step1.php");        
			}
		 
		function InstallFiles($arParams = array())
			{
				CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/simple_slider/install/components", $_SERVER["DOCUMENT_ROOT"]."/bitrix/components/simple_slider/", true, true);
				return true;
			}
		
		function InstallDb($arParams = array())
			{
				//install iblock type
				if(CModule::IncludeModule("iblock"))
				{
					global $DB;
					$arFields = Array(
						'ID'=>'simple_slider',
						'SECTIONS'=>'Y',
						'IN_RSS'=>'N',
						'SORT'=>10000,
						'LANG'=>Array(
							'en'=>Array(
								'NAME'=>'Slider',
								'SECTION_NAME'=>'Section',
								'ELEMENT_NAME'=>'Element'
								),
							'ru'=>Array(
								'NAME'=>'Слайдер',
								'SECTION_NAME'=>'Секция',
								'ELEMENT_NAME'=>'Элемент'
								)
							)
						);
					
					$obBlocktype = new CIBlockType;
					$DB->StartTransaction();
					$res = $obBlocktype->Add($arFields);
					if(!$res)
					{
					   $DB->Rollback();
					   echo 'Error: '.$obBlocktype->LAST_ERROR.'<br>';
					}
					else{
					   $DB->Commit();

						//install iblock from file
					
					$ABS_FILE_NAME = $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/simple_slider/install/xml/demo.xml";

					$WORK_DIR_NAME = substr($ABS_FILE_NAME, 0, strrpos($ABS_FILE_NAME, "/")+1);
                    
                    $obXMLFile = new CIBlockXMLFile;
                    // Удаляем результат предыдущей загрузки
                    $obXMLFile->DropTemporaryTables();
                    // Подготавливаем БД
                    if($obXMLFile->CreateTemporaryTables())
                    {
                        if($fp = fopen($ABS_FILE_NAME, "rb"))
                        {
                           // Чтение содержимого файла за один шаг
                           $obXMLFile->ReadXMLToDatabase($fp, $NS, 0);
                           fclose($fp);
                            // Индексируем загруженные данные для ускорения доступа
                            if(CIBlockXMLFile::IndexTemporaryTables()){
                                $obCatalog = new CIBlockCMLImport;
                             $obCatalog->InitEx($NS, array(
                                "files_dir" => $WORK_DIR_NAME
                             ));
                                $result = $obCatalog->ImportMetaData(1, "simple_slider", "s1");
                                if($result === true){
                                    $result = $obCatalog->ImportSections();
                                    $obCatalog->SectionsResort();
                                    $obCatalog->ReadCatalogData($_SESSION["BX_CML2_IMPORT"]["SECTION_MAP"], $_SESSION["BX_CML2_IMPORT"]["PRICES_MAP"]);
                                    $result = $obCatalog->ImportElements(time(), 0);
                                }
                            }
                        }
                    }							
					//setup element form
					
      //==========================//
      // Настраиваем форму        //
      // редактирования инфоблока //
      //==========================// 

		include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/simple_slider/classes/general/slider_utils.php");

      $IBLOCK_ID = CSliderUtils::getIblockIdByCode("slider"); // Номер инфоблока
	  
	  //получить id свойств экспортированного инфоблока.
	  $res = CIBlock::GetProperties($IBLOCK_ID, Array("ID"=>"asc"), Array());
	  while($element = $res->Fetch())
		{
		  $arProps[] = $element;
		}
/*		if($res_arr = $res->GetNext())
			{
			    $arProps[] = $res_arr;				
			}*/
	  
//	  echo "<pre>";	 print_r($arProps) ;echo "</pre>";
      
	      
      // Вкладки и свойства
      $arFormSettings = array(
         array(
            array("edit1", "Слайд"), // Название вкладки
            array("ACTIVE", "Активность"),
            array("SORT", "Сортировка"),			
            array("NAME", "*Название"), // Свойство со звездочкой - помечается как обязательное
            array("CODE", "*Символьный код"),
         ),
      );

		echo "<pre>";	 print_r($arFormSettings) ;echo "</pre>";

	  foreach($arProps as $arProp)
	  	{
			$arFormSettings[0][]=Array("PROPERTY_".$arProp["ID"] , $arProp["NAME"]);
		}
		echo "<pre>";	 print_r($arFormSettings) ;echo "</pre>";
		
		
      // Сериализация
      $arFormFields = array();
      foreach ($arFormSettings as $key => $arFormFields)
      {
         $arFormItems = array();
         foreach ($arFormFields as $strFormItem)
            $arFormItems[] = implode('--#--', $strFormItem);

         $arStrFields[] = implode('--,--', $arFormItems);
      }
      $arSettings = array("tabs" => implode('--;--', $arStrFields));

      // Применяем настройки для всех пользователей для данного инфоблока
      $rez = CUserOptions::SetOption("form", "form_element_".$IBLOCK_ID, $arSettings, $bCommon=true, $userId=false);
      if ($rez)
         echo "- Страница редактирования товара успешно настроена<br />";
      else
         echo "- Ошибка настройки страницы редактирования товара<br />";					
					
					
					//element form setup end
					
					
					
					   }//else
				}
				else{
						echo "Error";
					}
			}
			
			
		function UnInstallFiles()
			{
				DeleteDirFilesEx("/bitrix/components/simple_slider/");
				return true;
			}
		 
		function UnInstallDb()
			{
				global $DB;
				if(CModule::IncludeModule("iblock"))
				{
					//uninstall ib type
					$DB->StartTransaction();
					if(!CIBlockType::Delete('simple_slider'))
					{
						$DB->Rollback();
						echo 'Delete error!';
					}
					$DB->Commit();
				}
				else {
						echo 'Error!';					
					}
			}
		
		function DoUninstall()
			{
				global $DOCUMENT_ROOT, $APPLICATION;
				$this->UnInstallFiles();
				$this->UnInstallDb();
				Option::delete("simple_slider", "");		
				UnRegisterModule("simple_slider");
				$APPLICATION->IncludeAdminFile("Деинсталляция модуля simple_slider", $DOCUMENT_ROOT."/bitrix/modules/simple_slider/install/unstep1.php");
			}
	}
?>