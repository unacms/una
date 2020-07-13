<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Classes Classes
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Entry create/edit pages
 */
class BxClssPageEntry extends BxBaseModTextPageEntry
{
    public function __construct($aObject, $oTemplate = false)
    {
        $this->MODULE = 'bx_classes';
        parent::__construct($aObject, $oTemplate);
    }

    protected function _setSubmenu($aParams)
    {
    	parent::_setSubmenu(array_merge($aParams, array(
    		'title' => '',
    		'icon' => ''
    	)));
    }

    public function getCode ()
    {
        $this->_oTemplate->addJs('main.js');

        $s = parent::getCode ();

        // mark class as viewed
        $CNF = &$this->_oModule->_oConfig->CNF;
        if ($this->_aContentInfo && isLogged())
            $this->_oModule->_oDb->updateClassStatus($this->_aContentInfo[$CNF['FIELD_ID']], bx_get_logged_profile_id(), 'viewed');

        return $s;
    }
}

/** @} */
