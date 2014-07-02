<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinCore Dolphin Core
 * @{
 */

/**
 * System services related to Comments.
 */
class BxBaseCmtsServices extends BxDol
{
    public function __construct()
    {
        parent::__construct();
    }

    public function serviceGetMenuItemAddonVote($sSystem, $iId, $iCmtId)
    {
        bx_import('BxDolCmts');
        $oCmts = BxDolCmts::getObjectInstance($sSystem, $iId);

        $oVote = $oCmts->getVoteObject($iCmtId);
        if($oVote !== false)
            return $oVote->getCounter();

        return '';
    }
}

/** @} */
