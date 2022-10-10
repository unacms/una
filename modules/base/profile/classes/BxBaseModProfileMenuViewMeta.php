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

        $oAcl = BxDolAcl::getInstance();
        $oTemplate = BxDolTemplate::getInstance();
        $aMembership =  $oAcl->getMemberMembershipInfo($this->_oContentProfile->id());
        $aLevelInfo =  $oAcl->getMembershipInfo($aMembership['id']);
        return $aMembership ? $this->getUnitMetaItemText($oTemplate->parseHtmlByName('menu_meta_item.html', ['icon' => $oTemplate->getImage($aLevelInfo['icon'], array('class' => 'bx-acl-m-thumbnail')), 'caption' => _t($aMembership['name'])])): false;
                                                                                            
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

        $oObject = isset($CNF['OBJECT_VIEWS']) ? BxDolView::getObjectInstance($CNF['OBJECT_VIEWS'], $this->_aContentInfo[$CNF['FIELD_ID']]) : null;
        
        $sIcon = BxTemplFunctions::getInstanceWithTemplate($this->_oTemplate)->getIconAsHtml(!empty($aItem['icon']) ? $aItem['icon'] : '');
        
        return $oObject ? $oObject->getCounter(['show_counter_empty' => false, 'show_counter_in_brackets' => false, 'dynamic_mode' => true, 'caption' => '_view_n_views', 'custom_icon' => $sIcon]) : '';
    }

    protected function _getMenuItemVotes($aItem)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if(empty($CNF['OBJECT_VOTES']) || empty($CNF['FIELD_VOTES']) || (empty($this->_aContentInfo[$CNF['FIELD_VOTES']]) && !$this->_bShowZeros))
            return false;
        
        $sIcon = BxTemplFunctions::getInstanceWithTemplate($this->_oTemplate)->getIconAsHtml(!empty($aItem['icon']) ? $aItem['icon'] : '');
        
        $oObject = isset($CNF['OBJECT_VOTES']) ? BxDolVote::getObjectInstance($CNF['OBJECT_VOTES'], $this->_aContentInfo[$CNF['FIELD_ID']]) : null;
        return $oObject ? $oObject->getCounter(['show_counter_label_icon' => true, 'show_counter_empty' => false, 'dynamic_mode' => true, 'caption' => '_vote_n_votes', 'custom_icon' => $sIcon]) : '';
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

        $oObject = isset($CNF['OBJECT_COMMENTS']) ? BxDolCmts::getObjectInstance($CNF['OBJECT_COMMENTS'], $this->_aContentInfo[$CNF['FIELD_ID']]) : null;

        return $oObject ? $oObject->getCounter([
            'overwrite_counter_link_onclick' => 'javascript:void(0)', 
            'show_counter_empty' => false, 
            'recalculate_counter' => true,
            'dynamic_mode' => true, 
            'caption' => '_cmt_txt_n_comments', 
            'custom_icon' => BxTemplFunctions::getInstanceWithTemplate($this->_oTemplate)->getIconAsHtml(!empty($aItem['icon']) ? $aItem['icon'] : '')
        ]) : '';
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
