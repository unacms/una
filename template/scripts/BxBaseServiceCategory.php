<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

/**
 * Services for category objects functionality - @see BxDolCategory
 */
class BxBaseServiceCategory extends BxDol
{
    /**
     * Get categories list
     */
    public function serviceCategoriesList ($sObject, $aParams = array())
    {
    	$bShowEmpty = isset($aParams['show_empty']) ? (bool)$aParams['show_empty'] : false;
    	$bShowEmptyCategories = isset($aParams['show_empty_categories']) ? (bool)$aParams['show_empty_categories'] : false;

		$sResult = BxDolCategory::getObjectInstance($sObject)->getCategoriesList($bShowEmptyCategories);
		if(empty($sResult))
			return $bShowEmpty ? MsgBox(_t('_Empty')) : '';

        return $sResult;
    }
}

/** @} */
