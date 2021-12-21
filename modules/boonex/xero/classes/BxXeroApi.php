<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defdroup    Xero Xero
 * @indroup     UnaModules
 *
 * @{
 * 
 * @see https://github.com/XeroAPI/xero-php-oauth2
 * @see https://xeroapi.github.io/xero-php-oauth2/docs/v2/accounting/index.html
 */

require __DIR__ . '/../plugins/autoload.php';

use XeroAPI\XeroPHP\AccountingObjectSerializer;

class BxXeroApi extends BxDol
{
    protected $_oModule;
    protected $_oLog;

    protected $_oProvider;

    public function __construct(&$oModule)
    {
        $this->_oModule = $oModule;
        $this->_oLog = $this->_oModule->_oConfig->getObjectLog();
    }

    public function authorize()
    {
        $oProvider = $this->_getProvider();

        $aOptions = [
            'scope' => ['openid email profile offline_access assets projects accounting.settings accounting.transactions accounting.contacts accounting.journals.read accounting.reports.read accounting.attachments']
        ];

        $sAuthorizationUrl = $oProvider->getAuthorizationUrl($aOptions);

        $this->_oModule->_oConfig->setState($oProvider->getState());

        return $sAuthorizationUrl;
    }

    public function isAuthorized()
    {
        $sTenantId = $this->_oModule->_oConfig->getTenantId();
        if($sTenantId === false)
            return false;

        if(!$this->_oModule->_oConfig->isDataExpired())
            return true;
            
        $sRefreshToken = $this->_oModule->_oConfig->getRefreshToken();
        if($sRefreshToken === false) 
            return false;

        $oAccessToken = $this->_getProvider()->getAccessToken('refresh_token', [
            'refresh_token' => $sRefreshToken
        ]);

        $this->_oModule->_oConfig->setData(
            $oAccessToken->getToken(),
            $oAccessToken->getExpires(),
            $sTenantId,
            $oAccessToken->getRefreshToken(),
            $oAccessToken->getValues()['id_token']
        );

        return true;
    }

    public function callback($sCode)
    {
        try {
            $oProvider = $this->_getProvider();

            $oAccessToken = $oProvider->getAccessToken('authorization_code', [
              'code' => $sCode
            ]);

            $sAccessToken = (string)$oAccessToken->getToken();

            $oXeroConfig = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken($sAccessToken);
            $oIdentity = new XeroAPI\XeroPHP\Api\IdentityApi(new GuzzleHttp\Client(), $oXeroConfig);

            $aResults = $oIdentity->getConnections();

            $this->_oModule->_oConfig->setData(
                $oAccessToken->getToken(),
                $oAccessToken->getExpires(),
                $aResults[0]->getTenantId(),
                $oAccessToken->getRefreshToken(),
                $oAccessToken->getValues()['id_token']
            );

            return $this->_oModule->_oConfig->getAuthorizeUrl(0);
        }
        catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $oException) {
            $this->_oLog->write('Callback failed: ', $oException->getMessage());
            return false;
        }
    }

    public function actionTest()
    {
        $mixedApi = $this->_getApiObject();
        if($mixedApi === false)
            return false;

        list($sTenantId, $oXeroApi) = $mixedApi;

        $oXeroResponse = $oXeroApi->getOrganisations($sTenantId);

        return $oXeroResponse->getOrganisations()[0]->getName();
    }

    public function actionAddContact($iProfileId, $sProfileName, $sProfileEmail)
    {
        $mixedApi = $this->_getApiObject();
        if($mixedApi === false)
            return false;

        list($sTenantId, $oXeroApi) = $mixedApi;

        $mixedResult = false;
        try {
            $oContact = new XeroAPI\XeroPHP\Models\Accounting\Contact;
            $oContact->setName($sProfileName)
                ->setEmailAddress($sProfileEmail)
                ->setContactNumber($iProfileId);

            $aContacts = [$oContact];
            $oContacts = new XeroAPI\XeroPHP\Models\Accounting\Contacts;
            $oContacts->setContacts($aContacts);

            $oXeroResponse = $oXeroApi->createContacts($sTenantId, $oContacts);
            if(count($oXeroResponse->getContacts()) == 1) {
                $mixedResult = $oXeroResponse->getContacts()[0]->getContactId();

                $this->_oModule->_oDb->insertContact([
                    'email' => $sProfileEmail, 
                    'contact' => $mixedResult, 
                    'added' => time()
                ]);
            }
        }
        catch (Exception $oException) {
            $this->_oLog->write('Add Contact: ' . $oException->getMessage());
        }

        return $mixedResult;
    }

    public function actionGetContact($sProfileEmail)
    {
        $mixedApi = $this->_getApiObject();
        if($mixedApi === false)
            return false;

        list($sTenantId, $oXeroApi) = $mixedApi;

        $mixedResult = false;
        try {
            $oXeroResponse = $oXeroApi->getContacts($sTenantId, null, 'EmailAddress="' . $sProfileEmail . '"');
            if(count($oXeroResponse->getContacts()) == 1 && $oXeroResponse->getContacts()[0]->getEmailAddress() == $sProfileEmail)
                $mixedResult = $oXeroResponse->getContacts()[0];
        }
        catch (Exception $oException) {
            $this->_oLog->write('Get Contact: ' . $oException->getMessage());
        }

        return $mixedResult;
    }

    /**
     * Get contacts by parameter(s).
     * 
     * @param DateTime $oDate - Only records created or modified since this timestamp will be returned. Example: new DateTime("2019-01-02T19:20:30+01:00")
     * @param string $sWhere
     * @param string $sOrder
     * @param string $aIds - Filter by a comma-separated list of Invoice Ids.
     * @param int $iPage - e.g. page=1 – Up to 100 invoices will be returned in a single API call with line items.
     * @param bool $bIncludeArchived - e.g. includeArchived=true - Contacts with a status of ARCHIVED will be included
     * @return array of contacts or boolean false on failure.
     */
    public function actionGetContacts($oDate = null, $sWhere = null, $sOrder = null, $aIds = null, $iPage = 1, $bIncludeArchived = false)
    {
        $mixedApi = $this->_getApiObject();
        if($mixedApi === false)
            return false;

        list($sTenantId, $oXeroApi) = $mixedApi;

        $mixedResult = false;
        try {
            $oXeroResponse = $oXeroApi->getContacts($sTenantId, $oDate, $sWhere, $sOrder, $aIds, $iPage, $bIncludeArchived);
            if(count($oXeroResponse->getContacts()) > 0)
                $mixedResult = $oXeroResponse->getContacts();
        }
        catch (Exception $oException) {
            $this->_oLog->write('Get Contacts: ' . $oException->getMessage());
        }

        return $mixedResult;
    }

    public function actionIsContact($sProfileEmail)
    {
        $mixedResult = $this->actionGetContact($sProfileEmail);
        if($mixedResult === false)
            return $mixedResult;

        if(!$this->_oModule->_oDb->isContact($sProfileEmail))
            $this->_oModule->_oDb->insertContact([
                'email' => $sProfileEmail,
                'contact' => $mixedResult->getContactId(),
                'added' => time()
            ]);

        return true;
    }

    /**
     * 
     * @param type $sProfileEmail
     * @param type $sName
     * @param type $mixedAmount - float Amount or an array with float Amount and float Tax Amount.
     * @param type $iQuantity
     * @param mixed $mixedDueDate - e.g. string '2021-12-01' or a date object
     * @param type $sAccount
     * @return invoice ID or boolean false on failure.
     */
    public function actionAddInvoice($sProfileEmail, $sName, $mixedAmount, $iQuantity = 1, $mixedDueDate = false, $sAccount = BX_XERO_ACCOUNT_CODE_SALES)
    {
        $mixedApi = $this->_getApiObject();
        if($mixedApi === false)
            return false;

        list($sTenantId, $oXeroApi) = $mixedApi;

        $mixedResult = false;
        try {
            if(empty($mixedDueDate))
                $mixedDueDate = date_add(new DateTime(), new DateInterval('P1M'));
            else if(is_string($mixedDueDate))
                $mixedDueDate = new DateTime($mixedDueDate);

            $fAmount = $mixedAmount;
            $fTaxAmount = $sTaxType = false;
            if(is_array($mixedAmount))
                switch(count($mixedAmount)) {
                    case 2:
                        list($fAmount, $fTaxAmount) = $mixedAmount;
                        break;

                    case 3:
                        list($fAmount, $fTaxAmount, $sTaxType) = $mixedAmount;
                        break;
                }

            $sLineAmountType = \XeroAPI\XeroPHP\Models\Accounting\LineAmountTypes::NO_TAX;

            $oLineItem = new XeroAPI\XeroPHP\Models\Accounting\LineItem;
            $oLineItem->setDescription($sName)
                ->setUnitAmount($fAmount)
                ->setQuantity($iQuantity)
                ->setAccountCode($sAccount);
            if($fTaxAmount !== false) {
                $sLineAmountType = \XeroAPI\XeroPHP\Models\Accounting\LineAmountTypes::EXCLUSIVE;

                $oLineItem->setTaxAmount($fTaxAmount);
            }
            if($sTaxType !== false)
                $oLineItem->setTaxType($sTaxType);

            $aLineItems = [$oLineItem];		

            $oContact = $this->actionGetContact($sProfileEmail);
            if(!$oContact)
                return false;

            $oInvoiceContact = new XeroAPI\XeroPHP\Models\Accounting\Contact;
            $oInvoiceContact->setContactId($oContact->getContactId());

            $oInvoice = new XeroAPI\XeroPHP\Models\Accounting\Invoice;
            $oInvoice->setReference('Ref-' . $this->_getRandNum())
                ->setDueDate($mixedDueDate)
                ->setContact($oInvoiceContact)
                ->setLineItems($aLineItems)
                ->setStatus(XeroAPI\XeroPHP\Models\Accounting\Invoice::STATUS_AUTHORISED)
                ->setType(XeroAPI\XeroPHP\Models\Accounting\Invoice::TYPE_ACCREC)
                ->setLineAmountTypes($sLineAmountType);

            $oXeroResponse = $oXeroApi->createInvoices($sTenantId, $oInvoice); 
            if(count($oXeroResponse->getInvoices()) == 1)
                $mixedResult = $oXeroResponse->getInvoices()[0]->getInvoiceId();
        }
        catch (Exception $oException) {
            $this->_oLog->write('Add Invoice: ' . $oException->getMessage());
        }

        return $mixedResult;
    }

    /**
     * Note. Isn't used for now.
     */
    public function actionGetInvoices($oDate = null, $sWhere = null, $sOrder = null, $aIds = null, $aNumbers = null, $aContactIds = null, $aStatuses = null, $iPage = 1, $bIncludeArchived = false)
    {
        $mixedApi = $this->_getApiObject();
        if($mixedApi === false)
            return false;

        list($sTenantId, $oXeroApi) = $mixedApi;

        $mixedResult = false;
        try {
            $oXeroResponse = $oXeroApi->getInvoices($sTenantId, $oDate, $sWhere, $sOrder, $aIds, $aNumbers, $aContactIds, $aStatuses, $iPage, $bIncludeArchived);
            if(count($oXeroResponse->getInvoices()) > 0)
                $mixedResult = $oXeroResponse->getInvoices();
        }
        catch (Exception $oException) {
            $this->_oLog->write('Get Invoices: ' . $oException->getMessage());
        }

        return $mixedResult;
    }

    public function actionGetInvoice($sId, $iUnitDp = 2)
    {
        $mixedApi = $this->_getApiObject();
        if($mixedApi === false)
            return false;

        list($sTenantId, $oXeroApi) = $mixedApi;

        $mixedResult = false;
        try {
            $oXeroResponse = $oXeroApi->getInvoice($sTenantId, $sId, $iUnitDp);
            if(count($oXeroResponse->getInvoices()) == 1)
                $mixedResult = $oXeroResponse->getInvoices()[0];
        }
        catch (Exception $oException) {
            $this->_oLog->write('Get Invoice: ' . $oException->getMessage());
        }

        return $mixedResult;
    }

    public function sendInvoice($sInvoiceId)
    {
        $mixedApi = $this->_getApiObject();
        if($mixedApi === false)
            return false;

        list($sTenantId, $oXeroApi) = $mixedApi;

        $bResult = true;
        try {
            $oRequestEmpty = new XeroAPI\XeroPHP\Models\Accounting\RequestEmpty;

            $oXeroApi->emailInvoice($sTenantId, $sInvoiceId, $oRequestEmpty);            
        } 
        catch (Exception $oException) {
            $this->_oLog->write('Send Invoice: ' . $oException->getMessage());
            $bResult = false;
        }

        return $bResult;
    }

    protected function _getProvider()
    {
        if(empty($this->_oProvider))
            $this->_oProvider = new \League\OAuth2\Client\Provider\GenericProvider([
                'clientId' => $this->_oModule->_oConfig->getClientId(),
                'clientSecret' => $this->_oModule->_oConfig->getClientSecret(),
                'redirectUri' => $this->_oModule->_oConfig->getRedirectUrl(),
                'urlAuthorize' => 'https://login.xero.com/identity/connect/authorize',
                'urlAccessToken' => 'https://identity.xero.com/connect/token',
                'urlResourceOwnerDetails' => 'https://api.xero.com/api.xro/2.0/Organisation'
            ]);

        return $this->_oProvider;
    }
    
    protected function _getApiObject()
    {
        $sTenantId = $this->_oModule->_oConfig->getTenantId();
        if($sTenantId === false) {
            $this->_oLog->write('Fetch failed: Empty Tenant ID.');
            return false;
        }

        if(!$this->isAuthorized()) {
            $this->_oLog->write('Fetch failed: Unauthorized access.');
            return false;
        }

        $sAccessToken = $this->_oModule->_oConfig->getAccessToken();
        $oXeroConfig = XeroAPI\XeroPHP\Configuration::getDefaultConfiguration()->setAccessToken($sAccessToken);
        $oXeroApi = new XeroAPI\XeroPHP\Api\AccountingApi(
            new GuzzleHttp\Client(),
            $oXeroConfig
        );

        return array($sTenantId, $oXeroApi);
    }

    public function _getRandNum()
    {
        return strval(rand(1000, 100000));
    }
}