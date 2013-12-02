<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinCore Dolphin Core
 * @{
 */

bx_import('BxDolTemplate');

class BxBaseFunctions extends BxDol implements iBxDolSingleton {

    protected $_aSpecialKeys;
    protected $_oTemplate;

    function BxBaseFunctions($oTemplate) {

        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error ('Multiple instances are not allowed for the class: ' . get_class($this), E_USER_ERROR);

        parent::BxDol();        

        if ($oTemplate)
            $this->_oTemplate = $oTemplate;
        else
            $this->_oTemplate = BxDolTemplate::getInstance();

        $this->_aSpecialKeys = array('rate' => '', 'rate_cnt' => '');
    }

    /**
     * Prevent cloning the instance
     */
    public function __clone() {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error('Clone is not allowed for the class: ' . get_class($this), E_USER_ERROR);
    }

    public static function getInstance() {
        if(!isset($GLOBALS['bxDolClasses']['BxTemplFunctions']))
            $GLOBALS['bxDolClasses']['BxTemplFunctions'] = new BxTemplFunctions();

        return $GLOBALS['bxDolClasses']['BxTemplFunctions'];
    }

    function TemplPageAddComponent($sKey) {
        switch( $sKey ) {
            case 'something':
                return false; // return here additional components
            default:
                return false; // if you have not such component, return false!
        }
    }

    /**
    * Function will generate object's action link ;
    *
    * @param          : $aObjectParamaters (array) contain special markers ;
    * @param          : $aRow (array) links's info ;
    * @param          : $sCssClass (string) additional css style ;
    * @return         : Html presentation data ;
    */
    function genActionLink( &$aObjectParamaters, $aRow, $sCssClass = null ) {
        // ** init some needed variables ;
        $sOutputHtml = null;

        $aUsedTemplate = array (
            'action' => 'action_link.html'
        );

        // find and replace all special markers ;
        foreach( $aRow AS $sKeyName => $sKeyValue ) {
            if ( $sKeyName == 'Caption' ) {
                $aRow[$sKeyName] =  $this -> markerReplace($aObjectParamaters, $sKeyValue, $aRow['Eval'], true);
            } else {
                $aRow[$sKeyName] =  $this -> markerReplace($aObjectParamaters, $sKeyValue, $aRow['Eval']);
            }
        }

        $sKeyValue = trim($sKeyValue, '{}');

        if ( array_key_exists($sKeyValue, $this->_aSpecialKeys) ) {
            return $aRow['Eval'];
        } else {
            $sSiteUrl = (preg_match("/^(http|https|ftp|mailto)/", $aRow['Url'])) ? '' : BX_DOL_URL_ROOT;
            // build the link components ;
            //$sLinkSrc = (!$aRow['Script']) ? $aRow['Url'] : 'javascript:void(0)';

            $sScriptAction = ( $aRow['Script'] ) ? ' onclick="' . $aRow['Script'] . '"' : '';
            $sScriptAction = ($sScriptAction=='' && $aRow['Url']!='') ? " onclick=\"window.open ('{$sSiteUrl}{$aRow['Url']}','_self');\" " : $sScriptAction;

            $sIcon = getTemplateIcon($aRow['Icon']);

            if ( $aRow['Caption'] and ($aRow['Url'] or $aRow['Script'] ) ) {

                $sCssClass = ( $sCssClass ) ? 'class="' . $sCssClass . '"' :  null;

                $aTemplateKeys = array (
                    'action_img_alt'    => $aRow['Caption'],
                    'action_img_src'    => $sIcon,
                    'action_caption'    => $aRow['Caption'],
                    'extended_css'        => $sCssClass,
                    'extended_action'    => $sScriptAction,
                );
                
                $sOutputHtml .= $this->_oTemplate->parseHtmlByName( $aUsedTemplate['action'], $aTemplateKeys );
            }
        }

        return $sOutputHtml;
    }

    /**
     * Function will parse and replace all special markers ;
     *
     * @param $aMemberSettings (array) : all available member's information
     * @param $sTransformText (text) : string that will to parse
     * @param $bTranslate (boolean) : if isset this param - script will try to translate it used dolphin language file
     * @return (string) : parsed string
    */
    function markerReplace( &$aMemberSettings, $sTransformText, $sExecuteCode = null, $bTranslate = false ) {
        $aMatches = array();
        preg_match_all( "/([a-z0-9\-\_ ]{1,})|({([^\}]+)\})/i", $sTransformText, $aMatches );
        if ( is_array($aMatches) and !empty($aMatches) ) {
            // replace all founded markers ;
            foreach( $aMatches[3] as $iMarker => $sMarkerValue ) {
                if ( is_array($aMemberSettings) and array_key_exists($sMarkerValue, $aMemberSettings) and !array_key_exists($sMarkerValue, $this->_aSpecialKeys) ){
                    $sTransformText = str_replace( '{' . $sMarkerValue . '}', $aMemberSettings[$sMarkerValue],  $sTransformText);
                } else if ( $sMarkerValue == 'evalResult' and $sExecuteCode ) {
                    //find all special markers into Execute code ;
                    $sExecuteCode = $this -> markerReplace( $aMemberSettings, $sExecuteCode );
                    $sTransformText =  str_replace( '{' . $sMarkerValue . '}', eval( $sExecuteCode ),  $sTransformText);
                } else {
                    //  if isset into special keys ;
                    if ( array_key_exists($sMarkerValue, $this->_aSpecialKeys) ) {
                        return $aMemberSettings[$sMarkerValue];
                    } else {
                        // undefined keys
                        switch ($sMarkerValue) {
                        }
                    }
                }
            }

            // try to translate item ;
            if ( $bTranslate ) {
                foreach( $aMatches[1] as $iMarker => $sMarkerValue ) if ( $sMarkerValue )
                    $sTransformText = str_replace( $sMarkerValue , _t( trim($sMarkerValue) ),  $sTransformText);
            }
        }

        return $sTransformText;
    }

    function msgBox($sText, $iTimer = 0, $sOnClose = "") {    	
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

    function loadingBox($sName) {
        return $this->_oTemplate->parseHtmlByName('loading.html', array(
            'name' => $sName,
        ));
    }

    /**
     * Get standard popup box with title.
     *
     * @param string $sName - unique name
     * @param string $sTitle - translated title
     * @param string $sContent - content of the box
     * @return HTML string
     */
    function popupBox($sName, $sTitle, $sContent, $isHiddenByDefault = false) {
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
     * @param string $sName - unique name
     * @param string $sContent - content of the box
     * @return HTML string
     */
    function transBox($sName, $sContent, $isHiddenByDefault = false, $isPlaceInCenter = false) {
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

    function getTemplateIcon($sName) {            
        $sUrl = $this->_oTemplate->getIconUrl($sName);
        return !empty($sUrl) ? $sUrl : $this->_oTemplate->getIconUrl('spacer.gif');
    }

    function getTemplateImage($sName) {
        $sUrl = $this->_oTemplate->getImageUrl($sName);
        return !empty($sUrl) ? $sUrl : $this->_oTemplate->getImageUrl('spacer.gif');
    }

    /**
     * @description : function will generate object's action lists;
     * @param :  $aKeys (array)  - array with all nedded keys;
     * @param :  $sActionsType (string) - type of actions;
     * @param :  $iDivider (integer) - number of column;
     * @return:  HTML presentation data;
    */
    function genObjectsActions( &$aKeys,  $sActionsType, $bSubMenuMode = false ) {
            
        // ** init some needed variables ;
        $sActionsList     = null;
        $sResponceBlock = null;

        $aUsedTemplate    = array (
            'actions'     => 'member_actions_list.html',
            'ajaxy_popup' => 'ajaxy_popup_result.html',
        );

        // read data from cache file ;
        $oCache = BxDolDb::getInstance()->getDbCacheObject();
        $aActions = $oCache->getData(BxDolDb::getInstance()->genDbCacheKey('sys_objects_actions'));

        // if cache file empty - will read from db ;
        if (null === $aActions || empty($aActions[$sActionsType]) ) {

            $sQuery  =     "
                SELECT
                    `Caption`, `Icon`, `Url`, `Script`, `Eval`, `bDisplayInSubMenuHeader`
                FROM
                    `sys_objects_actions`
                WHERE
                    `Type` = '{$sActionsType}'
                ORDER BY
                    `Order`
            ";

            $rResult = db_res($sQuery);
            while ( $aRow = mysql_fetch_assoc($rResult) ) {
                $aActions[$sActionsType][] = $aRow;
            }

            // write data into cache file ;
            if ( is_array($aActions[$sActionsType]) and !empty($aActions[$sActionsType]) ) {
                $oCache->setData (BxDolDb::getInstance()->genDbCacheKey('sys_objects_actions'), $aActions);
            }
        }

        // ** generate actions block ;

        // contain all systems actions that will procces by self function ;
        $aCustomActions = array();
        if ( is_array($aActions[$sActionsType]) and !empty($aActions[$sActionsType]) ) {

            // need for table's divider ;
            $iDivider = $iIndex = 0;
            foreach( $aActions[$sActionsType] as  $aRow ) {
                if ($bSubMenuMode && $aRow['bDisplayInSubMenuHeader']==0) continue;

                $sOpenTag = $sCloseTag = null;

                // generate action's link ;
                $sActionLink = $this -> genActionLink( $aKeys, $aRow, 'menuLink') ;

                if ( $sActionLink ) {
                    $iDivider = $iIndex % 2;

                    if ( !$iDivider ) {
                        $sOpenTag = '<tr>';
                    }

                    if ( $iDivider ) {
                        $sCloseTag = '</tr>';
                    }

                    $aActionsItem[] = array (
                        'open_tag'    => $sOpenTag,
                        'action_link' => $sActionLink,
                        'close_tag'   => $sCloseTag,
                    );

                    $iIndex++;
                }

                // it's system action ;
                if ( !$aRow['Url'] && !$aRow['Script'] ) {
                    $aCustomActions[] =  array (
                        'caption'   => $aRow['Caption'],
                        'code'      => $aRow['Eval'],
                    );
                }
            }
        }

        if ($iIndex % 2 == 1) { //fix for ODD menu elements count
            $aActionsItem[] = array (
                'open_tag'    => '',
                'action_link' => '',
                'close_tag'   => ''
            );
        }

        if ( !empty($aActionsItem) ) {

            // check what response window use ;
            // is there any value to having this template even if the ID is empty?
            if ( !empty($aKeys['ID']) ) {
                $sResponceBlock = $this->_oTemplate->parseHtmlByName( $aUsedTemplate['ajaxy_popup'], array('object_id' => $aKeys['ID']) );
            }

            $aTemplateKeys = array (
                'bx_repeat:actions' => $aActionsItem,
                'responce_block'    => $sResponceBlock,
            );

            $sActionsList = $this->_oTemplate->parseHtmlByName( $aUsedTemplate['actions'], $aTemplateKeys );
        }

        //procces all the custom actions ;
        if ($aCustomActions) {
            foreach($aCustomActions as $iIndex => $sValue ) {
                $sActionsList .= eval( $this -> markerReplace($aKeys, $aCustomActions[$iIndex]['code']) );
            }
        }

        return $sActionsList;
    }

    /**
     * alternative to GenFormWrap
     * easy to use but javascript based
     * $s - content to be centered
     * $sBlockStyle - block's style, jquery selector
     *
     * see also bx_center_content javascript function, if you need to call this function from javascript
     */
    function centerContent ($s, $sBlockStyle, $isClearBoth = true) {
        $sId = 'id' . time() . rand();
        return  '<div id="'.$sId.'">' . $s . ($isClearBoth ? '<div class="clear_both"></div>' : '') . '</div><script>
            $(document).ready(function() {
                var eCenter = $("#'.$sId.'");
                var iAll = $("#'.$sId.' '.$sBlockStyle.'").size();
                var iWidthUnit = $("#'.$sId.' '.$sBlockStyle.':first").outerWidth({"margin":true});
                var iWidthContainer = eCenter.width();
                var iPerRow = parseInt(iWidthContainer/iWidthUnit);
                var iLeft = (iWidthContainer - (iAll > iPerRow ? iPerRow * iWidthUnit : iAll * iWidthUnit)) / 2;
                eCenter.css("padding-left", iLeft);
            });
        </script>';
    }

    function genNotifyMessage($sMessage, $sDirection = 'left', $isButton = false, $sScript = '') {
        $sDirStyle = ($sDirection == 'left') ? '' : 'notify_message_none';
        switch ($sDirection) {
            case 'none': break;
            case 'left': break;
        }

        $sPossibleID = ($isButton) ? ' id="isButton" ' : '';
        $sOnClick = $sScript ? ' onclick="' . $sScript . '"' : '';

        return <<<EOF
<div class="notify_message {$sDirStyle}" {$sPossibleID} {$sOnClick}>
    <table class="notify" cellpadding=0 cellspacing=0><tr><td>{$sMessage}</td></tr></table>
    <div class="notify_wrapper_close"> </div>
</div>
EOF;
    }

    function sysIcon ($sIcon, $sName, $sUrl = '', $iWidth = 0) {
        return '<div class="sys_icon">' . ($sUrl ? '<a title="'.$sName.'" href="'.$sUrl.'">' : '') . '<img alt="'.$sName.'" src="'.$sIcon.'" '.($iWidth ? 'width='.$iWidth : '').' />' . ($sUrl ? '</a>' : '') . '</div>';
    }

    /**
     * Outputs holder html for dynamically loaded RSS.
     * It automatically adds necessary js, css files and make injection into HTML HEAD section.
     * @param $mixedRssId - system rss name, or current block id (if inserted into builder page)
     * @param $iRssNum - numbr of rss items to disolay
     * @param $iMemberId - optional member id
     */
    function getRssHolder ($mixedRssId, $iRssNum, $iMemberId = 0) {


        if (!isset($GLOBALS['gbBxSysIsRssInitialized']) || !$GLOBALS['gbBxSysIsRssInitialized']) {

            $this->_oTemplate->addCss(array(
                'rss.css',
            ));

            $this->_oTemplate->addJs(array(
                'jquery.dolRSSFeed.js',
                'jquery.jfeed.js'
            ));

            $this->_oTemplate->addInjection ('injection_head', 'text', '
                <script type="text/javascript" language="javascript">
                    $(document).ready( function() {
                        $("div.RSSAggrCont").dolRSSFeed();
                    });
                </script>');

            $GLOBALS['gbBxSysIsRssInitialized'] = true;
        }

        return $this->_oTemplate->parseHtmlByName('rss_holder.html', array (
            'rss_id' => $mixedRssId,
            'rss_num' => $iRssNum,
            'member_id' => $iMemberId,
        ));
    }
    
    /**
     * Function will generate list of installed languages list;
     *
     * @return : Html presentation data;
     */
    function getLangSwitcher() {
        bx_import('BxDolLanguagesQuery');
        $aLangs = BxDolLanguagesQuery::getInstance()->getLanguages();
        if(count($aLangs) < 2)
            return '';

        $sOutputCode = '';
        foreach( $aLangs as $sName => $sLang ) {
            $sFlag = $this->_oTemplate->getIconUrl('sys_fl_' . $sName . '.gif');
            $aTemplateKeys = array (
                'bx_if:item_img' => array (
                    'condition' =>  ( $sFlag ),
                    'content'   => array (
                        'item_img_src'      => $sFlag,
                        'item_img_alt'      => $sName,
                        'item_img_width'    => 18,
                        'item_img_height'   => 12,
                    ),
                ),
                'item_link'    => BX_DOL_URL_ROOT . 'index.php?lang=' . $sName,
                'item_onclick' => null,
                'item_title'   => $sLang,
                'extra_info'   => null,
            );

            $sOutputCode .= $this->_oTemplate->parseHtmlByName( 'member_menu_sub_item.html', $aTemplateKeys );
        }
    
        return $sOutputCode;
    }

	/**
     * functions for limiting maximal string length 
     */
    function getStringWithLimitedLength($sString, $iWidth = 45, $isPopupOnOverflow = false, $bReturnString = true) {
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
        $sPopup .= '<div id="' . $sId . '" style="display:none;">' . BxTemplFunctions::getInstance()->transBox('<div class="bx-def-padding bx-def-color-bg-block">'.$sString.'</div>') . '</div>';

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
    function designBoxContent ($sTitle, $sContent, $iTemplateNum = BX_DB_DEF, $mixedMenu = false) {
        return $this->_oTemplate->parseHtmlByName('designbox_' . (int)$iTemplateNum . '.html', array(
            'title' => $sTitle,            
            'designbox_content' => $sContent,
            'caption_item' => $this->designBoxMenu ($mixedMenu, array (array('menu' => 1))),
            'bottom_item' => '', // TODO: remove or implement somehow
        ));
    }

    function designBoxMenu ($mixedMenu, $aButtons = array ()) {

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
                    $aMenu = array ('template' => 'menu_horizontal.html', 'menu_items' => $mixedMenu);
                bx_import('BxTemplMenu');
                $oMenu = new BxTemplMenu($aMenu, $this->_oTemplate);
                $sMenu = $oMenu->getCode ();
            }

            if ($sMenu) {
                $sId = 'bx-menu-db-' . time() . rand(0, PHP_INT_MAX);
                $sCode .= BxTemplFunctions::getInstance()->transBox($sId, '<div class="bx-def-padding bx-def-color-bg-block">' . $sMenu . '</div>', true);
                $aButtonMenu = array ('icon' => 'reorder', 'onclick' => "bx_menu_popup_inline('#" . $sId . "', this)");
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
    function getMainLogo() {
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
        	bx_import('BxDolImageTranscoder');

        	// favicon icon
	        $oTranscoder = BxDolImageTranscoder::getObjectInstance(BX_DOL_TRANSCODER_OBJ_ICON_FAVICON);
	        $sImageUrlFav = $oTranscoder->getImageUrl($iId);

	        // facebook icon
	        $oTranscoder = BxDolImageTranscoder::getObjectInstance(BX_DOL_TRANSCODER_OBJ_ICON_FACEBOOK);
	        $sImageUrlFcb = $oTranscoder->getImageUrl($iId);

	        //apple touch icon
	        $oTranscoder = BxDolImageTranscoder::getObjectInstance(BX_DOL_TRANSCODER_OBJ_ICON_APPLE);
	        $sImageUrlApl = $oTranscoder->getImageUrl($iId);
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
    function timeForJs ($iUnixTimestamp, $sFormatIdentifier = BX_FORMAT_DATE, $bForceFormat = false) {
        $sDateUTC = bx_time_utc ($iUnixTimestamp);
        return '<time datetime="' . $sDateUTC . '" data-bx-format="' . getParam($sFormatIdentifier) . '" data-bx-autoformat="' . ($bForceFormat ? 0 : getParam('sys_format_timeago')) . '">' . $sDateUTC . '</time>';
    }

}

/** @} */
