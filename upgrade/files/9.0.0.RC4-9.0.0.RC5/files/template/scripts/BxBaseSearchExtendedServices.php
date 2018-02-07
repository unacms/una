<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * System services related to Extended Search.
 * 
 * @see BxDolSearchExtended
 */
class BxBaseSearchExtendedServices extends BxDol
{
    public function __construct()
    {
        parent::__construct();
    }

    public function serviceGetForm($aParams)
    {
        if(empty($aParams['object']))
            return '';

        $oSearch = BxDolSearchExtended::getObjectInstance($aParams['object']);
        if(!$oSearch || !$oSearch->isEnabled())
            return '';

        return $oSearch->getForm();
    }

    public function serviceGetResults($aParams)
    {
        if(empty($aParams['object']))
            return '';

        $oSearch = BxDolSearchExtended::getObjectInstance($aParams['object']);
        if(!$oSearch || !$oSearch->isEnabled())
            return '';

        $bShowEmpty = isset($aParams['show_empty']) && (bool)$aParams['show_empty'];

        $a = isset($aParams['cond']) && $aParams['cond'] ? $aParams['cond'] : array();
        $sResults = $oSearch->getResults($a, !empty($aParams['template']) ? $aParams['template'] : '', !empty($aParams['start']) ? $aParams['start'] : 0, !empty($aParams['per_page']) ? $aParams['per_page'] : 0);

        return !empty($sResults) ? $sResults : ($bShowEmpty ? MsgBox(_t('_Empty')) : ''); 
    }
}

/** @} */
