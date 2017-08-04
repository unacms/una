<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Convos Convos
 * @ingroup     UnaModules
 *
 * @{
 */

class BxCnvCmts extends BxTemplCmts
{
    /**
     * Comments are enabled for collaborators only
     */
    public function isEnabled ()
    {
        if (!parent::isEnabled ())
            return false;

        $oModule = BxDolModule::getInstance('bx_convos');
        if (!$oModule->_oDb->getContentInfoById((int)$this->getId()))
            return false;

        $aCollaborators = $oModule->_oDb->getCollaborators((int)$this->getId());
        if (!isset($aCollaborators[bx_get_logged_profile_id()]))
            return false;

        return true;
    }

    public function isRemoveAllowed ($aCmt, $isPerformAction = false)
    {
        if (isAdmin())
            return true;

        return false;
    }

    public function msgErrRemoveAllowed ()
    {
        return _t('_sys_txt_access_denied');
    }

    public function isEditAllowed ($aCmt, $isPerformAction = false)
    {
        return $this->isRemoveAllowed ($aCmt, $isPerformAction);
    }

    public function msgErrEditAllowed ()
    {
        return $this->msgErrRemoveAllowed ();
    }

    protected function _getFormObject($sAction = BX_CMT_ACTION_POST)
    {
        $oForm = parent::_getFormObject($sAction);
        $oForm->aInputs['cmt_submit']['value'] = _t('_sys_send');
        return $oForm;
    }

    function getCommentsBlock($aBp = array(), $aDp = array())
    {
        $mixedBlock = parent::getCommentsBlock($aBp, $aDp);
        if (is_array($mixedBlock) && isset($mixedBlock['title']))
            $mixedBlock['title'] = _t('_bx_cnv_page_block_title_entry_comments', $this->getCommentsCount());
        return $mixedBlock;
    }
}

/** @} */
