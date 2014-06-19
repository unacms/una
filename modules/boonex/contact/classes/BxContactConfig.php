<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 * 
 * @defgroup    Contact Contact
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxDolPrivacy');
bx_import('BxDolModuleConfig');

class BxContactConfig extends BxDolModuleConfig
{
    protected $_oDb;

    protected $_sAlertSystemName;

	protected $_sEmail;

    /**
     * Constructor
     */
    public function __construct($aModule)
    {
        parent::__construct($aModule);

		$this->_sAlertSystemName = $this->_sName;

        $this->_sEmail = ''; 
    }

    public function init(&$oDb)
    {
        $this->_oDb = &$oDb;

        $this->_sEmail = getParam('bx_contact_email');
        if(empty($this->_sEmail))
        	$this->_sEmail = getParam('site_email');
    }

	public function getSystemName($sType)
    {
    	$sResult = '';

    	switch($sType) {
    		case 'alert':
				$sResult = $this->_sAlertSystemName;
				break;
    	}

        return $sResult;
    }

    public function getEmail()
    {
    	return $this->_sEmail;
    }
}

/** @} */ 
