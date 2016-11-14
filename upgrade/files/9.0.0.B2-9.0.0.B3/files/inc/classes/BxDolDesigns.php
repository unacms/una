<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

class BxDolDesigns extends BxDolFactory implements iBxDolSingleton
{
    protected $sDesign;
    protected $oDesign;

    protected $aParams;

    protected function __construct()
    {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error ('Multiple instances are not allowed for the class: ' . get_class($this), E_USER_ERROR);

        parent::__construct();
    }

    /**
     * Prevent cloning the instance
     */
    public function __clone()
    {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error('Clone is not allowed for the class: ' . get_class($this), E_USER_ERROR);
    }

    /**
     * Get singleton instance of the class
     */
    static function getInstance()
    {
        if(!isset($GLOBALS['bxDolClasses'][__CLASS__])) {
            $GLOBALS['bxDolClasses'][__CLASS__] = new BxDolDesigns();
            $GLOBALS['bxDolClasses'][__CLASS__]->init();
        }

        return $GLOBALS['bxDolClasses'][__CLASS__];
    }

    protected function init()
    {
    	$this->sDesign = BxDolTemplate::getInstance()->getCode();

    	$aDesign = BxDolModuleQuery::getInstance()->getModuleByUri($this->sDesign);
    	if(!empty($aDesign) && is_array($aDesign))
    		$this->oDesign = BxDolModule::getInstance($aDesign['name']);

    	//--- Init site's logo params.
    	if($this->oDesign instanceof BxDolModule && method_exists($this->oDesign->_oConfig, 'getLogoParams'))
    		list(
    			$this->aParams['logo'], 
    			$this->aParams['logo_alt'], 
    			$this->aParams['logo_width'], 
    			$this->aParams['logo_height']
    		) = $this->oDesign->_oConfig->getLogoParams();
    }

    public function getSiteLogo()
    {
    	return $this->getSiteLogoParam('logo');
    }
	public function getSiteLogoAlt()
    {
    	return $this->getSiteLogoParam('logo_alt');
    }
	public function getSiteLogoWidth()
    {
    	return $this->getSiteLogoParam('logo_width');
    }
	public function getSiteLogoHeight()
    {
    	return $this->getSiteLogoParam('logo_height');
    }
    protected function getSiteLogoParam($sName)
    {
    	if(!empty($this->aParams[$sName])) {
    		$sResult = getParam($this->aParams[$sName]);
    		if(!empty($sResult))
    			return $sResult;
    	}

    	return getParam('sys_site_' . $sName);
    }
}

/** @} */
