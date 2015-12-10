<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

/**
 * Menu representation.
 * @see BxDolMenu
 */
class BxBaseCmtsMenuActions extends BxTemplMenuCustom
{
    protected $_oCmts;
    protected $_aCmt;

    public function __construct ($aObject, $oTemplate)
    {
        parent::__construct ($aObject, $oTemplate);
    }

    public function setCmtsData($oCmts, $iCmtId)
    {
        if(empty($oCmts) || empty($iCmtId))
            return;

        $this->_oCmts = $oCmts;
        $this->_aCmt = $oCmts->getCommentRow($iCmtId);

        $sJsObject = $oCmts->getJsObjectName();
        $this->addMarkers(array(
            'js_object' => $sJsObject,
            'cmt_system' => $this->_oCmts->getSystemName(),
            'cmt_id' => $this->_oCmts->getId(),
            'content_id' => $iCmtId,
            'reply_onclick' => $sJsObject . '.toggleReply(this, ' . $iCmtId . ')'
        ));
    }

    protected function _getMenuItemItemVote($aItem)
    {
        $oVote = $this->_oCmts->getVoteObject($this->_aCmt['cmt_unique_id']);
        if(!$oVote)
        	return false;

    	return $oVote->getElementInline(array('dynamic_mode' => $this->_bDynamicMode));
    }

    /**
     * Check if menu items is visible.
     * @param $a menu item array
     * @return boolean
     */
    protected function _isVisible ($a)
    {
        if(!parent::_isVisible($a))
            return false;

        $sCheckFuncName = '';
        $aCheckFuncParams = array();
        switch ($a['name']) {
            case 'item-vote':
                $sCheckFuncName = 'isVoteAllowed';
                if(!empty($this->_aCmt))
                    $aCheckFuncParams = array($this->_aCmt);
                break;

            case 'item-reply':
                $sCheckFuncName = 'isPostReplyAllowed';
                break;
        }

        if(!$sCheckFuncName || !method_exists($this->_oCmts, $sCheckFuncName))
            return true;

        return call_user_func_array(array($this->_oCmts, $sCheckFuncName), $aCheckFuncParams);
    }
}

/** @} */
