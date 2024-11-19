<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Reputation Reputation
 * @ingroup     UnaModules
 *
 * @{
 */

class BxReputationConfig extends BxBaseModNotificationsConfig
{
    protected $_oDb;

    public function __construct($aModule)
    {
        parent::__construct($aModule);

        $this->CNF = [
            // database tables
            'TABLE_PROFILES' => $aModule['db_prefix'] . 'profiles',

            // database fields
            'FIELD_' => '',

            // some params
            'PARAM_' => 'bx_reputation_',

            // objects
            'OBJECT_FORM_HANDLER' => 'bx_reputation_handler',
            'OBJECT_FORM_HANDLER_DISPLAY_EDIT' => 'bx_reputation_handler_edit',
            'OBJECT_GRID_MANAGE' => 'bx_reputation_manage',

            // some language keys
            'T' => [
                'grid_action_err_perform' => '_bx_reputation_grid_action_err_perform',
                'popup_title_handler_edit' => '_bx_reputation_popup_title_handler_edit',
                'filter_item_select_one_filter1' => '_bx_reputation_grid_filter_item_title_select_one_filter1',
                'err_cannot_perform' => '_bx_reputation_err_cannot_perform',
            ]
        ];

        $this->_aJsClasses = [
            'manage' => 'BxReputationManage',
        ];

        $this->_aJsObjects = [
            'manage' => 'oBxReputationManage',
        ];

        $sHtmlPrefix = str_replace('_', '-', $this->_sName);
        $this->_aHtmlIds = [
            'handler_popup' => $sHtmlPrefix . '-handler-popup',
        ];

        $this->_aHandlerFields = ['group', 'type', 'alert_unit', 'alert_action', 'points_active', 'points_passive'];
        $this->_aHandlerDescriptor = [];
        $this->_sHandlersMethod = 'get_reputation_data';
    }

    public function init(&$oDb)
    {
        parent::init($oDb);
    }
}

/** @} */
