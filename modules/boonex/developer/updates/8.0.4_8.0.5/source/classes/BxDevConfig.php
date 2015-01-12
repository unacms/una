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

bx_import('BxBaseModGeneralConfig');

class BxDevConfig extends BxBaseModGeneralConfig
{
    protected $aJsClasses;
    protected $aJsObjects;
    protected $sAnimationEffect;
    protected $iAnimationSpeed;

    function __construct($aModule)
    {
        parent::__construct($aModule);

        $this->_aObjects = array(
        	//--- Forms builder grids.
			'grid_forms' => $this->_sName . '_forms',
        	'grid_forms_displays' => $this->_sName . '_forms_displays',
        	'grid_forms_fields' => $this->_sName . '_forms_fields',
	        'grid_forms_pre_lists' => $this->_sName . '_forms_pre_lists',
	        'grid_forms_pre_values' => $this->_sName . '_forms_pre_values',

        	//--- Forms builder forms.
        	'form_forms_form' => $this->_sName . '_forms_form',
        	'form_display_forms_form_add' => $this->_sName . '_forms_form_add',
        	'form_display_forms_form_edit' => $this->_sName . '_forms_form_edit',
        	'form_forms_display' => $this->_sName . '_forms_display',
        	'form_display_forms_display_add' => $this->_sName . '_forms_display_add',
        	'form_display_forms_display_edit' => $this->_sName . '_forms_display_edit',
        	'form_forms_prelist' => $this->_sName . '_forms_prelist',
        	'form_display_forms_prelist_add' => $this->_sName . '_forms_prelist_add',
        	'form_display_forms_prelist_edit' => $this->_sName . '_forms_prelist_edit',
        	'form_forms_prevalue' => $this->_sName . '_forms_prevalue',
        	'form_display_forms_prevalue_add' => $this->_sName . '_forms_prevalue_add',
        	'form_display_forms_prevalue_edit' => $this->_sName . '_forms_prevalue_edit',

	        //--- Navigation builder grids.
			'grid_nav_menus' => $this->_sName . '_nav_menus',
	        'grid_nav_sets' => $this->_sName . '_nav_sets',
	        'grid_nav_items' => $this->_sName . '_nav_items',

        	//--- Navigation builder forms.
        	'form_nav_menu' => $this->_sName . '_nav_menu',
        	'form_display_nav_menu_add' => $this->_sName . '_nav_menu_add',
        	'form_display_nav_menu_edit' => $this->_sName . '_nav_menu_edit',        
        	'form_nav_set' => $this->_sName . '_nav_set',
        	'form_display_nav_set_add' => $this->_sName . '_nav_set_add',
        	'form_display_nav_set_edit' => $this->_sName . '_nav_set_edit',
        	'form_nav_item' => $this->_sName . '_nav_item',
        	'form_display_nav_item_add' => $this->_sName . '_nav_item_add',
        	'form_display_nav_item_edit' => $this->_sName . '_nav_item_edit',

	        //--- Pages builder forms.
        	'form_bp_page' => $this->_sName . '_bp_page',
        	'form_display_bp_page_add' => $this->_sName . '_bp_page_add',
        	'form_bp_block' => $this->_sName . '_bp_block',
        	'form_display_bp_block_edit' => $this->_sName . '_bp_block_edit',

			//--- Polyglot grids.
        	'grid_pgt_manage' => $this->_sName . '_pgt_manage',
        
        );

        $this->aJsClasses = array('polyglot' => 'BxDevPolyglot');
        $this->aJsObjects = array('polyglot' => 'oBxDevPolyglot');
        $this->sAnimationEffect = 'fade';
        $this->iAnimationSpeed = 'slow';
    }

    function getJsClass($sType = 'main')
    {
        if(empty($sType))
            return $this->aJsClasses;

        return $this->aJsClasses[$sType];
    }

    function getJsObject($sType = 'main')
    {
        if(empty($sType))
            return $this->aJsObjects;

        return $this->aJsObjects[$sType];
    }

    function getAnimationEffect()
    {
        return $this->sAnimationEffect;
    }

    function getAnimationSpeed()
    {
        return $this->iAnimationSpeed;
    }
}

/** @} */
