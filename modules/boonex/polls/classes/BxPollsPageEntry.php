<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Polls Polls
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Entry create/edit pages
 */
class BxPollsPageEntry extends BxBaseModTextPageEntry
{
    public function __construct($aObject, $oTemplate = false)
    {
        $this->MODULE = 'bx_polls';
        parent::__construct($aObject, $oTemplate);
    }

    public function getCode()
    {
        return $this->_oModule->_oTemplate->getJsCode('entry') . parent::getCode();
    }

    protected function _addJsCss()
    {
        parent::_addJsCss();

        $this->_oModule->_oTemplate->addJs(array('entry.js'));
        $this->_oModule->_oTemplate->addCss(array('entry.css'));
    }
}

/** @} */
