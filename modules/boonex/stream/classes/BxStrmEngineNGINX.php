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

class BxStrmEngineNGINX extends BxDol
{
    public function getViewersNum($sStreamKey)
    {
        $sHost = getParam('bx_stream_server_host');
        $sApp = getParam('bx_stream_app');

        if (!$sHost || !$sApp)
            return false;

        $sUrl = str_replace(
            ['{host}', '{app}', '{key}'], 
            [$sHost, $sApp, $sStreamKey], 
            getParam('bx_stream_server_nginx_stats_url')
        );
        $s = bx_file_get_contents($sUrl);
        if (!$s)
            return false;

        $o = @simplexml_load_string ($s);
        if (!$o)
            return false;

        foreach ($o->server->application as $oApp) {
            if ($sApp == $oApp->name && $sStreamKey == $oApp->live->stream->name)
                return (int)$oApp->live->stream->nclients;
        }

        return false;
    }

    public function getRtmpSettings($sStreamKey)
    {
        $sHost = getParam('bx_stream_server_host');
        $sApp = getParam('bx_stream_app');
        
        if (!$sHost || !$sApp)
            return false;

        $sBaseUrl = "rtmp://$sHost:1935/$sApp";
        return ['server' => $sBaseUrl, 'key' => $sStreamKey];
    }

    public function getRtmpIngestUrl($sStreamKey)
    {
        if (!($a = $this->getRtmpSettings($sStreamKey)))
            return false;
        list ($sBaseUrl, $sStreamKey) = $a;
        return $a['server'] . '/' . $a['key'];
    }
}

/** @} */
