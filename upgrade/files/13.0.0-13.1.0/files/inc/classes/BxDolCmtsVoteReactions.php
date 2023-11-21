<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */
class BxDolCmtsVoteReactions extends BxTemplVoteReactions
{
    function __construct($sSystem, $iId, $iInit = true, $oTemplate = false)
    {
        parent::__construct($sSystem, $iId, $iInit, $oTemplate);
    }

    protected function _isAllowedVoteByObject($aObject)
    {
        $oCmts = BxDolCmts::getObjectInstanceByUniqId($aObject['id'], true, $this->_oTemplate);
        if(!$oCmts)
            return false;

        return $oCmts->isViewAllowed() === CHECK_ACTION_RESULT_ALLOWED;
    }
}
/** @} */
