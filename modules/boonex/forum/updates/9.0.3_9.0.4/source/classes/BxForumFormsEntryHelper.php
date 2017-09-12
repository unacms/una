<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Forum Forum
 * @ingroup     UnaModules
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

        $s = parent::onDataAddAfter($iAccountId, $iContentId);
        if(!empty($s))
            return $s;

        if(getParam($CNF['PARAM_AUTOSUBSCRIBE_CREATED']) == 'on')
            BxDolConnection::getObjectInstance($CNF['OBJECT_CONNECTION_SUBSCRIBERS'])->actionAdd($iContentId, BxDolProfile::getInstanceByAccount($iAccountId)->id());

        return '';
    }
}

/** @} */
