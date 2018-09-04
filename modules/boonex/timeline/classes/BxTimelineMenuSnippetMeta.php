<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Timeline Timeline
 * @ingroup     UnaModules
 *
 * @{
 */

class BxTimelineMenuSnippetMeta extends BxBaseModTextMenuSnippetMeta
{
    public function __construct($aObject, $oTemplate = false)
    {
        $this->_sModule = 'bx_timeline';

        parent::__construct($aObject, $oTemplate);

        $this->_sStylePrefix = $this->_oModule->_oConfig->getPrefix('style') . '-item-meta';
    }

    public function setEvent($aEvent)
    {
        if(empty($aEvent) || !is_array($aEvent))
            return;

        $this->_aContentInfo = $aEvent;
        $this->_iContentId = (int)$aEvent['id'];
    }

    protected function _getMenuItemDate($aItem)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        return $this->getUnitMetaItemLink(bx_time_js($this->_aContentInfo[$CNF['FIELD_ADDED']], BX_FORMAT_DATE), array(
            'href' => $this->_oModule->_oConfig->getItemViewUrl($this->_aContentInfo),
            'class' => $this->_sStylePrefix . '-date bx-base-text-unit-date'
        ));
    }

    protected function _getMenuItemMembership($aItem)
    {
        $aMembership = BxDolAcl::getInstance()->getMemberMembershipInfo($this->_aContentInfo['object_owner_id']);
        if(empty($aMembership) || !is_array($aMembership))
            return '';

        return $this->getUnitMetaItemText(_t($aMembership['name']), array(
            'class' => $this->_sStylePrefix . '-membership'
        ));
    }
}

/** @} */
