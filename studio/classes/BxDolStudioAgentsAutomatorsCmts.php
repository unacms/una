<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaStudio UNA Studio
 * @{
 */

class BxDolStudioAgentsAutomatorsCmts extends BxTemplCmts
{
    protected $_oQueryAgents;

    public function __construct($sSystem, $iId, $iInit = true, $oTemplate = false)
    {
        parent::__construct($sSystem, $iId, $iInit, $oTemplate);
        
        $this->_oQueryAgents = new BxDolStudioAgentsQuery();
    }
    
    public function isAttachImageEnabled()
    {
        return false;
    }
    
    public function onPostAfter($iCmtId, $aDp = [])
    {
        $mixedResult = parent::onPostAfter($iCmtId, $aDp);
        if($mixedResult !== false) {
            $iObjId = (int)$this->getId();

            $aCmt = $this->_oQuery->getCommentSimple($iObjId, $iCmtId);
            $aAutomator = $this->_oQueryAgents->getAutomatorsBy(['sample' => 'id_full', 'id' => $iObjId]);

            //TODO: Send to AI $aCmt['cmt_text']
        }

        return $mixedResult;
    }

    protected function _getActionsBox(&$aCmt, $aBp = [], $aDp = [])
    {
        return parent::_getActionsBox($aCmt, $aBp, array_merge($aDp, ['view_only' => true]));
    }
    
    protected function _getFormBox($sType, $aBp, $aDp)
    {
        return parent::_getFormBox($sType, $aBp, array_merge($aDp, ['min_post_form' => false]));
    }
}

/** @} */
