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

class BxSitesConfig extends BxDolModuleConfig
{
    public static $FIELD_AUTHOR = 'owner_id';
    public static $FIELD_ADDED = 'created';

    protected $_oDb;

    //General
    protected $sDomainMask;
    protected $aJsClasses;
    protected $aJsObjects;
    protected $sAnimationEffect;
    protected $iAnimationSpeed;

    //PayPal payments integration
    protected $iTrialMaxNumber;
    protected $fTrialPrice;
    protected $sTrialPeriod;
    protected $iTrialFrequency;
    protected $iTrialBillingCycles;
    protected $fRegularPrice;
    protected $sRegularPeriod;
    protected $iRegularFrequency;
    protected $iRegularBillingCycles;
    protected $sEmailBusiness;
    protected $sEmailSandbox;
    protected $bDemoMode;
    protected $sCurrencyCode;
    protected $sCurrencySign;

    protected $aPeriodConverter;

    /**
     * Constructor
     */
    function __construct($aModule)
    {
        parent::__construct($aModule);

        $this->sDomainMask = '%s.online.me';
        $this->aJsClasses = array('main' => 'BxSitesMain');
        $this->aJsObjects = array('main' => 'oSitesMain');
        $this->sAnimationEffect = 'fade';
        $this->iAnimationSpeed = 'slow';

        $this->aPeriodConverter = array(
            'Day' => 86400,
            'Week' => 604800,
            'SemiMonth' => 1296000,
            'Month' => 2592000,
            'Year' => 31536000
        );
    }

    function init(&$oDb)
    {
        $this->_oDb = $oDb;

        $this->iTrialMaxNumber = (int)getParam('bx_sites_payment_trial_max_number');
        $this->fTrialPrice = (float)getParam('bx_sites_payment_trial_price');
        $this->sTrialPeriod = getParam('bx_sites_payment_trial_period');
        $this->iTrialFrequency = (float)getParam('bx_sites_payment_trial_frequency');
        $this->iTrialBillingCycles = 1;
        $this->fRegularPrice = (float)getParam('bx_sites_payment_regular_price');
        $this->sRegularPeriod = getParam('bx_sites_payment_regular_period');
        $this->iRegularFrequency = (float)getParam('bx_sites_payment_regular_frequency');
        $this->iRegularBillingCycles = 0;
        $this->sEmailBusiness = getParam('bx_sites_payment_email_business');
        $this->sEmailSandbox = getParam('bx_sites_payment_email_sandbox');
        $this->bDemoMode = getParam('bx_sites_payment_demo_mode') == 'on';
        $this->sCurrencyCode = getParam('bx_sites_payment_currency_code');
        $this->sCurrencySign = getParam('bx_sites_payment_currency_sign');
    }

    function getDomainMask()
    {
        return $this->sDomainMask;
    }
    function getPaymentPrice($sType)
    {
        $fResult = 0;
        switch($sType) {
            case BX_SITES_PP_PERIOD_TRIAL:
                $fResult = $this->fTrialPrice;
                break;
            case BX_SITES_PP_PERIOD_REGULAR:
                $fResult = $this->fRegularPrice;
                break;
        }
        return $fResult;
    }

    function getPaymentPeriod($sType, $bInSeconds = false)
    {
        $sResult = 0;
        switch($sType) {
            case BX_SITES_PP_PERIOD_TRIAL:
                $sResult = $this->sTrialPeriod;
                break;
            case BX_SITES_PP_PERIOD_REGULAR:
                $sResult = $this->sRegularPeriod;
                break;
        }
        return $bInSeconds ? $this->aPeriodConverter[$sResult] : $sResult;
    }

    function getPaymentFrequency($sType)
    {
        $iResult = 0;
        switch($sType) {
            case BX_SITES_PP_PERIOD_TRIAL:
                $iResult = $this->iTrialFrequency;
                break;
            case BX_SITES_PP_PERIOD_REGULAR:
                $iResult = $this->iRegularFrequency;
                break;
        }
        return $iResult;
    }

    function getPaymentBillingCycles($sType)
    {
        $iResult = 0;
        switch($sType) {
            case BX_SITES_PP_PERIOD_TRIAL:
                $iResult = $this->iTrialBillingCycles;
                break;
            case BX_SITES_PP_PERIOD_REGULAR:
                $iResult = $this->iRegularBillingCycles;
                break;
        }
        return $iResult;
    }

    function isPaymentDemo()
    {
        return $this->bDemoMode;
    }

    function getPaymentEmail()
    {
        return $this->isPaymentDemo() ? $this->sEmailSandbox : $this->sEmailBusiness;
    }

    function getCurrencyCode()
    {
        return $this->sCurrencyCode;
    }

    function getCurrencySign()
    {
        return $this->sCurrencySign;
    }

    function getTrialDuration()
    {
        return $this->getPaymentPeriod(BX_SITES_PP_PERIOD_TRIAL, true) * $this->iTrialFrequency * $this->iTrialBillingCycles;
    }

    function getTrialMaxNumber()
    {
        return $this->iTrialMaxNumber;
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
