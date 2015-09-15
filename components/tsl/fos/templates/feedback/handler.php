<? 
require_once($_SERVER["DOCUMENT_ROOT"].'/bitrix/modules/main/include/prolog_before.php');
CModule::IncludeModule('iblock');
session_start();
$arProps = $_SESSION['PROPERTIES'];
$arParams = $_SESSION['PARAMS'];
foreach ($arProps as $key => $Prop) {
	$propertyValues[$Prop['CODE']] = $_POST[$Prop['CODE']];
}

$el = new CIblockElement;
$record = array(
    "NAME" => '#1',
    "IBLOCK_ID" => $_SESSION['IBLOCK_ID'],
    "PROPERTY_VALUES" => $propertyValues
);
$recordId = $el->Add($record);
$result = $el->Update($recordId, Array("NAME"=>"Заявка #".$recordId));
if($result){
	ob_start();?>
	<h3 style="text-align:center">Пользователь заполнил заявку</h3>
	<table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-collapse:collapse; border: none; margin: 40px 20px;">
	<?foreach ($arProps as $key => $Prop) {
		if ( in_array($Prop["CODE"], $arParams["SHOW_FIELDS"]) ){?>
	    <tr>
	        <td style="padding:5px; width:40%; color:#999;"><?=$Prop["NAME"]?>:</td>
	        <td style="padding:5px;"><?=$propertyValues[$Prop['CODE']]?></td>
	    </tr>
	    <?}?>
	<?}?>
	</table>

	<?$message = ob_get_clean();

	$arEventFields = array(
	    "TEXT" => $message,
	    "FORM_NAME" => $arParams["FORM_NAME"]
	);
	$eventId = CEvent::Send("TSL_REQUEST", SITE_ID, $arEventFields);
	if ($eventId){
		fb_showResult(array(
			'success' => true
		));
	}else{
		fb_showError('Заявка не отправилась!');
	}
}else{
	fb_showError('Заявка не сохранилась!');
}


function fb_showResult($result){
	header('Content-type: text/plain; charset=utf-8');
	header('Cache-Control: no-cache, must-revalidate');
	echo json_encode($result);
	exit;
}

function fb_showError($error){
	fb_showResult(array(
		'success' => false,
		'data' => array(
			'error' => $error
		)
	));
}
?>