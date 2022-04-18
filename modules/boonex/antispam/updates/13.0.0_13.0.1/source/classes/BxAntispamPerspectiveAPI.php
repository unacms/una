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

/**
 * Toxicity detection based on the message content and logged in user info - http://www.perspectiveapi.com
 */

define('BX_ANTISPAM_PERSPECTIVE_API_URL', 'https://commentanalyzer.googleapis.com/v1alpha1/comments:analyze');

class BxAntispamPerspectiveAPI extends BxDol
{
    private $_sAPIKey;
    private $_iThreshold;

    public function __construct()
    {
        parent::__construct();
        $this->_sAPIKey = getParam('bx_antispam_toxicity_filter_api_key');
        $this->_iThreshold = getParam('bx_antispam_toxicity_filter_threshold');
    }

    public function isToxic ($s)
    {
        if (!$this->_sAPIKey || !$this->_iThreshold)
            return false;

        $aRequest = [
            'comment' => ['text' => $s],
            'doNotStore' => true,
            'requestedAttributes' => [
                'TOXICITY' => [],
            ],
        ];

        $sResponse = bx_file_get_contents(
            bx_append_url_params(BX_ANTISPAM_PERSPECTIVE_API_URL, ['key' => $this->_sAPIKey]),
            $aRequest,
            'post-json-object'
        );

        $aResponse = [];
        if ($sResponse) $aResponse = json_decode($sResponse, true);

        // if we get any score then compare it with a threshold
        if ($aResponse && isset($aResponse['attributeScores']) && isset($aResponse['attributeScores']['TOXICITY']) && isset($aResponse['attributeScores']['TOXICITY']['summaryScore'])) {
            return $aResponse['attributeScores']['TOXICITY']['summaryScore']['value'] >= $this->_iThreshold/100;
        }

        // in any other case consider this text as non toxic
        return false;
    }

    public function onPositiveDetection ($sExtraData = '')
    {
        $o = bx_instance('DNSBlacklists', array(), 'bx_antispam');
        $o->onPositiveDetection (getVisitorIP(), $sExtraData, 'toxicity_filter');
    }
}

/** @} */
