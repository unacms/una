<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * Menu representation.
 * @see BxDolMenu
 */
class BxBaseCmtsMenuUnitMeta extends BxTemplMenuUnitMeta
{
    protected $_oCmts;
    protected $_aCmtsSystem;
    protected $_aCmt;
    protected $_sCmtStylePrefix;

    public function __construct ($aObject, $oTemplate)
    {
        parent::__construct ($aObject, $oTemplate);
    }

    public function setCmtsData($oCmts, $iCmtId)
    {
        if(empty($oCmts) || empty($iCmtId))
            return;

        $this->_oCmts = $oCmts;
        $this->_aCmtsSystem = $oCmts->getSystemInfo();
        $this->_aCmt = $oCmts->getCommentRow($iCmtId);
        $this->_sCmtStylePrefix = (!empty($this->_aCmtsSystem['root_style_prefix']) ? $this->_aCmtsSystem['root_style_prefix'] : 'cmt');

        $this->_sStylePrefix = $this->_sCmtStylePrefix . '-meta bx-base-general-unit-meta';

        $this->addMarkers(array(
            'cmt_system' => $this->_oCmts->getSystemName(),
            'cmt_object_id' => $this->_oCmts->getId(),
            'cmt_id' => $iCmtId,
            'content_id' => $iCmtId,
        ));
    }

    protected function _getMenuItemInReplyTo($aItem)
    {
        if((int)$this->_aCmt['cmt_parent_id'] == 0) 
            return '';

        $aParent = $this->_oCmts->getCommentRow($this->_aCmt['cmt_parent_id']);
        if(empty($aParent) || !is_array($aParent))
            return '';

        $oProfile = BxDolProfile::getInstanceMagic((int)$aParent['cmt_author_id']);
        $sParAuthorName = $oProfile->getDisplayName();
        $sParAuthorUnit = $oProfile->getUnit(0, array('template' => array('name' => 'unit_wo_info_links', 'size' => 'icon')));

        $sResult = $this->_oTemplate->parseHtmlByName('comment_reply_to.html', array(
            'style_prefix' => $this->_sCmtStylePrefix,
            'par_cmt_link' => $this->_oCmts->getItemUrl($this->_aCmt['cmt_parent_id']),
            'par_cmt_title' => bx_html_attribute(_t('_in_reply_to_x', $sParAuthorName)),
            'par_cmt_author' => $sParAuthorName,
            'par_cmt_author_unit' => $sParAuthorUnit
        ));

        return $this->getUnitMetaItemCustom($sResult);
    }

    protected function _getMenuItemAuthor($aItem)
    {
        list($sAuthorName, $sAuthorLink, $sAuthorIcon, $sAuthorUnit, $sAuthorBadges) = $this->_oCmts->getAuthorInfo($this->_aCmt['cmt_author_id']);
    
        $sResult = '';
        if(!empty($sAuthorLink))
            $sResult = $this->getUnitMetaItemLink($sAuthorName, array(
                'href' => $sAuthorLink,
                'class' => $this->_sStylePrefix . '-username',
                'title' => bx_html_attribute($sAuthorName),
            )). $sAuthorBadges;
        else
            $sResult = $this->getUnitMetaItemText($sAuthorName, array(
                'class' => $this->_sStylePrefix . '-username'
            )). $sAuthorBadges;

        return $sResult;
    }

    protected function _getMenuItemDate($aItem)
    {
        $sAgo = bx_time_js($this->_aCmt['cmt_time']);

        if(!empty($this->_aCmtsSystem['trigger_field_title']))
            return $this->getUnitMetaItemLink($sAgo, array(
                'href' => $this->_oCmts->getViewUrl($this->_aCmt['cmt_id']),
                'class' => $this->_sStylePrefix . '-ago'
            ));
        else
            return $this->getUnitMetaItemText($sAgo, array(
                'class' => $this->_sStylePrefix . '-ago'
            ));
    }

    protected function _getMenuItemMembership($aItem)
    {
        $iUserId = bx_get_logged_profile_id();
        $iAuthorId = (int)$this->_aCmt['cmt_author_id'];
        if($iAuthorId < 0  && (abs($iAuthorId) == $iUserId || $this->_oCmts->isModerator()))
            $iAuthorId *= -1;

        $aMembership = BxDolAcl::getInstance()->getMemberMembershipInfo($iAuthorId);
        if(empty($aMembership) || !is_array($aMembership))
            return '';

        return $this->getUnitMetaItemText($iAuthorId < 0 ? _t('_anonymous') : _t($aMembership['name']), array(
            'class' => $this->_sStylePrefix . '-membership'
        ));
    }

    protected function _getMenuItemDefault($aItem)
    {
        $sResult = false;

        bx_alert('comment', 'menu_custom_item', 0, 0, array(
            'res' => &$sResult, 
            'menu' => $this->_sObject, 
            'menu_object' => $this, 
            'item' => $aItem,
            'content_id' => $this->_aCmt['cmt_id'],
            'content_data' => $this->_aCmt,
            'cmts_object' => $this->_oCmts
        ));

        if (false !== $sResult)
            return $sResult;

        return parent::_getMenuItemDefault($aItem);
    }
}

/** @} */
