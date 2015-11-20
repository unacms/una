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

class BxInvResponse extends BxDolAlertsResponse
{
	protected $_sModule;
	protected $_oModule;

    public function __construct()
    {
        parent::__construct();

        $this->_sModule = 'bx_invites';
        $this->_oModule = BxDolModule::getInstance($this->_sModule);
    }

    /**
     * Overwtire the method of parent class.
     *
     * @param BxDolAlerts $oAlert an instance of alert.
     */
    public function response($oAlert)
    {
		$sMethod = '_process' . bx_gen_method_name($oAlert->sUnit . '_' . $oAlert->sAction);
		if(!method_exists($this, $sMethod))
			return;

		return $this->$sMethod($oAlert);
    }

    protected function _processAccountAddForm($oAlert)
    {
    	if(!$this->_oModule->_oConfig->isRegistrationByInvitation())
    		return;

    	$oSession = BxDolSession::getInstance();

		$sKeyCode = $this->_oModule->_oConfig->getKeyCode();
		if(bx_get($sKeyCode) !== false) {
			$sKey = bx_process_input(bx_get($sKeyCode));

        	$oKeys = BxDolKey::getInstance();
        	if($oKeys && $oKeys->isKeyExists($sKey))
		    	$oSession->setValue($sKeyCode, $sKey);
		}

		$sKey = $oSession->getValue($sKeyCode);
		if($sKey === false)
			$oAlert->aExtras['form_code'] = $this->_oModule->_oTemplate->getBlockRequest();

		return;    	
    }

    protected function _processAccountAdded($oAlert)
    {
    	if(!$this->_oModule->_oConfig->isRegistrationByInvitation())
    		return;

    	$sKeyCode = $this->_oModule->_oConfig->getKeyCode();

    	$sKey = BxDolSession::getInstance()->getUnsetValue($sKeyCode);
    	if($sKey === false)
    		return;

		$oKeys = BxDolKey::getInstance();
    	if($oKeys && $oKeys->isKeyExists($sKey))
    		$oKeys->removeKey($sKey);

    	return;
    }

    protected function _processProfileDelete($oAlert)
    {
    	$this->_oModule->_oDb->deleteInvites(array('profile_id' => $oAlert->iObject));
    }
}

/** @} */
