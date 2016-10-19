<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Forum Forum
 * @ingroup     TridentModules
 *
 * @{
 */

/**
 * Entry forms helper functions
 */
class BxForumFormsEntryHelper extends BxBaseModTextFormsEntryHelper
{
    public function __construct($oModule)
    {
        parent::__construct($oModule);
    }

    public function onDataAddAfter ($iAccountId, $iContentId)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if($s = parent::onDataAddAfter($iAccountId, $iContentId))
            return $s;     

        if(getParam($CNF['PARAM_AUTOSUBSCRIBE_CREATED']) == 'on')
            BxDolConnection::getObjectInstance($CNF['OBJECT_CONNECTION_SUBSCRIBERS'])->actionAdd($iContentId, $this->_iProfileId);

        return '';
    }
}

/** @} */
