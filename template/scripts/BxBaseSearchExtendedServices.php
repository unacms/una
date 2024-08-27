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


    
/*
 * TODO: Continue from here!            
https://linguria.vercel.app/api/api.php?r=system/get_results/TemplSearchExtendedServices&params[]={%22params%22:{%22per_page%22:10,%22start%22:0,%22object%22:%22bx_persons%22,%22search_params%22:{%22fullname%22:{%22type%22:%22text%22,%22value%22:%22umit%22,%22operator%22:%22like%22}}}}&lang=en
https://linguria.vercel.app/api/api.php?r=system/get_results/TemplSearchExtendedServices&params[]={%22params%22:{%22per_page%22:10,%22start%22:0,%22object%22:%22bx_persons%22,%22search_params%22:{%22fullname%22:{%22type%22:%22text%22,%22value%22:%22umit%22,%22operator%22:%22like%22}},%22filters%22:{%22fullname%22:%22umit%22,%22labels%22:%22%22,%22expertise%22:%22%22,%22native_language%22:%22%22,%22second_language%22:%22%22,%22third_language%22:%22%22,%22association_memberships%22:%22%22,%22searchbx_persons%22:%22Apply%22}}}&lang=en
*/
    public function serviceGetResults($mParams)
    {
        $aParams = [];
        $fProcessDefValues = function($aValues) {
            if(empty($aValues) || !is_array($aValues))
                return;

            foreach($aValues as $sKey => $sValue) {
                if(empty($sValue))
                    continue;

                $_POST[$sKey] = $sValue;
            }
        };

        if(($mDefValues = bx_get('filters')) !== false)
            $fProcessDefValues(json_decode($mDefValues, true));

        if(is_string($mParams)) {
            $mParams = json_decode($mParams, true);    
            if(!empty($mParams['params']) && is_array($mParams['params']))
                $aParams = $mParams['params'];

            if(isset($aParams['filters']))
                $fProcessDefValues($aParams['filters']);
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
