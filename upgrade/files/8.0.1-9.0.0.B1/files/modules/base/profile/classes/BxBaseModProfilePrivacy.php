<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    BaseProfile Base classes for profile modules
 * @ingroup     TridentModules
 *
 * @{
 */

class BxBaseModProfilePrivacy extends BxTemplPrivacy
{
	protected $MODULE;
    protected $_oModule;
    protected $_aPrivacyParticallyVisible = array ();

    public function __construct($aOptions, $oTemplate = false)
    {
        parent::__construct($aOptions, $oTemplate);

    	$this->_oModule = BxDolModule::getInstance($this->MODULE);
    	if(!$oTemplate)
			$oTemplate = $this->_oModule->_oTemplate;
    }

    public function isPartiallyVisible ($mixedPrivacy)
    {
        return in_array($mixedPrivacy, $this->_aPrivacyParticallyVisible);
    }

    public function getPartiallyVisiblePrivacyGroups ()
    {
        return $this->_aPrivacyParticallyVisible;
    }
}

/** @} */
