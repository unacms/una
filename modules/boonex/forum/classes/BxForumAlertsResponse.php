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
        $this->updateCommentsSummary($oAlert->iObject);
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
