<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 * 
 * @defgroup    Timeline Timeline
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxTemplCmtsView');

class BxTimelineCmts extends BxTemplCmtsView
{
    var $_oModule;

    function __construct($sSystem, $iId, $iInit = 1)
    {
        parent::BxTemplCmtsView($sSystem, $iId, $iInit);

        $this->_oModule = BxDolModule::getInstance('BxTimelineModule');
    }

    function actionCmtPost ()
    {
        $mixedResult = parent::actionCmtPost();
        if(empty($mixedResult))
            return $mixedResult;

        $aEvents = $this->_oModule->_oDb->getEvents(array('type' => 'id', 'object_id' => (int)$this->getId()));
        if(isset($aEvents[0]['owner_id']) && (int)$aEvents[0]['owner_id'] > 0) {
            //--- Wall -> Update for Alerts Engine ---//
            bx_import('BxDolAlerts');
            $oAlert = new BxDolAlerts('bx_' . $this->_oModule->_oConfig->getUri(), 'update', $aEvents[0]['owner_id']);
            $oAlert->alert();
            //--- Wall -> Update for Alerts Engine ---//
        }

        return $mixedResult;
    }

    /**
     * get full comments block with initializations
     */
    function getCommentsFirst($sType)
    {
        $iObjectId = $this->getId();
        return $this->_oModule->_oTemplate->parseHtmlByTemplateName('comments', array(
            'cmt_actions' => $this->getActions(0, $sType, $iObjectId),
            'cmt_system' => $this->_sSystem,
            'cmt_object' => $iObjectId,
            'cmt_addon' => $this->getCmtsInit()
        ));
    }

    /**
     * get full comments(system) block with initializations
     */
    function getCommentsFirstSystem($sType, $iEventId, $iCommentId = 0)
    {
        return $this->_oModule->_oTemplate->parseHtmlByTemplateName('comments', array(
            'cmt_actions' => $this->getActions($iCommentId, $sType, $iEventId),
            'cmt_system' => $this->_sSystem,
            'cmt_object' => $this->getId(),
            'bx_if:show_replies' => array(
                'condition' => $iCommentId != 0,
                'content' => array(
                    'id' => $iCommentId,
                )
            ),
            'cmt_addon' => $this->getCmtsInit()
        ));
    }

    function getActions($iCmtId, $sType = 'reply', $iEventId = 0)
    {
        $aEvent = $this->_oModule->_oDb->getEvents(array('type' => 'id', 'object_id' => $iEventId));
        $aEvent = array_shift($aEvent);

        $this->_oModule->_iOwnerId = (int)$aEvent['owner_id'];

        $iObjectId = $this->getId();
        $aParams = array(
            'cmt_id' => $iCmtId,
            'cmt_replies' => $this->_oQuery->getObjectCommentsCount($iObjectId, $iCmtId),
            'cmt_type' => $sType
        );

        $sRet = $this->_oModule->_oTemplate->parseHtmlByTemplateName('comments_actions', array(
            'date' => $aEvent['ago'],
            'bx_if:show_delete' => array(
                'condition' => $this->_oModule->_isCommentDeleteAllowed(),
                'content' => array(
                    'js_view_object' => $this->_oModule->_oConfig->getJsObject('view'),
                    'id' => $aEvent['id']
                )
            ),
            'bx_if:show_replies' => array(
                'condition' => $aParams['cmt_replies'],
                'content' => array(
                    'content' => $this->_getRepliesBox($aParams)
                )
            ),
            'bx_if:show_reply' => array(
                'condition' => $this->isPostReplyAllowed(),
                'content' => array(
                    'content' => $this->_getPostReplyBoxTo($aParams)
                )
            ),
        ));

        return $sRet;
    }
}

/** @} */ 
