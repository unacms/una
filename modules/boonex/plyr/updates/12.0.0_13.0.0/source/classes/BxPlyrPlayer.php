<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Plyr Plyr player integration
 * @ingroup     UnaModules
 * 
 * @{
 */

/**
 * Plyr player integration.
 * @see BxDolPlayer
 */
class BxPlyrPlayer extends BxDolPlayer
{
    /**
     * Standard view initialization params
     */
    protected static $CONF_STANDARD = '
    <div {attrs_wrapper}>
        <video {attrs}>
            {webm}
            {mp4}
            {captions}
        </video>
    </div>
    ';

    /**
     * Standard view initialization params for audio
     */
    protected static $CONF_STANDARD_AUDIO = '
    <div {attrs_wrapper}>
        <audio {attrs}>
            {mp3}
        </audio>
    </div>
    ';

    /**
     * Minimal view initialization params
     */
    protected static $CONF_MINI = "";

    /**
     * Embed view initialization params
     */
    protected static $CONF_EMBED = "";


    protected $_oTemplate;
    protected $_bJsCssAdded = false;

    public function __construct ($aObject, $oTemplate)
    {
        parent::__construct ($aObject);

        if ($oTemplate)
            $this->_oTemplate = $oTemplate;
        else
            $this->_oTemplate = BxDolTemplate::getInstance();

        $this->_aSkins = array(
        );
    }

    public function getCodeAudio ($iViewMode, $aParams, $bDynamicMode = false)
    {
        $aControls = explode(',', 'play-large,play,progress,current-time,mute,volume,pip,airplay');
        $aSettings = array();
        return $this->_getCodePlyr (self::$CONF_STANDARD_AUDIO, 'bx-player-plyr', $aControls, $aSettings, $iViewMode, $aParams, $bDynamicMode);
    }
    
    public function getCodeVideo ($iViewMode, $aParams, $bDynamicMode = false)
    {
        $aControls = explode(',', getParam('bx_plyr_option_controls'));
        $aSettings = explode(',', getParam('bx_plyr_option_settings'));
        return $this->_getCodePlyr (self::$CONF_STANDARD, 'bx-player-plyr', $aControls, $aSettings, $iViewMode, $aParams, $bDynamicMode);
    }

    protected function _getCodePlyr ($sInit, $sClass, $aControls, $aSettings, $iViewMode, $aParams, $bDynamicMode = false)
    {        
        // set visual mode
        switch ($iViewMode) {
        case BX_PLAYER_MINI:
            $sClass .= ' bx-player-plyr-mini';
            break;
        case BX_PLAYER_EMBED:
            $sClass .= ' bx-player-plyr-embed';
            break;
        case BX_PLAYER_STANDARD:
        default:
            $sClass .= ' bx-player-plyr-standard';
            break;
        }

        // attrs

        $sId = isset($aParams['attrs']) && isset($aParams['attrs']['id']) ? $aParams['attrs']['id'] : 'BxPlyr' . mt_rand();
        $aAttrsDefault = array(
            'id' => $sId,
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
        $sAttrs = bx_convert_array2attrs($aAttrs);

        $aAttrsWrapper = isset($aParams['attrs_wrapper']) && is_array($aParams['attrs_wrapper']) ? $aParams['attrs_wrapper'] : array();
        $sAttrsWrapper = bx_convert_array2attrs($aAttrsWrapper, 'bx-player-plyr-wrapper', isset($aParams['styles']) && is_string($aParams['styles']) ? $aParams['styles'] : false);

        
        // generate files list for HTML5 player
        
        $aTypes = array(
            'webm' => '<source type="video/webm" src="{url}" size="{size}" />',
            'mp4' => '<source type="video/mp4" src="{url}" size="{size}" />',
            'mp3' => '<source type="audio/mpeg" src="{url}" />',
        );              
        $mp4 = '';
        $webm = '';
        $mp3 = '';
        foreach ($aTypes as $s => $ss) {
            if (!isset($aParams[$s]))
                continue;
            if (is_array($aParams[$s])) {
                foreach ($aParams[$s] as $sType => $sUrl) {
                    if (empty($sUrl) || !isset($this->_aSizes[$sType]))
                        continue;
                    $$s .= str_replace(
                        array('{url}', '{size}'), 
                        array($sUrl, $this->_aSizes[$sType]), 
                        $ss
                    );
                }
            }
            elseif (is_string($aParams[$s]) && !empty($aParams[$s]))
                $$s = str_replace('{url}', $aParams[$s], $ss);
            else
                $$s = '';
        }

        // player code

        $sCode = $this->_replaceMarkers($sInit, array(
            'attrs' => $sAttrs,
            'attrs_wrapper' => $sAttrsWrapper,
            'webm' => $webm,
            'mp4' => $mp4,
            'mp3' => $mp3,
            'captions' => isset($aParams['captions']) ? $aParams['captions'] : '',
        ));

        // plyr initialization

        if (!($sFormat = getParam('sys_player_default_format')))
            $sFormat = 'sd';
        $aOptions = array_merge(array(
            // 'debug' => true,
            'quality' => array('default' => $this->_aSizes[$sFormat]),
            'displayDuration' => false,
            'controls' => $aControls,
            'settings' => $aSettings,
            'ratio' => '16:9',
        ), $this->_aConfCustom);
        $sInitEditor = "
            glBxPlyr" . mt_rand() . " = new Plyr('#$sId', " . json_encode($aOptions) . ");
        ";

        // load necessary JS and CSS

        if ($bDynamicMode) {

            list($aJs, $aCss) = $this->_getJsCss(true);
            
            $sCss = $this->_oTemplate->addCss($aCss, true);
            
            $sScript = $sCss . "<script>
                if ('undefined' == typeof(window.Plyr)) {
                    bx_get_scripts(" . json_encode($aJs) . ", function () {
                        $sInitEditor
                    });
                } else {
                	setTimeout(function () {
                    	$sInitEditor
                    }, 10); // wait while html is rendered in case of dynamic adding html
                }
            </script>";

        } else {            
                
            $sScript = "
            <script>
                $(document).ready(function () {
                    if (bx_is_selector_in_stylesheet('.plyr')) {
                        $sInitEditor
                    } 
                    else {
                    	setTimeout(function () {
                        	$sInitEditor
                        }, 250);
                    }
                });
            </script>";

        }

        return $this->_addJsCss($bDynamicMode) . $sScript . $sCode;
    }

    /**
     * Add css/js files which are needed for editor display and functionality.
     */
    protected function _addJsCss($bDynamicMode = false, $sInitEditor = '')
    {
        if ($bDynamicMode)
            return '';
        if ($this->_bJsCssAdded)
            return '';

        list($aJs, $aCss) = $this->_getJsCss();

        $this->_oTemplate->addJs($aJs);
        $this->_oTemplate->addCss($aCss);
        $this->_bJsCssAdded = true;
        return '';
    }

    protected function _getJsCss($bUseUrlsForJs = false)
    {
        $sJsPrefix = $bUseUrlsForJs ? BX_DOL_URL_MODULES : BX_DIRECTORY_PATH_MODULES;
        $sJsSuffix = $bUseUrlsForJs ? '' : '|';
        
        $aJs = array(
            $sJsPrefix . 'boonex/plyr/plugins/plyr/' . $sJsSuffix . 'plyr.min.js',
        );
        
        $aCss = array(
            BX_DIRECTORY_PATH_MODULES . 'boonex/plyr/plugins/plyr/|plyr.css',
            BX_DIRECTORY_PATH_MODULES . 'boonex/plyr/template/css/|main.css',
        );
        
        return array($aJs, $aCss);
    }
}

/** @} */
