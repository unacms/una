<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * Basic HTML5 player representation.
 * @see BxDolPlayer
 */
class BxBasePlayerHTML5 extends BxDolPlayer
{
    /**
     * Standard view initialization params
     */
    protected static $CONF_STANDARD = "
        <video {attrs}>
            {webm}
            {mp4}
        </video>
    ";

    /**
     * Standard view initialization params for audio
     */
    protected static $CONF_STANDARD_AUDIO = "
        <audio {attrs}>
            {mp3}
        </audio>
    ";

    /**
     * Minimal view initialization params
     */
    protected static $CONF_MINI = "";

    /**
     * Embed view initialization params
     */
    protected static $CONF_EMBED = "";

    /**
     * Available player languages
     */
    protected static $CONF_LANGS = array();

    protected $_oTemplate;
    protected $_bJsCssAdded = false;

    public function __construct ($aObject, $oTemplate)
    {
        parent::__construct ($aObject);

        if ($oTemplate)
            $this->_oTemplate = $oTemplate;
        else
            $this->_oTemplate = BxDolTemplate::getInstance();
    }

    public function getCodeAudio ($iViewMode, $aParams, $bDynamicMode = false)
    {
        return $this->_getCode(self::$CONF_STANDARD_AUDIO, 'bx-player', $iViewMode, $aParams, $bDynamicMode);
    }

    public function getCodeVideo ($iViewMode, $aParams, $bDynamicMode = false)
    {
        return $this->_getCode(self::$CONF_STANDARD, 'bx-player', $iViewMode, $aParams, $bDynamicMode);
    }

    protected function _getCode ($sInit, $sClass, $iViewMode, $aParams, $bDynamicMode = false)
    {
        // set visual mode
        switch ($iViewMode) {
        case BX_PLAYER_MINI:
            $sClass .= ' bx-player-mini';
            break;
        case BX_PLAYER_EMBED:
            $sClass .= ' bx-player-embed';
            break;
        case BX_PLAYER_STANDARD:
        default:
            $sClass .= ' bx-player-standard';
        }

        // attrs
        $aAttrsDefault = array(
            'controls' => '',
            'controlsList' => 'nodownload',
            'preload' => 'none',
            'autobuffer' => '', 
            'class' => $sClass,
        );
        $aAttrs = isset($aParams['attrs']) && is_array($aParams['attrs']) ? $aParams['attrs'] : array();
        $aAttrs = array_merge($aAttrsDefault, $aAttrs);
        if (isset($aParams['poster']) && is_string($aParams['poster']))
            $aAttrs['poster'] = $aParams['poster'];
        $sAttrs = bx_convert_array2attrs($aAttrs, false, isset($aParams['styles']) && is_string($aParams['styles']) ? $aParams['styles'] : false);

        // generate files list for HTML5 player
        $sFormat = getParam('sys_player_default_format');
        $aTypes = array(
            'webm' => '<source type="video/webm; codecs=\'vp8, vorbis\'" src="{url}" />',
            'mp4' => '<source type="video/mp4" src="{url}" />',
            'mp3' => '<source type="audio/mpeg" src="{url}" />',
        );
        foreach ($aTypes as $s => $ss) {
            if (!isset($aParams[$s]))
                continue;
            if (is_array($aParams[$s]) && !empty($aParams[$s][$sFormat]))
                $aParams[$s] = str_replace('{url}', $aParams[$s][$sFormat], $ss);
            elseif (is_array($aParams[$s]) && !empty($aParams[$s]['sd']))
                $aParams[$s] = str_replace('{url}', $aParams[$s]['sd'], $ss);
            elseif (is_string($aParams[$s]) && !empty($aParams[$s]))
                $aParams[$s] = str_replace('{url}', $aParams[$s], $ss);
            else
                $aParams[$s] = '';
        }

        // player code
        $sCode = $this->_replaceMarkers($sInit, array(
            'attrs' => $sAttrs,
            'webm' => isset($aParams['webm']) ? $aParams['webm'] : '',
            'mp4' => isset($aParams['mp4']) ? $aParams['mp4'] : '',
            'mp3' => isset($aParams['mp3']) ? $aParams['mp3'] : '',
        ));

        return $sCode;
    }
}

/** @} */
