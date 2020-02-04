<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * Wiki block menu
 */
class BxBaseMenuWiki extends BxTemplMenu
{
    protected $_oWikiObject;
    protected $_iBlockId;

    public function __construct ($aObject, $oTemplate)
    {
        parent::__construct ($aObject, $oTemplate);
        if ($sWikiObj = bx_get('wiki_obj')) {
            $oWiki = BxDolWiki::getObjectInstance($sWikiObj);
            if ($oWiki) {
                $this->_oWikiObject = $oWiki;
                $this->_iBlockId = (int)bx_get('block_id');
            }
        }
    }

    public function getCode ()
    {
        $s = parent::getCode ();
        $s .= '<script>window.glBxDolWiki' . $this->_iBlockId . '.bindEvents();</script>';
        return $s;
    }

    /**
     * Check if menu items is visible with extended checking for friends notifications
     * @param $a menu item array
     * @return boolean
     */
    protected function _isVisible ($a)
    {
        if(!$this->_oWikiObject || !parent::_isVisible($a))
            return false;

        return $this->_oWikiObject->isAllowed($a['name']);
    }
}

/** @} */
