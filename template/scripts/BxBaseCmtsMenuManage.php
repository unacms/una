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

    public function initContentParams()
    {
        parent::initContentParams();

        $this->_aContentParams = array_merge($this->_aContentParams, [
            'cmt_system' => $this->_oCmts->getSystemName(),
            'cmt_object_id' => $this->_oCmts->getId(),
            'cmt_id' => (int)$this->_aCmt['cmt_id']
        ]);
    }

    public function setContentParams($aParams)
    {   
        if(!isset($aParams['cmt_system'], $aParams['cmt_object_id'], $aParams['cmt_id']))
            return false;

        $oCmts = BxTemplCmts::getObjectInstance($aParams['cmt_system'], (int)$aParams['cmt_object_id']);
        if(!$oCmts)
            return false;

        $this->setCmtsData($oCmts, (int)$aParams['cmt_id']);

        return parent::setContentParams($aParams);
    }

    public function setCmtsData($oCmts, $iCmtId, $aBp = [], $aDp = [])
    {
        parent::setCmtsData($oCmts, $iCmtId, $aBp, $aDp);

        $this->_aContentParams = array_merge($this->_aContentParams, [
            'cmt_system' => $this->_oCmts->getSystemName(),
            'cmt_object_id' => $this->_oCmts->getId(),
            'cmt_id' => (int)$this->_aCmt['cmt_id']
        ]);
    }
}

/** @} */
