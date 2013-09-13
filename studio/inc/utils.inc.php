<?php
/**
 * @package     Dolphin Core
 * @copyright   Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * @license     CC-BY - http://creativecommons.org/licenses/by/3.0/
 */
defined('BX_DOL') or die('hack attempt');


/*
 * Returns unique SID for communication with BoonEx Unity.
 * @see BxDolStudioStore  -> checkoutCart and BxDolStudioOAuth -> authorize
 */
function generateSid() {
	return md5(BX_DOL_URL_ROOT . '_' . BX_DOL_VERSION . '.' . BX_DOL_BUILD);
}

function bx_array_insert_before($aInsert, $aSource, $sKey) {
	return bx_array_insert($aInsert, $aSource, $sKey, 0);
}

function bx_array_insert_after($aInsert, $aSource, $sKey) {
	return bx_array_insert($aInsert, $aSource, $sKey, 1);
}

function bx_array_insert($aInsert, $aSource, $sKey, $iDirection = 1) {
	$iPosition = array_search($sKey, array_keys($aSource)) + $iDirection;

	if($iPosition == 0)
		return $aInsert + $aSource;

	if($iPosition == count($aSource))
		return $aSource + $aInsert;

	return array_slice($aSource, 0, $iPosition, true) + $aInsert + array_slice($aSource, $iPosition, NULL, true); 
}
/** @} */