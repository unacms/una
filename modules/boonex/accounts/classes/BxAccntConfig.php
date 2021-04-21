<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Accounts Accounts
 * @ingroup     UnaModules
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
            // some params
            'PARAM_PROFILE_NAME_LENGTH_MAX' => 10,
            'PARAM_EXPORT_TO' => 'bx_accounts_export_to',
            'PARAM_EXPORT_FIELDS' => 'bx_accounts_export_fields',

            // page URIs
            'URL_MANAGE_ADMINISTRATION' => 'page.php?i=accounts-administration',

            // objects
            'OBJECT_STORAGE_FILES' => 'sys_files',
            'OBJECT_FORM_ACCOUNT' => 'bx_accounts_account',
            'OBJECT_FORM_ACCOUNT_DISPLAY_SETTINGS_EMAIL' => 'bx_accounts_account_settings_email',
            'OBJECT_FORM_ACCOUNT_DISPLAY_CREATE' => 'bx_accounts_account_create',
            'OBJECT_MENU_MANAGE_TOOLS' => 'bx_accounts_menu_manage_tools', //manage menu in content administration tools
            'OBJECT_GRID_ADMINISTRATION' => 'bx_accounts_administration',
            'OBJECT_GRID_MODERATION' => 'bx_accounts_moderation',

                // some language keys
            'T' => array (
                'grid_action_err_delete' => '_bx_accnt_grid_action_err_delete',
                'grid_action_err_perform' => '_bx_accnt_grid_action_err_perform',
                'filter_item_active' => '_bx_accnt_grid_filter_item_title_adm_active',
                'filter_item_operators' => '_bx_accnt_grid_filter_item_title_adm_operators',
                'filter_item_pending' => '_bx_accnt_grid_filter_item_title_adm_pending',
                'filter_item_suspended' => '_bx_accnt_grid_filter_item_title_adm_suspended',
                'filter_item_unconfirmed' => '_bx_accnt_grid_filter_item_title_adm_unconfirmed',
                'filter_item_locked' => '_bx_accnt_grid_filter_item_title_adm_locked',
                'filter_item_without_profile' => '_bx_accnt_grid_filter_item_title_adm_without_profile',
                'filter_item_select_one_filter1' => '_bx_accnt_grid_filter_item_title_adm_select_one_filter1',
                'filter_item_select_one_filter2' => '_bx_accnt_grid_filter_item_title_adm_select_one_filter2',
            )
        );

        $this->_aObjects = array(
            'alert' => $this->_sName,
        );

        $this->_aJsClasses = array(
            'main' => 'BxAccntMain',
            'manage_tools' => 'BxAccntManageTools'
        );

        $this->_aJsObjects = array(
            'main' => 'oBxAccntMain',
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

            'password_text' => $sHtmlPrefix . '-txt-password',
            'password_button' => $sHtmlPrefix . '-btn-copy',
            'password_popup' => $sHtmlPrefix . '-password-popup',
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
