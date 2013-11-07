<?php
/**
 * @package     Dolphin Core
 * @copyright   Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * @license     CC-BY - http://creativecommons.org/licenses/by/3.0/
 */
defined('BX_DOL') or die('hack attempt');

bx_import('BxDolCmts');
bx_import('BxDolProfile');
bx_import('BxTemplPaginate');

/**
 * @see BxDolCmts
 */
class BxBaseCmtsView extends BxDolCmts {
	var $_sJsObjName;
    var $_sStylePrefix;

    function BxBaseCmtsView( $sSystem, $iId, $iInit = 1 ) {
        BxDolCmts::BxDolCmts( $sSystem, $iId, $iInit );
        if(empty($sSystem))
            return;

        $this->_sJsObjName = 'oCmts' . str_replace(' ', '', ucwords(str_replace(array('_' , '-'), array(' ', ' '), $sSystem))) . $iId;
        $this->_sStylePrefix = isset($this->_aSystem['root_style_prefix']) ? $this->_aSystem['root_style_prefix'] : 'cmt';

        BxDolTemplate::getInstance()->addJsTranslation('_sys_txt_cmt_loading');
    }

	/**
     * Add comments CSS/JS
     */
    public function addCssJs ()
    {
    	$oTemplate = BxDolTemplate::getInstance();

        $oTemplate->addCss(array('cmts.css'));
        $oTemplate->addJs(array('common_anim.js', 'jquery.form.js', 'BxDolCmts.js'));

        bx_import('BxDolForm');
        $oForm = BxDolForm::getObjectInstance($this->_sFormObject, $this->_sFormDisplayPost);
		$oForm->addCssJs();
    }

	/**
     * Get initialization section of comments box
     *
     * @return string
     */
    function getScript()
    {
        $this->addCssJs();

        return BxDolTemplate::getInstance()->_wrapInTagJsCode("var " . $this->_sJsObjName . " = new BxDolCmts({
        	sObjName: '" . $this->_sJsObjName . "',
            sRootUrl: '" . BX_DOL_URL_ROOT . "',
            sSystem: '" . $this->getSystemName() . "',
            sSystemTable: '" . $this->_aSystem['table_cmts'] . "',
            iAuthorId: '" . $this->_getAuthorId() . "',
            iObjId: '" . $this->getId () . "',
            sPostFormPosition: '" . $this->_aSystem['post_form_position'] . "',
    		sBrowseType: '" . $this->_sBrowseType . "',
    		sDisplayType: '" . $this->_sDisplayType . "'
    	});");
    }

    /**
     * get full comments block with initializations
     */
    function getCommentsBlock($iParentId = 0, $iVParentId = 0, $bInDesignbox = true) {
    	$aBp = array('parent_id' => $iParentId, 'vparent_id' => $iVParentId);

    	$sCmts = $this->getComments($aBp);

    	$sCaption = _t('_cmt_block_comments_title', $this->getCommentsCount());
    	$sContent = BxDolTemplate::getInstance()->parseHtmlByName('comments_block.html', array(
    		'system' => $this->_sSystem,
    		'list_anchor' => $this->getListAnchor(),
    		'id' => $this->getId(),
    		'bx_if:show_empty' => array(
				'condition' => $sCmts == '',
				'content' => array(
					'style_prefix' => $this->_sStylePrefix
				)
			),
    		'comments' => $sCmts,
    		'post_form_top' => $this->getFormBoxPost($aBp, array('type' => $this->_sDisplayType, 'position' => BX_CMT_PFP_TOP)),
			'post_form_bottom'  => $this->getFormBoxPost($aBp, array('type' => $this->_sDisplayType, 'position' => BX_CMT_PFP_BOTTOM)),
    		'script' => $this->getScript()
    	));

    	return $bInDesignbox ? DesignBoxContent($sCaption, $sContent, BX_DB_PADDING_DEF, $this->_getControlsBox()) : array(
            'title' => $sCaption, 
            'content' => $sContent,
            'designbox_id' => BX_DB_PADDING_DEF,
            'menu' => $this->_getControlsBox(),
        ); 
    }

    /**
     * get comments list for specified parent comment
     *
     * @param array $aBp - browse params array
     * @param array $aDp - display params array
     * 
     */
    function getComments($aBp = array(), $aDp = array())
    {
    	$this->_prepareParams($aBp, $aDp);

		$aCmts = $this->getCommentsArray($aBp['vparent_id'], $aBp['filter'], $aBp['order'], $aBp['start'], $aBp['per_view']);
		if(empty($aCmts) || !is_array($aCmts))
			return '';

		$sCmts = '';
		foreach($aCmts as $k => $aCmt)
			$sCmts .= $this->getComment($aCmt, $aBp, $aDp);

		$sCmts = $this->_getMoreLink($sCmts, $aBp, $aDp);
		return $sCmts;
    }

    /**
     * get comment view block with initializations
     */
    function getCommentBlock($iCmtId = 0)
    {
    	return BxDolTemplate::getInstance()->parseHtmlByName('comment_block.html', array(
    		'system' => $this->_sSystem,
    		'id' => $this->getId(),
    		'comment' => $this->getComment($iCmtId, array('type' => $this->_sBrowseType), array('type' => BX_CMT_DISPLAY_THREADED)),
    		'script' => $this->getScript()
    	));
    }

    /**
     * get one just posted comment
     *
     * @param int $iCmtId - comment id
     * @return string
     */
    function getComment($mixedCmt, $aBp = array(), $aDp = array())
    {
    	$oTemplate = BxDolTemplate::getInstance();

    	$iUserId = $this->_getAuthorId();
    	$aCmt = !is_array($mixedCmt) ? $this->getCommentRow((int)$mixedCmt) : $mixedCmt;

        list($sAuthorName, $sAuthorLink, $sAuthorIcon) = $this->_getAuthorInfo($aCmt['cmt_author_id']);

        $sClass = $sRet = '';
        if($aCmt['cmt_rated'] == -1 || $aCmt['cmt_rate'] < $this->_aSystem['viewing_threshold']) {
        	$oTemplate->pareseHtmlByName('comment_hidden.html', array(
        		'js_object' => $this->_sJsObjName,
        		'id' => $aCmt['cmt_id'],
        		'title' => bx_process_output(_t('_hidden_comment', $sAuthorName)),
        		'bx_if:show_replies' => array(
        			'condition' => $aCmt['cmt_replies'] > 0,
        			'content' => array(
						'replies' => _t('_Show N replies', $aCmt['cmt_replies'])
        			)
        		)
        	));

            $sClass = ' cmt-hidden';
        }

		if($aCmt['cmt_author_id'] == $iUserId)
			$sClass .= ' cmt-mine';

		$sActions = $this->_getActionsBox($aCmt, $aDp);

		$sText = $aCmt['cmt_text'];
		$sTextMore = '';

		$iMaxLength = (int)$this->_aSystem['chars_display_max'];
		if(strlen($sText) > $iMaxLength) {
			$iLength = strpos($sText, ' ', $iMaxLength);
			
			$sTextMore = trim(substr($sText, $iLength));
			$sText = trim(substr($sText, 0, $iLength));
		}

		$sText = $this->_prepareTextForOutput($sText);
		$sTextMore = $this->_prepareTextForOutput($sTextMore);

		$aTmplReplyTo = array();
		if((int)$aCmt['cmt_parent_id'] != 0) {
			$aParent = $this->getCommentRow($aCmt['cmt_parent_id']);
			list($sParAuthorName, $sParAuthorLink, $sParAuthorIcon) = $this->_getAuthorInfo($aParent['cmt_author_id']);

			$aTmplReplyTo = array(
				'style_prefix' => $this->_sStylePrefix,
        		'par_cmt_link' => $this->getBaseUrl() . '#' . $this->_sSystem . $aCmt['cmt_parent_id'],
        		'par_cmt_author' => $sParAuthorName
        	);
		}

		$aTmplImages = array();
		if($this->isAttachImageEnabled()) {
			$aImages = $this->_oQuery->getImages($this->_aSystem['system_id'], $aCmt['cmt_id']);
			if(!empty($aImages) && is_array($aImages)) {
				bx_import('BxDolImageTranscoder');
	        	$oTranscoder = BxDolImageTranscoder::getObjectInstance($this->_sTranscoderPreview);
	
	        	foreach($aImages as $aImage)
	        		$aTmplImages[] = array(
	        			'style_prefix' => $this->_sStylePrefix,
	        			'js_object' => $this->_sJsObjName,
	        			'id' => $aImage['image_id'],
	        			'image' => $oTranscoder->getImageUrl($aImage['image_id'])
	        		);
			}
		}

		$sReplies = '';
		if((int)$aCmt['cmt_replies'] > 0 && !empty($aDp) && $aDp['type'] == BX_CMT_DISPLAY_THREADED)
			$sReplies = $this->getComments(array('parent_id' => $aCmt['cmt_id'], 'vparent_id' => $aCmt['cmt_id'], 'type' => $aBp['type']), $aDp);

		$bAuthorIcon = !empty($sAuthorIcon);
        return $oTemplate->parseHtmlByName('comment.html', array(
        	'system' => $this->_sSystem,
        	'style_prefix' => $this->_sStylePrefix,
        	'js_object' => $this->_sJsObjName,
        	'id' => $aCmt['cmt_id'],
        	'class' => $sClass,
        	'bx_if:show_icon' => array(
        		'condition' => $bAuthorIcon,
        		'content' => array(
        			'author_icon' => $sAuthorIcon
        		)
        	),
        	'bx_if:show_icon_empty' => array(
        		'condition' => !$bAuthorIcon,
        		'content' => array()
        	),
        	'bx_if:show_author_link' => array(
        		'condition' => !empty($sAuthorLink),
        		'content' => array(
        			'author_link' => $sAuthorLink,
        			'author_name' => $sAuthorName
        		)
        	),
        	'bx_if:show_author_text' => array(
        		'condition' => empty($sAuthorLink),
        		'content' => array(
        			'author_name' => $sAuthorName
        		)
        	),
        	'bx_if:show_reply_to' => array(
        		'condition' => !empty($aTmplReplyTo),
        		'content' => $aTmplReplyTo
        	),
        	'view_link' => bx_append_url_params($this->_sViewUrl, array(
        		'sys' => $this->_sSystem,
        		'id' => $this->_iId,
        		'cmt_id' => $aCmt['cmt_id']
        	)),
        	'ago' => bx_time_js($aCmt['cmt_time']),
        	'bx_if:hide_rate_count' => array(
        		'condition' => (int)$aCmt['cmt_rate'] <= 0,
        		'content' => array()
        	),
        	'points' => _t(in_array($aCmt['cmt_rate'], array(-1, 0, 1)) ? '_N_point' : '_N_points', $aCmt['cmt_rate']),
        	'text' => $sText,
        	'bx_if:show_more' => array(
        		'condition' => !empty($sTextMore),
        		'content' => array(
        			'style_prefix' => $this->_sStylePrefix,
        			'js_object' => $this->_sJsObjName,
        			'text_more' => $sTextMore
        		)
        	),
        	'bx_if:show_attached' => array(
        		'condition' => !empty($aTmplImages),
        		'content' => array(
        			'style_prefix' => $this->_sStylePrefix,
        			'bx_repeat:attached' => $aTmplImages
        		)
        	),
        	'actions' => $sActions,
        	'replies' =>  $sReplies
        ));
    }

    function getFormBoxPost($aBp = array(), $aDp = array())
    {
        return $this->_getFormBox(BX_CMT_ACTION_POST, $aBp, $aDp);
    }

	function getFormBoxEdit($aBp = array(), $aDp = array())
    {
        return $this->_getFormBox(BX_CMT_ACTION_EDIT, $aBp, $aDp);
    }

	function getFormPost($iCmtParentId = 0)
	{
        return $this->_getFormPost($iCmtParentId);
    }

	function getFormEdit($aCmt)
	{
        return $this->_getFormEdit($aCmt);
    }

    function getPlusedBy($iCmtId)
    {
    	$oTemplate = BxDolTemplate::getInstance();

    	$aTmplUsers = array();

    	$aUserIds = $this->_oQuery->getRatedBy($this->_aSystem['system_id'], $iCmtId);
    	foreach($aUserIds as $iUserId) {
    		list($sUserName, $sUserUrl, $sUserIcon, $sUserUnit) = $this->_getAuthorInfo($iUserId);
			$aTmplUsers[] = array(
				'style_prefix' => $this->_sStylePrefix,
				'user_unit' => $sUserUnit
			);
    	}

    	bx_import('BxTemplStudioFunctions');
        $sContent = BxTemplStudioFunctions::getInstance()->transBox($oTemplate->parseHtmlByName('comment_pb_list.html', array(
    		'style_prefix' => $this->_sStylePrefix,
    		'bx_repeat:list' => $aTmplUsers
    	)));

        return $oTemplate->parseHtmlByName('comment_plused_by.html', array(
        	'style_prefix' => $this->_sStylePrefix,
        	'id' => $this->_sSystem . '-plused-by',
        	'content' => $sContent
        ));
    }

    function getImage($iImgId)
    {
    	if(!$this->isAttachImageEnabled())
    		return '';

    	$oTemplate = BxDolTemplate::getInstance();

    	bx_import('BxDolStorage');
		$oStorage = BxDolStorage::getObjectInstance($this->_sStorageObject);

    	bx_import('BxTemplStudioFunctions');
        $sContent = BxTemplStudioFunctions::getInstance()->transBox($oTemplate->parseHtmlByName('bx_img.html', array(
    		'src' => $oStorage->getFileUrlById($iImgId),
        	'bx_repeat:attrs' => array(
        		array('key' => 'alt', 'value' => bx_html_attribute(_t('_cmt_view_attached_image'))),
        		array('key' => 'title', 'value' => bx_html_attribute(_t('_cmt_close_attached_image'))),
        		array('key' => 'onclick', 'value' => $this->_sJsObjName . '.hideImage(this);')
        	)
    	)));

        return $oTemplate->parseHtmlByName('comment_image.html', array(
        	'style_prefix' => $this->_sStylePrefix,
        	'id' => $this->_sSystem . '-attached-image', 
        	'content' => $sContent
        ));
    }

    /**
     * private functions
     */
    protected function _getControlsBox()
    {
    	$oTemplate = BxDolTemplate::getInstance();

    	$sDisplay = '';
    	$bDisplay = (int)$this->_aSystem['is_display_switch'] == 1;
    	if($bDisplay) {
			$aDisplayLinks = array(
				array('id' => $this->_sSystem . '-flat', 'name' => $this->_sSystem . '-flat', 'class' => '', 'title' => '_cmt_display_flat', 'target' => '_self', 'onclick' => 'javascript:' . $this->_sJsObjName . '.cmtChangeDisplay(this, \'flat\');'),
				array('id' => $this->_sSystem . '-threaded', 'name' => $this->_sSystem . '-threaded', 'class' => '', 'title' => '_cmt_display_threaded', 'target' => '_self', 'onclick' => 'javascript:' . $this->_sJsObjName . '.cmtChangeDisplay(this, \'threaded\');')
			);

    		bx_import('BxTemplMenuInteractive');
			$oMenu = new BxTemplMenuInteractive(array('template' => 'menu_interactive_vertical.html', 'menu_id'=> $this->_sSystem . '-display', 'menu_items' => $aDisplayLinks));
			$oMenu->setSelected('', $this->_sSystem . '-' . $this->_sDisplayType);
        	$sDisplay = $oMenu->getCode();
    	}

    	$sBrowseType = '';
    	$bBrowseType = (int)$this->_aSystem['is_browse_switch'] == 1;
    	if($bBrowseType) {
    		$aBrowseLinks = array(
    			array('id' => $this->_sSystem . '-tail', 'name' => $this->_sSystem . '-tail', 'class' => '', 'title' => '_cmt_browse_tail', 'target' => '_self', 'onclick' => 'javascript:' . $this->_sJsObjName . '.cmtChangeBrowse(this, \'tail\');'),
				array('id' => $this->_sSystem . '-head', 'name' => $this->_sSystem . '-head', 'class' => '', 'title' => '_cmt_browse_head', 'target' => '_self', 'onclick' => 'javascript:' . $this->_sJsObjName . '.cmtChangeBrowse(this, \'head\');'),
				array('id' => $this->_sSystem . '-popular', 'name' => $this->_sSystem . '-popular', 'class' => '', 'title' => '_cmt_browse_popular', 'target' => '_self', 'onclick' => 'javascript:' . $this->_sJsObjName . '.cmtChangeBrowse(this, \'popular\');'),
    		);

    		bx_import('BxTemplMenuInteractive');
			$oMenu = new BxTemplMenuInteractive(array('template' => 'menu_interactive_vertical.html', 'menu_id'=> $this->_sSystem . '-browse', 'menu_items' => $aBrowseLinks));
			$oMenu->setSelected('', $this->_sSystem . '-' . $this->_sBrowseType);
        	$sBrowseType = $oMenu->getCode();
    	}

    	$sBrowseFilter = '';
    	$bBrowseFilter = $bBrowseType;
    	if($bBrowseFilter) {
    		$aFilterLinks = array(
	        	array('id' => $this->_sSystem . '-all', 'name' => $this->_sSystem . '-all', 'class' => '', 'title' => '_cmt_browse_all', 'target' => '_self', 'onclick' => 'javascript:' . $this->_sJsObjName . '.cmtChangeFilter(this, \'all\');'),
        		array('id' => $this->_sSystem . '-friends', 'name' => $this->_sSystem . '-friends', 'class' => '', 'title' => '_cmt_browse_friends', 'target' => '_self', 'onclick' => 'javascript:' . $this->_sJsObjName . '.cmtChangeFilter(this, \'friends\');'),
				array('id' => $this->_sSystem . '-subscriptions', 'name' => $this->_sSystem . '-subscriptions', 'class' => '', 'title' => '_cmt_browse_subscriptions', 'target' => '_self', 'onclick' => 'javascript:' . $this->_sJsObjName . '.cmtChangeFilter(this, \'subscriptions\');')
        	);

        	$oMenu = new BxTemplMenuInteractive(array('template' => 'menu_interactive_vertical.html', 'menu_id'=> $this->_sSystem . '-filter', 'menu_items' => $aFilterLinks));
			$oMenu->setSelected('', $this->_sSystem . '-' . $this->_sBrowseFilter);
        	$sBrowseFilter = $oMenu->getCode();
    	}

    	return $oTemplate->parseHtmlByName('comments_controls.html', array(
    		'display_switcher' => $bDisplay ? $sDisplay : '',
    		'bx_if:is_divider_1' => array(
    			'condition' => $bDisplay && $bBrowseType,
    			'content' => array(
    				'style_prefix' => $this->_sStylePrefix,
    			)
    		),
    		'browse_switcher' => $bBrowseType ? $sBrowseType : '',
    		'bx_if:is_divider_2' => array(
    			'condition' => $bBrowseType && $bBrowseFilter,
    			'content' => array(
    				'style_prefix' => $this->_sStylePrefix,
    			)
    		),
    		'filter_switcher' => $bBrowseFilter ? $sBrowseFilter : '',
		));
    }

	protected function _getActionsBox(&$aCmt, $aDp = array())
    {
    	$oTemplate = BxDolTemplate::getInstance();

        $iUserId = $this->_getAuthorId();
        $isEditAllowedPermanently = ($aCmt['cmt_author_id'] == $iUserId && $this->isEditAllowed()) || $this->isEditAllowedAll();
        $isRemoveAllowedPermanently = ($aCmt['cmt_author_id'] == $iUserId && $this->isRemoveAllowed()) || $this->isRemoveAllowedAll();

		$sManagePopupId = $sManagePopupText = '';
        if($isEditAllowedPermanently || $isRemoveAllowedPermanently) {
			$aMenu = array(
				array('name' => 'cmt-edit', 'icon' => 'pencil', 'onclick' => $this->_sJsObjName . ".cmtEdit(this, " . $aCmt['cmt_id'] . ")", 'title' => _t('_Edit')),
				array('name' => 'cmt-delete', 'icon' => 'remove', 'onclick' => $this->_sJsObjName . ".cmtRemove(this, " . $aCmt['cmt_id'] . ")", 'title' => _t('_Delete')),
			);

        	bx_import('BxTemplStudioMenu');
        	$oMenu = new BxTemplStudioMenu(array('template' => 'menu_vertical.html', 'menu_items' => $aMenu));

        	bx_import('BxTemplStudioFunctions');
	        $sManagePopupText = BxTemplStudioFunctions::getInstance()->transBox($oTemplate->parseHtmlByName('comment_manage.html', array(
	        	'style_prefix' => $this->_sStylePrefix,
	        	'content' => $oMenu->getCode()
	        )));
        }

        $bRated = (int)$aCmt['cmt_rated'] > 0;
        return $oTemplate->parseHtmlByName('comment_actions.html', array(
        	'id' => $aCmt['cmt_id'],
        	'style_prefix' => $this->_sStylePrefix,
        	'view_link' => bx_append_url_params($this->_sViewUrl, array(
        		'sys' => $this->_sSystem,
        		'id' => $this->_iId,
        		'cmt_id' => $aCmt['cmt_id']
        	)),
        	'points' => _t($aCmt['cmt_rate'] == 1 || $aCmt['cmt_rate'] == -1 ? '_N_point' : '_N_points', $aCmt['cmt_rate']),
        	'bx_if:show_reply' => array(
				'condition' => $this->isPostReplyAllowed(),
        		'content' => array(
        			'js_object' => $this->_sJsObjName,
        			'style_prefix' => $this->_sStylePrefix,
        			'id' => $aCmt['cmt_id'],
        			'title_reply' => bx_html_attribute(_t(isset($aCmt['cmt_type']) && $aCmt['cmt_type'] == 'comment' ? '_Comment_to_this_comment' : '_Reply_to_this_comment'))
        		)
        	),
        	'bx_if:show_rate' => array(
				'condition' => $this->isRatable() && $this->isRateAllowed(),
        		'content' => array(
        			'js_object' => $this->_sJsObjName,
        			'style_prefix' => $this->_sStylePrefix,
        			'id' => $aCmt['cmt_id'],
        			'bx_if:hide_rate_plus' => array(
        				'condition' => $bRated,
        				'content' => array()
        			),
        			'bx_if:hide_rate_minus' => array(
        				'condition' => !$bRated,
        				'content' => array()
        			)
        		)
        	),
        	'bx_if:show_manage' => array(
        		'condition' => $isEditAllowedPermanently || $isRemoveAllowedPermanently,
        		'content' => array(
        			'js_object' => $this->_sJsObjName,
        			'style_prefix' => $this->_sStylePrefix,
        			'id' => $aCmt['cmt_id'],
        			'popup_id' => $this->_sSystem . '-manage-' . $aCmt['cmt_id'],
        			'popup_text' => $sManagePopupText
        		)
        	)
        ));
    }

    protected function _getFormBox($sType, $aBp, $aDp)
    {
    	$iCmtParentId = isset($aBp['parent_id']) ? (int)$aBp['parent_id'] : 0;
    	$sPosition = isset($aDp['position']) ? $aDp['position'] : '';

    	if(!$this->isPostReplyAllowed())
    		return '';

/*
		//TODO: Remove if it's not needed.
    	list($sAuthorName, $sAuthorLink, $sAuthorIcon) = $this->_getAuthorInfo();
    	$bAuthorIcon = !empty($sAuthorIcon);
*/

		$sPositionSystem = $this->_aSystem['post_form_position'];
		if(!empty($sPosition) && $sPositionSystem != $sPosition)
			return '';

		$sMethod = '_getForm' . ucfirst($sType);
		$aForm = $this->$sMethod($iCmtParentId);

    	return BxDolTemplate::getInstance()->parseHtmlByName('comment_reply_box.html', array(
    		'js_object' => $this->_sJsObjName,
    		'style_prefix' => $this->_sStylePrefix,
    		'bx_if:show_class' => array(
    			'condition' => !empty($sPosition),
    			'content' => array(
    				'class' => $this->_sStylePrefix . '-reply-' . $sPosition
    			)
    		),
/*
			//TODO: Remove if it's not needed.
    		'bx_if:show_icon' => array(
        		'condition' => $bAuthorIcon,
        		'content' => array(
        			'author_icon' => $sAuthorIcon
        		)
        	),
        	'bx_if:show_icon_empty' => array(
        		'condition' => !$bAuthorIcon,
        		'content' => array()
        	),
        	'bx_if:show_author_link' => array(
        		'condition' => !empty($sAuthorLink),
        		'content' => array(
        			'author_link' => $sAuthorLink,
        			'author_name' => $sAuthorName
        		)
        	),
        	'bx_if:show_author_text' => array(
        		'condition' => empty($sAuthorLink),
        		'content' => array(
        			'author_name' => $sAuthorName
        		)
        	),
*/
			'form' => $aForm['form'],
        	'form_id' => $aForm['form_id'],
    	));
    }

    protected function _getFormPost($iCmtParentId = 0)
    {
    	$oForm = $this->_getFormObject(BX_CMT_ACTION_POST, $iCmtParentId);
        $oForm->aInputs['cmt_parent_id']['value'] = $iCmtParentId;
    	$oForm->initChecker();

        if($oForm->isSubmittedAndValid()) {
        	$iCmtAuthorId = $this->_getAuthorId();
        	$iCmtParentId = $oForm->getCleanValue('cmt_parent_id');

        	$sCmtText = $oForm->getCleanValue('cmt_text');
	        if($this->_isSpam($sCmtText))
	            return array('msg' => _t('_sys_spam_detected', BX_DOL_URL_ROOT . 'contact.php'));

			$sCmtText = $this->_prepareTextForSave ($sCmtText);
			$oForm->setSubmittedValue('cmt_text', $sCmtText, $oForm->aFormAttrs['method']);

	        $iLevel = 0;
	    	$iCmtVisualParentId = 0;
	    	if((int)$iCmtParentId > 0) {
	    		$aParent = $this->getCommentRow($iCmtParentId);

	    		$iLevel = (int)$aParent['cmt_level'] + 1;
	    		$iCmtVisualParentId = $iLevel > $this->getMaxLevel() ? $aParent['cmt_vparent_id'] : $iCmtParentId;
	    	}

			$iCmtId = (int)$oForm->insert(array('cmt_vparent_id' => $iCmtVisualParentId, 'cmt_object_id' => $this->_iId, 'cmt_author_id' => $iCmtAuthorId, 'cmt_level' => $iLevel, 'cmt_time' => time()));
			if($iCmtId != 0) {
				if($this->isAttachImageEnabled()) {
					$aImages = $oForm->getCleanValue('cmt_image');
					if(!empty($aImages) && is_array($aImages)) {
						bx_import('BxDolStorage');
						$oStorage = BxDolStorage::getObjectInstance($this->_sStorageObject);
	
						foreach($aImages as $iImageId)
							if($this->_oQuery->saveImages($this->_aSystem['system_id'], $iCmtId, $iImageId))
								$oStorage->afterUploadCleanup($iImageId, $iCmtAuthorId);
					}
				}

				if($iCmtParentId) {
        			$this->_oQuery->updateRepliesCount($iCmtParentId, 1);

        			$this->_sendNotificationEmail($iCmtId, $iCmtParentId);
				}

				$this->_triggerComment();

		        $this->isPostReplyAllowed(true);

		        bx_import('BxDolAlerts');
		        $oZ = new BxDolAlerts($this->_sSystem, 'commentPost', $this->getId(), $iCmtAuthorId, array('comment_id' => $iCmtId, 'comment_author_id' => $iCmtAuthorId));
		        $oZ->alert();

		        return array('id' => $iCmtId, 'parent_id' => $iCmtParentId);
			}

			return array('msg' => _t('_cmt_err_cannot_perform_action'));
        }

        return array('form' => $oForm->getCode(), 'form_id' => $oForm->id);
    }

	protected function _getFormEdit($iCmtId)
    {
	    $aCmt = $this->_oQuery->getCommentSimple ($this->getId(), $iCmtId);
        if(!$aCmt)
        	return array('msg' => _t('_No such comment'));

		$iCmtAuthorId = $this->_getAuthorId();
        if(!$this->isEditAllowedAll() && ($aCmt['cmt_author_id'] != $iCmtAuthorId || !$this->isEditAllowed()))
        	return array('msg' => $aCmt['cmt_author_id'] == $iCmtAuthorId && !$this->isEditAllowed() ? strip_tags($this->msgErrEditAllowed()) : _t('_Access denied'));

		$oForm = $this->_getFormObject(BX_CMT_ACTION_EDIT, $aCmt['cmt_id']);

		$oForm->initChecker($aCmt);
		if($oForm->isSubmittedAndValid()) {
			$sCmtText = $oForm->getCleanValue('cmt_text');
	        if($this->_isSpam($sCmtText))
	            return array('msg' => _t('_sys_spam_detected', BX_DOL_URL_ROOT . 'contact.php'));

			$sCmtText = $this->_prepareTextForSave ($sCmtText);
			$oForm->setSubmittedValue('cmt_text', $sCmtText, $oForm->aFormAttrs['method']);

	        if($oForm->update($iCmtId)) {
				if ($aCmt['cmt_author_id'] == $iCmtAuthorId)
               		$this->isEditAllowed(true);

	            bx_import('BxDolAlerts');
	            $oZ = new BxDolAlerts($this->_sSystem, 'commentUpdated', $this->getId(), $iCmtAuthorId, array('comment_id' => $aCmt['cmt_id'], 'comment_author_id' => $aCmt['cmt_author_id']));
	            $oZ->alert();

	            return array('id' => $iCmtId, 'text' => $sCmtText);
	        }

	        return array('msg' => _t('_cmt_err_cannot_perform_action'));
		}

		return array('form' => $oForm->getCode(), 'form_id' => $oForm->id);
    }

	protected function _getFormObject($sAction, $iId)
    {
    	$sActionCap = ucfirst($sAction);
    	$sDisplayName = '_sFormDisplay' . $sActionCap;

    	bx_import('BxDolForm');
        $oForm = BxDolForm::getObjectInstance($this->_sFormObject, $this->$sDisplayName);
        $oForm->setId(sprintf($oForm->aFormAttrs['id'], $sAction, $this->_sSystem, $iId));
        $oForm->setName(sprintf($oForm->aFormAttrs['name'], $sAction, $this->_sSystem, $iId));
        $oForm->aParams['db']['table'] = $this->_aSystem['table_cmts'];
        $oForm->aInputs['sys']['value'] = $this->_sSystem;
        $oForm->aInputs['id']['value'] = $this->_iId;
        $oForm->aInputs['action']['value'] = 'Submit' . $sActionCap . 'Form';

        if(!$this->isAttachImageEnabled())
        	unset($oForm->aInputs['cmt_image']);

	    if(isset($oForm->aInputs['cmt_image'])) {
	    	$aFormNested = array(
	        	'params' =>array(
	        		'nested_form_template' => 'comments_uploader_nfw.html'
	        	),
		        'inputs' => array(),
		    );

		    bx_import('BxDolFormNested');
		    $oFormNested = new BxDolFormNested('cmt_image', $aFormNested, 'cmt_submit');
	
	        $oForm->aInputs['cmt_image']['storage_object'] = $this->_sStorageObject;
	        $oForm->aInputs['cmt_image']['images_transcoder'] = $this->_sTranscoderPreview;
	        $oForm->aInputs['cmt_image']['uploaders'] = $this->_aImageUploaders;
	        $oForm->aInputs['cmt_image']['upload_buttons_titles'] = array('Simple' => 'camera');
	        $oForm->aInputs['cmt_image']['multiple'] = true;
	        $oForm->aInputs['cmt_image']['ghost_template'] = $oFormNested;
	    }

        if(isset($oForm->aInputs['cmt_text'])) {
        	$iCmtTextMin = (int)$this->_aSystem['chars_post_min'];
			$iCmtTextMax = (int)$this->_aSystem['chars_post_max'];

        	$oForm->aInputs['cmt_text']['checker']['params'] = array($iCmtTextMin, $iCmtTextMax);
        	$oForm->aInputs['cmt_text']['checker']['error'] = _t('_Please enter n1-n2 characters', $iCmtTextMin, $iCmtTextMax);
        }

        return $oForm;
    }

	protected function _getMoreLink($sCmts, $aBp = array(), $aDp = array())
    {
    	$iStart = $iPerView = 0;
    	switch($aBp['type']) {
    		case BX_CMT_BROWSE_HEAD:
    		case BX_CMT_BROWSE_POPULAR:
    		case BX_CMT_BROWSE_CONNECTION:
    			$iPerView = $aBp['per_view'];

    			$iStart = $aBp['start'] + $iPerView;
    			if($iStart >= $aBp['count'])
    				return $sCmts;

    			break;

    		case BX_CMT_BROWSE_TAIL:
    			$iPerView = $aBp['per_view'];

				$iStart = $aBp['start'] - $iPerView;
    			if($iStart < 0) {
    				$iPerView += $iStart;
    				$iStart = 0;
    			}

    			if($iStart == 0 && $iPerView == 0)
					return $sCmts;

    			break;
    	}

    	$bRoot = (int)$aBp['vparent_id'] <= 0;

    	$sMore = BxDolTemplate::getInstance()->parseHtmlByName('comment_more.html', array(
			'js_object' => $this->_sJsObjName,
			'style_prefix' => $this->_sStylePrefix,
    		'bx_if:is_root' => array(
    			'condition' => $bRoot,
    			'content' => array()
    		),
    		'bx_if:is_button' => array(
    			'condition' => $bRoot,
    			'content' => array()
    		),
			'parent_id' => $aBp['vparent_id'],
    		'start' => $iStart,
    		'per_view' => $iPerView,
    		'title' => _t('_load_more_' . ($aBp['vparent_id'] == 0 ? 'comments' : 'replies') . '_' . $aBp['type'])
		));

    	switch($aBp['type']) {
    		case BX_CMT_BROWSE_HEAD:
    			case BX_CMT_BROWSE_POPULAR:
    			case BX_CMT_BROWSE_CONNECTION:
    			$sCmts .= $sMore;
    			break;

    		case BX_CMT_BROWSE_TAIL:
				$sCmts = $sMore . $sCmts;
    			break;
    	}

    	return $sCmts;
    }

	protected function _echoResultJson($a, $isAutoWrapForFormFileSubmit = false) {

        header('Content-type: text/html; charset=utf-8');    

        require_once(BX_DIRECTORY_PATH_PLUGINS . 'Services_JSON.php');

        $oParser = new Services_JSON();
        $s = $oParser->encode($a);
        if ($isAutoWrapForFormFileSubmit && !empty($_FILES)) 
            $s = '<textarea>' . $s . '</textarea>'; // http://jquery.malsup.com/form/#file-upload
        echo $s;
    }
}
