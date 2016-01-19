<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Ocean Ocean
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxBaseModGeneralConfig');

class BxOceanConfig extends BxBaseModGeneralConfig
{
	protected $_oDb;

	protected $_iLogo;
	protected $_sLogoAlt;
	protected $_iLogoWidth;
	protected $_iLogoHeight;

    function __construct($aModule)
    {
        parent::__construct($aModule);

        $this->_aPrefixes = array(
        	'option' => 'bx_ocean_'
        );
    }

	public function init(&$oDb)
    {
        $this->_oDb = &$oDb;
        $sOptionPrefix = $this->getPrefix('option');

        $this->_iLogo = (int)$this->_oDb->getParam($sOptionPrefix . 'site_logo');
        $this->_sLogoAlt = $this->_oDb->getParam($sOptionPrefix . 'site_logo_alt');
        $this->_iLogoWidth = (int)$this->_oDb->getParam($sOptionPrefix . 'site_logo_width');
        $this->_iLogoHeight = (int)$this->_oDb->getParam($sOptionPrefix . 'site_logo_height');
    }

    public function getLogoParams()
    {
    	$sPrefix = $this->getPrefix('option');

    	return array(
    		$sPrefix . 'site_logo',
    		$sPrefix . 'site_logo_alt',
    		$sPrefix . 'site_logo_width',
    		$sPrefix . 'site_logo_height'
    	);
    }

    public function getLogo()
    {
    	return $this->_iLogo;
    }

	public function getLogoAlt()
    {
    	return $this->_sLogoAlt;
    }

	public function getLogoWidth()
    {
    	return $this->_iLogoWidth;
    }

	public function getLogoHeight()
    {
    	return $this->_iLogoHeight;
    }
}

/** @} */
