<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */


class BxBaseServiceGrid extends BxDol
{
    public function servicePerfomActionApi($aParams = [])
    {
        $sObject = '';
        if(!empty($aParams['object']))
            $sObject = $aParams['object'];
        else if(($sO = bx_get('o')) !== false)
            $sObject = bx_process_input($sO);

        $oGrid = BxDolGrid::getObjectInstance($sObject);
        if(!$oGrid)
            return ['grid not found'];

        $sAction = '';
        if(!empty($aParams['action']))
            $sAction = $aParams['action'];
        else if(($sA = bx_get('a')) !== false)
            $sAction = bx_process_input($sA);

        $sMethod = 'performAction' . bx_gen_method_name($sAction);
        if(!method_exists($oGrid, $sMethod)) 
            return ['method not found'];

        return $oGrid->$sMethod();       
    }
}

/** @} */
