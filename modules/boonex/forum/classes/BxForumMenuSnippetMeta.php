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

class BxForumMenuSnippetMeta extends BxBaseModTextMenuSnippetMeta
{
    public function __construct($aObject, $oTemplate = false)
    {
        $this->_sModule = 'bx_forum';

        parent::__construct($aObject, $oTemplate);
    }

    protected function _getMenuItemViews($aItem)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if(empty($CNF['FIELD_VIEWS']) || empty($CNF['FIELD_VIEWS']) || (empty($this->_aContentInfo[$CNF['FIELD_VIEWS']]) && !$this->_bShowZeros))
            return false;

        $oViews = BxDolView::getObjectInstance($CNF['OBJECT_VIEWS'], $this->_aContentInfo[$CNF['FIELD_ID']]);
        if(!$oViews || !$oViews->isEnabled())
            return false;

        return $this->getUnitMetaItemCustom($oViews->getElementInline(array('show_counter' => true)));
    }

    protected function _getMenuItemVotes($aItem)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if(empty($CNF['OBJECT_VOTES']) || empty($CNF['FIELD_VOTES']) || (empty($this->_aContentInfo[$CNF['FIELD_VOTES']]) && !$this->_bShowZeros))
            return false;

        $oVotes = BxDolVote::getObjectInstance($CNF['OBJECT_VOTES'], $this->_aContentInfo[$CNF['FIELD_ID']]);
        if(!$oVotes || !$oVotes->isEnabled())
            return false;

        return $this->getUnitMetaItemCustom($oVotes->getElementInline(array('show_counter' => true)));
    }

    protected function _getMenuItemComments($aItem)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if(empty($CNF['OBJECT_COMMENTS']) || empty($CNF['FIELD_COMMENTS']) || (empty($this->_aContentInfo[$CNF['FIELD_COMMENTS']]) && !$this->_bShowZeros))
            return false;

        $oComments = BxDolCmts::getObjectInstance($CNF['OBJECT_COMMENTS'], $this->_aContentInfo[$CNF['FIELD_ID']]);
        if(!$oComments || !$oComments->isEnabled())
            return false;

        return $this->getUnitMetaItemCustom($oComments->getElementInline(array('show_counter' => true)));
    }

    protected function _getMenuItemReplyAuthor($aItem)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;
        if(empty($this->_aContentInfo[$CNF['FIELD_LR_COMMENT_ID']]))
            return '';

        $oProfile = BxDolProfile::getInstanceMagic($this->_aContentInfo[$CNF['FIELD_LR_AUTHOR']]);
        return $this->getUnitMetaItemCustom($oProfile->getUnit(0, array('template' => array(
            'name' => 'unit',
            'size' => 'icon'
        ))));
    }

    protected function _getMenuItemReplyDate($aItem)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;
        if(empty($this->_aContentInfo[$CNF['FIELD_LR_COMMENT_ID']]))
            return '';

        return $this->getUnitMetaItemText(bx_time_js($this->_aContentInfo[$CNF['FIELD_LR_ADDED']], BX_FORMAT_DATE));
    }

    protected function _getMenuItemReplyText($aItem)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;
        if(empty($this->_aContentInfo[$CNF['FIELD_LR_COMMENT_ID']]))
            return '';

        $iComment = $this->_aContentInfo[$CNF['FIELD_LR_COMMENT_ID']];
        $aComment = BxDolCmts::getObjectInstance($CNF['OBJECT_COMMENTS'], $this->_aContentInfo[$CNF['FIELD_ID']])->getCommentRow($iComment);
        if(empty($aComment) || !is_array($aComment))
            return '';

        return $this->getUnitMetaItemText(strmaxtextlen($aComment['cmt_text'], 100), array('class' => $this->_sModule . '-gpm-reply-text'));
    }
    
    protected function _getMenuItemStatus($aItem)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $oObject = isset($CNF['OBJECT_COMMENTS']) ? BxDolCmts::getObjectInstance($CNF['OBJECT_COMMENTS'], $this->_aContentInfo[$CNF['FIELD_ID']]) : null;
        $sIcon = '';
        if ($this->_aContentInfo['resolvable']){
            if ($this->_aContentInfo['resolved']){
                $sIcon .= 'check-circle';
            }
            else{
                $sIcon .= 'question-circle';
            }   
        }
        else{
            $sIcon .= 'comment'; 
        }
        return $this->getUnitMetaItemCustom($this->_oModule->_oTemplate->parseHtmlByName('status.html', ['icon' => $sIcon , 'counter' => (int)$oObject->getCommentsCountAll()])); 
    }
    
    protected function _getMenuItemBadges($aItem)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;
        return $this->getUnitMetaItemCustom($this->_oModule->serviceGetBadges($this->_aContentInfo[$CNF['FIELD_ID']], false, true)); 
    }
}

/** @} */
