<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaTemplate UNA Template Classes
 * @{
 */

class BxTemplSearchResult extends BxBaseSearchResult
{
    protected $_sModule;
    protected $_oModule;

    function __construct($oFunctions = false)
    {
        parent::__construct($oFunctions);

        $this->_sModule = 'bx_artificer';
        $this->_oModule = BxDolModule::getInstance($this->_sModule);
    }

    /**
     * Apply class(es) for search result container 
     * @return string with a list of classes.
     */
    function applyContainerClass ()
    {
        return $this->_oModule->processReplacements(parent::applyContainerClass());
    }
}

/** @} */
