<?php
/**
 * @package     Dolphin Core
 * @copyright   Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * @license     CC-BY - http://creativecommons.org/licenses/by/3.0/
 */
defined('BX_DOL') or die('hack attempt');

bx_import('BxDolCmts');
bx_import('BxTemplPaginate');

/**
 * @see BxDolCmts
 */
class BxBaseCmtsView extends BxDolCmts {
    var $_oPaginate;
    var $_sStylePrefix;

    function BxBaseCmtsView( $sSystem, $iId, $iInit = 1 ) {
        BxDolCmts::BxDolCmts( $sSystem, $iId, $iInit );
        if(empty($sSystem))
            return;

        $this->_sJsObjName = 'oCmts' . ucfirst($sSystem) . $iId;
        $this->_oPaginate = new BxTemplPaginate(array(
            'page_url' => 'javascript:void(0);',
            'start' => 0,
            //'count' => $this->_oQuery->getObjectCommentsCount($this->getId(), 0),
            'per_page' => $this->getPerView(),
            'sorting' => $this->_sOrder,
            'per_page_step' => 2,
            'per_page_interval' => 3,
            'on_change_page' => $this->_sJsObjName . '.changePage({start}, {per_page})',
            'on_change_per_page' => $this->_sJsObjName . '.changePerPage(this)',
            'on_change_sorting' => $this->_sJsObjName . '.changeOrder(this)'
        ));
        $this->_sStylePrefix = isset($this->_aSystem['root_style_prefix']) ? $this->_aSystem['root_style_prefix'] : 'cmt';

        BxDolTemplate::getInstance()->addJsTranslation('_sys_txt_cmt_loading');
    }

    /**
     * get full comments block with initializations
     */
    function getCommentsFirst () {
        $sRet  = '<div id="cmts-box-' . $this->_sSystem . '-' . $this->getId() . '">';
        $sRet .= '<div class="cmt-browse">' . $this->_getBrowse() . '</div>';
        $sRet .= '<div class="cmts">' . $this->getComments (0, $this->_sOrder) . '</div>';
        if(($sPaginate = $this->getPaginate()) !== "")
            $sRet .= '<div class="cmt-show-more">' . $sPaginate . '</div>';
        if($this->isPostReplyAllowed ())
            $sRet .= '<div class="cmt-reply">' . $this->_getPostReplyBox() . "</div>";
        $sRet .= '</div>';
        $sRet .= $this->getCmtsInit ();
        return $sRet;
    }

    /**
     * get comments list for specified parent comment
     *
     * @param int $iCmtsParentId - parent comment to get child comments from
     */
    function getComments ($iCmtsParentId = 0, $sCmtOrder = 'asc', $iStart = 0, $iPerPage = -1) {
        if ($iCmtsParentId == 0 && $iPerPage == -1)
            $iPerPage = $this->getPerView();

        $sRet = '<ul class="cmts">';

        $aCmts = $this->getCommentsArray ($iCmtsParentId, $sCmtOrder, $iStart, $iPerPage);
        if (!$aCmts) {
            $sRet .= '<li class="cmt-no">' . _t('_There are no comments yet') . '</li>';
        } else {
            $i = 0;
            for ( reset($aCmts) ; list ($k, $r) = each ($aCmts) ;  ++$i) {
                $sClass = '';
                if ($r['cmt_rated'] == -1 || $r['cmt_rate'] < $this->_aSystem['viewing_threshold']) {
                    $sRet .= '<li id="cmt' . $r['cmt_id'] . '-hidden" class="cmt-replacement">';
                    $sRet .= bx_process_output(_t('_hidden_comment', $r['cmt_author_name'])) . ' ' . ($r['cmt_replies'] > 0 ? _t('_Show N replies', $r['cmt_replies']) . '. ' : '') . '<a href="javascript:void(0)" onclick="javascript:' . $this->_sJsObjName . '.showReplacement(' . $r['cmt_id'] . ')">' . _t('_show_replacement') . '</a>.';
                    if ($this->isRatable())
                        $sRet .= $this->_getRateBox($r);
                    $sRet .= '</li>';

                    $sClass = ' cmt-hidden';
                }


                $isOwnComment = $r['cmt_author_id'] == $this->_getAuthorId();
                if ($isOwnComment)
                    $sClass .= ' cmt-mine';

                $sRet .= '<li id="cmt'.$r['cmt_id'].'" class="cmt' . $sClass . '">';

                $sRet .= '<div class="cmt-cont">';
                $sRet .= $this->_getAuthorIcon($r);
                $sRet .= '<table class="cmt-balloon">';
                $sRet .= $this->_getCommentHeadBox($r);

                $sRet .= '<tr class="cmt-cont" ' . $sStyle . '>';
                $sRet .= $this->_getCommentBodyBox ($r);
                $sRet .= '</tr>';

                $sRet .= $this->_getActionsBox ($r, false);

                $sRet .= '<tr class="cmt-foot"><td class="' . $this->_sStylePrefix . '-foot-l">&nbsp;</td><td class="' . $this->_sStylePrefix . '-foot-m">&nbsp;</td><td class="' . $this->_sStylePrefix . '-foot-r">&nbsp;</td></tr>';
                $sRet .= '</table>';

                $sRet .= '<div class="cmt-replies">' . ($r['cmt_replies'] ? $this->_getRepliesBox($r) : '&nbsp;') . '</div>';
                if ($this->isPostReplyAllowed())
                    $sRet .= $this->_getPostReplyBoxTo($r);
                $sRet .= '<div class="clear_both">&nbsp;</div>';

                if ($this->isRatable())
                    $sRet .= $this->_getRateBox($r);
                $sRet .= '</div></li>';
            }
        }
        $sRet .= '</ul>';

        return $sRet;
    }

    /**
     * get one just posted comment
     *
     * @param int $iCmtId - comment id
     * @return string
     */
    function getComment($iCmtId, $sType = 'new')
    {
        $r = $this->getCommentRow ($iCmtId);

        $sRet = '';
        if($r['cmt_rated'] == -1 || $r['cmt_rate'] < $this->_aSystem['viewing_threshold']) {
            $sRet .= '<li id="cmt' . $r['cmt_id'] . '-hidden" class="cmt-replacement">';
            $sRet .= bx_process_output(_t('_hidden_comment', $r['cmt_author_name'])) . ' ' . ($r['cmt_replies'] > 0 ? _t('_Show N replies', $r['cmt_replies']) . '. ' : '') . '<a href="javascript:void(0)" onclick="javascript:' . $this->_sJsObjName . '.showReplacement(' . $r['cmt_id'] . ')">' . _t('_show_replacement') . '</a>.';
            if($this->isRatable())
                $sRet .= $this->_getRateBox($r);
            $sRet .= '</li>';

            $sClass = ' cmt-hidden';
        }

        $sRet .= '<li id="cmt' . $r['cmt_id'] . '" class="cmt cmt-mine cmt-just-posted' . $sClass . '">';

        $sRet .= '<div class="cmt-cont">';

        if ($this->isRatable())
            $sRet .= $this->_getRateBox($r);

        $sRet .= $this->_getAuthorIcon($r);
        $sRet .= '<table class="cmt-balloon">';
        $sRet .= $this->_getCommentHeadBox($r);

        $sRet .= '<tr class="cmt-cont" ' . $sStyle . '>';
        $sRet .= $this->_getCommentBodyBox ($r);
        $sRet .= '</tr>';

        if ($sType == 'new' && ($r['cmt_author_id'] == $this->_getAuthorId() || $this->isEditAllowedAll() || $this->isRemoveAllowedAll()))
            $sRet .= $this->_getActionsBox ($r, true);

        $sRet .= '<tr class="cmt-foot"><td class="' . $this->_sStylePrefix . '-foot-l">&nbsp;</td><td class="' . $this->_sStylePrefix . '-foot-m">&nbsp;</td><td class="' . $this->_sStylePrefix . '-foot-r">&nbsp;</td></tr>';
        $sRet .= '</table>';

        $sRet .= '<div class="cmt-replies">' . ($r['cmt_replies'] ? $this->_getRepliesBox($r) : '') . '</div>';
        if($sType != 'new' && $this->isPostReplyAllowed())
                    $sRet .= $this->_getPostReplyBoxTo($r);
        $sRet .= '<div class="clear_both">&nbsp;</div></div></li>';

        return $sRet;
    }
    function getPaginate($iStart = -1, $iPerPage = -1) {
        return $this->_oPaginate->getPaginate($iStart, $iPerPage);
    }
    function getForm($sType, $iParentId) {
        return $this->_getPostReplyBox($sType, $iParentId);
    }
    function getActions($iCmtId, $sType = 'reply') {
        $aParams = array(
            'cmt_id' => $iCmtId,
            'cmt_replies' => $this->_oQuery->getObjectCommentsCount($this->getId(), 0),
            'cmt_type' => $sType
        );

        $sRet = "";
        $sRet .= '<div class="cmt-replies">' . ($aParams['cmt_replies'] ? $this->_getRepliesBox($aParams) : '&nbsp;') . '</div>';
        if($this->isPostReplyAllowed())
            $sRet .= $this->_getPostReplyBoxTo($aParams);
        $sRet .= '<div class="clear_both">&nbsp;</div>';
        return $sRet;
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
    function getExtraJs () {
        BxDolTemplate::getInstance()->addJs('BxDolCmts.js');
    }

    /**
     * Get initialization section of comments box
     *
     * @return string
     */
    function getCmtsInit() {
        $sToggleAdd = '';
        $sUseHtmlAdd = '';
        $ret = '';

        if($this->iGlobAllowHtml == 1 && $this->iGlobUseTinyMCE == 1 && ($this->isEditAllowed() || $this->isEditAllowedAll())) {
            bx_import('BxTemplConfig');
            $ret .= BxTemplConfig::getInstance()->sTinyMceEditorMicroJS;
        }

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
                    sAnimationEffect : '" . $this->_aSystem['animation_effect'] . "',
                    sAnimationSpeed : '" . $this->_aSystem['animation_speed'] . "',
                    isEditAllowed : " . ($this->isEditAllowed() || $this->isEditAllowedAll() ? 1 : 0) . ",
                    isRemoveAllowed : " . ($this->isRemoveAllowed() || $this->isRemoveAllowedAll() ? 1 : 0) . ",
                    sTextAreaId: '" . $this->sTextAreaId . "',
                    iGlobAllowHtml: " . ($this->iGlobAllowHtml == 1 && $this->iGlobUseTinyMCE == 1 ? 1 : 0) . ",
                    iSecsToEdit : " . (int)$this->getAllowedEditTime() . "});
                " . $this->_sJsObjName . ".oCmtElements = {";

        for (reset($this->_aCmtElements); list($k,$r) = each ($this->_aCmtElements); ) {
            $ret .= "\n'$k' : { 'reg' : '{$r['reg']}', 'msg' : \"". bx_js_string(trim($r['msg'])) . "\" },";
        }
        $ret = substr($ret, 0, -1);
        $ret .= "\n};\n";

        if ($this->iGlobAllowHtml == 1 && $this->iGlobUseTinyMCE == 1)
            $ret .= $this->_sJsObjName . ".createEditor(0, $('#cmts-box-" . $this->_sSystem . "-" . $this->_iId . "> .cmt-reply form [name=CmtText][tinypossible=true]'), true);\n";

        $ret .= "</script>";

        $this->getExtraJs();
        $this->getExtraCss();
        return $ret;
    }

    /**
     * private functions
     */
    function _getCommentHeadBox (&$a) {
        if ($a['cmt_author_id'] && $a['cmt_author_name'])
            $sAuthor = '<a href="' . getProfileLink($a['cmt_author_id']) . '" class="cmt-author">' . bx_process_output($a['cmt_author_name']) . '</a>';
        else
            $sAuthor = _t('_Anonymous');

        $sRet = '<tr class="cmt-head"><td class="' . $this->_sStylePrefix . '-head-l">&nbsp;</td><td class="' . $this->_sStylePrefix . '-head-m">' . bx_process_output($sAuthor) . ' ' . _t('_wrote') . ' <span class="cmt-posted-ago">' . $a['cmt_ago'] . ' (<span class="cmt-mood-text">' . _t($this->_aMoodText[$a['cmt_mood']]) . '</span>)</span></td><td class="' . $this->_sStylePrefix . '-head-r">&nbsp;</td></tr>';

        return $sRet;
    }

    function _getCommentBodyBox(&$a) {
        return '
                <td class="' . $this->_sStylePrefix . '-cont-l">&nbsp;</td>
                <td class="' . $this->_sStylePrefix . '-cont-m">
                    <div class="cmt-mood">' . bx_process_output($a['cmt_mood']) . '</div>
                    <div class="cmt-body">' . bx_process_output($a['cmt_text'], BX_DATA_TEXT_MULTILINE) . '</div>
                </td>
                <td class="' . $this->_sStylePrefix . '-cont-r">&nbsp;</td>';
    }

    function _getRateBox(&$a)
    {
        $sClass = '';
        if ($a['cmt_rated'] || $a['cmt_rate'] < $this->_aSystem['viewing_threshold'])
            $sClass = ' cmt-rate-disabled';

        return '
            <div class="cmt-rate'.$sClass.'">
                <div class="cmt-points">'._t( (1 == $a['cmt_rate'] || -1 == $a['cmt_rate'])  ? '_N point' : '_N points', $a['cmt_rate']).'</div>
                <div class="cmt-buttons"><a title="'._t('_Thumb Up').'" href="javascript:void(0)" id="cmt-pos-'.$a['cmt_id'].'" class="cmt-pos"><img src="' . getTemplateIcon('spacer.gif') . '" /></a><a title="'._t('_Thumb Down').'" href="javascript:void(0)" id="cmt-neg-'.$a['cmt_id'].'" class="cmt-neg"><img src="' . getTemplateIcon('spacer.gif') . '" /></a></div>
                <div class="clear_both">&nbsp;</div>
            </div>';
    }

    function _getActionsBox (&$a, $isJustPosted) {

        $n = $this->getAllowedEditTime();

        $isEditAllowedPermanently = ($a['cmt_author_id'] == $this->_getAuthorId() && $this->isEditAllowed()) || $this->isEditAllowedAll();
        $isRemoveAllowedPermanently = ($a['cmt_author_id'] == $this->_getAuthorId() && $this->isRemoveAllowed()) || $this->isRemoveAllowedAll();

        if (!($n && $isJustPosted) && !$isEditAllowedPermanently && !$isRemoveAllowedPermanently)
            return '';

        $sRet  = '<tr id="cmt-jp-'.$a['cmt_id'].'" class="cmt-jp"><td class="' . $this->_sStylePrefix . '-cont-l">&nbsp;</td><td class="' . $this->_sStylePrefix . '-cont-m">';

        if (($isEditAllowedPermanently || ($isJustPosted && $n)) && strpos($a['cmt_text'], 'video_comments') === false)
            $sRet .= '<a class="cmt-comment-manage-edit" title="'._t('_Edit').'" href="javascript:void(0)" onclick="' . $this->_sJsObjName . '.cmtEdit(this, \'' . $a['cmt_id'] . '\'); return false;">'._t('_Edit').'</a>&nbsp;';

        if ($isRemoveAllowedPermanently || ($isJustPosted && $n))
            $sRet .= '<a class="cmt-comment-manage-delete" title="'._t('_Remove').'" href="javascript:void(0)" onclick="' . $this->_sJsObjName . '.cmtRemove(this, \'' . $a['cmt_id'] . '\'); return false;">'._t('_Remove').'</a>';

        if ($isJustPosted && $n && !$isEditAllowedPermanently)
            $sRet .= _t('_available_for_n_seconds', $n);

        $sRet .= '</td><td class="' . $this->_sStylePrefix . '-cont-r">&nbsp;</td></tr>';

        return $sRet;
    }

    function _getRepliesBox (&$a) {
        $sContentShow = _t((isset($a['cmt_type']) && $a['cmt_type'] == 'comment' ? '_Show N comments' : '_Show N replies'), $a['cmt_replies']);
        $sContentHide = _t((isset($a['cmt_type']) && $a['cmt_type'] == 'comment' ? '_Hide N comments' : '_Hide N replies'), $a['cmt_replies']);
        return '<a class="cmt-replies-show" href="javascript:void(0)" onclick="' . $this->_sJsObjName . '.toggleCmts(this, \'' . $a['cmt_id'] . '\'); return false;">' . $sContentShow . '</a><a class="cmt-replies-hide" href="javascript:void(0)" onclick="' . $this->_sJsObjName . '.toggleCmts(this, \'' . $a['cmt_id'] . '\'); return false;">' . $sContentHide . '</a>';
    }

    function _getPostReplyBoxTo (&$a) {
        $sContent = _t(isset($a['cmt_type']) && $a['cmt_type'] == 'comment' ? '_Comment to this comment' : '_Reply to this comment');
        return '<div class="cmt-post-reply-to">
                    <a href="javascript:void(0)" onclick="' . $this->_sJsObjName . '.toggleReply(this, \''.$a['cmt_id'].'\'); return false;">' . $sContent . '</a>
                </div>';
    }

    function _getPostReplyBox($sType = 'comment', $iCmtParentId = 0) {

        if ($sType == 'comment')
            $sSwitcher = '<a class="cmt-post-reply-text inactive" href="javascript:void(0)" onclick="javascript:' . $this->_sJsObjName . '.toggleType(this)">' . _t('_Add Your Comment') . '</a><a class="cmt-post-reply-video" href="javascript:void(0)" onclick="javascript:' . $this->_sJsObjName . '.toggleType(this)">' . _t('_Record Your Comment') . '</a>';
        else if ($sType == 'reply')
            $sSwitcher = '<a class="cmt-post-reply-text inactive" href="javascript:void(0)" onclick="javascript:' . $this->_sJsObjName . '.toggleType(this)">' . _t('_Reply as text') . '</a><a class="cmt-post-reply-video" href="javascript:void(0)" onclick="javascript:' . $this->_sJsObjName . '.toggleType(this)">' . _t('_Reply as video') . '</a>';

        return '
                <div class="cmt-post-reply">
                    ' . $this->_getAuthorIcon(array('cmt_author_id' => $this->_getAuthorId())) . '
                    <table class="cmt-balloon">
                        <tr class="cmt-head">
                            <td class="cmt-head-l">&nbsp;</td>
                            <td class="cmt-head-m">' . $sSwitcher . '<div class="clear_both"></div></td>
                            <td class="cmt-head-r">&nbsp;</td>
                        </tr>
                        <tr class="cmt-cont">
                            <td class="cmt-cont-l">&nbsp;</td>
                            <td class="cmt-cont-m">' . $this->_getFormBox($iCmtParentId) . '</td>
                            <td class="cmt-cont-r">&nbsp;</td>
                        </tr>
                        <tr class="cmt-foot">
                            <td class="cmt-foot-l">&nbsp;</td>
                            <td class="cmt-foot-m">&nbsp;</td>
                            <td class="cmt-foot-r">&nbsp;</td>
                        </tr>
                    </table>
                </div>';
    }

    function _getFormBox($iCmtParentId = 0, $sText = "", $sFunction = "submitComment(this)") {

        $sTinyStyle = ($this->iGlobAllowHtml == 1 && $this->iGlobUseTinyMCE == 1) ? ' tinypossible="true" ' : '';

        if ($this->_aSystem['is_mood'])
            $sMood = '
                    <div class="cmt-post-reply-mood">
                        <div class="cmt-post-mood-ctl"><input type="radio" name="CmtMood" value="1" id="' . $this->_sSystem . '-mood-positive" /></div>
                        <div class="cmt-post-mood-lbl"><label for="' . $this->_sSystem . '-mood-positive">' . _t('_Comment Positive') . '</label></div>
                        <div class="cmt-post-mood-ctl"><input type="radio" name="CmtMood" value="-1" id="' . $this->_sSystem . '-mood-negative" /></div>
                        <div class="cmt-post-mood-lbl"><label for="' . $this->_sSystem . '-mood-negative">' . _t('_Comment Negative') . '</label></div>
                        <div class="cmt-post-mood-ctl"><input type="radio" name="CmtMood" value="0" id="' . $this->_sSystem . '-mood-neutral" checked="checked" /></div>
                        <div class="cmt-post-mood-lbl"><label for="' . $this->_sSystem . '-mood-neutral">' . _t('_Comment Neutral') . '</label></div>
                        <div class="clear_both">&nbsp;</div>
                    </div>';

        return '
                <form name="cmt-post-reply" onsubmit="' . $this->_sJsObjName . '.' . $sFunction . '; return false;">
                    <input type="hidden" name="CmtParent" value="' . $iCmtParentId . '" />
                    <input type="hidden" name="CmtType" value="text" />
                    <div class="cmt-post-reply-text">
                        <textarea name="CmtText" ' . $sTinyStyle . ' >' . bx_process_output($sText) . '</textarea>
                    </div>
                    <div class="cmt-post-reply-video">' . getApplicationContent('video_comments', 'recorder', array('user' => $this->_getAuthorId(), 'password' => $this->_getAuthorPassword(), 'extra' => implode('_', array($this->_sSystem . '-' . $this->getId(), $iCmtParentId))), true) . '</div>
                    <div class="cmt-post-reply-post"><input type="submit" value="' . _t('_Submit Comment') . '" /></div>
                    ' . $sMood . '
                </form>';
    }

	function _getAuthorIcon ($a) {
        global $oFunctions;
        if (!$a['cmt_author_id'] || !getProfileInfo($a['cmt_author_id'])) {
            if (!@include_once (BX_DIRECTORY_PATH_MODULES . 'boonex/avatar/include.php'))
                return '';
            return '<div class="thumbnail_block" style="float:none;width:'.(BX_AVA_ICON_W+8).'px;height:'.(BX_AVA_ICON_H+8).'px;"><div class="thumbnail_image" style="width:'.(BX_AVA_ICON_W+4).'px;height:'.(BX_AVA_ICON_H+4).'px;"><img src="' . $oFunctions->getSexPic('', 'small') . '" /></div></div>';
        } else {
            return $oFunctions->getMemberIcon($a['cmt_author_id']);
        }
    }

    function _getBrowse() {
        return "TODO: cmts browse";
/*
        $sRet = '
            <div class="cmt-order">' . $this->_oPaginate->getSorting(array('asc' => '_oldest first', 'desc' => '_newest first')) . ' 
                <input type="checkbox" id="cmt-expand" name="cmt-expand" onclick="javascript:' . $this->_sJsObjName . '.expandAll(this)"/><label for="cmt-expand">' . _t('_expand all') . '</label>
            </div>
            <div class="cmt-pages">' . $this->_oPaginate->getPages() . '</div>
            <div class="clear_both">&nbsp;</div>';
        return $sRet;
*/
    }
}

