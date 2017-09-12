<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * @see BxDolFavorite
 */
class BxBaseFavorite extends BxDolFavorite
{
    protected static $_sTmplContentElementBlock;
    protected static $_sTmplContentElementInline;
    protected static $_sTmplContentDoFavoriteLabel;

    protected $_bCssJsAdded;

	protected $_sJsObjClass;
    protected $_sJsObjName;
    protected $_sStylePrefix;

    protected $_aHtmlIds;

    protected $_aElementDefaults;

    public function __construct($sSystem, $iId, $iInit = 1, $oTemplate = false)
    {
        parent::__construct($sSystem, $iId, $iInit, $oTemplate);

        $this->_bCssJsAdded = false;

        $this->_sJsObjClass = 'BxDolFavorite';
        $this->_sJsObjName = 'oFavorite' . bx_gen_method_name($sSystem, array('_' , '-')) . $iId;
        $this->_sStylePrefix = 'bx-favorite';

        $sHtmlId = str_replace(array('_' , ' '), array('-', '-'), $sSystem) . '-' . $iId;
        $this->_aHtmlIds = array(
            'main' => 'bx-favorite-' . $sHtmlId,
            'counter' => 'bx-favorite-counter-' . $sHtmlId,
        	'do_link' => 'bx-favorite-do-link-' . $sHtmlId,
            'by_popup' => 'bx-favorite-by-popup-' . $sHtmlId
        );

        $this->_aElementDefaults = array(
			'show_do_favorite_as_button' => false,
			'show_do_favorite_as_button_small' => false,
			'show_do_favorite_icon' => true,
			'show_do_favorite_label' => false,
			'show_counter' => true
        );

        if(empty(self::$_sTmplContentElementBlock))
            self::$_sTmplContentElementBlock = $this->_oTemplate->getHtml('favorite_element_block.html');

        if(empty(self::$_sTmplContentElementInline))
            self::$_sTmplContentElementInline = $this->_oTemplate->getHtml('favorite_element_inline.html');

        if(empty(self::$_sTmplContentDoFavoriteLabel))
            self::$_sTmplContentDoFavoriteLabel = $this->_oTemplate->getHtml('favorite_do_favorite_label.html');
    }

    public function addCssJs($bDynamicMode = false)
    {
    	if($bDynamicMode || $this->_bCssJsAdded)
    		return;

    	$this->_oTemplate->addJs(array('jquery.anim.js', 'BxDolFavorite.js'));
        $this->_oTemplate->addCss(array('favorite.css'));

        $this->_bCssJsAdded = true;
    }

    public function getJsObjectName()
    {
        return $this->_sJsObjName;
    }

    public function getJsScript($bDynamicMode = false)
    {
        $aParams = array(
            'sObjName' => $this->_sJsObjName,
            'sSystem' => $this->getSystemName(),
            'iAuthorId' => $this->_getAuthorId(),
            'iObjId' => $this->getId(),
            'sRootUrl' => BX_DOL_URL_ROOT,
            'sStylePrefix' => $this->_sStylePrefix,
            'aHtmlIds' => $this->_aHtmlIds
        );
        $sCode = $this->_sJsObjName . " = new " . $this->_sJsObjClass . "(" . json_encode($aParams) . ");";

        if($bDynamicMode) {
			$sCode = "var " . $this->_sJsObjName . " = null; 
			$.getScript('" . bx_js_string($this->_oTemplate->getJsUrl('BxDolFavorite.js'), BX_ESCAPE_STR_APOS) . "', function(data, textStatus, jqxhr) {
				bx_get_style('" . bx_js_string($this->_oTemplate->getCssUrl('favorite.css'), BX_ESCAPE_STR_APOS) . "');
				" . $sCode . "
        	}); ";
        }
        else
        	$sCode = "var " . $sCode;

        $this->addCssJs($bDynamicMode);
        return $this->_oTemplate->_wrapInTagJsCode($sCode);
    }

    public function getJsClick()
    {
        return $this->getJsObjectName() . '.favorite(this)';
    }

    public function getCounter($aParams = array())
    {
        $bShowDoFavoriteAsButtonSmall = isset($aParams['show_do_favorite_as_button_small']) && $aParams['show_do_favorite_as_button_small'] == true;
        $bShowDoFavoriteAsButton = !$bShowDoFavoriteAsButtonSmall && isset($aParams['show_do_favorite_as_button']) && $aParams['show_do_favorite_as_button'] == true;

        $aFavorite = $this->_oQuery->getFavorite($this->getId());
        $sClass = $this->_sStylePrefix . '-counter';
        if($bShowDoFavoriteAsButtonSmall)
            $sClass .= ' bx-btn-small-height';
        if($bShowDoFavoriteAsButton)
            $sClass .= ' bx-btn-height';

        return $this->_oTemplate->parseLink('javascript:void(0)', (int)$aFavorite['count'] > 0 ? $this->_getLabelCounter($aFavorite['count']) : '', array(
            'title' => _t('_favorite_do_favorite_by'),
        	'id' => $this->_aHtmlIds['counter'],
        	'class' => $sClass,
        	'onclick' => 'javascript:' . $this->getJsObjectName() . '.toggleByPopup(this)'
        ));
    }

    public function getElementBlock($aParams = array())
    {
        $aParams['usage'] = BX_DOL_FAVORITE_USAGE_BLOCK;

        return $this->getElement($aParams);
    }

    public function getElementInline($aParams = array())
    {
        $aParams['usage'] = BX_DOL_FAVORITE_USAGE_INLINE;

        return $this->getElement($aParams);
    }

    public function getElement($aParams = array())
    {
    	$aParams = array_merge($this->_aElementDefaults, $aParams);
    	$bDynamicMode = isset($aParams['dynamic_mode']) && $aParams['dynamic_mode'] === true;

        $bShowDoFavoriteAsButtonSmall = isset($aParams['show_do_favorite_as_button_small']) && $aParams['show_do_favorite_as_button_small'] == true;
        $bShowDoFavoriteAsButton = !$bShowDoFavoriteAsButtonSmall && isset($aParams['show_do_favorite_as_button']) && $aParams['show_do_favorite_as_button'] == true;
		$bShowCounter = isset($aParams['show_counter']) && $aParams['show_counter'] === true && $this->isAllowedFavoriteView();

		$iObjectId = $this->getId();
		$iAuthorId = $this->_getAuthorId();
        $aFavorite = $this->_oQuery->getFavorite($iObjectId);
        $bCount = (int)$aFavorite['count'] != 0;

        $bAllowedFavorite = $this->isAllowedFavorite();
        if(!$bAllowedFavorite && (!$this->isAllowedFavoriteView() || !$bCount))
            return '';

        $aParams['is_favorited'] = $this->isPerformed($iObjectId, $iAuthorId) ? true : false;

        $sTmplName = self::${'_sTmplContentElement' . bx_gen_method_name(!empty($aParams['usage']) ? $aParams['usage'] : BX_DOL_FAVORITE_USAGE_DEFAULT)};
        return $this->_oTemplate->parseHtmlByContent($sTmplName, array(
            'style_prefix' => $this->_sStylePrefix,
            'html_id' => $this->_aHtmlIds['main'],
            'class' => $this->_sStylePrefix . ($bShowDoFavoriteAsButton ? '-button' : '-link') . ($bShowDoFavoriteAsButtonSmall ? '-button-small' : ''),
            'count' => $aFavorite['count'],
            'do_favorite' => $this->_getDoFavorite($aParams, $bAllowedFavorite),
            'bx_if:show_counter' => array(
                'condition' => $bShowCounter,
                'content' => array(
                    'style_prefix' => $this->_sStylePrefix,
        			'bx_if:show_hidden' => array(
        				'condition' => !$bCount,
        				'content' => array()
        			),
                    'counter' => $this->getCounter($aParams)
                )
            ),
            'script' => $this->getJsScript($bDynamicMode)
        ));
    }

    protected function _getDoFavorite($aParams = array(), $bAllowedFavorite = true)
    {
    	$bFavorited = isset($aParams['is_favorited']) && $aParams['is_favorited'] === true;
        $bShowDoFavoriteAsButtonSmall = isset($aParams['show_do_favorite_as_button_small']) && $aParams['show_do_favorite_as_button_small'] == true;
        $bShowDoFavoriteAsButton = !$bShowDoFavoriteAsButtonSmall && isset($aParams['show_do_favorite_as_button']) && $aParams['show_do_favorite_as_button'] == true;
		$bDisabled = !$bAllowedFavorite || ($bFavorited  && !$this->isUndo());

        $sClass = '';
		if($bShowDoFavoriteAsButton)
			$sClass = 'bx-btn';
		else if ($bShowDoFavoriteAsButtonSmall)
			$sClass = 'bx-btn bx-btn-small';

		if($bDisabled)
			$sClass .= $bShowDoFavoriteAsButton || $bShowDoFavoriteAsButtonSmall ? ' bx-btn-disabled' : 'bx-favorite-disabled';

        return $this->_oTemplate->parseLink('javascript:void(0)', $this->_getLabelDoFavorite($aParams), array(
        	'id' => $this->_aHtmlIds['do_link'],
            'class' => $this->_sStylePrefix . '-do-favorite ' . $sClass,
            'title' => _t($this->_getTitleDoFavorite($bFavorited)),
        	'onclick' => !$bDisabled ? $this->getJsClick() : ''
        ));
    }

    protected function _getLabelCounter($iCount)
    {
        return _t('_favorite_counter', $iCount);
    }

    protected function _getLabelDoFavorite($aParams = array())
    {
    	$bFavorited = isset($aParams['is_favorited']) && $aParams['is_favorited'] === true;
        return $this->_oTemplate->parseHtmlByContent(self::$_sTmplContentDoFavoriteLabel, array(
        	'bx_if:show_icon' => array(
        		'condition' => isset($aParams['show_do_favorite_icon']) && $aParams['show_do_favorite_icon'] == true,
        		'content' => array(
        			'name' => $this->_getIconDoFavorite($bFavorited)
        		)
        	),
        	'bx_if:show_text' => array(
        		'condition' => isset($aParams['show_do_favorite_label']) && $aParams['show_do_favorite_label'] == true,
        		'content' => array(
        			'text' => _t($this->_getTitleDoFavorite($bFavorited))
        		)
        	)
        ));
    }

	protected function _getFavoritedBy()
    {
        $aTmplFavorites = array();

        $aFavorites = $this->_oQuery->getPerformedBy($this->getId());
        foreach($aFavorites as $aFavorite) {
            list($sUserName, $sUserUrl, $sUserIcon, $sUserUnit) = $this->_getAuthorInfo($aFavorite['author_id']);

            $aTmplFavorites[] = array(
                'style_prefix' => $this->_sStylePrefix,
                'user_unit' => $sUserUnit,
            );
        }

        if(empty($aTmplFavorites))
            $aTmplFavorites = MsgBox(_t('_Empty'));

        return $this->_oTemplate->parseHtmlByName('favorite_by_list.html', array(
            'style_prefix' => $this->_sStylePrefix,
            'bx_repeat:list' => $aTmplFavorites
        ));
    }
}

/** @} */
