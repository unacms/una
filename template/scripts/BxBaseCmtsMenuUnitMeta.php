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

        $this->_sStylePrefix = (!empty($this->_aCmtsSystem['root_style_prefix']) ? $this->_aCmtsSystem['root_style_prefix'] : 'cmt') . '-meta';

        $this->addMarkers(array(
            'cmt_system' => $this->_oCmts->getSystemName(),
            'cmt_object_id' => $this->_oCmts->getId(),
            'cmt_id' => $iCmtId,
            'content_id' => $iCmtId,
        ));
    }

    protected function _getMenuItemAuthor($aItem)
    {
        list($sAuthorName, $sAuthorLink, $sAuthorIcon, $sAuthorUnit) = $this->_oCmts->getAuthorInfo($this->_aCmt['cmt_author_id']);

        if(!empty($sAuthorLink))
            return $this->getUnitMetaItemLink($sAuthorName, array(
                'href' => $sAuthorLink,
                'class' => $this->_sStylePrefix . '-username',
                'title' => bx_html_attribute($sAuthorName),
            ));
        else
            return $this->getUnitMetaItemText($sAuthorName, array(
                'class' => $this->_sStylePrefix . '-username'
            ));
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
        $aMembership = BxDolAcl::getInstance()->getMemberMembershipInfo($this->_aCmt['cmt_author_id']);
        if(empty($aMembership) || !is_array($aMembership))
            return '';

        return $this->getUnitMetaItemText(_t($aMembership['name']), array(
                'class' => $this->_sStylePrefix . '-membership'
            ));
    }
}

/** @} */
