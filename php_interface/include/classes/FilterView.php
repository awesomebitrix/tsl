<?
class TSL_FilterView{
	private $arItems = array();
	private $arRequest = array();

	function __construct($arItems, $get) {
		$this->arItems = $arItems;
		$this->arRequest = $get;
	}

	public function get_filter($action, $method){?>
		<form action="<?=$action?>" method="<?=$method?>">
		<?foreach ($this->arItems as $key => $Item) {
			echo $Item["NAME"];
			$this->get_view($Item["FILTER_HINT"], $Item);
		}?>
		<input type="submit" value="Найти">
		</form>
	<?}

	function get_view($type, $arItem){
		switch ($type) {
			case 'checkbox':
				$this->get_checkbox_view($arItem);
				break;
			case 'radio':
				$this->get_radio_view($arItem);
				break;
			case 'select':
				$this->get_select_view($arItem);
				break;
			case 'daterange':
				$this->get_daterange_view($arItem);
				break;
			case 'range':
				$this->get_range_view($arItem);
				break;
			
			default:
				# code...
				break;
		}
	}

	private function get_checkbox_view($arItem){?>
		<div>
		<?foreach($arItem["VALUES"] as $val => $arInner):?>
			<label for="<?=$arItem["CODE"]?>">
			<?=$arInner["VALUE"]?><input type="checkbox" value="<?=TSL_Helper::GetListPropInfo($arItem["IBLOCK_ID"], $arInner["URL_ID"], "ID");?>" name="<?=$arItem["CODE"]?>[]"  <? echo $arInner["CHECKED"]? 'checked="checked"': '' ?>/>
			</label>
		<?endforeach;?>
		</div>
		<br>
	<?}


	private function get_select_view($arItem){?>
		<select name="<?=$arItem["CODE"]?>">
		<?foreach($arItem["VALUES"] as $val => $arInner):?>
			<option value="<?=$arInner["URL_ID"]?>"  <? echo $arInner["SELECTED"]? 'selected="selected"': '' ?>><?=$arInner["VALUE"]?></option>
		<?endforeach;?>
		</select>
		<br>
	<?}

	private function get_daterange_view($arItem){?>
		От <input type="text" name="<?=$arItem["CODE"]?>_FROM" value="<?=$arItem["REQUEST"]["FROM"]?>" > До <input type="text" name="<?=$arItem["CODE"]?>_TO" value="<?=$arItem["REQUEST"]["TO"]?>">
		<br>
	<?}

	private function get_range_view($arItem){
		if (!empty($arItem["REQUEST"]["MIN"])){
			$minValue = $arItem["REQUEST"]["MIN"];
		}else{
			$minValue = $arItem["VALUES"]["MIN"];
		}
		if (!empty($arItem["REQUEST"]["MAX"])){
			$maxValue = $arItem["REQUEST"]["MAX"];
		}else{
			$maxValue = $arItem["VALUES"]["MAX"];
		}?>
		От <input type="text"  value="<?=$minValue?>" name="<?=$arItem["CODE"]?>_MIN"> До <input type="text" value="<?=$maxValue?>" name="<?=$arItem["CODE"]?>_MAX">
		<br>
	<?}

}//class

?>
