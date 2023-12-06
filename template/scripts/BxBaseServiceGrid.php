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
        $sObject = bx_process_input(bx_get('o'));
        $oTemplate = BxDolStudioTemplate::getInstance();
        $oGrid = BxDolGrid::getObjectInstance($sObject, $oTemplate);
        $sAction = 'performAction' . bx_gen_method_name(bx_process_input(bx_get('a')));
        if (method_exists($oGrid, $sAction)) {
            return $oGrid->$sAction();
            exit();
        }
       
    }
}

/** @} */
