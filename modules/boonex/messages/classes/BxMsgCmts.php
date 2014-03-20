<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Messages Messages
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxTemplCmts');

class BxMsgCmts extends BxTemplCmts
{
    /**
     * Comments are enabled for collaborators only
     */
    public function isEnabled ()
    {
        if (!parent::isEnabled ())
            return false;

        $oModule = BxDolModule::getInstance('bx_messages');
        if (!($aContentInfo = $oModule->_oDb->getContentInfoById((int)$this->getId())))
            return false;

        $aCollaborators = $oModule->_oDb->getCollaborators((int)$this->getId());
        if (!isset($aCollaborators[bx_get_logged_profile_id()]))
            return false;

        return true;
    }
}

/** @} */
