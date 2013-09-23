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

        $this->_sJsObjName = 'oCmts' . ucfirst($sSystem) . $iId;
        $this->_sStylePrefix = isset($this->_aSystem['root_style_prefix']) ? $this->_aSystem['root_style_prefix'] : 'cmt';

        BxDolTemplate::getInstance()->addJsTranslation('_sys_txt_cmt_loading');
    }

	/**
     * Get comments css file string
     *
     * @return string
     */
    function getExtraCss ()
    {
        BxDolTemplate::getInstance()->addCss(array('cmts.css'));
    }

    /**
     * Get comments js file string
     *
     * @return string
     */
    function getExtraJs ()
    {
        BxDolTemplate::getInstance()->addJs(array('common_anim.js', 'BxDolCmts.js'));
    }

	/**
     * Get initialization section of comments box
     *
     * @return string
     */
    function getScript()
    {
        $sToggleAdd = '';

        $ret = '';
        $ret .= $sToggleAdd . "
            <script  type=\"text/javascript\">
                var " . $this->_sJsObjName . " = new BxDolCmts({
                    sObjName: '" . $this->_sJsObjName . "',
                    sRootUrl: '" . BX_DOL_URL_ROOT . "',
                    sSystem: '" . $this->getSystemName() . "',
                    sSystemTable: '" . $this->_aSystem['table_cmts'] . "',
                    iAuthorId: '" . $this->_getAuthorId() . "',
                    iObjId: '" . $this->getId () . "',
                    sPostFormPosition: '" . $this->_aSystem['post_form_position'] . "',
    				sBrowseType: '" . $this->_sBrowseType . "',
    				sDisplayType: '" . $this->_sDisplayType . "'});
                " . $this->_sJsObjName . ".oCmtElements = {";
        for (reset($this->_aPostElements); list($k,$r) = each ($this->_aPostElements); ) {
            $ret .= "\n'$k' : { 'reg' : '{$r['reg']}', 'msg' : \"". bx_js_string(trim($r['msg'])) . "\" },";
        }
        $ret = substr($ret, 0, -1);
        $ret .= "\n};\n";
        $ret .= "</script>";

        $this->getExtraJs();
        $this->getExtraCss();
        BxDolTemplate::getInstance()->addJsTranslation(array(
        	'_Error occured',
        	'_Are you sure?'
        ));
        
        return $ret;
    }

    /**
     * get full comments block with initializations
     */
    function getCommentsBlock($iParentId = 0, $iVParentId = 0) {
    	$aBp = array('parent_id' => $iParentId, 'vparent_id' => $iVParentId);

    	$sCmts = $this->getComments($aBp);

    	return BxDolTemplate::getInstance()->parseHtmlByName('comments_block.html', array(
    		'system' => $this->_sSystem,
    		'id' => $this->getId(),
    		'bx_if:show_empty' => array(
				'condition' => $sCmts == '',
				'content' => array(
					'style_prefix' => $this->_sStylePrefix
				)
			),
			'controls' => $this->_getControlsBox(),
    		'comments' => $sCmts,
    		'post_form_top' => $this->_getPostReplyBox($aBp, array('type' => $this->_sDisplayType, 'position' => BX_CMT_PFP_TOP)),
			'post_form_bottom'  => $this->_getPostReplyBox($aBp, array('type' => $this->_sDisplayType, 'position' => BX_CMT_PFP_BOTTOM)),
    		'script' => $this->getScript()
    	));
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

		$aCmts = $this->getCommentsArray($aBp['vparent_id'], $aBp['order'], $aBp['start'], $aBp['per_view']);
		if(empty($aCmts) || !is_array($aCmts))
			return '';

		$sCmts = '';
		foreach($aCmts as $k => $r)
			$sCmts .= $this->getComment($r, $aBp, $aDp);

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
    	$r = !is_array($mixedCmt) ? $this->getCommentRow((int)$mixedCmt) : $mixedCmt;

        list($sAuthorName, $sAuthorLink, $sAuthorIcon) = $this->_getAuthorInfo($r['cmt_author_id']);

        $sClass = $sRet = '';
        if($r['cmt_rated'] == -1 || $r['cmt_rate'] < $this->_aSystem['viewing_threshold']) {
        	$oTemplate->pareseHtmlByName('comment_hidden.html', array(
        		'js_object' => $this->_sJsObjName,
        		'id' => $r['cmt_id'],
        		'title' => bx_process_output(_t('_hidden_comment', $sAuthorName)),
        		'bx_if:show_replies' => array(
        			'condition' => $r['cmt_replies'] > 0,
        			'content' => array(
						'replies' => _t('_Show N replies', $r['cmt_replies'])
        			)
        		)
        	));

            $sClass = ' cmt-hidden';
        }

		if($r['cmt_author_id'] == $iUserId)
			$sClass .= ' cmt-mine';

		$sActions = $this->_getActionsBox($r, $aDp);

		$sText = $r['cmt_text'];
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
		if((int)$r['cmt_parent_id'] != 0) {
			$aParent = $this->getCommentRow($r['cmt_parent_id']);
			list($sParAuthorName, $sParAuthorLink, $sParAuthorIcon) = $this->_getAuthorInfo($aParent['cmt_author_id']);

			$aTmplReplyTo = array(
				'style_prefix' => $this->_sStylePrefix,
        		'par_cmt_link' => $this->getBaseUrl() . '#' . $this->_sSystem . $r['cmt_parent_id'],
        		'par_cmt_author' => $sParAuthorName
        	);
		}
 
		$sReplies = '';
		if((int)$r['cmt_replies'] > 0 && !empty($aDp) && $aDp['type'] == BX_CMT_DISPLAY_THREADED)
			$sReplies = $this->getComments(array('parent_id' => $r['cmt_id'], 'vparent_id' => $r['cmt_id'], 'type' => $aBp['type']), $aDp);

		$bAuthorIcon = !empty($sAuthorIcon);
        return $oTemplate->parseHtmlByName('comment.html', array(
        	'system' => $this->_sSystem,
        	'style_prefix' => $this->_sStylePrefix,
        	'js_object' => $this->_sJsObjName,
        	'id' => $r['cmt_id'],
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
        		'cmt_id' => $r['cmt_id']
        	)),
        	'ago' => $r['cmt_ago'],
        	'bx_if:hide_rate_count' => array(
        		'condition' => (int)$r['cmt_rate'] <= 0,
        		'content' => array()
        	),
        	'points' => _t(in_array($r['cmt_rate'], array(-1, 0, 1)) ? '_N_point' : '_N_points', $r['cmt_rate']),
        	'text' => $sText,
        	'bx_if:show_more' => array(
        		'condition' => !empty($sTextMore),
        		'content' => array(
        			'style_prefix' => $this->_sStylePrefix,
        			'js_object' => $this->_sJsObjName,
        			'text_more' => $sTextMore
        		)
        	),
        	'actions' => $sActions,
        	'replies' =>  $sReplies
        ));
    }

    function getFormBox($aBp = array(), $aDp = array())
    {
        return $this->_getPostReplyBox($aBp, $aDp);
    }

	function getForm($iCmtParentId, $sText, $sFunction)
	{
        return $this->_getPostReplyForm($iCmtParentId, $sText, $sFunction);
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

    	$sContent = $oTemplate->parseHtmlByName('comment_pb_list.html', array(
    		'style_prefix' => $this->_sStylePrefix,
    		'bx_repeat:list' => $aTmplUsers
    	));

    	bx_import('BxTemplStudioFunctions');
        $sContent = BxTemplStudioFunctions::getInstance()->transBox($oTemplate->parseHtmlByName('comment_manage.html', array(
        	'content' => $sContent
        )));

        return $oTemplate->parseHtmlByName('comment_plused_by.html', array(
        	'style_prefix' => $this->_sStylePrefix,
        	'id' => $this->_sSystem . '-plused-by' . $iCmtId,
        	'content' => $sContent
        ));
    }

    /**
     * private functions
     */
    function _getControlsBox()
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
			$oMenu = new BxTemplMenuInteractive(array('template' => 'menu_interactive.html', 'menu_id'=> $this->_sSystem . '-display', 'menu_items' => $aDisplayLinks));
			$oMenu->setSelected('', $this->_sSystem . '-' . $this->_sDisplayType);
        	$sDisplay = $oMenu->getCode();
    	}

    	$sBrowse = '';
    	$bBrowse = (int)$this->_aSystem['is_browse_switch'] == 1;
    	if($bBrowse) {
    		$aBrowseLinks = array(
    			array('id' => $this->_sSystem . '-tail', 'name' => $this->_sSystem . '-tail', 'class' => '', 'title' => '_cmt_browse_tail', 'target' => '_self', 'onclick' => 'javascript:' . $this->_sJsObjName . '.cmtChangeBrowse(this, \'tail\');'),
				array('id' => $this->_sSystem . '-head', 'name' => $this->_sSystem . '-head', 'class' => '', 'title' => '_cmt_browse_head', 'target' => '_self', 'onclick' => 'javascript:' . $this->_sJsObjName . '.cmtChangeBrowse(this, \'head\');'),
				array('id' => $this->_sSystem . '-popular', 'name' => $this->_sSystem . '-popular', 'class' => '', 'title' => '_cmt_browse_popular', 'target' => '_self', 'onclick' => 'javascript:' . $this->_sJsObjName . '.cmtChangeBrowse(this, \'popular\');'),
				array('id' => $this->_sSystem . '-connection', 'name' => $this->_sSystem . '-connection', 'class' => '', 'title' => '_cmt_browse_connection', 'target' => '_self', 'onclick' => 'javascript:' . $this->_sJsObjName . '.cmtChangeBrowse(this, \'connection\');')
    		);

    		bx_import('BxTemplMenuInteractive');
			$oMenu = new BxTemplMenuInteractive(array('template' => 'menu_interactive.html', 'menu_id'=> $this->_sSystem . '-browse', 'menu_items' => $aBrowseLinks));
			$oMenu->setSelected('', $this->_sSystem . '-' . $this->_sBrowseType);
        	$sBrowse = $oMenu->getCode();
    	}
    	
    	return $oTemplate->parseHtmlByName('comments_controls.html', array(
			'js_object' => $this->_sJsObjName,
			'style_prefix' => $this->_sStylePrefix,
			'comments_count' => _t('_N_comments', $this->_oQuery->getCommentsCount($this->_iId)),
    		'display_switcher' => $bDisplay ? $sDisplay : '',
    		'bx_if:is_divider' => array(
    			'condition' => $bDisplay && $bBrowse,
    			'content' => array(
    				'style_prefix' => $this->_sStylePrefix,
    			)
    		),
    		'browse_switcher' => $bBrowse ? $sBrowse : '',
		));
    }

	function _getActionsBox(&$a, $aDp = array())
    {
    	$oTemplate = BxDolTemplate::getInstance();

        $iUserId = $this->_getAuthorId();
        $isEditAllowedPermanently = ($a['cmt_author_id'] == $iUserId && $this->isEditAllowed()) || $this->isEditAllowedAll();
        $isRemoveAllowedPermanently = ($a['cmt_author_id'] == $iUserId && $this->isRemoveAllowed()) || $this->isRemoveAllowedAll();

		$sManagePopupId = $sManagePopupText = '';
        if($isEditAllowedPermanently || $isRemoveAllowedPermanently) {
			$aMenu = array(
				array('name' => 'cmt-edit', 'icon' => '', 'onclick' => $this->_sJsObjName . ".cmtEdit(this, " . $a['cmt_id'] . ")", 'title' => _t('_Edit')),
				array('name' => 'cmt-delete', 'icon' => '', 'onclick' => $this->_sJsObjName . ".cmtRemove(this, " . $a['cmt_id'] . ")", 'title' => _t('_Delete')),
			);

        	bx_import('BxTemplStudioMenu');
        	$oMenu = new BxTemplStudioMenu(array('template' => 'menu_vertical_lite.html', 'menu_items' => $aMenu));

        	bx_import('BxTemplStudioFunctions');
	        $sManagePopupText = BxTemplStudioFunctions::getInstance()->transBox($oTemplate->parseHtmlByName('comment_manage.html', array(
	        	'content' => $oMenu->getCode()
	        )));
        }

        $bRated = (int)$a['cmt_rated'] > 0;
        return $oTemplate->parseHtmlByName('comment_actions.html', array(
        	'id' => $a['cmt_id'],
        	'style_prefix' => $this->_sStylePrefix,
        	'view_link' => bx_append_url_params($this->_sViewUrl, array(
        		'sys' => $this->_sSystem,
        		'id' => $this->_iId,
        		'cmt_id' => $a['cmt_id']
        	)),
        	'ago' => $a['cmt_ago'],
        	'points' => _t($a['cmt_rate'] == 1 || $a['cmt_rate'] == -1 ? '_N_point' : '_N_points', $a['cmt_rate']),
        	'bx_if:show_reply' => array(
				'condition' => $this->isPostReplyAllowed(),
        		'content' => array(
        			'js_object' => $this->_sJsObjName,
        			'style_prefix' => $this->_sStylePrefix,
        			'id' => $a['cmt_id'],
        			'text' => _t(isset($a['cmt_type']) && $a['cmt_type'] == 'comment' ? '_Comment_to_this_comment' : '_Reply_to_this_comment')
        		)
        	),
        	'bx_if:show_rate' => array(
				'condition' => $this->isRatable() && $this->isRateAllowed(),
        		'content' => array(
        			'js_object' => $this->_sJsObjName,
        			'style_prefix' => $this->_sStylePrefix,
        			'id' => $a['cmt_id'],
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
        			'id' => $a['cmt_id'],
        			'popup_id' => $this->_sSystem . '-manage' . $a['cmt_id'],
        			'popup_text' => $sManagePopupText
        		)
        	)
        ));
    }

    function _getPostReplyBox($aBp, $aDp)
    {
    	$iCmtParentId = isset($aBp['parent_id']) ? (int)$aBp['parent_id'] : 0;
    	$sPosition = isset($aDp['position']) ? $aDp['position'] : '';

    	if(!$this->isPostReplyAllowed())
    		return '';

    	list($sAuthorName, $sAuthorLink, $sAuthorIcon) = $this->_getAuthorInfo();

		$sPositionSystem = $this->_aSystem['post_form_position'];
		if(!empty($sPosition) && $sPositionSystem != BX_CMT_PFP_BOTH && $sPositionSystem != $sPosition)
			return '';

		$bAuthorIcon = !empty($sAuthorIcon);
    	return BxDolTemplate::getInstance()->parseHtmlByName('comment_reply_box.html', array(
    		'style_prefix' => $this->_sStylePrefix,
    		'bx_if:show_class' => array(
    			'condition' => !empty($sPosition),
    			'content' => array(
    				'class' => $this->_sStylePrefix . '-reply-' . $sPosition
    			)
    		),
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
			'form' => $this->_getPostReplyForm($iCmtParentId)
    	));
    }

    function _getPostReplyForm($iCmtParentId = 0, $sText = "", $sFunction = "cmtSubmit(this)")
    {
    	$aForm = array(
			'form_attrs' => array(
				'name' => 'cmt-post-reply-' . $this->_sSystem,
				'method' => 'post',
    			'onsubmit' => $this->_sJsObjName . '.' . $sFunction . '; return false;',
    			'class' => 'cmt-post-reply'
			 ), 
			'params' => array (
				'csrf' => array(
					'disable' => true,
				)
			),
			'inputs' => array(
				'CmtParent' => array(
					'type' => 'hidden',
					'name' => 'CmtParent',
					'value' => $iCmtParentId
				),
				'CmtType' => array(
					'type' => 'hidden',
					'name' => 'CmtType',
					'value' => 'text'
				),
				'CmtText' => array(
					'type' => 'textarea',
					'name' => 'CmtText',
					'caption' => '',
					'value' => $sText,
					'required' => false,
				),
				'CmtSubmit' => array(
					'type' => 'submit', 
					'name' => 'CmtSubmit', 
					'value' => _t('_Submit_Comment')
				)
			)
		);

		bx_import('BxTemplFormView');
		$oForm = new BxTemplFormView ($aForm);
		return $oForm->getCode();
    }

	function _getMoreLink($sCmts, $aBp = array(), $aDp = array())
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

    function _getLevelGap($a, $aDp = array())
    {
    	if($aDp['type'] != BX_CMT_DISPLAY_THREADED || !is_array($a) || !isset($a['cmt_level']))
    		return 0;

    	return 84 * ((int)$a['cmt_level'] <= $this->_iDpMaxLevel ? (int)$a['cmt_level'] : $this->_iDpMaxLevel);
    }

    function _getLevelGapByParent($iParentId, $aDp = array()) {
		if($aDp['type'] != BX_CMT_DISPLAY_THREADED || (int)$iParentId == 0)
    		return 0;

		$a = $this->getCommentRow($iParentId);
		if(isset($a['cmt_level']))
			$a['cmt_level'] += 1;

		return $this->_getLevelGap($a, $aDp);
    }
}
