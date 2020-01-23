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

    public function __construct ($aObject, $oTemplate)
    {
        parent::__construct ($aObject, $oTemplate);
    }

    public function setMenuObject($o)
    {
        $this->_oWikiObject = $o;
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
