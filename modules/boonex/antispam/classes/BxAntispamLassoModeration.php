<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Antispam Antispam
 * @ingroup     UnaModules
 *
 * @{
 */

class BxAntispamLassoModeration extends BxDol
{
    protected $_sModule;
    protected $_oModule;

    protected $_sApiKey;
    protected $_sWebhookSecret;
    protected $_sEndpoint;

    public function __construct()
    {
        parent::__construct();

        $this->_sModule = 'bx_antispam';
        $this->_oModule = BxDolModule::getInstance($this->_sModule);

        $this->_sApiKey = $this->_oModule->_oConfig->getAntispamOption('lm_api_key');
        $this->_sWebhookSecret = $this->_oModule->_oConfig->getAntispamOption('lm_webhook_secret');

        $this->_sEndpoint = 'https://api.lassomoderation.com/api/v1';
    }

    public function processEvent()
    {
        $sInput = @file_get_contents("php://input");
        
        $sInputHmac = hash_hmac('sha256', $sInput, $this->_sWebhookSecret, true);
        $sInputHmac = 'sha256=' . base64_encode($sInputHmac);
        
        if($sInputHmac !== '')
            return 404;
        
        $aEvent = json_decode($sInput, true);
        if(empty($aEvent) || !is_array($aEvent)) 
            return 404;

        //TODO: Do something here.

        //$this->_onHarmfulContentFound();

        return 200;
    }

    public function addContent($sModule, $iId, $aData = [])
    {
        $aModule = BxDolModuleQuery::getInstance()->getModuleByName($sModule);
        if(empty($aModule) || !is_array($aModule))
            return false;

        $iAuthorId = !empty($aData['author_id']) ? $aData['author_id'] : bx_srv($sModule, 'get_author', [$iId]);
        $sAuthorName = !empty($aData['author_name']) ? $aData['author_name'] : BxDolProfile::getInstanceMagic($iAuthorId)->getDisplayName();

        $iDataAdded = !empty($aData['date_added']) ? $aData['date_added'] : bx_srv($sModule, 'get_date_added', [$iId]);

        $sText = !empty($aData['text']) ? $aData['text'] : bx_srv($sModule, 'get_text', [$iId]);

        $aParams = [
            'project' => [
                'id' => md5(BX_DOL_URL_ROOT),
                'name' => getParam('site_title'),
            ],
            'topic' => [
                'id' => 'mod_' . $aModule['id'],
                'name' => $aModule['title'],
            ],
            'user' => [
                'id' => 'prof_' . $iAuthorId,
                'name' => $sAuthorName
            ],
            'content_id' => $sModule . '_' . $iId,
            'created_at' => bx_time_utc($iDataAdded ? $iDataAdded : time()),
            'text' => $sText,
            'image_urls' => isset($aData['images']) ? $aData['images'] : [],
            'video_urls' => isset($aData['videos']) ? $aData['videos'] : []
        ];

        return $this->_call('/content', $aParams);
    }

    public function log($mixedContents, $sTitle = '')
    {
        $this->_oModule->log($mixedContents, 'Lasso Moderation', $sTitle);
    }

    /**
     * Internal methods.
     */
    protected function _call($sRequest, $aParams, $sMethod = 'post-json', $aHeaders = [])
    {
        $aHeaders[] = 'Authorization: Bearer ' . $this->_sApiKey;           

        $sResult = bx_file_get_contents($this->_sEndpoint . $sRequest, $aParams, $sMethod, $aHeaders);
        if(empty($sResult)) {
            $this->log($sResult, 'Call (' . $sRequest . '): ');
            $this->log($aParams);
            return false;
        }

        $aResult = json_decode($sResult, true);
        if(empty($aResult) || !is_array($aResult) || !isset($aResult['success'])) {
            $this->log($sResult, 'Call (' . $sRequest . '): ');
            $this->log($aParams);
            return false;
        }

        return true;
    }
    
    protected function _onHarmfulContentFound($sModule, $iContentId)
    {        
        if(!$sModule || $this->_oConfig->getAntispamOption('lm_report') != 'on') 
            return;

        $oModule = BxDolModule::getInstance($sModule);
        $CNF = &$oModule->_oConfig->CNF;
        $sContentUrl = isset($CNF['URI_VIEW_ENTRY']) ? bx_absolute_url(BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_VIEW_ENTRY'] . '&id=' . $iContentId)) : false;
        $sManageContentUrl = isset($CNF['URL_MANAGE_ADMINISTRATION']) ? bx_absolute_url(BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URL_MANAGE_ADMINISTRATION'])) : false;

        $oProfile = BxDolProfile::getInstance();
        if(!$oProfile)
            return;

        $aPlus = array(
            'AuthorUrl' => $oProfile->getUrl(),
            'AuthorNickName' => $oProfile->getDisplayName(),
            'Page' => htmlspecialchars_adv($_SERVER['PHP_SELF']),
            'bx_if:content_url' => [
                'condition' => boolval($sContentUrl),
                'content' => ['c_url' => $sContentUrl],
            ],
            'bx_if:manage_content_url' => [
                'condition' => boolval($sManageContentUrl),
                'content' => ['m_url' => $sManageContentUrl],
            ],
        );

        $aTemplate = BxDolEmailTemplates::getInstance()->parseTemplate('bx_antispam_lasso_moderation_report', $aPlus);
        if(!$aTemplate)
            trigger_error('Email template or translation missing: bx_antispam_lasso_moderation_report', E_USER_ERROR);

        sendMail(getParam('site_email'), $aTemplate['Subject'], $aTemplate['Body']);

        bx_alert('bx_antispam', 'harmful_content_posted', $iContentId, bx_get_logged_profile_id(), [
            'module' => $sModule,
            'entry_id' => $iContentId,
            'entry_url' => $sContentUrl,
        ]);
    }
}

/** @} */
