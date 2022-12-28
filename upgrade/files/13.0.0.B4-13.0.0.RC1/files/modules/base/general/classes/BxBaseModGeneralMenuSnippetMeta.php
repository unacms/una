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

class BxBaseModGeneralMenuSnippetMeta extends BxTemplMenuUnitMeta
{
    protected $_bShowZeros;

    protected $_sModule;
    protected $_oModule;

    protected $_iContentId;
    protected $_aContentInfo;

    public function __construct($aObject, $oTemplate = false)
    {
        $this->_oModule = BxDolModule::getInstance($this->_sModule);
        if(empty($oTemplate))
            $oTemplate = $this->_oModule->_oTemplate;

        parent::__construct($aObject, $oTemplate);

        $this->_sStylePrefix = 'bx-base-general-unit-meta';
        $this->_bShowZeros = false;
    }

    public function getCode()
    {
        if(empty($this->_aContentInfo) || !is_array($this->_aContentInfo))
            return '';

        return parent::getCode();
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

        $oProfile = BxDolProfile::getInstanceMagic($this->_aContentInfo[$CNF['FIELD_AUTHOR']]);

        return $this->getUnitMetaItemLink($oProfile->getDisplayName(), array(
            'href' => $oProfile->getUrl(),
            'class' => 'bx-base-text-unit-author'
        ));
    }

    protected function _getMenuItemDate($aItem)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;
        if ($aItem['icon'] == '')
            return $this->getUnitMetaItemText(bx_time_js($this->_aContentInfo[$CNF['FIELD_ADDED']], BX_FORMAT_DATE));
        else
            return $this->getUnitMetaItemExtended(bx_time_js($this->_aContentInfo[$CNF['FIELD_ADDED']], BX_FORMAT_DATE), $aItem['icon'], '');
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
        return $this->getUnitMetaItemCustom($oCategory->getCategoryLink($sTitle, $this->_aContentInfo[$CNF['FIELD_CATEGORY']]));
    }

    protected function _getMenuItemTags($aItem)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if(empty($CNF['OBJECT_METATAGS']))
            return false;

        $oMetatags = BxDolMetatags::getObjectInstance($CNF['OBJECT_METATAGS']);
        if(!$oMetatags || !$oMetatags->keywordsIsEnabled())
            return false;

        return $this->getUnitMetaItemCustom($oMetatags->getKeywordsList($this->_aContentInfo[$CNF['FIELD_ID']], 3));
    }

    protected function _getMenuItemViews($aItem)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if(empty($CNF['FIELD_VIEWS']) || (empty($this->_aContentInfo[$CNF['FIELD_VIEWS']]) && !$this->_bShowZeros))
            return false;

        return $this->getUnitMetaItemText(_t('_view_n_views', $this->_aContentInfo[$CNF['FIELD_VIEWS']]));
    }

    protected function _getMenuItemVotes($aItem)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if(empty($CNF['OBJECT_VOTES']) || (empty($this->_aContentInfo[$CNF['FIELD_VOTES']]) && !$this->_bShowZeros))
            return false;
        
        return $this->getUnitMetaItemText(_t('_vote_n_votes', $this->_aContentInfo[$CNF['FIELD_VOTES']]));
    }

    protected function _getMenuItemRating($aItem)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if(empty($CNF['OBJECT_VOTES_STARS']))
            return false;

        $oVotes = BxDolVote::getObjectInstance($CNF['OBJECT_VOTES_STARS'], $this->_aContentInfo[$CNF['FIELD_ID']]);
        if(!$oVotes)
            return false;

        return $this->getUnitMetaItemCustom($oVotes->getElementInline(array('show_counter' => true)));
    }
    
    protected function _getMenuItemReactions($aItem)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if(empty($CNF['OBJECT_REACTIONS']))
            return false;

        $oVotes = BxDolVote::getObjectInstance($CNF['OBJECT_REACTIONS'], $this->_aContentInfo[$CNF['FIELD_ID']]);
        if(!$oVotes)
            return false;

        return $this->getUnitMetaItemCustom($oVotes->getElementInline(array('show_counter' => false)));
    }

    protected function _getMenuItemComments($aItem)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if(empty($CNF['OBJECT_COMMENTS']) || empty($CNF['FIELD_COMMENTS']) || (empty($this->_aContentInfo[$CNF['FIELD_COMMENTS']]) && !$this->_bShowZeros))
            return false;

        $oComments = BxDolCmts::getObjectInstance($CNF['OBJECT_COMMENTS'], $this->_aContentInfo[$CNF['FIELD_ID']]);
        if(!$oComments || !$oComments->isEnabled())
            return false;

        return $this->getUnitMetaItemLink(_t('_cmt_txt_n_comments', $oComments->getCommentsCountAll(0, true)), array(
            'href' => $oComments->getListUrl()
        ));
    }

    protected function _getMenuItemDefault($aItem)
    {
        $sResult = false;

        $a = array(
            'res' => &$sResult, 
            'menu' => $this->_sObject, 
            'menu_object' => $this, 
            'item' => $aItem,
            'module' => $this->_sModule,
            'content_id' => $this->_iContentId,
            'content_data' => $this->_aContentInfo,
        );
        bx_alert($this->_sModule, 'menu_custom_item', 0, 0, $a);
        bx_alert('menu', 'menu_custom_item', 0, 0, $a);
        
        if (false !== $sResult)
            return $sResult;

        return parent::_getMenuItemDefault($aItem);
    }
}

/** @} */
