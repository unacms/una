<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    BaseGeneral Base classes for modules
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxDolModuleConfig');

class BxBaseModGeneralConfig extends BxDolModuleConfig
{
    public $CNF;

    protected $_aJsClass;
    protected $_aJsObjects;
    protected $_aGridObjects;

    function __construct($aModule)
    {
        parent::__construct($aModule);
    }

	public function getJsClass($sType)
    {
        return isset($this->_aJsClass[$sType]) ? $this->_aJsClass[$sType] : '';
    }

    public function getJsObject($sType)
    {
		return isset($this->_aJsObjects[$sType]) ? $this->_aJsObjects[$sType] : '';
    }

    public function getGridObject($sType)
    {
		return isset($this->_aGridObjects[$sType]) ? $this->_aGridObjects[$sType] : '';
    }
}

/** @} */
