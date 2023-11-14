<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Invites Invites
 * @ingroup     UnaModules
 *
 * @{
 */

class BxInvConfig extends BxBaseModGeneralConfig
{
    protected $_oDb;

    protected $_iCountPerUser;
    protected $_sKeyCode;
    protected $_iKeyLifetime;
    protected $_sRedirectCode;
    protected $_bRequestInvite;
    protected $_sRequestsEmail;
    protected $_bRegistrationByInvitation;

    /**
     * Constructor
     */
    public function __construct($aModule)
    {
        parent::__construct($aModule);

        $this->CNF = array (
            'URL_INVITE' => 'page.php?i=invites-invite',
            'URL_REQUEST' => 'page.php?i=invites-request',
            'URL_REQUESTS' => 'page.php?i=invites-requests',
            'URL_INVITES' => 'page.php?i=invites-invites',
            
            'TABLE_REQUESTS' => $aModule['db_prefix'] . 'requests',
            'TABLE_INVITES' => $aModule['db_prefix'] . 'invites',
            'TABLE_ENTRIES' => $aModule['db_prefix'] . 'invites',
            
            'FIELD_ADDED' => 'date'
        );

        $this->_aObjects = array(
            'alert' => $this->_sName,
            'grid_requests' => $this->_sName . '_requests',
            'grid_invites' => $this->_sName . '_invites',
            'form_invite' => $this->_sName . '_invite',
            'form_request' => $this->_sName . '_request',
            'form_display_invite_send' => $this->_sName . '_invite_send',
            'form_display_request_send' => $this->_sName . '_request_send',
        );

        $this->_aPrefixes = array(
            'style' => 'bx-inv',
            'option' => 'bx_invites_',
        );

        $this->_iCountPerUser = 0;
        $this->_sKeyCode = 'icode';
        $this->_iKeyLifetime = 86400;
        $this->_sRedirectCode = 'iredirect';
        $this->_bRequestInvite = true;
        $this->_sRequestsEmail = '';
        $this->_bRegistrationByInvitation = true;

        $this->_aJsClasses = array(
            'main' => 'BxInvMain',
        );
        $this->_aJsObjects = array(
            'main' => 'oInvMain',
        );

        $sHtmlPrefix = str_replace('_', '-', $this->_sName);
        $this->_aHtmlIds = array(
            'link_popup' => $sHtmlPrefix . '-link-popup',
            'link_input' => $sHtmlPrefix . '-link-input',
        );
    }

    public function init(&$oDb)
    {
        $this->_oDb = &$oDb;

        $sOptionPrefix = $this->getPrefix('option');

        $this->_iCountPerUser = (int)getParam($sOptionPrefix . 'count_per_user');
        $this->_iKeyLifetime = 86400 * (int)getParam($sOptionPrefix . 'key_lifetime');
        $this->_bRequestInvite = getParam($sOptionPrefix . 'enable_request_invite') == 'on';
        $this->_sRequestsEmail = getParam($sOptionPrefix . 'requests_email');
        $this->_bRegistrationByInvitation = getParam($sOptionPrefix . 'enable_reg_by_inv') == 'on';
    }

    public function getCountPerUser()
    {
        return getActionNumberLeftModule(bx_get_logged_profile_id(), 'invite', $this->_sName);
    }

    public function getKeyCode()
    {
        return $this->_sKeyCode;
    }

    public function getKeyLifetime()
    {
        return $this->_iKeyLifetime;
    }

    public function getRedirectCode()
    {
        return $this->_sRedirectCode;
    }

    public function isRequestInvite()
    {
        return $this->_bRequestInvite;
    }

    public function getRequestsEmail()
    {
        return $this->_sRequestsEmail;
    }

    public function isRegistrationByInvitation()
    {
        return $this->_bRegistrationByInvitation;
    }

    public function getHtmlIds($sKey = '')
    {
        if(empty($sKey))
            return $this->_aHtmlIds;

        return isset($this->_aHtmlIds[$sKey]) ? $this->_aHtmlIds[$sKey] : '';
    }
}

/** @} */
