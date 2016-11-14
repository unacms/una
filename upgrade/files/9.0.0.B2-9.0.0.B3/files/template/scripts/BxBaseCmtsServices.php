<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
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

		$sKey = $oCmts->getNotificationId();

		bx_import('BxDolSession');
    	if((int)BxDolSession::getInstance()->getValue($sKey) == 1)
    		return false;

		$iCountNew = $oCmts->getCommentsCount($iContentId, -1, BX_CMT_FILTER_OTHERS);
		if($iCountNew <= $iCount)
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
