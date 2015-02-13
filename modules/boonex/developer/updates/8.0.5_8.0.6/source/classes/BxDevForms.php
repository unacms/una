<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Developer Developer
 * @ingroup     TridentModules
 *
 * @{
 */

class BxDevForms extends BxTemplStudioForms
{
    protected $oModule;
    protected $aParams;
    protected $aGridObjects;

    function __construct($aParams)
    {
        parent::__construct(isset($aParams['page']) ? $aParams['page'] : '');

        $this->aParams = $aParams;
        $this->sSubpageUrl = $this->aParams['url'] . '&form_page=';

        $this->oModule = BxDolModule::getInstance('bx_developer');

        $this->aGridObjects = array(
        	'forms' => $this->oModule->_oConfig->getObject('grid_forms'),
	        'displays' => $this->oModule->_oConfig->getObject('grid_forms_displays'),
	        'fields' => $this->oModule->_oConfig->getObject('grid_forms_fields'),
	        'pre_lists' => $this->oModule->_oConfig->getObject('grid_forms_pre_lists'),
	        'pre_values' => $this->oModule->_oConfig->getObject('grid_forms_pre_values'),
        );

        $this->oModule->_oTemplate->addStudioCss(array('forms.css'));
    }
}

/** @} */
