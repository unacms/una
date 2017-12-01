<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseGeneral Base classes for modules
 * @ingroup     UnaModules
 *
 * @{
 */

class BxBaseModGeneralMenuSnippetMeta extends BxTemplMenuCustom
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

    protected function _getMenuItemAuthor($aItem)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $oProfile = BxDolProfile::getInstance($this->_aContentInfo[$CNF['FIELD_AUTHOR']]);
        if(!$oProfile) 
            $oProfile = BxDolProfileUndefined::getInstance();

        return $this->_oTemplate->getUnitMetaItemLink($oProfile->getDisplayName(), array(
            'href' => $oProfile->getUrl(),
            'class' => 'bx-base-text-unit-author'
        ));
    }

    protected function _getMenuItemDate($aItem)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        return $this->_oTemplate->getUnitMetaItemText(bx_time_js($this->_aContentInfo[$CNF['FIELD_ADDED']], BX_FORMAT_DATE));
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
        return $this->_oTemplate->getUnitMetaItemCustom($oCategory->getCategoryLink($sTitle, $this->_aContentInfo[$CNF['FIELD_CATEGORY']]));
    }

    protected function _getMenuItemTags($aItem)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if(empty($CNF['OBJECT_METATAGS']))
            return false;

        $oMetatags = BxDolMetatags::getObjectInstance($CNF['OBJECT_METATAGS']);
        if(!$oMetatags || !$oMetatags->keywordsIsEnabled())
            return false;

        return $this->_oTemplate->getUnitMetaItemCustom($oMetatags->getKeywordsList($this->_aContentInfo[$CNF['FIELD_ID']], 3));
    }

    protected function _getMenuItemViews($aItem)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if(empty($CNF['FIELD_VIEWS']) || empty($this->_aContentInfo[$CNF['FIELD_VIEWS']]))
            return false;

        return $this->_oTemplate->getUnitMetaItemText(_t('_view_n_views', $this->_aContentInfo[$CNF['FIELD_VIEWS']]));
    }

    protected function _getMenuItemRating($aItem)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if(empty($CNF['OBJECT_VOTES_STARS']))
            return false;

        $oVotes = BxDolVote::getObjectInstance($CNF['OBJECT_VOTES_STARS'], $this->_aContentInfo[$CNF['FIELD_ID']]);
        if(!$oVotes)
            return false;

        return $this->_oTemplate->getUnitMetaItemCustom($oVotes->getElementInline(array('show_counter' => true)));
    }

    protected function _getMenuItemComments($aItem)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if(empty($CNF['OBJECT_COMMENTS']) || empty($CNF['FIELD_COMMENTS']) || empty($this->_aContentInfo[$CNF['FIELD_COMMENTS']]))
            return false;

        $oComments = BxDolCmts::getObjectInstance($CNF['OBJECT_COMMENTS'], $this->_aContentInfo[$CNF['FIELD_ID']]);
        if(!$oComments || !$oComments->isEnabled())
            return false;

        return $this->_oTemplate->getUnitMetaItemLink(_t('_cmt_txt_n_comments', $this->_aContentInfo[$CNF['FIELD_COMMENTS']]), array(
            'href' => $oComments->getListUrl()
        ));
    }
}

/** @} */
