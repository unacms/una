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

require_once('BxDevFormsField.php');

class BxDevFormsFields extends BxTemplStudioFormsFields
{
    protected $oModule;

    function __construct($aOptions, $oTemplate = false)
    {
        parent::__construct($aOptions, $oTemplate);

        $this->oModule = BxDolModule::getInstance('bx_developer');
        $this->sClass = 'BxDevFormsField';
        $this->sUrlPage = BX_DOL_URL_STUDIO . 'module.php?name=' . $this->oModule->_oConfig->getName() . '&page=forms&form_page=fields';

        $sModule = bx_get('form_module');
        if(!empty($sModule)) {
            $this->sModule = bx_process_input($sModule);
            $this->_aQueryAppend['module'] = $this->sModule;
        }

        $sObject = bx_get('form_object');
        if(!empty($sObject)) {
            $this->sObject = bx_process_input($sObject);
            $this->_aQueryAppend['object'] = $this->sObject;
        }

        $sDisplay = bx_get('form_display');
        if(!empty($sDisplay)) {
            $this->sDisplay = bx_process_input($sDisplay);
            $this->_aQueryAppend['display'] = $this->sDisplay;
        }
    }

	protected function _isEditable(&$aRow)
    {
    	return true;
    }

	protected function _isDeletable(&$aRow)
    {
    	return true;
    }
}

/** @} */
