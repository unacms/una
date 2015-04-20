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
    public function serviceCategoriesList ($sObject, $bDisplayEmptyCats = true)
    {
        return BxTemplCategory::getObjectInstance($sObject)->getCategoriesList($bDisplayEmptyCats);
    }
}

/** @} */
