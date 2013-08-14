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
     * get full comments block with initializations
     */
    function getCommentsFirst() {
    	$sCmts = $this->getComments(0);

    	return BxDolTemplate::getInstance()->parseHtmlByName('comments_block.html', array(
    		'system' => $this->_sSystem,
    		'js_object' => $this->_sJsObjName,
    		'id' => $this->getId(),
    		'bx_if:show_empty' => array(
				'condition' => $sCmts == '',
				'content' => array(
					'style_prefix' => $this->_sStylePrefix
				)
			),
    		'comments' => $sCmts,
    		'reply' => $this->isPostReplyAllowed() ? $this->_getPostReplyBox() : '',
    		'script' => $this->getCmtsInit()
    	));
    }

    /**
     * get comments list for specified parent comment
     *
     * @param int $iCmtsParentId - parent comment to get child comments from
     */
    function getComments ($iCmtParentId = 0, $iStart = -1, $iPerView = -1)
    {
    	list($iStart, $iPerView) = $this->_getBrowseParams($iCmtParentId, $iStart, $iPerView);

		$aCmts = $this->getCommentsArray($iCmtParentId, $this->_sOrder, $iStart, $iPerView);
		if(empty($aCmts) || !is_array($aCmts))
			return '';

			
		$sCmts = $iStart > 0 ? $this->_getMoreLink($iCmtParentId, $iStart, $iPerView) : '';
		foreach($aCmts as $k => $r)
			$sCmts .= $this->getComment($r);

		return $sCmts;
    }

    /**
     * get one just posted comment
     *
     * @param int $iCmtId - comment id
     * @return string
     */
    function getComment($mixedCmt)
    {
    	$oTemplate = BxDolTemplate::getInstance();

    	$iUserId = $this->_getAuthorId();
    	$r = !is_array($mixedCmt) ? $this->getCommentRow((int)$mixedCmt) : $mixedCmt;

        $sClass = $sRet = '';
        if($r['cmt_rated'] == -1 || $r['cmt_rate'] < $this->_aSystem['viewing_threshold']) {
        	$oTemplate->pareseHtmlByName('comment_hidden.html', array(
        		'js_object' => $this->_sJsObjName,
        		'id' => $r['cmt_id'],
        		'title' => bx_process_output(_t('_hidden_comment', $r['cmt_author_name'])),
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

        list($sAuthorName, $sAuthorLink, $sAuthorIcon) = $this->_getAuthorInfo($r);

		$sActions = $this->_getActionsBox($r);

		$sText = bx_process_output($r['cmt_text'], BX_DATA_TEXT_MULTILINE);
		$sTextMore = '';

		$iMaxLength = (int)$this->_aSystem['chars_display_max'];
		if(strlen($sText) > $iMaxLength) {
			$iLength = strpos($sText, ' ', $iMaxLength);
			
			$sTextMore = substr($sText, $iLength);
			$sText = substr($sText, 0, $iLength);
		}

		$aTmplReplyTo = array();
		if((int)$r['cmt_parent_id'] != 0) {
			$aParent = $this->getCommentRow($r['cmt_parent_id']);
			list($sParAuthorName, $sParAuthorLink, $sParAuthorIcon) = $this->_getAuthorInfo($aParent);

			$aTmplReplyTo = array(
        		'par_cmt_link' => '#' . $r['cmt_parent_id'],
        		'par_cmt_author' => $sParAuthorName
        	);
		}
 
        return $oTemplate->parseHtmlByName('comment.html', array(
        	'system' => $this->_sSystem,
        	'id' => $r['cmt_id'],
        	'style_prefix' => $this->_sStylePrefix,
        	'class' => $sClass,
        	'author_icon' => $sAuthorIcon,
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
        	'ago' => $r['cmt_ago'],
        	'points' => _t($r['cmt_rate'] == 1 || $r['cmt_rate'] == -1 ? '_N_point' : '_N_points', $r['cmt_rate']),
        	'bx_if:show_reply_to' => array(
        		'condition' => !empty($aTmplReplyTo),
        		'content' => $aTmplReplyTo
        	),
        	'text' => $sText,
        	'bx_if:show_more' => array(
        		'condition' => !empty($sTextMore),
        		'content' => array(
        			'js_object' => $this->_sJsObjName,
        			'text_more' => $sTextMore
        		)
        	),
        	'actions' => $sActions
        ));
    }

    function getFormBox($sType, $iParentId)
    {
        return $this->_getPostReplyBox($sType, $iParentId);
    }

	function getForm($iCmtParentId, $sText, $sFunction)
	{
        return $this->_getPostReplyForm($iCmtParentId, $sText, $sFunction);
    }

    /**
     * Get comments css file string
     *
     * @return string
     */
    function getExtraCss ()
    {
        BxDolTemplate::getInstance()->addCss('cmts.css');
    }

    /**
     * Get comments js file string
     *
     * @return string
     */
    function getExtraJs ()
    {
        BxDolTemplate::getInstance()->addJs('BxDolCmts.js');
    }

    /**
     * Get initialization section of comments box
     *
     * @return string
     */
    function getCmtsInit()
    {
        $sToggleAdd = '';

        $ret = '';
        $ret .= $sToggleAdd . "
            <script  type=\"text/javascript\">
                var " . $this->_sJsObjName . " = new BxDolCmts({
                    sObjName : '" . $this->_sJsObjName . "',
                    sBaseUrl : '" . BX_DOL_URL_ROOT . "',
                    sSystem : '" . $this->getSystemName() . "',
                    sSystemTable: '" . $this->_aSystem['table_cmts'] . "',
                    iAuthorId: '" . $this->_getAuthorId() . "',
                    iObjId : '" . $this->getId () . "',
                    sOrder : '" . $this->getOrder() . "',
                    sDefaultErrMsg : '" . bx_js_string(_t('_Error occured')) . "',
                    sConfirmMsg : '" . bx_js_string(_t('_Are you sure?')) . "',
                    sTextAreaId: '" . $this->sTextAreaId . "'});
                " . $this->_sJsObjName . ".oCmtElements = {";
        for (reset($this->_aCmtElements); list($k,$r) = each ($this->_aCmtElements); ) {
            $ret .= "\n'$k' : { 'reg' : '{$r['reg']}', 'msg' : \"". bx_js_string(trim($r['msg'])) . "\" },";
        }
        $ret = substr($ret, 0, -1);
        $ret .= "\n};\n";
        $ret .= "</script>";

        $this->getExtraJs();
        $this->getExtraCss();
        return $ret;
    }

    /**
     * private functions
     */
    function _getMoreLink($iParentId, $iStart, $iPerView)
    {
    	return BxDolTemplate::getInstance()->parseHtmlByName('comment_more.html', array(
			'js_object' => $this->_sJsObjName,
			'style_prefix' => $this->_sStylePrefix,
			'parent_id' => $iParentId,
    		'start' => $iStart,
    		'per_view' => $iPerView
		));
    }

	function _getActionsBox(&$a)
    {
        $iUserId = $this->_getAuthorId();
        $isEditAllowedPermanently = ($a['cmt_author_id'] == $iUserId && $this->isEditAllowed()) || $this->isEditAllowedAll();
        $isRemoveAllowedPermanently = ($a['cmt_author_id'] == $iUserId && $this->isRemoveAllowed()) || $this->isRemoveAllowedAll();

        return BxDolTemplate::getInstance()->parseHtmlByName('comment_actions.html', array(
        	'id' => $a['cmt_id'],
        	'style_prefix' => $this->_sStylePrefix,
        	'bx_if:show_edit' => array(
				'condition' => $isEditAllowedPermanently,
        		'content' => array(
        			'js_object' => $this->_sJsObjName,
        			'style_prefix' => $this->_sStylePrefix,
        			'id' => $a['cmt_id']
        		)
        	),
        	'bx_if:show_delete' => array(
				'condition' => $isRemoveAllowedPermanently,
        		'content' => array(
        			'js_object' => $this->_sJsObjName,
        			'style_prefix' => $this->_sStylePrefix,
        			'id' => $a['cmt_id']
        		)
        	),
        	'bx_if:show_rate' => array(
				'condition' => $this->isRatable(),
        		'content' => array(
        			'js_object' => $this->_sJsObjName,
        			'style_prefix' => $this->_sStylePrefix,
        			'id' => $a['cmt_id']
        		)
        	),
        	'bx_if:show_replies' => array(
				'condition' => (int)$a['cmt_replies'] > 0,
        		'content' => array(
        			'js_object' => $this->_sJsObjName,
        			'style_prefix' => $this->_sStylePrefix,
        			'id' => $a['cmt_id'],
        			'text' => _t((isset($a['cmt_type']) && $a['cmt_type'] == 'comment' ? '_N_comments' : '_N_replies'), $a['cmt_replies'])
        		)
        	),
        	'bx_if:show_reply' => array(
				'condition' => $this->isPostReplyAllowed(),
        		'content' => array(
        			'js_object' => $this->_sJsObjName,
        			'style_prefix' => $this->_sStylePrefix,
        			'id' => $a['cmt_id'],
        			'text' => _t(isset($a['cmt_type']) && $a['cmt_type'] == 'comment' ? '_Comment_to_this_comment' : '_Reply_to_this_comment')
        		)
        	)
        ));
    }

    function _getPostReplyBox($sType = 'comment', $iCmtParentId = 0)
    {
    	bx_import('BxDolProfile');
		$oProfile = null; //BxDolProfile::getInstanceAccountProfile($this->_getAuthorId());

    	return BxDolTemplate::getInstance()->parseHtmlByName('comment_reply_box.html', array(
    		'style_prefix' => $this->_sStylePrefix,
    		'author_icon' => $oProfile ? $oProfile->getThumb() : 'icon',
			'form' => $this->_getPostReplyForm($iCmtParentId)
    	));
    }

    function _getPostReplyForm($iCmtParentId = 0, $sText = "", $sFunction = "cmtSubmit(this)")
    {
    	return BxDolTemplate::getInstance()->parseHtmlByName('comment_reply_form.html', array(
    		'js_object' => $this->_sJsObjName,
    		'js_function' => $sFunction,
    		'parent_id' => $iCmtParentId,
    		'text' => bx_process_output($sText)
    	));
    }
	function _getBrowseParams($iCmtParentId = 0, $iStart = -1, $iPerView = -1)
    {
    	$iStart = $iStart != -1 ? $iStart : $this->_oQuery->getCommentsCount($this->_iId, $iCmtParentId);
    	$iPerView = $iPerView != -1 ? $iPerView : $this->getPerView($iCmtParentId);

    	switch($this->_aSystem['show']) {
    		case BX_CMT_SHOW_HEAD:
    			$iStart = 0;
    			break;
    		case BX_CMT_SHOW_TAIL:
    			$iStart = $iStart - $iPerView;
    			if($iStart < 0) {
    				$iPerView += $iStart;
    				$iStart = 0;
    			}
    			break;
    	}

    	return array($iStart, $iPerView);
    }

    function _getAuthorInfo($r)
    {
    	$sAuthorName = _t('_Anonymous');
    	$sAuthorLink = ''; 
        $sAuthorIcon = '';

		$bAuthor = false; //$r['cmt_author_id'] && $r['cmt_author_name'];
		if($bAuthor) {
			$oProfile = null; //BxDolProfile::getInstanceAccountProfile($r['cmt_author_id']);

			$sAuthorName = bx_process_output($r['cmt_author_name']);
			$sAuthorLink = $oProfile->getUrl();
			$sAuthorIcon = $oProfile->getThumb();
			
		}

		return array($sAuthorName, $sAuthorLink, $sAuthorIcon);
    }
}
