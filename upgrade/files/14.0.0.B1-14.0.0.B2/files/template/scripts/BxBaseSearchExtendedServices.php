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

    public function serviceGetForm($mParams)
    {
        $aParams = [];
        if (is_string($mParams))
            $aParams['object'] = $mParams;
        else
            $aParams = $mParams;
        $this->prepareParams($aParams);
        
        if(empty($aParams['object']))
            return '';

        $oSearch = BxDolSearchExtended::getObjectInstance($aParams['object']);
        if(!$oSearch || !$oSearch->isEnabled())
            return '';

        $mForm = $oSearch->getForm($aParams);
        if (bx_is_api()){
            /*$aRes =$this->serviceGetResults($aParams);
            if ($aRes)
                return [$mForm, $aRes];
            else
                return [$mForm];*/
return [$mForm];
        }
        return $mForm;
    }

    public function serviceGetResults($mParams)
    {
        $aParams = [];
        
        $mDefValues = bx_get('filters');
       
        if ($mDefValues){
            $mDefValues = json_decode($mDefValues, true);
        }
        
        foreach($mDefValues as $sKey => $aParam){
            $_POST[$sKey] = $aParam;
        }
        
        if (is_string($mParams)){
           $aPa = json_decode($mParams, true);    
            $aParams['object'] = $aPa['params']["object"];
            $aParams['params'] = $aPa['params'];
            
            foreach($aParams['params']['filters'] as $sKey => $aParam){
               $_POST[$sKey] = $aParam;
            }
        }
        else
            $aParams = $mParams;
        
        $this->prepareParams($aParams);

        if(empty($aParams['object']))
            return '';

        $oSearch = BxDolSearchExtended::getObjectInstance($aParams['object']);
        if(!$oSearch || !$oSearch->isEnabled())
            return '';

        $sResults = $oSearch->getResults($aParams);

        if (bx_is_api()){
            return $sResults;
        }
        
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

        if(!isset($aParams['total']) && ($mixedTotal = bx_get('total')) !== false)
            $aParams['total'] = is_numeric($mixedTotal) ? (int)$mixedTotal : $mixedTotal == 'true';
    }
}

/** @} */
