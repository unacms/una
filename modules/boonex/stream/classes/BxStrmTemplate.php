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

/*
 * Module representation.
 */
class BxStrmTemplate extends BxBaseModTextTemplate
{
    function __construct(&$oConfig, &$oDb)
    {
        $this->MODULE = 'bx_stream';

        parent::__construct($oConfig, $oDb);
    }

    public function getLiveBadge ($aContentInfo)
    {
        $CNF = &$this->getModule()->_oConfig->CNF;
        if (!$aContentInfo || $aContentInfo[$CNF['FIELD_STATUS']] != 'active')
            return '';

        return $this->parseHtmlByName('stream_badge.html', array(
            'label' => _t('_bx_stream_txt_live_now'),
        ));
    }

    public function entryStreamViewers ($aContentInfo)
    {
        $CNF = &$this->getModule()->_oConfig->CNF;
        return $this->parseHtmlByName('stream_viewers.html', array(
            'viewers' => _t('_bx_stream_txt_viewers_loading'),
            'suffix' => md5($aContentInfo[$CNF['FIELD_KEY']]),
            'url' => BX_DOL_URL_MODULES . '?r=' . $this->getModule()->_oConfig->getUri() . '/stream_viewers/' . $aContentInfo[$CNF['FIELD_ID']]
        ));
    }
 
    public function entryStreamPlayer ($aContentInfo)
    {
        $CNF = &$this->getModule()->_oConfig->CNF;

        $sSources = getParam('bx_stream_sources_pattern');
        $sHost = getParam('bx_stream_server_host');
        $sApp = getParam('bx_stream_app');
        if (!$sSources || !$sHost)
            return MsgBox(_t('_bx_stream_txt_no_config'));

        $sSources = str_replace(
            ['{host}', '{app}', '{key}', '{params}'],
            [$sHost, $sApp, $aContentInfo[$CNF['FIELD_KEY']], ''],
            $sSources
        );

        $this->addJs('ovenplayer/ovenplayer.js');
        if (getParam('bx_stream_dash_enabled'))
            $this->addJs('ovenplayer/dash.all.min.js');
        if (getParam('bx_stream_hls_enabled'))
            $this->addJs('ovenplayer/hls.min.js');
        $this->addJsTranslation('_bx_stream_txt_wait_for_stream');

        return $this->parseHtmlByName('stream_player.html', array(
            'suffix' => md5($aContentInfo[$CNF['FIELD_KEY']]),
            'sources' => $sSources,
        ));
    }

    /**
     * Use Gallery image for both because currently there is no Unit types with small thumbnails.
     */
    protected function getUnitThumbAndGallery ($aData)
    {
        list($sPhotoThumb, $sPhotoGallery) = parent::getUnitThumbAndGallery($aData);

        return array($sPhotoGallery, $sPhotoGallery);
    }
}

/** @} */
