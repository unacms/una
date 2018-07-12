<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * Menu representation.
 * @see BxDolMenu
 */
class BxBaseCmtsMenuManage extends BxTemplCmtsMenuActions
{
    public function __construct ($aObject, $oTemplate)
    {
        parent::__construct ($aObject, $oTemplate);

        if(bx_get('cmt_system') !== false && bx_get('cmt_object_id') !== false && bx_get('cmt_id') !== false) {
            $oCmts = BxTemplCmts::getObjectInstance(bx_process_input(bx_get('cmt_system')), (int)bx_get('cmt_object_id'));
            if($oCmts)
                $this->setCmtsData($oCmts, (int)bx_get('cmt_id'));
        }

        $this->_bDynamicMode = true;
        $this->_bShowTitles = true;
    }
}

/** @} */
