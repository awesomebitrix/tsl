<?
class CSliderUtils
{
	public static function getIblockIdByCode($iblockCode)
	{
		$id = null;
		$res = CIBlock::GetList(array(), array('CODE' => $code), false);
		if ($ob = $res->GetNext()) {
			$id = (int) $ob['ID'];
		}
		return $id;		
	}
}
?>