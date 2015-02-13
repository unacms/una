<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Accounts Accounts
 * @ingroup     DolphinModules
 *
 * @{
 */

class BxAccntConfig extends BxBaseModGeneralConfig
{
    protected $_oDb;
    protected $_aHtmlIds;

    /**
     * Constructor
     */
    public function __construct($aModule)
    {
        parent::__construct($aModule);

        $this->CNF = array (
        	// page URIs
        	'URL_MANAGE_COMMON' => 'page.php?i=accounts-manage',
        	'URL_MANAGE_MODERATION' => 'page.php?i=accounts-moderation',
        	'URL_MANAGE_ADMINISTRATION' => 'page.php?i=accounts-administration',

	        // objects
	        'OBJECT_MENU_MANAGE_TOOLS' => 'bx_accounts_menu_manage_tools', //manage menu in content administration tools
        	'OBJECT_GRID_ADMINISTRATION' => 'bx_accounts_administration',
        	'OBJECT_GRID_MODERATION' => 'bx_accounts_moderation',

        	// some language keys
            'T' => array (
        		'grid_action_err_delete' => '_bx_accnt_grid_action_err_delete',
        		'grid_action_err_perform' => '_bx_accnt_grid_action_err_perform',
            	'filter_item_active' => '_bx_accnt_grid_filter_item_title_adm_active',
            	'filter_item_pending' => '_bx_accnt_grid_filter_item_title_adm_pending',
            	'filter_item_suspended' => '_bx_accnt_grid_filter_item_title_adm_suspended',
            	'filter_item_select_one_filter1' => '_bx_accnt_grid_filter_item_title_adm_select_one_filter1',
        	)
        );

        $this->_aObjects = array(
        	'alert' => $this->_sName,
        );

        $this->_aJsClass = array(
        	'manage_tools' => 'BxAccntManageTools'
        );

        $this->_aJsObjects = array(
        	'manage_tools' => 'oBxAccntManageTools'
        );

        $this->_aGridObjects = array(
        	'moderation' => $this->CNF['OBJECT_GRID_MODERATION'],
        	'administration' => $this->CNF['OBJECT_GRID_ADMINISTRATION'],
        	
        );

        $sHtmlPrefix = str_replace('_', '-', $this->_sName);
        $this->_aHtmlIds = array(
        	'profile' => $sHtmlPrefix . '-profile-',
        	'profile_more_popup' => $sHtmlPrefix . '-profile-more-popup-',
        );
    }

    public function init(&$oDb)
    {
        $this->_oDb = &$oDb;
    }

	public function getHtmlIds($sKey = '')
    {
        if(empty($sKey))
            return $this->_aHtmlIds;

        return isset($this->_aHtmlIds[$sKey]) ? $this->_aHtmlIds[$sKey] : '';
    }
}

/** @} */
