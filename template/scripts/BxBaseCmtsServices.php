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

    public function serviceGetLiveUpdate($sSystem, $iContentId, $iProfileId, $iCount = 0)
    {
        $oCmts = BxDolCmts::getObjectInstance($sSystem, $iContentId);
        if(!$oCmts || !$oCmts->isEnabled())
            return false;

        $sKey = $oCmts->getNotificationId();

        bx_import('BxDolSession');
        if((int)BxDolSession::getInstance()->getValue($sKey) == 1)
            return false;

        $iCountNew = $oCmts->getCommentsCount($iContentId, -1, BX_CMT_FILTER_OTHERS);
        if($iCountNew == $iCount)
            return false;

        return array(
            'count' => $iCountNew, // required
            'method' => $oCmts->getJsObjectName() . '.showLiveUpdate(oData)', // required
            'data' => array(
                'code' => $oCmts->getLiveUpdate($iCount, $iCountNew)
            ),  // optional, may have some additional data to be passed in JS method provided using 'method' param above.
        );
    }

    public function serviceGetLiveUpdates($sSystem, $iContentId, $iProfileId, $iCount = 0)
    {
        $oCmts = BxDolCmts::getObjectInstance($sSystem, $iContentId);
        if(!$oCmts || !$oCmts->isEnabled())
            return false;

        $sKey = $oCmts->getNotificationId();

        bx_import('BxDolSession');
        if((int)BxDolSession::getInstance()->getValue($sKey) == 1)
            return false;

        $iCountNew = $oCmts->getCommentsCount($iContentId, -1, BX_CMT_FILTER_OTHERS);
        if($iCountNew == $iCount)
            return false;

        return array(
            'count' => $iCountNew, // required
            'method' => $oCmts->getJsObjectName() . '.showLiveUpdates(oData)', // required
            'data' => array(
                'code' => $oCmts->getLiveUpdates($iCount, $iCountNew)
            ),  // optional, may have some additional data to be passed in JS method provided using 'method' param above.
        );
    }
    
    public function serviceManageTools($sType = 'common')
    {
        $oGrid = BxDolGrid::getObjectInstance('sys_cmts_administration');
        if(!$oGrid)
            return '';
        $oTemplate = BxDolTemplate::getInstance();
        $oTemplate->addJs(array('BxDolCmtsManageTools.js'));
        $oTemplate->addCss(array('cmts_manage_tools.css'));
        $oTemplate->addJsTranslation(array('_sys_grid_search'));
        return array(
        	'content' =>  $oGrid->getCode()
        );
    }
}

/** @} */
