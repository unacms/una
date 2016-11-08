<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Payment Payment
 * @ingroup     UnaModules
 *
 * @{
 */

class BxPaymentResponse extends BxDolAlertsResponse
{
	protected $MODULE;
    protected $_oModule;

	public function __construct()
    {
    	$this->MODULE = 'bx_payment';

        parent::__construct();

        $this->_oModule = BxDolModule::getInstance($this->MODULE);
    }

	/**
     * Overwtire the method of parent class.
     *
     * @param BxDolAlerts $oAlert an instance of alert.
     */
    public function response($oAlert)
    {
    	if($oAlert->sUnit != 'profile' || !in_array($oAlert->sAction, array('join', 'delete')))
    		return;

		switch($oAlert->sAction) {
			case 'join':
				$this->_oModule->onProfileJoin($oAlert->iObject);
				break;

			case 'delete':
				$this->_oModule->onProfileDelete($oAlert->iObject);
				break;
		}
    }
}

/** @} */
