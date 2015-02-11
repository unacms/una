<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
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
        $oCmts = BxDolCmts::getObjectInstance($sSystem, $iId);

        $oVote = $oCmts->getVoteObject($iCmtId);
        if($oVote !== false)
            return $oVote->getCounter();

        return '';
    }

    public function serviceGetLiveUpdatesComments($sSystem, $iContentId, $iProfileId, $iCount = 0)
    {
        $oCmts = BxDolCmts::getObjectInstance($sSystem, $iContentId);
        if(!$oCmts || !$oCmts->isEnabled())
            return false;

		$iCountNew = $oCmts->getCommentsCount($iContentId, 0, BX_CMT_FILTER_OTHERS);
		if($iCountNew < $iCount)
			return false;

    	return array(
    		'count' => $iCountNew, // required
    		'method' => $oCmts->getJsObjectName() . '.showLiveUpdate(oData)', // required
    		'data' => array(
    			'code' => $oCmts->getNotification($iCount, $iCountNew)
    		),  // optional, may have some additional data to be passed in JS method provided using 'method' param above.
    	);
    }
}

/** @} */
