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

    public function entryStreamBroadcast ($aContentInfo)
    {
        $oEngine = $this->getModule()->getStreamEngine();
        if (!$oEngine->isSreamFromBrowser()) 
            return MsgBox(_t('_bx_stream_from_webcam_not_supported'));

        $mixedMsg = $this->getModule()->checkAllowedAdd (false);
        if ($mixedMsg !== CHECK_ACTION_RESULT_ALLOWED)
            return MsgBox($mixedMsg);

        $CNF = &$this->getModule()->_oConfig->CNF;

        return $this->parseHtmlByName('stream_broadcast_ome.html', array(
            'suffix' => md5($aContentInfo[$CNF['FIELD_KEY']]),
            'broadcast_url' => $oEngine->getWebrtcIngestUrl($aContentInfo[$CNF['FIELD_KEY']]),
            'popup_share' => $this->popupShare($aContentInfo),
            'popup_settings' => $this->popupSettings($aContentInfo),
        ));
    }

    public function popupShare ($aContentInfo)
    {
        $CNF = &$this->getModule()->_oConfig->CNF;

        $sUrlViewStream = 'page.php?i=' . $CNF['URI_VIEW_ENTRY'] . '&id=' . (int)$aContentInfo['id'];
        $sUrlViewStream = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink($sUrlViewStream);
        $sUrlEmbed = BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'embed/' . (int)$aContentInfo['id'];

        $aForm = array(
            'form_attrs' => array(
                'name' => 'bx-stream-form-share',
                'class' => 'bx-def-margin',
            ),
            'inputs' => array(
                'url' => array(
                    'type' => 'text',
                    'name' => 'url'.time(),
                    'caption' => _t('_bx_stream_field_link'),
                    'value' => $sUrlViewStream,
                    'attrs' => array('readonly' => 'readonly'),
                ),
                'embed' => array(
                    'type' => 'text',
                    'name' => 'embed'.time(),
                    'caption' => _t('_bx_stream_field_embed'),
                    'value' => '<iframe width="560" height="315" frameborder="0" allow="autoplay; picture-in-picture" allowfullscreen src="' . $sUrlEmbed . '"></iframe>',
                    'attrs' => array('readonly' => 'readonly'),
                ),
            ),
        );
        $oForm = new BxTemplFormView ($aForm);

        return BxTemplFunctions::getInstance()->popupBox('bx-stream-popup-share', _t('_bx_stream_popup_share'), $oForm->getCode(), true);
    }

    public function popupSettings ($aContentInfo)
    {
        $CNF = &$this->getModule()->_oConfig->CNF;

        $aForm = array(
            'form_attrs' => array(
                'name' => 'bx-stream-form-settings',
                'onsubmit' => 'return false',
                'onreset' => 'return false',
            ),
            'inputs' => array(
                'audio' => array(
                    'type' => 'select',
                    'name' => 'audio',
                    'caption' => _t('_bx_stream_field_audio_source'),
                    'value' => '',
                ),
                'video' => array(
                    'type' => 'select',
                    'name' => 'video',
                    'caption' => _t('_bx_stream_field_video_source'),
                    'value' => '',
                ),
            ),
        );
        $oForm = new BxTemplFormView ($aForm);

        return BxTemplFunctions::getInstance()->popupBox('bx-stream-popup-settings', _t('_bx_stream_popup_settings'), $oForm->getCode(), true);
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
            'mute' => getParam('bx_stream_mute') ? 1 : 0,
        ));
    }

    public function embedStream($aContentInfo)
    {
        $CNF = &$this->getModule()->_oConfig->CNF;

        // TODO: visibility and other checks

        $sCode = $this->parseHtmlByName('stream_embed.html', array(
            'player' => $this->entryStreamPlayer ($aContentInfo),
            'viewers' => $this->entryStreamViewers ($aContentInfo),
        ));

        $this->addCss('main.css');

        // display page
        $oTemplate = BxDolTemplate::getInstance();
        $oTemplate->setPageNameIndex (BX_PAGE_EMBED);
        $oTemplate->setPageHeader ($aContentInfo[$CNF['FIELD_TITLE']]);
        $oTemplate->setPageContent ('page_main_code', $sCode);
        $oTemplate->getPageCode();
        exit;
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
