<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$arProps = $arResult["PROPERTIES"];
$randId = mt_rand();
?>
<script>
$(document).ready(function(){
  var objSendForm = new SendForm('<?=$templateFolder?>', '<?=$randId?>');
});
</script>

<form class="validate-form" id="form_<?=$randId?>">
  <?foreach ($arProps as $key => $Prop) {
    if ( in_array($Prop["CODE"], $arParams["SHOW_FIELDS"]) ){
    switch ($Prop["PROPERTY_TYPE"]) {
      case 'E':?>
        <span class="field_cover select_type">
          <select name="<?=$Prop["CODE"]?>">
          <?foreach ($Prop["LIST"] as $key2 => $Option) {?>
            <option data-id="<?=$Option["ID"]?>"  value="<?=$Option["ID"]?>"><?=$Option["NAME"]?></option>
          <?}?>
          </select>
        </span><br>
      <?break;

      case 'L':?>
        <span class="field_cover select_type">
          <select name="<?=$Prop["CODE"]?>">
          <?foreach ($Prop["LIST"] as $key2 => $Option) {?>
            <option data-id="<?=$Option["ID"]?>" data-xml_id="<?=$Option["XML_ID"]?>" value="<?=$Option["ID"]?>"><?=$Option["VALUE"]?></option>
          <?}?>
          </select>
        </span><br>
      <?break;

      case 'S':?>
      <?if ($Prop["USER_TYPE"] == "HTML"){?>
          <textarea rows="8" cols="40" name="<?=$Prop["CODE"]?>" value="<?=$Prop["DEFAULT_VALUE"]?>" placeholder="<?=$Prop["NAME"]?>" <?=($Prop["IS_REQUIRED"] == "Y" ? 'required' : '')?>></textarea>
      <?}else{?>
          <?if ($Prop["HINT"] === "hidden"){?>
            <?if (!empty($arParams["ADD_PARAM"][$Prop["CODE"]])){?>
              <input type="<?=(!empty($Prop["HINT"]) ? $Prop["HINT"] : 'text')?>" name="<?=$Prop["CODE"]?>" value="<?=$arParams["ADD_PARAM"][$Prop["CODE"]]?>">
            <?}else{?>
              <input type="<?=(!empty($Prop["HINT"]) ? $Prop["HINT"] : 'text')?>" name="<?=$Prop["CODE"]?>" value="<?=$arParams["FORM_NAME"]?>">
            <?}?>
          <?}else{?>
            <input type="<?=(!empty($Prop["HINT"]) ? $Prop["HINT"] : 'text')?>" name="<?=$Prop["CODE"]?>" value="<?=$Prop["DEFAULT_VALUE"]?>" placeholder="<?=$Prop["NAME"]?>" <?=($Prop["IS_REQUIRED"] == "Y" ? 'required' : '')?>>
          <?}?>
      <?}?>
      <?break;
      
      default:?>
        <input type="<?=(!empty($Prop["HINT"]) ? $Prop["HINT"] : 'text')?>" name="<?=$Prop["CODE"]?>" value="<?=$Prop["DEFAULT_VALUE"]?>" placeholder="<?=$Prop["NAME"]?>" <?=($Prop["IS_REQUIRED"] == "Y" ? 'required' : '')?>>
      <?break;
    }
    }//if
}//foreach

?>
<div class="btn btn-message btn_cover">
  <button type="submit" class="now btn red"><?=$arParams["BTN"]?></button>
</div>


</form>




