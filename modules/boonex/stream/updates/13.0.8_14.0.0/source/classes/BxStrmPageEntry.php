<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Stream Stream
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Entry create/edit pages
 */
class BxStrmPageEntry extends BxBaseModTextPageEntry
{
    public function __construct($aObject, $oTemplate = false)
    {
        $this->MODULE = 'bx_stream';
        parent::__construct($aObject, $oTemplate);
    }

    protected function _isAvailablePage($a)
    {
        if(!$this->_aContentInfo || (!$this->_oModule->isEntryActive($this->_aContentInfo) && !getParam('bx_stream_always_accessible')))
            return false;
        
        return BxBaseModGeneralPageEntry::_isAvailablePage($a);
    }

    protected function _setSubmenu($aParams)
    {
    	parent::_setSubmenu(array_merge($aParams, array(
    		'title' => '',
    		'icon' => ''
    	)));
    }
}

/** @} */
