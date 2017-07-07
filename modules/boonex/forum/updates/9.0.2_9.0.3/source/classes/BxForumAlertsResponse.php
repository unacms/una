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

class BxForumAlertsResponse extends BxBaseModTextAlertsResponse
{
    public function __construct()
    {
        $this->MODULE = 'bx_forum';
        parent::__construct();        
    }

    public function response($oAlert)
    {
        $sMethod = 'process' . bx_gen_method_name($oAlert->sUnit . '_' . $oAlert->sAction);
    	if(method_exists($this, $sMethod))
    		$this->$sMethod($oAlert);

        parent::response($oAlert);
    }

    protected function processBxForumCommentPost($oAlert)
    {
        $this->updateCommentsSummary($oAlert->iObject);

        $this->_oModule->serviceTriggerCommentPost($oAlert->iObject, $oAlert->aExtras['comment_author_id'], $oAlert->aExtras['comment_id'], 0, $oAlert->aExtras['comment_text']);
    }
    
    protected function processBxForumCommentUpdated($oAlert)
    {
        $this->updateCommentsSummary($oAlert->iObject);
    }

    protected function processBxForumCommentRemoved($oAlert)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $this->updateCommentsSummary($oAlert->iObject);

        $aEntry = $this->_oModule->_oDb->getContentInfoById($oAlert->iObject);
        if((int)$aEntry['lr_comment_id'] == (int)$oAlert->aExtras['comment_id']) {
            $iLrCommentId = $iLrTimestamp = $iLrProfileId = 0;

            $aComment = $this->_oModule->_oDb->getComments(array('type' => 'entry_last', 'entry_id' => $oAlert->iObject));
            if(!empty($aComment) && is_array($aComment)) {
                $iLrCommentId = $aComment['cmt_id'];
                $iLrTimestamp = $aComment['cmt_time'];
                $iLrProfileId = $aComment['cmt_author_id'];
            }

            $this->_oModule->_oDb->updateEntries(array(
                'lr_timestamp' => $iLrTimestamp,
                'lr_profile_id' => $iLrProfileId,
                'lr_comment_id' => $iLrCommentId
            ), array($CNF['FIELD_ID'] => $oAlert->iObject));
        }
    }

    protected function updateCommentsSummary($iContentId)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $aCmts = BxDolCmts::getObjectInstance($CNF['OBJECT_COMMENTS'], $iContentId)->getQueryObject()->getCommentsBy(array(
        	'type' => 'object_id', 
        	'object_id' => $iContentId
        ));
        if(empty($aCmts) || !is_array($aCmts))
            return;

        $sResult = '';
        foreach($aCmts as $aCmt)
            $sResult .= strip_tags($aCmt['cmt_text']) . "\n\r";

        if(empty($sResult))
            return;

        $this->_oModule->_oDb->updateEntries(array($CNF['FIELD_TEXT_COMMENTS'] => $sResult), array($CNF['FIELD_ID'] => $iContentId));
    }
}

/** @} */
