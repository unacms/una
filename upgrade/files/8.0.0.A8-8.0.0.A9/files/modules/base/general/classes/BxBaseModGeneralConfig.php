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

    protected $_aObjects;
    protected $_aPrefixes;
    protected $_aJsClass;
    protected $_aJsObjects;
    protected $_aGridObjects;

    function __construct($aModule)
    {
        parent::__construct($aModule);

        $this->_aObjects = array();
        $this->_aPrefixes = array();
        $this->_aJsClass = array();
        $this->_aJsObjects = array();
        $this->_aGridObjects = array();
    }

    public function getObject($sType = '')
    {
    	if(empty($sType))
            return $this->_aObjects;

        return isset($this->_aObjects[$sType]) ? $this->_aObjects[$sType] : '';
    }

	public function getPrefix($sType = '')
    {
    	if(empty($sType))
            return $this->_aPrefixes;

        return isset($this->_aPrefixes[$sType]) ? $this->_aPrefixes[$sType] : '';
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
