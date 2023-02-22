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
        return $this->_oTemplate->getJsCode('view', [], true, true) . $this->_oTemplate->parseHtmlByName('menu_preview.html', array(
            'content' => parent::getCode()
        ));
    }

    protected function getMenuItemsRaw ()
    {
        $iPerPage = $this->_oModule->_oConfig->getPerPage('preview');

        $aItems = $this->_oModule->serviceGetNotifications($this->_iOwnerId, ['per_page' => 3 * $iPerPage]);
        if(empty($aItems) || !is_array($aItems))
            return array();

        $aResults = array();
        foreach($aItems as $iKey => $aItem) {
            if(!$this->_oModule->_oTemplate->getPost($aItem))
                continue;

            $aItem['name'] = 'event';

            $aResults[] = $aItem;
        }
        
        if(count($aResults) > $iPerPage)
            $aResults = array_slice($aResults, 0, $iPerPage);

        $aFirst = reset($aItems);
        $this->_oModule->_oDb->markAsRead($this->_iOwnerId, $aFirst['id']);

        return array_merge($aResults, $this->_oQuery->getMenuItems());
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
