<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Sites Sites
 * @ingroup     TridentModules
 *
 * @{
 */

bx_import('BxDolAlerts');

class BxSitesResponse extends BxDolAlertsResponse
{
    protected $_oModule;

    /**
     * Constructor
     * @param BxTimelineModule $oModule - an instance of current module
     */
    public function __construct()
    {
        parent::__construct();

        $this->_oModule = BxDolModule::getInstance('bx_sites');
    }

    /**
     * Overwtire the method of parent class.
     *
     * @param BxDolAlerts $oAlert an instance of alert.
     */
    public function response($oAlert)
    {
        if($oAlert->sUnit != 'account' || !in_array($oAlert->sAction, array('login')))
            return;

        $sDomain = BxDolSession::getInstance()->getUnsetValue('bx_sites_domain');
        if($sDomain === false)
            return;

        $iAccountId = $this->_oModule->_oDb->insertAccount(array(
            'owner_id' => $oAlert->iObject,
            'domain' => $sDomain,
            'created' => time(),
            'status' => BX_SITES_ACCOUNT_STATUS_UNCONFIRMED
        ));

        if(!$iAccountId)
            return;

        $oAccount = $this->_oModule->getObject('Account');
        $oAccount->onAccountCreated($iAccountId);

        $sUrl = $this->_oModule->startSubscription($iAccountId);
        header('Location: ' . $sUrl);
        exit;
    }
}

/** @} */
