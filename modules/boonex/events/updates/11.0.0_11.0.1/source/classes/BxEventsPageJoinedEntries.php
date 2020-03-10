<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Events Events
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Entries user joined to.
 */
class BxEventsPageJoinedEntries extends BxBaseModGroupsPageJoinedEntries
{
    protected $MODULE;

    protected $_oModule;
    
    public function __construct($aObject, $oTemplate = false)
    {
        $this->MODULE = 'bx_events';
        $this->_oModule = BxDolModule::getInstance($this->MODULE);
        
        parent::__construct($aObject, $oTemplate);
    }
}

/** @} */
