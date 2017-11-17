<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseText Base classes for text modules
 * @ingroup     UnaModules
 *
 * @{
 */

class BxBaseModTextMenuSnippetMeta extends BxTemplMenuCustom
{
    protected $_sModule;
    protected $_oModule;

    protected $_iContentId;
    protected $_aContentInfo;

    public function __construct($aObject, $oTemplate = false)
    {
        parent::__construct($aObject, $oTemplate);

        $this->_oModule = BxDolModule::getInstance($this->_sModule);
    }

    public function setContentId($iContentId)
    {
        $this->_iContentId = (int)$iContentId;

        $this->_aContentInfo = $this->_oModule->_oDb->getContentInfoById($this->_iContentId);
        if($this->_aContentInfo)
            $this->addMarkers(array('content_id' => (int)$this->_iContentId));
    }  
    
    protected function _getMenuItem ($aItem)
    {
        $mixedResult = parent::_getMenuItem($aItem);
        if(empty($mixedResult) || !is_array($mixedResult))
            return $mixedResult;

        $mixedResult['item'] = $this->_oTemplate->parseSpan('&#183;&nbsp;', array(
        	'class' => 'bx-base-text-unit-meta-div bx-def-font-grayed'
        )) . $mixedResult['item'];
        
        return $mixedResult;
    }

    protected function _getMenuItemAuthor($aItem)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $oProfile = BxDolProfile::getInstance($this->_aContentInfo[$CNF['FIELD_AUTHOR']]);
        if(!$oProfile) 
            $oProfile = BxDolProfileUndefined::getInstance();

        $sAuthor = $oProfile->getDisplayName();
		$sAuthorUrl = $oProfile->getUrl();
        return $this->_oTemplate->parseLink($sAuthorUrl, $sAuthor, array('class' => 'bx-base-text-unit-author'));
    }

    protected function _getMenuItemDate($aItem)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        return $this->_oTemplate->parseSpan(bx_time_js($this->_aContentInfo[$CNF['FIELD_ADDED']], BX_FORMAT_DATE), array(
        	'class' => 'bx-def-font-grayed'
        ));
    }

    protected function _getMenuItemCategory($aItem)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if(empty($CNF['OBJECT_CATEGORY']) || empty($CNF['FIELD_CATEGORY']) || empty($this->_aContentInfo[$CNF['FIELD_CATEGORY']]))
            return false;

        $oCategory = BxDolCategory::getObjectInstance($CNF['OBJECT_CATEGORY']);
        if(!$oCategory)
            return false;

        $sTitle = $oCategory->getCategoryTitle($this->_aContentInfo[$CNF['FIELD_CATEGORY']]);
        return $oCategory->getCategoryLink($sTitle, $this->_aContentInfo[$CNF['FIELD_CATEGORY']]);
    }

    protected function _getMenuItemTags($aItem)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if(empty($CNF['OBJECT_METATAGS']))
            return false;

        $oMetatags = BxDolMetatags::getObjectInstance($CNF['OBJECT_METATAGS']);
        if(!$oMetatags || !$oMetatags->keywordsIsEnabled())
            return false;

        return $oMetatags->getKeywordsList($this->_aContentInfo[$CNF['FIELD_ID']], 3);
    }

    protected function _getMenuItemViews($aItem)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if(empty($CNF['FIELD_VIEWS']) || empty($this->_aContentInfo[$CNF['FIELD_VIEWS']]))
            return false;

        return $this->_oTemplate->parseSpan(_t('_view_n_views', $this->_aContentInfo[$CNF['FIELD_VIEWS']]), array(
        	'class' => 'bx-def-font-grayed'
        ));
    }

    protected function _getMenuItemRating($aItem)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if(empty($CNF['OBJECT_VOTES_STARS']))
            return false;

        $oVotes = BxDolVote::getObjectInstance($CNF['OBJECT_VOTES_STARS'], $this->_aContentInfo[$CNF['FIELD_ID']]);
        if(!$oVotes)
            return false;

        return $oVotes->getElementInline(array('show_counter' => true));
    }

    protected function _getMenuItemComments($aItem)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if(empty($CNF['OBJECT_COMMENTS']) || empty($CNF['FIELD_COMMENTS']) || empty($this->_aContentInfo[$CNF['FIELD_COMMENTS']]))
            return false;

        $oComments = BxDolCmts::getObjectInstance($CNF['OBJECT_COMMENTS'], $this->_aContentInfo[$CNF['FIELD_ID']]);
        if(!$oComments || !$oComments->isEnabled())
            return false;

        return $this->_oTemplate->parseLink($oComments->getListUrl(), _t('_cmt_txt_n_comments', $this->_aContentInfo[$CNF['FIELD_COMMENTS']]));
    }
}

/** @} */
