<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Stream Stream
 * @ingroup     UnaModules
 *
 * @{
 */

class BxStrmEngineOvenMediaEngine extends BxDol
{
    public function getViewersNum($sStreamKey)
    {
        $a = $this->getStreamStats($sStreamKey);
        if (!$a)
            return false;

        return $a['totalConnections'];
    }

    public function getStreamStats($sStreamKey)
    {
        $sApiProtocol = getParam('bx_stream_server_ome_api_protocol');
        $sHost = getParam('bx_stream_server_host');
        $iApiPort = (int)getParam('bx_stream_server_ome_api_port');
        $sApp = getParam('bx_stream_app');
        $sApiKey = getParam('bx_stream_server_ome_api_key');

        if (!$sApiProtocol || !$sHost || !$iApiPort || !$sApp || !$sApiKey)
            return false;

        $sUrl = "http://{$sHost}:$iApiPort/v1/stats/current/vhosts/default/apps/{$sApp}/streams/{$sStreamKey}";
        $s = bx_file_get_contents($sUrl, [], 'get', ['Authorization: Basic ' . $sApiKey]);
        bx_log('bx_stream_ome_api', $sUrl . "\n" . $s);
        if (!$s)
            return false;

        $a = @json_decode($s, true);
        if (null === $a)
            return false;

        if (!isset($a['response']))
            return false;

        return $a['response'];
    }

    public function getRtmpSettings($sStreamKey)
    {
        $sHost = getParam('bx_stream_server_host');
        $sApp = getParam('bx_stream_app');
        $sPolicySecret = getParam('bx_stream_server_ome_policy_secret');
        
        if (!$sHost || !$sApp)
            return false;

        $sBaseUrl = "rtmp://$sHost:1935/$sApp";
        if (!$sPolicySecret)
            return ['server' => $sBaseUrl, 'key' => $sStreamKey];

        return ['server' => $sBaseUrl, 'key' => str_replace($sBaseUrl . '/', '', $this->_signUrl($sBaseUrl . '/' . $sStreamKey, $sPolicySecret))];
    }

    public function getRtmpIngestUrl($sStreamKey)
    {
        if (!($a = $this->getRtmpSettings($sStreamKey)))
            return false;
        list ($sBaseUrl, $sStreamKey) = $a;
        return $a['server'] . '/' . $a['key'];
        

        $sHost = getParam('bx_stream_server_host');
        $sApp = getParam('bx_stream_app');
        $sPolicySecret = getParam('bx_stream_server_ome_policy_secret');
        
        if (!$sHost || !$sApp)
            return false;

        $sBaseUrl = "rtmp://$sHost:1935/$sApp/$sStreamKey";
        if (!$sPolicySecret)
            return $sBaseUrl;

        return $this->_signUrl($sBaseUrl, $sPolicySecret);
    }

    protected function _signUrl($sBaseUrl, $sPolicySecret)
    {
        $sSignatureQueryKeyName = 'signature';
        $sPolicyQueryKeyName = 'policy';
        $sPolicy = json_encode(['url_expire' => 2556104400000]);//(time() + 50*365*24*60*60)*1000]);

        // 1. Perform base64url() for POLICY (RFC 4648 5.)        
        $sPolicyBase64 = $this->_base64URLEncode($sPolicy);

        // 2. Generates an URL such as "ws://ome_host:3333/app/stream?policy=${POLICY_BASE64}"
        $sPolicyUrl = bx_append_url_params($sBaseUrl, [$sPolicyQueryKeyName => $sPolicyBase64]);

        // 3. Perform sha1(base64url()) for SIGNATURE (RFC 4648 5.)
        $sSha1 = hash_hmac('sha1', $sPolicyUrl, $sPolicySecret, true);
        $sSignature = $this->_base64URLEncode($sSha1);

        // 4. Create the whole URL
        $sWholeUrl = bx_append_url_params($sPolicyUrl, [$sSignatureQueryKeyName => $sSignature]);

        return $sWholeUrl;
    }

    protected function _base64URLEncode($s)
    {
        return trim(strtr(base64_encode($s), '+/', '-_'), '=');
    }
}

/** @} */
