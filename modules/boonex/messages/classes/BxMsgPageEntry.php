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

bx_import('BxBaseModTextPageEntry');

/**
 * Entry create/edit pages
 */
class BxMsgPageEntry extends BxBaseModTextPageEntry 
{    
    public function __construct($aObject, $oTemplate = false) 
    {
        $this->MODULE = 'bx_messages';
        parent::__construct($aObject, $oTemplate);

        $CNF = &$this->_oModule->_oConfig->CNF;

        $this->_oModule->_oDb->updateReadComments(bx_get_logged_profile_id(), $this->_aContentInfo[$CNF['FIELD_ID']], $this->_aContentInfo[$CNF['FIELD_COMMENTS']]);

        $this->_oModule->setModuleSubmenu ();

        $this->_oModule->_oTemplate->addJs('main.js');
    }
}

/** @} */
