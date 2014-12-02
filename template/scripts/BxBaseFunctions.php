<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinCore Dolphin Core
 * @{
 */

bx_import('BxDolTemplate');

class BxBaseFunctions extends BxDol implements iBxDolSingleton
{
    protected $_oTemplate;
    protected $_sDesignBoxIcon = 'chevron';

    protected function __construct($oTemplate)
    {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error ('Multiple instances are not allowed for the class: ' . get_class($this), E_USER_ERROR);

        parent::__construct();

        $this->_oTemplate = $oTemplate ? $oTemplate : BxDolTemplate::getInstance();
    }

    /**
     * Prevent cloning the instance
     */
    public function __clone()
    {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error('Clone is not allowed for the class: ' . get_class($this), E_USER_ERROR);
    }

    public static function getInstance()
    {
        if(!isset($GLOBALS['bxDolClasses']['BxTemplFunctions']))
            $GLOBALS['bxDolClasses']['BxTemplFunctions'] = new BxTemplFunctions();

        return $GLOBALS['bxDolClasses']['BxTemplFunctions'];
    }

    function TemplPageAddComponent($sKey)
    {
        switch( $sKey ) {
            case 'something':
                return false; // return here additional components
            default:
                return false; // if you have not such component, return false!
        }
    }

    function msgBox($sText, $iTimer = 0, $sOnClose = "")
    {
        $iId = time() . mt_rand(1, 1000);

        return $this->_oTemplate->parseHtmlByName('messageBox.html', array(
            'id' => $iId,
            'msgText' => $sText,
            'bx_if:timer' => array(
                'condition' => $iTimer > 0,
                'content' => array(
                    'id' => $iId,
                    'time' => 1000 * $iTimer,
                    'on_close' => $sOnClose,
                )
            )
        ));
    }

    /**
     * Get standard popup box with title.
     *
     * @param  string $sName    - unique name
     * @param  string $sTitle   - translated title
     * @param  string $sContent - content of the box
     * @return HTML   string
     */
    function popupBox($sName, $sTitle, $sContent, $isHiddenByDefault = false)
    {
        $iId = !empty($sName) ? $sName : time();

        return $this->_oTemplate->parseHtmlByName('popup_box.html', array(
            'id' => $iId,
            'wrapper_style' => $isHiddenByDefault ? 'display:none;' : '',
            'title' => $sTitle,
            'content' => $sContent
        ));
    }

    /**
     * Get popup box without title.
     *
     * @param  string $sName    - unique name
     * @param  string $sContent - content of the box
     * @return HTML   string
     */
    function transBox($sName, $sContent, $isHiddenByDefault = false, $isPlaceInCenter = false)
    {
        $iId = !empty($sName) ? $sName : time();

        return
            ($isPlaceInCenter ? '<div class="login_ajax_wrap">' : '') .
                $this->_oTemplate->parseHtmlByName('popup_trans.html', array(
                    'id' => $iId,
                    'wrapper_style' => $isHiddenByDefault ? 'display:none;' : '',
                    'content' => $sContent
                )) .
            ($isPlaceInCenter ? '</div>' : '');
    }

    function slideBox($sName, $sContent, $isHiddenByDefault = false)
    {
        $iId = !empty($sName) ? $sName : time();

        return $this->_oTemplate->parseHtmlByName('popup_slide.html', array(
            'id' => $iId,
            'wrapper_style' => $isHiddenByDefault ? 'display:none;' : '',
            'content' => $sContent
        ));
    }

    function getTemplateIcon($sName)
    {
        $sUrl = $this->_oTemplate->getIconUrl($sName);
        return !empty($sUrl) ? $sUrl : $this->_oTemplate->getIconUrl('spacer.gif');
    }

    function getTemplateImage($sName)
    {
        $sUrl = $this->_oTemplate->getImageUrl($sName);
        return !empty($sUrl) ? $sUrl : $this->_oTemplate->getImageUrl('spacer.gif');
    }

    function sysIcon ($sIcon, $sName, $sUrl = '', $iWidth = 0)
    {
        return '<div class="sys_icon">' . ($sUrl ? '<a title="'.$sName.'" href="'.$sUrl.'">' : '') . '<img alt="'.$sName.'" src="'.$sIcon.'" '.($iWidth ? 'width='.$iWidth : '').' />' . ($sUrl ? '</a>' : '') . '</div>';
    }

    /**
     * functions for limiting maximal string length
     */
    function getStringWithLimitedLength($sString, $iWidth = 45, $isPopupOnOverflow = false, $bReturnString = true)
    {
        if (empty($sString) || mb_strlen($sString, 'UTF-8') <= $iWidth)
            return $bReturnString ? $sString : array($sString);

        $sResult = '';
        $aWords = mb_split("[\s\r\n]", $sString);
        $iPosition = 0;
        $iWidthReal = $iWidth - 3;
        $iWidthMin = $iWidth - 15;
        foreach($aWords as $sWord) {
            $sWord = trim($sWord);
            $iWord = mb_strlen($sWord, 'UTF-8');
            if ($iPosition + $iWord > $iWidthReal)
                break;

            // add word and continue
            $sResult .= ' ' . $sWord;
            $iPosition += 1 + $iWord;
        }

        // last word is too long, cut it
        if(!$iPosition || $iPosition < $iWidthMin)
            $sResult .= ' ' . mb_substr($sWord, 0, $iWidthReal - $iPosition - $iWord, 'UTF-8');
        $sResult = trim($sResult);

        // add tripple dot
        if(!$isPopupOnOverflow) {
            $sResult .= '...';
            return $bReturnString ? $sResult : array($sResult);
        }

        // add button width popup
        $sId = 'bx-str-limit-' . rand(1, PHP_INT_MAX);
        $sPopup = '<img class="bx-str-limit" onclick="$(\'#' . $sId . '\').dolPopup({pointer:{el:$(this), offset:\'10 1\'}})" src="' . $this->getTemplateImage('str-limit.png') . '"/>';
        $sPopup .= '<div id="' . $sId . '" style="display:none;">' . BxTemplFunctions::getInstance()->transBox('', '<div class="bx-def-padding bx-def-color-bg-block">'.$sString.'</div>') . '</div>';

        return $bReturnString ? $sResult . $sPopup : array($sResult, $sPopup);
    }

    /**
     * Display design box with specified title, template, content and menu.
     * @param $sTitle - design box title, please note that some templates don't use title.
     * @param $sContent - design box content.
     * @param $iTemplateNum - number of design box template, use predefined contants only, default is BX_DB_DEF.
     * @param $mixedMenu - design box menu, it can be:
     *      - object: instance of BxTemplMenu class
     *      - string: menu object identifier
     *      - array: array of menu links to create menu from
     * @return string
     *
     * @see BX_DB_CONTENT_ONLY
     * @see BX_DB_DEF
     * @see BX_DB_EMPTY
     * @see BX_DB_NO_CAPTION
     * @see BX_DB_PADDING_CONTENT_ONLY
     * @see BX_DB_PADDING_DEF
     * @see BX_DB_PADDING_NO_CAPTION
     */
    function designBoxContent ($sTitle, $sContent, $iTemplateNum = BX_DB_DEF, $mixedMenu = false)
    {
        return $this->_oTemplate->parseHtmlByName('designbox_' . (int)$iTemplateNum . '.html', array(
            'title' => $sTitle,
            'designbox_content' => $sContent,
            'caption_item' => $this->designBoxMenu ($mixedMenu, array (array('menu' => 1))),
        ));
    }

    function designBoxMenu ($mixedMenu, $aButtons = array ())
    {
        $sCode = '';
        $aButtonMenu = false;
        if ($mixedMenu) {

            $sMenu = '';

            if (is_string($mixedMenu)) {

                bx_import('BxTemplMenu');
                $oMenu = BxTemplMenu::getObjectInstance($mixedMenu);
                $sMenu = $oMenu ? $oMenu->getCode () : $mixedMenu;

            } elseif (is_object($mixedMenu) && is_a($mixedMenu, 'BxTemplMenu')) {

                $sMenu = $mixedMenu->getCode();

            } elseif (is_array($mixedMenu)) {

                if (isset($mixedMenu['template']) && isset($mixedMenu['menu_items']))
                    $aMenu = $mixedMenu;
                else
                    $aMenu = array ('template' => 'menu_vertical.html', 'menu_items' => $mixedMenu);
                bx_import('BxTemplMenu');
                $oMenu = new BxTemplMenu($aMenu, $this->_oTemplate);
                $sMenu = $oMenu->getCode ();
            }

            if ($sMenu) {
                $sId = 'bx-menu-db-' . time() . rand(0, PHP_INT_MAX);
                $sCode .= $this->slideBox($sId, '<div class="bx-def-padding">' . $sMenu . '</div>', true);
                $aButtonMenu = array ('icon-a' => $this->_sDesignBoxIcon, 'onclick' => "bx_menu_slide('#" . $sId . "', this)");
            }

        }

        if ($aButtons) {
            $sCode .= '<div class="bx-db-menu"><div class="bx-db-menu-tab bx-db-menu-tab-btn">';
            foreach ($aButtons as $aButton) {
                if (isset($aButton['menu']) && $aButton['menu']) {
                    if (!$aButtonMenu)
                        continue;
                    $aButton = $aButtonMenu;
                }

                $aAttrs = array ();
                if (!empty($aButton['onclick']))
                    $aAttrs['onclick'] = $aButton['onclick'];
                if (!empty($aButton['class']))
                    $aAttrs['class'] = $aButton['class'];
                $sAttrs = bx_convert_array2attrs ($aAttrs, 'bx-def-margin-sec-left');

                $sCode .= '<a href="javascript:void(0);" ' . $sAttrs . '>';
                $sCode .= !empty($aButton['icon']) ? '<i class="sys-icon ' . $aButton['icon'] . ' bx-def-font-h2"></i>' : '';
                $sCode .= !empty($aButton['icon-a']) ? '<i class="sys-icon-a" data-rotate="down" data-icon="' . $aButton['icon-a'] . '"></i>' : '';
                $sCode .= !empty($aButton['title']) ? $aButton['title'] : '';
                $sCode .= '</a>';
            }
            $sCode .= '</div></div>';
        }

        return $sCode;
    }

    /**
     * Get logo HTML.
     * @return string
     */
    function getMainLogo()
    {
        bx_import('BxDolConfig');

        $sAlt = getParam('sys_site_logo_alt') ? getParam('sys_site_logo_alt') : getParam('site_title');
        $sLogo = $sAlt;

        $iFileId = (int)getParam('sys_site_logo');
        if ($iFileId) {
            bx_import('BxDolStorage');
            $oStorage = BxDolStorage::getObjectInstance(BX_DOL_STORAGE_OBJ_IMAGES);
            $sFileUrl = $oStorage->getFileUrlById($iFileId);
            if ($sFileUrl)
                $sLogo = '<img src="' . $sFileUrl . '" id="bx-logo" class="bx-def-margin-sec" alt="' . bx_html_attribute($sAlt, BX_ESCAPE_STR_QUOTE) . '" />';
        }

        return '<a class="bx-def-font-contrasted" href="' . BX_DOL_URL_ROOT . '" title="' . bx_html_attribute($sAlt, BX_ESCAPE_STR_QUOTE) . '">' . $sLogo . '</a>';
    }

    /**
     * Get HTML code for meta icons.
     * @return HTML string to insert into HEAD section
     */
    function getMetaIcons()
    {
        $iId = (int)getParam('sys_site_icon');
        $sImageUrlFav = $sImageUrlFcb = $sImageUrlApl = '';

        if(!empty($iId)) {
            bx_import('BxDolTranscoderImage');

            // favicon icon
            $oTranscoder = BxDolTranscoderImage::getObjectInstance(BX_DOL_TRANSCODER_OBJ_ICON_FAVICON);
            $sImageUrlFav = $oTranscoder->getFileUrl($iId);

            // facebook icon
            $oTranscoder = BxDolTranscoderImage::getObjectInstance(BX_DOL_TRANSCODER_OBJ_ICON_FACEBOOK);
            $sImageUrlFcb = $oTranscoder->getFileUrl($iId);

            // apple touch icon
            $oTranscoder = BxDolTranscoderImage::getObjectInstance(BX_DOL_TRANSCODER_OBJ_ICON_APPLE);
            $sImageUrlApl = $oTranscoder->getFileUrl($iId);
        }

        if(empty($sImageUrlFav))
            $sImageUrlFav = $this->_oTemplate->getIconUrl('favicon.png');

        if(empty($sImageUrlFcb))
            $sImageUrlFcb = $this->_oTemplate->getIconUrl('facebook-icon.png');

        if(empty($sImageUrlApl))
            $sImageUrlApl = $this->_oTemplate->getIconUrl('apple-touch-icon.png');

        $sRet = '';
        $sRet .= '<link rel="icon" sizes="16x16" type="image/png" href="' . $sImageUrlFav . '" />';
        $sRet .= '<link rel="image_src" sizes="100x100" href="' . $sImageUrlFcb . '" />';
        $sRet .= '<link rel="apple-touch-icon" sizes="152x152" href="' . $sImageUrlApl . '" />';

        return $sRet;
    }

    /**
     * Output time wrapped in <time> tag in HTML.
     * Then time is autoformatted using JS upon page load, this is aumatically converted to user's timezone and
     * updated in realtime in case of short periods of 'from now' time format.
     *
     * Short version of this function:
     * @see bx_time_js
     *
     * @param $iUnixTimestamp time as unixtimestamp
     * @param $sFormatIdentifier output format identifier
     *     @see BX_FORMAT_DATE
     *     @see BX_FORMAT_TIME
     *     @see BX_FORMAT_DATE_TIME
     * @param $bForceFormat force provided format and don't use "from now" time autoformat.
     */
    function timeForJs ($iUnixTimestamp, $sFormatIdentifier = BX_FORMAT_DATE, $bForceFormat = false)
    {
        $sDateUTC = bx_time_utc ($iUnixTimestamp);
        return '<time datetime="' . $sDateUTC . '" data-bx-format="' . getParam($sFormatIdentifier) . '" data-bx-autoformat="' . ($bForceFormat ? 0 : getParam('sys_format_timeago')) . '">' . $sDateUTC . '</time>';
    }

    function statusOnOff ($mixed, $isMsg = false)
    {
        if ((is_bool($mixed) && !$mixed) || (is_string($mixed) && 'fail' == $mixed))
            return '<i class="sys-icon circle col-red2"></i> ' . ($isMsg ? _t('_sys_off') : '');
        elseif (is_string($mixed) && 'warn' == $mixed)
            return '<i class="sys-icon circle col-red3"></i> ' . ($isMsg ? _t('_sys_warn') : '');
        elseif (is_string($mixed) && 'undef' == $mixed)
            return '<i class="sys-icon circle col-gray"></i> ' . ($isMsg ? _t('_undefined') : '');
        else
            return '<i class="sys-icon circle col-green1"></i> ' . ($isMsg ? _t('_sys_on') : '');
    }

    /**
     * Ouputs HTML5 video player.
     * @param $sUrlPoster video poster image
     * @param $sUrlMP4 .mp4 video
     * @param $sUrlWebM .webm video
     * @param $aAttrs custom attributes, defaults are: controls="" preload="none" autobuffer=""
     * @param $sStyles custom styles, defaults are: width:100%; height:auto;
     */
    function videoPlayer ($sUrlPoster, $sUrlMP4, $sUrlWebM = '', $aAttrs = false, $sStyles = '')
    {
        $aAttrsDefaults = array(
            'controls' => '',
            'preload' => 'none',
            'autobuffer' => '', 
        );
        $aAttrs = array_merge($aAttrsDefaults, is_array($aAttrs) ? $aAttrs : array());
        $sAttrs = bx_convert_array2attrs($aAttrs, '', 'width:100%; height:auto;' . trim($sStyles));

        return '<video ' . $sAttrs . ' poster="' . $sUrlPoster . '">
                    ' . ($sUrlWebM ? '<source type="video/webm; codecs="vp8, vorbis" src="' . $sUrlWebM . '" />' : '') . '
                    ' . ($sUrlMP4  ? '<source type="video/mp4" src="' . $sUrlMP4 . '" />' : '') . '
                </video>';
    }
}

/** @} */
