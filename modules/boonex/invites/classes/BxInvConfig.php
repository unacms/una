<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Invites Invites
 * @ingroup     TridentModules
 *
 * @{
 */

class BxInvConfig extends BxBaseModGeneralConfig
{
    protected $_oDb;

    protected $_iCountPerUser;
    protected $_sKeyCode;
    protected $_iKeyLifetime;
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
        );

		$this->_aObjects = array(
			'alert' => $this->_sName,
			'grid_requests' => $this->_sName . '_requests',
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
        $this->_bRequestInvite = true;
        $this->_sRequestsEmail = '';
        $this->_bRegistrationByInvitation = true;
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
        return $this->_iCountPerUser;
    }

	public function getKeyCode()
    {
        return $this->_sKeyCode;
    }

	public function getKeyLifetime()
    {
        return $this->_iKeyLifetime;
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
}

/** @} */
