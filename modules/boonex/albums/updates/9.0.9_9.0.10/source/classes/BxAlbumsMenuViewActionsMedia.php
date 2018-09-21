<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Albums Albums
 * @ingroup     UnaModules
 *
 * @{
 */

require_once('BxAlbumsMenuViewActions.php');

/**
 * View media actions menu
 */
class BxAlbumsMenuViewActionsMedia extends BxAlbumsMenuViewActions
{
    protected $_iMediaId;
    protected $_aMediaInfo;

    public function __construct($aObject, $oTemplate = false)
    {
        parent::__construct($aObject, $oTemplate);
    }

    public function setContentId($iMediaId)
    {
        $this->_iMediaId = (int)$iMediaId;
        $this->_aMediaInfo = $this->_oModule->_oDb->getMediaInfoById($this->_iMediaId);
        if($this->_aMediaInfo) {
            $this->addMarkers(array('media_id' => (int)$this->_iMediaId));

            $this->_iContentId = (int)$this->_aMediaInfo['content_id'];
            $this->_aContentInfo = $this->_oModule->_oDb->getContentInfoById($this->_iContentId);
            if($this->_aContentInfo)
                $this->addMarkers(array('content_id' => (int)$this->_iContentId));
        }
    }

    protected function _getMenuItemAddImagesToAlbum($aItem)
    {
        return $this->_getMenuItemByNameActions($aItem);
    }

    protected function _getMenuItemEditAlbum($aItem)
    {
        return $this->_getMenuItemByNameActions($aItem);
    }
    
    protected function _getMenuItemView($aItem, $aParams = array())
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        return parent::_getMenuItemView($aItem, array(
            'object' => $CNF['OBJECT_VIEWS_MEDIA'],
            'id' => $this->_iMediaId
        ));
    }

    protected function _getMenuItemComment($aItem, $aParams = array())
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        return parent::_getMenuItemComment($aItem, array(
            'object' => $CNF['OBJECT_COMMENTS_MEDIA'],
            'id' => $this->_iMediaId
        ));
    }

    protected function _getMenuItemVote($aItem, $aParams = array())
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        return parent::_getMenuItemVote($aItem, array(
            'object' => $CNF['OBJECT_VOTES_MEDIA'],
            'id' => $this->_iMediaId
        ));
    }

    protected function _getMenuItemScore($aItem, $aParams = array())
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        return parent::_getMenuItemScore($aItem, array(
            'object' => $CNF['OBJECT_SCORES_MEDIA'],
            'id' => $this->_iMediaId
        ));
    }

    protected function _getMenuItemFavorite($aItem, $aParams = array())
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        return parent::_getMenuItemFavorite($aItem, array(
            'object' => $CNF['OBJECT_FAVORITES_MEDIA'],
            'id' => $this->_iMediaId
        ));
    }

    protected function _getMenuItemFeature($aItem, $aParams = array())
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        return parent::_getMenuItemFeature($aItem, array(
            'object' => $CNF['OBJECT_FEATURED_MEDIA'],
            'id' => $this->_iMediaId
        ));
    }

    protected function _getMenuItemRepost($aItem, $aParams = array())
    {
        return '';
    }

    protected function _getMenuItemReport($aItem, $aParams = array())
    {
        return '';
    }
}

/** @} */
