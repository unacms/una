<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Notifications Notifications
 * @ingroup     UnaModules
 *
 * @{
 */

class BxNtfsMenuPreview extends BxTemplMenuCustom
{
    protected $_sModule;
    protected $_oModule;

    protected $_sTmplContentPreviewItem;

    protected $_iOwnerId;
    protected $_aBrowseParams;

    public function __construct($aObject, $oTemplate = false)
    {
        $this->_sModule = 'bx_notifications';
        $this->_oModule = BxDolModule::getInstance($this->_sModule);

        if(!$oTemplate)
            $oTemplate = $this->_oModule->_oTemplate;

        parent::__construct($aObject, $oTemplate);

        if(empty($this->_sTmplContentPreviewItem))
            $this->_sTmplContentPreviewItem = $this->_oTemplate->getHtml('menu_preview_item.html');

        $this->_iOwnerId = $this->_oModule->getUserId();
        $this->_aBrowseParams = array(
            'last_read' => $this->_oModule->_oDb->getLastRead($this->_iOwnerId)
        );
    }

    public function getMenuItems ()
    {
        $aItems = parent::getMenuItems();
        if(empty($aItems) || !is_array($aItems))
            return MsgBox(_t('_Empty'));

        return $aItems;
    }

    public function getCode ()
    {
        $sCode = parent::getCode();
        $sCode = $this->_oTemplate->parseHtmlByName('menu_preview.html', array(
            'content' => $sCode
        ));
        $sCode .= $this->_oTemplate->getCss(true);

        return $sCode;
    }

    protected function getMenuItemsRaw ()
    {
        $aItems = $this->_oModule->serviceGetNotifications($this->_iOwnerId, array('per_page' => $this->_oModule->_oConfig->getPerPage('preview')));
        if(empty($aItems) || !is_array($aItems))
            return array();

        $bFirst = true;
        foreach($aItems as $iKey => $aItem) {
            if($bFirst)
    			$bFirst = !$this->_oModule->_oDb->markAsRead($this->_iOwnerId, $aItem['id']);

            $aItems[$iKey]['name'] = 'event';
        }

        return array_merge($aItems, $this->_oQuery->getMenuItems());
    }

    protected function _getMenuItemDefault ($aItem)
    {
        return $this->_oTemplate->parseHtmlByContent($this->_sTmplContentPreviewItem, $aItem);
    }

    protected function _getMenuItemEvent($aItem)
    {
        return $this->_oTemplate->getPost($aItem, $this->_aBrowseParams);
    }
}

/** @} */
