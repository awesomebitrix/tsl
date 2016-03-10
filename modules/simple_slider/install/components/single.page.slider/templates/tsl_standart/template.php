<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
//echo "<pre>";print_r($arResult);echo "</pre>";
?>
			<section class="slider">
				<div class="slider__nav"></div>
				<div class="slider__container">
					<? foreach ($arResult["ITEMS"] as $arItem){?>
                    <?
						$this->AddEditAction($arItem["ID"], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT"));                    
					?>
					
			<? if($arParam["SHOW_RESIZED"]!="Y"){?>
                    <div id="<? echo $this->GetEditAreaId($arItem['ID'])?>" class="slider__slide" data-navname="<?=$arItem["NAME"]?>" style="background-image: url('<?=$arItem["PICTURES"]["ORIGINAL"]?>');">
            <? } else {?>        
                    <div id="<? echo $this->GetEditAreaId($arItem["ID"])?>" class="slider__slide" data-navname="<?=$arItem["NAME"]?>" style="background-image: url('<?=$arItem["PICTURES"]["RESIZED"]["src"]?>');">
            <? }?>
                    
						<div class="slider__text">
							<h2><?=$arItem["HEADER"]?></h2>
							<div class="slider__description">
								<?=$arItem["SUBHEADER"]?>
							</div>
								<?=$arItem["TEXT"]?>
      							<a href="<?=$arItem["BUTTON_LINK"]?>" class="slider__link button button--orange"><?=$arItem["BUTTON_TEXT"]?></a>
						</div>
					</div>
                    <? }?>                        

			</section><!-- section slider -->
