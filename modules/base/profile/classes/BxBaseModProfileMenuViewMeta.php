<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseProfile Base classes for profile modules
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * View entry meta menu
 */
class BxBaseModProfileMenuViewMeta extends BxTemplMenuUnitMeta
{
    protected $_sModule;
    protected $_oModule;

    protected $_bShowZeros;

    protected $_iContentId;
    protected $_aContentInfo;
    protected $_bContentPublic;
    protected $_oContentProfile;
    protected $_aContentProfileInfo;

    public function __construct($aObject, $oTemplate = false)
    {
        $this->_oModule = BxDolModule::getInstance($this->_sModule);

        parent::__construct($aObject, $oTemplate);

        $this->_bShowZeros = false;

        $this->_iContentId = 0;
        $this->_aContentInfo = [];
        $this->_bContentPublic = false;
        $this->_oContentProfile = null;
        $this->_aContentProfileInfo = [];
    }

    public function setContentId($iContentId)
    {
        $this->_iContentId = $iContentId;
        $this->_aContentInfo = $this->_oModule->_oDb->getContentInfoById($this->_iContentId);
        if(empty($this->_aContentInfo) || !is_array($this->_aContentInfo))
            return;

        $this->_oContentProfile = BxDolProfile::getInstanceByContentAndType($this->_iContentId, $this->_sModule);
        if(!$this->_oContentProfile) 
            return;

        $this->_aContentProfileInfo = $this->_oContentProfile->getInfo();     

        $this->addMarkers($this->_aContentProfileInfo);
        $this->addMarkers(array(
            'profile_id' => $this->_oContentProfile->id()
        ));
    }

    public function setContentPublic($bContentPublic)
    {
        $this->_bContentPublic = $bContentPublic;
    }

    public function getCode()
    {
        if(empty($this->_iContentId))
            $this->_retrieveContentId();

        return parent::getCode();
    }

    protected function _getMenuItemMembership($aItem)
    {
        if(!$this->_bContentPublic || !$this->_oContentProfile)
            return false;

        $aMembership = BxDolAcl::getInstance()->getMemberMembershipInfo($this->_oContentProfile->id());
        return $aMembership ? $this->getUnitMetaItemText(_t($aMembership['name'])) : false;
    }
    
    protected function _getMenuItemBadges($aItem)
    {
        $sResult = $this->_oModule->serviceGetBadges($this->_iContentId);
        if(!empty($sResult))
            $sResult = $this->getUnitMetaItemText($sResult, ['class' => 'bx-base-bages-container']);

        return $sResult;
    }

    protected function _getMenuItemViews($aItem)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if(empty($CNF['OBJECT_VIEWS']) || empty($CNF['FIELD_VIEWS']) || (empty($this->_aContentInfo[$CNF['FIELD_VIEWS']]) && !$this->_bShowZeros))
            return false;

        return $this->getUnitMetaItemText(_t('_view_n_views', $this->_aContentInfo[$CNF['FIELD_VIEWS']]));
    }

    protected function _getMenuItemVotes($aItem)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if(empty($CNF['OBJECT_VOTES']) || empty($CNF['FIELD_VOTES']) || (empty($this->_aContentInfo[$CNF['FIELD_VOTES']]) && !$this->_bShowZeros))
            return false;
        
        return $this->getUnitMetaItemText(_t('_vote_n_votes', $this->_aContentInfo[$CNF['FIELD_VOTES']]));
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
    
    protected function _getMenuItemScores($aItem)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if(empty($CNF['OBJECT_SCORES']))
            return false;

        $oScores = BxDolScore::getObjectInstance($CNF['OBJECT_SCORES'], $this->_aContentInfo[$CNF['FIELD_ID']]);
        if(!$oScores)
            return false;

        return $this->getUnitMetaItemCustom($oScores->getElementInline(array('show_counter' => false)));
    }

    protected function _getMenuItemComments($aItem)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if(empty($CNF['OBJECT_COMMENTS']) || empty($CNF['FIELD_COMMENTS']) || (empty($this->_aContentInfo[$CNF['FIELD_COMMENTS']]) && !$this->_bShowZeros))
            return false;

        $oComments = BxDolCmts::getObjectInstance($CNF['OBJECT_COMMENTS'], $this->_aContentInfo[$CNF['FIELD_ID']]);
        if(!$oComments || !$oComments->isEnabled())
            return false;

        return $this->getUnitMetaItemLink(_t('_cmt_txt_n_comments', $this->_aContentInfo[$CNF['FIELD_COMMENTS']]), array(
            'href' => $oComments->getListUrl()
        ));
    }

    protected function _retrieveContentId()
    {
        if(bx_get('id') !== false)
            $this->setContentId(bx_process_input(bx_get('id'), BX_DATA_INT));

        if(empty($this->_iContentId) && bx_get('profile_id') !== false)
            $this->setContentId(BxDolProfile::getInstance(bx_process_input(bx_get('profile_id'), BX_DATA_INT))->getContentId());
    }
}

/** @} */
