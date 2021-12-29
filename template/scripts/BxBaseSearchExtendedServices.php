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
        $this->prepareParams($aParams);
        
        if(empty($aParams['object']))
            return '';

        $oSearch = BxDolSearchExtended::getObjectInstance($aParams['object']);
        if(!$oSearch || !$oSearch->isEnabled())
            return '';

        return $oSearch->getForm($aParams);
    }

    public function serviceGetResults($aParams)
    {
        $this->prepareParams($aParams);

        if(empty($aParams['object']))
            return '';

        $oSearch = BxDolSearchExtended::getObjectInstance($aParams['object']);
        if(!$oSearch || !$oSearch->isEnabled())
            return '';

        $sResults = $oSearch->getResults($aParams);

        return !empty($sResults) ? $sResults : (isset($aParams['show_empty']) && (bool)$aParams['show_empty'] ? MsgBox(_t('_Empty')) : ''); 
    }
    
    public function prepareParams(&$aParams)
    {
        if(empty($aParams['object']) && bx_get('object') !== false)
            $aParams['object'] = bx_process_input(bx_get('object'), BX_DATA_TEXT);

        if(!isset($aParams['show_empty']) && bx_get('show_empty') !== false)
            $aParams['show_empty'] = (bool)bx_get('show_empty');

        if(empty($aParams['template']) && bx_get('template') !== false)
            $aParams['template'] = bx_process_input(bx_get('template'), BX_DATA_TEXT);

        if(empty($aParams['cond']) && bx_get('cond') !== false)
            $aParams['cond'] = BxDolSearchExtended::decodeConditions(bx_process_input(bx_get('cond'), BX_DATA_TEXT));

        if(!isset($aParams['start']) && bx_get('start') !== false)
            $aParams['start'] = (int)bx_get('start');

        if(!isset($aParams['per_page']) && bx_get('per_page') !== false)
            $aParams['per_page'] = (int)bx_get('per_page');
    }
}

/** @} */
