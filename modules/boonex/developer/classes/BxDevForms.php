<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Developer Developer
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxTemplStudioForms');

class BxDevForms extends BxTemplStudioForms
{
    protected $oModule;
    protected $aParams;
    protected $aGridObjects = array(
        'forms' => 'mod_dev_forms',
        'displays' => 'mod_dev_forms_displays',
        'fields' => 'mod_dev_forms_fields',
        'pre_lists' => 'mod_dev_forms_pre_lists',
        'pre_values' => 'mod_dev_forms_pre_values'
    );

    function __construct($aParams)
    {
        parent::__construct(isset($aParams['page']) ? $aParams['page'] : '');

        $this->aParams = $aParams;
        $this->sSubpageUrl = $this->aParams['url'] . '&form_page=';

        bx_import('BxDolModule');
        $this->oModule = BxDolModule::getInstance('bx_developer');

        $this->oModule->_oTemplate->addStudioCss(array('forms.css'));
    }
}

/** @} */
