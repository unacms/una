<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * @see BxDolCmts
 */
class BxBaseCmts extends BxDolCmts
{
    protected static $_sTmplContentElementBlock;
    protected static $_sTmplContentElementInline;
    protected static $_sTmplContentDoCommentLabel;
    protected static $_sTmplContentCounter;

    protected $_sTmplNameItem;
    protected $_sTmplNameItemContent;

    protected $_sJsObjClass;
    protected $_sJsObjName;
    protected $_sStylePrefix;

    protected $_aHtmlIds;

    protected $_aElementDefaults;
    
    protected $_aAclId2Name;

    function __construct( $sSystem, $iId, $iInit = true, $oTemplate = false)
    {
        parent::__construct($sSystem, $iId, $iInit, $oTemplate);
        if (empty($sSystem))
            return;

        $this->_sTmplNameItem = 'comment.html';
        $this->_sTmplNameItemContent = 'comment_content.html';

        $this->_sJsObjClass = 'BxDolCmts';
        $this->_sJsObjName = 'oCmts' . bx_gen_method_name($sSystem, array('_' , '-')) . '_' .str_replace('-', 'n', $iId);
        $this->_sStylePrefix = isset($this->_aSystem['root_style_prefix']) ? $this->_aSystem['root_style_prefix'] : 'cmt';

        $sHtmlId = str_replace(array('_' , ' '), array('-', '-'), $sSystem) . '-' . $iId;

        $this->_aHtmlIds = array(
            'main' => 'bx-cmt-' . $sHtmlId,
            'counter' => 'bx-cmt-counter-' . $sHtmlId
        );

        $this->_aElementDefaults = array(
            'show_do_comment_as_button' => false,
            'show_do_comment_as_button_small' => false,
            'show_do_comment_icon' => true,
            'show_do_comment_label' => false,
            'show_counter' => true,
            'show_counter_only' => true,
            'show_counter_empty' => false,
            'show_counter_reversed' => false,
            'recalculate_counter' => false
        );

        $this->_aAclId2Name = array();
        $aAclLevels = BxDolAcl::getInstance()->getMemberships(false, false, false);
        foreach($aAclLevels as $iAclId => $sAclName)
            $this->_aAclId2Name[$iAclId] = str_replace('_', '-', str_replace('_adm_prm_txt_level_', '', $sAclName));

        if(empty(self::$_sTmplContentElementBlock))
            self::$_sTmplContentElementBlock = $this->_oTemplate->getHtml('comment_element_block.html');

        if(empty(self::$_sTmplContentElementInline))
            self::$_sTmplContentElementInline = $this->_oTemplate->getHtml('comment_element_inline.html');

        if(empty(self::$_sTmplContentDoCommentLabel))
            self::$_sTmplContentDoCommentLabel = $this->_oTemplate->getHtml('comment_do_comment_label.html');

        if(empty(self::$_sTmplContentCounter))
            self::$_sTmplContentCounter = $this->_oTemplate->getHtml('comment_counter.html');

        $this->_oTemplate->addJsTranslation('_sys_txt_cmt_loading');
    }

    /**
     * Add comments CSS/JS
     */
    public function addCssJs ()
    {
        $oForm = BxDolForm::getObjectInstance($this->_sFormObject, $this->_sFormDisplayPost);
        $oForm->addCssJs();

        $this->_oTemplate->addCss(array(
            BX_DIRECTORY_PATH_PLUGINS_PUBLIC . 'photo-swipe/|photoswipe.css',
            BX_DIRECTORY_PATH_PLUGINS_PUBLIC . 'photo-swipe/default-skin/|default-skin.css',
        ));

        $this->_oTemplate->addJs(array(
            'photo-swipe/photoswipe.min.js',
            'photo-swipe/photoswipe-ui-default.min.js',
        ));
    }

    public function getJsObjectName()
    {
        return $this->_sJsObjName;
    }

    /**
     * Get initialization section of comments box
     *
     * @return string
     */
    public function getJsScript($aBp = array(), $aDp = array())
    {
        $bMinPostForm = isset($aDp['min_post_form']) ? $aDp['min_post_form'] : $this->_bMinPostForm;

        $aParams = array(
            'sObjName' => $this->_sJsObjName,
            'sRootUrl' => BX_DOL_URL_ROOT,
            'sSystem' => $this->getSystemName(),
            'iAuthorId' => $this->_getAuthorId(),
            'iObjId' => $this->getId(),
            'sBaseUrl' => $this->getBaseUrl(),
            'sPostFormPosition' => $this->_aSystem['post_form_position'],
            'sBrowseType' => $this->_sBrowseType,
            'sDisplayType' => $this->_sDisplayType,
            'iDisplayStructure' => isset($aDp['structure']) && !empty($aDp['structure']) ? 1 : 0,
            'iMinPostForm' => $bMinPostForm ? 1 : 0,
            'sStylePrefix' => $this->_sStylePrefix,
            'aHtmlIds' => $this->_aHtmlIds,
        );

        $this->addCssJs();
        return $this->_oTemplate->_wrapInTagJsCode("if(window['" . $this->_sJsObjName . "'] == undefined) var " . $this->_sJsObjName . " = new " . $this->_sJsObjClass . "(" . json_encode($aParams) . "); " . $this->_sJsObjName . ".cmtInit();");
    }

    function getCommentsBlockAPI($aParams, $aBp = [], $aDp = ['in_designbox' => false, 'show_empty' => false])
    {

        $mixedResult = $this->isViewAllowed();
        if($mixedResult !== CHECK_ACTION_RESULT_ALLOWED)
            return $mixedResult; // TODO: error checking

        $aBp['type'] = 'head';
        $this->_getParams($aBp, $aDp);
        $this->_prepareParams($aBp, $aDp);
        
        $aBp['order']['way'] = 'desc';
        $aBp['order_way'] =  'desc';
        $aBp['start'] = 0 ; 
        $aPp = $aBp['per_view'];
        $aBp['per_view'] =  $aBp['per_view'] + 1; 

        $aCmts = [];

        if (!isset($aParams['comment_id']))
            $aCmts = $this->getCommentsArray($aBp['vparent_id'], $aBp['filter'], $aBp['order'], $aBp['start'], $aBp[($aBp['init_view'] != -1 ? 'init' : 'per') . '_view']);
        else
            $aCmts = [['cmt_id' => $aParams['comment_id']]];
        
        $aParams['start_from'] = 0;
        if (count($aCmts) == $aBp['per_view']){
            $aBp['per_view'] = $aPp;
            $aCmts = array_slice($aCmts, 0, $aBp['per_view']); 
            $aParams['start_from'] = $aBp['start'] + $aBp['per_view'];
        }
            
        $aCmtsRv = [];
        foreach ($aCmts as $aCmt) {
            $aCmtsRv[] = $this->getCommentStructure($aCmt['cmt_id'], $aBp, $aDp);
        }
        return [
            'unit' => 'comments',
            'start' => 0,
            'order' => 'a',
            'module' => $this->_sSystem, 
            'object_id' => $this->_iId,
            'data' => $aCmtsRv,
        ];
        
    }

    function decodeData ($a)
    {
        foreach ($a as $i => $r) {
            if (isset($r['cmt_author_id']))
                $a[$i]['author_data'] = BxDolProfile::getData($r['cmt_author_id']);
        }

        return $a;
    }

    /**
     * get full comments block with initializations
     */
    function getCommentsBlock($aBp = [], $aDp = [])
    {
        $mixedResult = $this->isViewAllowed();
        if($mixedResult !== CHECK_ACTION_RESULT_ALLOWED)
            return $mixedResult;

        $this->_getParams($aBp, $aDp);

        //add live update
        $this->actionResumeLiveUpdate();

        if(($oLiveUpdates = BxDolLiveUpdates::getInstance())!== false) {
            $sServiceCall = BxDolService::getSerializedService('system', 'get_live_update', [$this->_sSystem, $this->_iId, $this->_getAuthorId(), '{count}'], 'TemplCmtsServices');
            $oLiveUpdates->add($this->_sSystem . '_live_updates_cmts_' . $this->_iId, 1, $sServiceCall);
        }
        //add live update

        $sComments = $this->getComments($aBp, $aDp);
        $sCommentsPinned = $this->getCommentsPinned(array_merge($aBp, ['pinned' => 1]), $aDp);
        $sContentBefore = $this->_getContentBefore($aBp, $aDp);
        $sContentAfter = $this->_getContentAfter($aBp, $aDp);
        $sPostFormTop = $this->getFormBoxPost($aBp, array_merge($aDp, ['type' => $this->_sDisplayType, 'position' => BX_CMT_PFP_TOP]));
        $sPostFormBottom = $this->getFormBoxPost($aBp, array_merge($aDp, ['type' => $this->_sDisplayType, 'position' => BX_CMT_PFP_BOTTOM]));
        $sJsContent = $this->getJsScript($aBp, $aDp);

        $sBlockTitle = _t($this->_aT['block_comments_title'], $this->getCommentsCountAll(0, true));
        $sBlockMenu = $this->_getControlsBox();

        bx_alert('system', 'view_comments', 0, 0, [
            'object' => $this,
            'system' => $this->_sSystem,
            'id' => $this->getId(),
            'params_browse' => $aBp,
            'params_display' => $aDp,
            'post_form_top' => &$sPostFormTop,
            'content_before' => &$sContentBefore,
            'comments' => &$sComments,
            'comments_pinned' => &$sCommentsPinned,
            'content_after' => &$sContentAfter,
            'post_form_bottom'  => &$sPostFormBottom,
            'js_content' => &$sJsContent,
            'block_title' => &$sBlockTitle,
            'block_menu' => &$sBlockMenu,
        ]);

        $sContent = $this->_oTemplate->parseHtmlByName('comments_block.html', [
            'system' => $this->_sSystem,
            'list_anchor' => $this->getListAnchor(),
            'id' => $this->getId(),
            'content_before' => $sContentBefore,
            'comments' => $sComments,
            'comments_pinned' => $sCommentsPinned,
            'bx_if:show_divider_hidden' => [
                'condition' => empty($sCommentsPinned),
                'content' => []
            ],
            'content_after' => $sContentAfter,
            'post_form_top' => $sPostFormTop,
            'post_form_bottom'  => $sPostFormBottom,
            'image_preview' => $this->_oTemplate->parseHtmlByName('comments_photoswipe.html', []),
            'script' => $sJsContent
        ]);

        return $aDp['in_designbox'] ? DesignBoxContent($sBlockTitle, $sContent, BX_DB_DEF, $sBlockMenu) : [
            'title' => $sBlockTitle,
            'content' => $sContent,
            'menu' => $sBlockMenu,
        ];
    }

    /**
     * get comments list for specified parent comment
     *
     * @param array $aBp - browse params array
     * @param array $aDp - display params array
     *
     */
    function getComments($aBp = [], $aDp = [])
    {
        $this->_prepareParams($aBp, $aDp);

        $aCmts = $this->getCommentsArray($aBp['vparent_id'], $aBp['filter'], $aBp['order'], $aBp['start'], $aBp[($aBp['init_view'] != -1 ? 'init' : 'per') . '_view']);
        if(empty($aCmts) || !is_array($aCmts))
            return isset($aDp['show_empty']) && $aDp['show_empty'] === true ? $this->_getEmpty($aDp) : '';

        $sCmts = '';
        foreach($aCmts as $k => $aCmt)
            $sCmts .= $this->getComment($aCmt, $aBp, $aDp);
        $sCmts = $this->_getMoreLink($sCmts, $aBp, $aDp);       

        return $sCmts;
    }

    function getCommentsPinned($aBp = [], $aDp = [])
    {
        $this->_prepareParams($aBp, $aDp);

        $aCmts = $this->getCommentsArray($aBp['vparent_id'], BX_CMT_FILTER_PINNED, $aBp['order'], 0, -1);
        if(empty($aCmts) || !is_array($aCmts))
            return '';

        $sCmts = '';
        foreach($aCmts as $k => $aCmt)
            $sCmts .= $this->getComment($aCmt, $aBp, $aDp);

        return $sCmts;
    }

    public function getCommentsByStructure($aBp = array(), $aDp = array())
    {
        if(empty($aDp['structure']))
            return isset($aDp['show_empty']) && $aDp['show_empty'] === true ? $this->_getEmpty($aDp) : '';

        $aBp['count'] = count($aDp['structure']);
        $this->_prepareParams($aBp, $aDp);
        $aDp['structure'] = array_slice($aDp['structure'], $aBp['start'], $aBp[($aBp['init_view'] != -1 ? 'init' : 'per') . '_view'], true);

        $sCmts = '';
        foreach($aDp['structure'] as $iCmtId => $aCmt)
            $sCmts .= $this->getComment($iCmtId, $aBp, $aDp);

        $sCmts = $this->_getMoreLink($sCmts, $aBp, $aDp);
        return $sCmts;
    }    

    /**
     * get comment view block with initializations
     */
    function getCommentBlock($iCmtId = 0, $aBp = array(), $aDp = array())
    {
        $mixedResult = $this->isViewAllowed();
        if($mixedResult !== CHECK_ACTION_RESULT_ALLOWED)
            return $mixedResult;

        $aBp = array_merge(array('type' => $this->_sBrowseType), $aBp);
        $aDp = array_merge(array('type' => BX_CMT_DISPLAY_THREADED), $aDp);

        //--- Beg: Using pregenerated structure
        $mixedStructure = $this->getCommentStructure((int)$iCmtId, $aBp, $aDp);
        if($mixedStructure !== false)
            $aDp['structure'] = $mixedStructure;
        //--- End: Using pregenerated structure

        $sComment = $this->getComment($iCmtId, $aBp, $aDp);
        if (!$sComment)
            return '';

        BxDolTemplate::getInstance()->addInjection ('injection_footer', 'text', $this->_oTemplate->parseHtmlByName('comments_photoswipe.html', []));

        return $this->_oTemplate->parseHtmlByName('comment_block.html', array(
            'system' => $this->_sSystem,
            'id' => $this->getId(),
            'comment' => $sComment,
            'script' => $this->getJsScript($aBp, $aDp)
        ));
    }
    
    /**
     * get one just posted comment
     *
     * @param  int    $iCmtId - comment id
     * @return string
     */
    function getComment($mixedCmt, $aBp = array(), $aDp = array())
    {
        $iUserId = $this->_getAuthorId();
        $aCmt = !is_array($mixedCmt) ? $this->getCommentRow((int)$mixedCmt) : $mixedCmt;
        if (!$aCmt)
            return '';

        list($sAuthorName, $sAuthorLink, $sAuthorIcon) = $this->_getAuthorInfo($aCmt['cmt_author_id']);

        $sClass = $sClassCnt = '';
        if(isset($aCmt['vote_rate']) && (float)$aCmt['vote_rate'] < $this->_aSystem['viewing_threshold']) {
            $this->_oTemplate->pareseHtmlByName('comment_hidden.html', array(
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

            $sClass = ' ' . $this->_sStylePrefix . '-hidden';
        }

        if($aCmt['cmt_author_id'] == $iUserId)
            $sClass .= ' ' . $this->_sStylePrefix . '-mine';

        $aAuthorAcl = BxDolAcl::getInstance()->getMemberMembershipInfo($aCmt['cmt_author_id']);
        if(!empty($aAuthorAcl) && isset($this->_aAclId2Name[$aAuthorAcl['id']]))
            $sClass .= ' ' . $this->_sStylePrefix . '-aml-' . $this->_aAclId2Name[$aAuthorAcl['id']];

        if(!empty($aDp['blink']) && in_array($aCmt['cmt_id'], $aDp['blink']))
            $sClass .= ' ' . $this->_sStylePrefix . '-blink';

        if(!empty($aDp['class_comment']))
            $sClass .= ' ' . $aDp['class_comment'];

        if(!empty($aDp['class_comment_content']))
            $sClassCnt .= ' ' . $aDp['class_comment_content'];

        $sActions = $this->_getActionsBox($aCmt, $aBp, $aDp);

        $aTmplReplyTo = array();
        if((int)$aCmt['cmt_parent_id'] != 0) {
            $aParent = $this->getCommentRow($aCmt['cmt_parent_id']);

            if(!empty($aParent) && is_array($aParent)) {
                $oProfile = $this->_getAuthorObject($aParent['cmt_author_id']);
                $sParAuthorName = $oProfile->getDisplayName();
                $sParAuthorUnit = $oProfile->getUnit(0, array('template' => array('name' => 'unit_wo_info_links', 'size' => 'icon')));

                $aTmplReplyTo = array(
                    'style_prefix' => $this->_sStylePrefix,
                    'par_cmt_link' => $this->getItemUrl($aCmt['cmt_parent_id']),
                    'par_cmt_title' => bx_html_attribute(_t('_in_reply_to_x', $sParAuthorName)),
                    'par_cmt_author' => $sParAuthorName,
                    'par_cmt_author_unit' => $sParAuthorUnit
                );
            }
        }

        $sReplies = '';
        if(!(isset($aBp['pinned']) && (int)$aBp['pinned'] != 0 && (int)$aCmt['cmt_pinned'] != 0) && !empty($aDp)) {
            $aDp['show_empty'] = false;

            if(!empty($aDp['structure'][$aCmt['cmt_id']]) && is_array($aDp['structure'][$aCmt['cmt_id']])) {
                if(!empty($aDp['structure'][$aCmt['cmt_id']]['items'])) {
                    $aDp['structure'] = $aDp['structure'][$aCmt['cmt_id']]['items'];
                    $sReplies = $this->getCommentsByStructure(array('parent_id' => $aCmt['cmt_id'], 'type' => $aBp['type']), $aDp);
                }
            } 
            else if((int)$aCmt['cmt_replies'] > 0 && $aDp['type'] == BX_CMT_DISPLAY_THREADED)
                $sReplies = $this->getComments(array('parent_id' => $aCmt['cmt_id'], 'vparent_id' => $aCmt['cmt_id'], 'type' => $aBp['type']), $aDp);
        }

        $aTmplVarsMeta = array();
        if(!empty($this->_sMenuObjMeta)) {
            $oMenuMeta = BxDolMenu::getObjectInstance($this->_sMenuObjMeta, $this->_oTemplate);
            if($oMenuMeta) {
                $oMenuMeta->setCmtsData($this, $aCmt['cmt_id']);

                $aTmplVarsMeta = array(
                    'style_prefix' => $this->_sStylePrefix,
                    'meta' => $oMenuMeta->getCode()
                );
            }
        }

        $oProfileAuthor = BxDolProfile::getInstance($aCmt['cmt_author_id']);
        if($this->_isShowContent($aCmt)) {
            $sContent = $this->_getContent($aCmt, $aBp, $aDp);
        }
        else {
            $sClass .= ' cmt-author-not-active';
            $sContent = _t('_hidden_comment', BxDolProfileUndefined::getInstance()->getDisplayName());
        }

        $aVars = array_merge(array(
            'system' => $this->_sSystem,
            'style_prefix' => $this->_sStylePrefix,
            'js_object' => $this->_sJsObjName,
            'id' => $aCmt['cmt_id'],
            'anchor' => $this->getItemAnchor($aCmt['cmt_id']),
            'class' => $sClass,
            'class_cnt' => $sClassCnt,
            'bx_if:show_reply_to' => array(
                'condition' => !empty($aTmplReplyTo),
                'content' => $aTmplReplyTo
            ),
            'bx_if:meta' => array(
                'condition' => !empty($aTmplVarsMeta),
                'content' => $aTmplVarsMeta
            ),
            'bx_if:show_pinned' => array(
                'condition' => (int)$aCmt['cmt_pinned'] > 0,
                'content' => array(
                    'style_prefix' => $this->_sStylePrefix,
                )
            ),
            'content' => $sContent,
            'actions' => $sActions,
            'replies' =>  $sReplies,
        ), $this->_getTmplVarsAuthor($aCmt), $this->_getTmplVarsNotes($aCmt));
        
        $sResult = $this->_oTemplate->parseHtmlByName($this->_sTmplNameItem, $aVars);
        
        bx_alert('system', 'view_comment', $aCmt['cmt_id'], 0, array('comment' => $aCmt, 'system' => $this->_sSystem, 'tmpl_name' => $this->_sTmplNameItem, 'tmpl_vars' => $aVars, 'override_result' => &$sResult));
        
        return $sResult;
    }

    public function getCommentStructure($iCmtId, $aBp = array(), $aDp = array())
    {
        $aRoot = $this->getCommentRow((int)$iCmtId);

        $aBps = $aBp;
        if(!empty($aRoot))
            $aBps['parent_id'] = $aRoot['cmt_id'];
        $this->_prepareStructureBp($aDp['type'], $aBps);

        $iLevel = 0;
        $aStructure = array();
        $this->_getStructure($aRoot, $aBps, $iLevel, $aStructure);

        return !empty($aStructure) && is_array($aStructure) ? $aStructure : false;
    }

    function getCommentSearch($iCmtId, &$sAddon)
    {
        $aBp = array();
        $aDp = array(
            'type' => BX_CMT_DISPLAY_FLAT, 
            'view_only' => true
        );

        if(empty($sAddon))
            $sAddon = $this->getJsScript($aBp, $aDp);

        BxDolTemplate::getInstance()->addInjection ('injection_footer', 'text', $this->_oTemplate->parseHtmlByName('comments_photoswipe.html', []));

        return $this->_oTemplate->parseHtmlByName('comment_search.html', array(
            'comment' => $this->getComment($iCmtId, $aBp, $aDp),
        )); 
    }

    /**
     * get one comment for "Live Search"
     *
     * @param  int    $iCmtId - comment id
     * @return string
     */
    function getCommentLiveSearch($mixedCmt, $aParams = array())
    {
        $aCmt = !is_array($mixedCmt) ? $this->getCommentRow((int)$mixedCmt) : $mixedCmt;

        list($sAuthorName, $sAuthorLink, $sAuthorIcon) = $this->_getAuthorInfo($aCmt['cmt_author_id']);
        $bAuthorIcon = !empty($sAuthorIcon);

        $sViewLink = $this->getViewUrl($aCmt['cmt_id']);
        if(empty($sViewLink))
            $sViewLink = '#';

        return $this->_oTemplate->parseHtmlByName('comment_live_search.html', array(
            'bx_if:show_icon' => array(
                'condition' => $bAuthorIcon,
                'content' => array(
                    'author_icon' => $sAuthorIcon,
        			'view_link' => $sViewLink
                )
            ),
            'bx_if:show_icon_empty' => array(
                'condition' => !$bAuthorIcon,
                'content' => array()
            ),
            'view_link' => $sViewLink,
            'text' => BxTemplFunctions::getInstance()->getStringWithLimitedLength(strip_tags($aCmt['cmt_text']), $this->_sSnippetLenthLiveSearch),
            'sample' => isset($aParams['txt_sample_single']) ? _t($aParams['txt_sample_single']) : ''
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

    function getFormPost($iCmtParentId = 0, $aDp = array())
    {
        return $this->_getFormPost($iCmtParentId, $aDp);
    }

    function getFormEdit($iCmtId, $aDp = array())
    {
        return $this->_getFormEdit($iCmtId, $aDp);
    }

    function getControlsBox()
    {
        return $this->_getControlsBox();
    }
    
    function getLiveUpdate($iCountOld = 0, $iCountNew = 0)
    {
        $iCount = (int)$iCountNew - (int)$iCountOld;
        if($iCount < 0)
            return '';

        $aComments = $this->_oQuery->getCommentsBy(array('type' => 'latest', 'object_id' => $this->_iId, 'author' => $this->_getAuthorId(), 'others' => 1, 'start' => '0', 'per_page' => $iCount));
        if(empty($aComments) || !is_array($aComments))
            return '';

        $aComment = array_shift($aComments);
        if(empty($aComment) || !is_array($aComment))
            return '';

        $sJsObject = $this->getJsObjectName();
        return $this->_oTemplate->parseHtmlByName('comments_lu_button.html', array(
            'style_prefix' => $this->_sStylePrefix,
            'html_id' => $this->getNotificationId(),
            'onclick_show' => "javascript:" . $sJsObject . ".goToBtn(this, '" . $this->getItemAnchor($aComment['cmt_id']) . "', '" . $aComment['cmt_id'] . "');",
        ));
    }

    function getLiveUpdates($iCountOld = 0, $iCountNew = 0)
    {
        $bShowAll = true;
        $bShowActions = false;

        $iCount = (int)$iCountNew - (int)$iCountOld;
        if($iCount < 0)
            return '';

        $aComments = $this->_oQuery->getCommentsBy(array('type' => 'latest', 'object_id' => $this->_iId, 'author' => $this->_getAuthorId(), 'others' => 1, 'start' => '0', 'per_page' => $iCount));
        if(empty($aComments) || !is_array($aComments))
            return '';

        $sJsObject = $this->getJsObjectName();

        $iUserId = $this->_getAuthorId();
        $bModerator = $this->isModerator();

        $aComments = array_reverse($aComments);
        $iComments = count($aComments);

        $aTmplVarsNotifs = array();
        foreach($aComments as $iIndex => $aComment) {
            $iCommentId = $aComment['cmt_id'];

            $sShowOnClick = "javascript:" . $sJsObject . ".goTo(this, '" .  $this->getItemAnchor($iCommentId) . "', '" . $iCommentId . "');";
            $sReplyOnClick = "javascript:" . $sJsObject . ".goToAndReply(this, '" . $this->getItemAnchor($iCommentId) . "', '" . $iCommentId . "');";

            $iAuthorId = (int)$aComment['cmt_author_id'];
            if($iAuthorId < 0) {
                if(abs($iAuthorId) == $iUserId)
                    continue;
                else if($bModerator)
                    $iAuthorId *= -1;
            }

            $oAuthor = $this->_getAuthorObject($iAuthorId);
            $sAuthorName = $oAuthor->getDisplayName();

            $aTmplVarsNotifs[] = array(
                'bx_if:show_as_hidden' => array(
                    'condition' => !$bShowAll && $iIndex < ($iComments - 1),
                    'content' => array(),
                ),
                'item' => $this->_oTemplate->parseHtmlByName('comments_lu_notifications.html', array(
                    'style_prefix' => $this->_sStylePrefix,
                    'onclick_show' => $sShowOnClick,
                    'onclick_reply' => $sReplyOnClick,
                    'author_link' => $oAuthor->getUrl(), 
                    'author_title' => bx_html_attribute($sAuthorName),
                    'author_name' => $sAuthorName,
                    'author_unit' => $oAuthor->getUnit(0, array('template' => 'unit_wo_info_links')), 
                    'text' => _t('_cmt_txt_added_sample')
                )),
                'bx_if:show_previous' => array(
                    'condition' => $bShowActions && $iIndex > 0,
                    'content' => array(
                        'onclick_previous' => $sJsObject . '.previousLiveUpdate(this)'
                    )
                ),
                'bx_if:show_close' => array(
                    'condition' => $bShowActions,
                    'content' => array(
                        'onclick_close' => $sJsObject . '.hideLiveUpdate(this)'
                    )
                )
            );
        }

        return $this->_oTemplate->parseHtmlByName('popup_chain.html', array(
            'html_id' => $this->getNotificationId(),
            'bx_repeat:items' => $aTmplVarsNotifs
        ));
    }

    public function getElementBlock($aParams = array())
    {
        $aParams['usage'] = BX_CMT_USAGE_BLOCK;

        return $this->getElement($aParams);
    }

    public function getElementInline($aParams = array())
    {
        $aParams['usage'] = BX_CMT_USAGE_INLINE;

        return $this->getElement($aParams);
    }

    public function getElement($aParams = array())
    {
    	$aParams = array_merge($this->_aElementDefaults, $aParams);

        $bShowDoCommentAsButtonSmall = isset($aParams['show_do_comment_as_button_small']) && $aParams['show_do_comment_as_button_small'] == true;
        $bShowDoCommentAsButton = !$bShowDoCommentAsButtonSmall && isset($aParams['show_do_comment_as_button']) && $aParams['show_do_comment_as_button'] == true;
        $bShowCounterEmpty = isset($aParams['show_counter_empty']) && $aParams['show_counter_empty'] == true;
        $bRecalculateCounter = isset($aParams['recalculate_counter']) && $aParams['recalculate_counter'] == true;

        $iObjectId = $this->getId();
        $iAuthorId = $this->_getAuthorId();
        $iAuthorIp = $this->_getAuthorIp();

        $iCount = $this->getCommentsCountAll(0, $bRecalculateCounter);
        $bCount = (int)$iCount != 0;

        $isAllowedComment = $this->isPostAllowed();

        //--- Do Comment
        $bTmplVarsDoComment = $this->_isShowDoComment($aParams, $isAllowedComment, $bCount);
        $aTmplVarsDoComment = array();
        if($bTmplVarsDoComment) {
            $sClass = '';
            if($bShowDoCommentAsButton)
                $sClass = 'bx-btn';
            else if ($bShowDoCommentAsButtonSmall)
                $sClass = 'bx-btn bx-btn-small';

            if(!$isAllowedComment)
                $sClass .= $bShowDoCommentAsButton || $bShowDoCommentAsButtonSmall ? ' bx-btn-disabled' : 'bx-cmts-disabled';

            $aTmplVarsDoComment = array(
                'style_prefix' => $this->_sStylePrefix,
                'do_comment' => $this->_oTemplate->parseLink($this->getListUrl(), $this->_getLabelDo($aParams), array(
                    'class' => $this->_sStylePrefix . '-do-comment ' . $this->_sStylePrefix . '-dc ' . $sClass,
                    'title' => _t($this->_getTitleDo())
                )),
            );
        }

        //--- Counter
        $bTmplVarsCounter = $this->_isShowCounter($aParams, $isAllowedComment, $bCount);

        $aTmplVarsCounter = array();
        if($bTmplVarsCounter)
            $aTmplVarsCounter = array(
                'style_prefix' => $this->_sStylePrefix,
                'bx_if:show_hidden' => array(
                    'condition' => !$bShowCounterEmpty && !$bCount,
                    'content' => array()
                ),
                'counter' => $this->getCounter(array_merge($aParams, [
                    'show_counter_only' => false
                ]))
            );

        if(!$bTmplVarsDoComment && !$bTmplVarsCounter)
            return '';

        $sTmplName = $this->{'_getTmplElement' . bx_gen_method_name(!empty($aParams['usage']) ? $aParams['usage'] : BX_CMT_USAGE_DEFAULT)}();
        return $this->_oTemplate->parseHtmlByContent($sTmplName, array(
            'style_prefix' => $this->_sStylePrefix,
            'html_id' => $this->_aHtmlIds['main'],
            'class' => $this->_sStylePrefix . ($bShowDoCommentAsButton ? '-button' : '') . ($bShowDoCommentAsButtonSmall ? '-button-small' : ''),
            'count' => $iCount,
            'bx_if:show_do_comment' => array(
                'condition' => $bTmplVarsDoComment,
                'content' => $aTmplVarsDoComment
            ),
            'bx_if:show_counter' => array(
                'condition' => $bTmplVarsCounter,
                'content' => $aTmplVarsCounter
            ),
            'script' => ''
        ));
    }

    protected function _getCounterItems($iCmtsLimit, $iCmtsStart = 0)
    {
        return $this->_oQuery->getCommentsBy(array('type' => 'object_id', 'object_id' => $this->getId(), 'order_way' => 'desc', 'start' => $iCmtsStart, 'per_page' => $iCmtsLimit * 4));
    }
    
    private function _isShowContent($aCmt)
    {
        $oProfileAuthor = BxDolProfile::getInstance($aCmt['cmt_author_id']);
        return (int)$aCmt['cmt_author_id'] == 0 || ($oProfileAuthor && $oProfileAuthor->isActive()) || isAdmin() || BxDolAcl::getInstance()->isMemberLevelInSet([MEMBERSHIP_ID_MODERATOR, MEMBERSHIP_ID_ADMINISTRATOR]);
    }
    
    public function getCounter($aParams = [])
    {
        $aParams = array_merge($this->_aElementDefaults, $aParams);

        $bShowDoCommentAsButtonSmall = isset($aParams['show_do_comment_as_button_small']) && $aParams['show_do_comment_as_button_small'] == true;
        $bShowDoCommentAsButton = !$bShowDoCommentAsButtonSmall && isset($aParams['show_do_comment_as_button']) && $aParams['show_do_comment_as_button'] == true;
        $bShowEmpty = isset($aParams['show_counter_empty']) && $aParams['show_counter_empty'] == true;
        $bShowReversed = isset($aParams['show_counter_reversed']) && $aParams['show_counter_reversed'] == true;
        $bRecalculateCounter = isset($aParams['recalculate_counter']) && $aParams['recalculate_counter'] == true;

        $sClass = 'sys-action-counter';
        if(isset($aParams['show_counter_only']) && (bool)$aParams['show_counter_only'] === true)
            $sClass .= ' sys-ac-only';

        $iCount = (int)$this->getCommentsCountAll(0, $bRecalculateCounter);        
        if($iCount == 0 && !$bShowEmpty)
            $sClass .= ' sys-ac-hidden';

        $sClass .= ' ' . $this->_sStylePrefix . '-counter';
        if($bShowDoCommentAsButtonSmall)
            $sClass .= ' bx-btn-small-height';
        if($bShowDoCommentAsButton)
            $sClass .= ' bx-btn-height';

        $iCmtsLimit = 5;
        $aCmts = $this->_getCounterItems($iCmtsLimit);
        if(!$bShowReversed)
            $aCmts = array_reverse($aCmts);

        $aTmplVarsProfiles = [];
        foreach($aCmts as $aCmt) {
            $iAuthor = (int)$aCmt['cmt_author_id'];
            if(array_key_exists($iAuthor, $aTmplVarsProfiles))
                continue;

            $oAuthor = BxDolProfile::getInstanceMagic($iAuthor);
            if(!$oAuthor)
                continue;

            $aTmplVarsProfiles[$iAuthor] = [
                'icon' => $oAuthor->getUnit(0, ['template' => ['name' => 'unit_wo_info_links', 'size' => 'icon']]) 
            ];

            if(count($aTmplVarsProfiles) >= $iCmtsLimit)
                break;
        }
        $aTmplVarsProfiles = array_values($aTmplVarsProfiles);

        $sHref = !empty($aParams['overwrite_counter_link_href']) ? $aParams['overwrite_counter_link_href'] : $this->getListUrl();
        $sOnclick = '';
        if(!empty($aParams['overwrite_counter_link_onclick']))
            $sOnclick = $aParams['overwrite_counter_link_onclick'];

        return $this->_oTemplate->parseHtmlByContent($this->_getTmplCounter(), [
            'style_prefix' => $this->_sStylePrefix,
            'id' => $this->_aHtmlIds['counter'],
            'class' => $sClass,
            'href' => $sHref,
            'bx_if:show_onclick' => [
                'condition' => !empty($sOnclick),
                'content' => [
                    'onclick' => $sOnclick
                ]
            ],
            'content' => $this->_getCounterLabel($iCount, $aParams),
            'bx_repeat:profiles' => $aTmplVarsProfiles,
            'bx_if:show_icon' => [
                'condition' => ($bShowEmpty || !empty($aTmplVarsProfiles)) && (!isset($aParams['show_icon']) || $aParams['show_icon']),
                'content' => [
                    'style_prefix' => $this->_sStylePrefix,
                    'icon' => !empty($aParams['custom_icon']) ? $aParams['custom_icon'] : $this->_oTemplate->getImageAuto('comment|' . $this->_sStylePrefix . '-counter-icon')
                ]
            ]
        ]);
    }

    protected function _getLabelDo($aParams = [])
    {
        return $this->_oTemplate->parseHtmlByContent($this->_getTmplLabelDo(), [
            'style_prefix' => $this->_sStylePrefix,
            'bx_if:show_icon' => [
                'condition' => isset($aParams['show_do_comment_icon']) && $aParams['show_do_comment_icon'] == true,
                'content' => [
                    'style_prefix' => $this->_sStylePrefix,
                    'icon' => $this->_oTemplate->getImageAuto($this->_getIconDo())
                ]
            ],
            'bx_if:show_text' => [
                'condition' => isset($aParams['show_do_comment_label']) && $aParams['show_do_comment_label'] == true,
                'content' => [
                    'style_prefix' => $this->_sStylePrefix,
                    'text' => _t($this->_getTitleDo())
                ]
            ]
        ]);
    }

    protected function _getCounterLabel($iCount, $aParams = [])
    {
        $aParams = array_merge($this->_aElementDefaults, $aParams);

        $sResult = '';
        if((int)$iCount != 0)
            $sResult = _t(isset($aParams['caption']) ? $aParams['caption'] : '_cmt_txt_counter', $iCount);
        else
            $sResult = _t(isset($aParams['caption_empty']) ? $aParams['caption_empty'] : '_cmt_txt_counter_empty');
        
        return $sResult;
    }

    protected function _getTmplElementBlock()
    {
        return self::$_sTmplContentElementBlock;
    }

    protected function _getTmplElementInline()
    {
        return self::$_sTmplContentElementInline;
    }

    protected function _getTmplLabelDo()
    {
        return self::$_sTmplContentDoCommentLabel;
    }

    protected function _getTmplCounter()
    {
        return self::$_sTmplContentCounter;
    }


    /**
     * private functions
     */
    protected function _getContentBefore($aBp = array(), $aDp = array())
    {
        return '';
    }

    protected function _getContentAfter($aBp = array(), $aDp = array())
    {
        return '';
    }

    protected function _getControlsBox()
    {
        $sDisplay = '';
        $bDisplay = (int)$this->_aSystem['is_display_switch'] == 1;
        if($bDisplay) {
            $aDisplayLinks = array(
                array('id' => $this->_sSystem . '-flat', 'name' => $this->_sSystem . '-flat', 'class' => '', 'title' => '_cmt_display_flat', 'target' => '_self', 'onclick' => 'javascript:' . $this->_sJsObjName . '.cmtChangeDisplay(this, \'flat\');'),
                array('id' => $this->_sSystem . '-threaded', 'name' => $this->_sSystem . '-threaded', 'class' => '', 'title' => '_cmt_display_threaded', 'target' => '_self', 'onclick' => 'javascript:' . $this->_sJsObjName . '.cmtChangeDisplay(this, \'threaded\');')
            );

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

            $oMenu = new BxTemplMenuInteractive(array('template' => 'menu_interactive_vertical.html', 'menu_id'=> $this->_sSystem . '-browse', 'menu_items' => $aBrowseLinks));
            $oMenu->setSelected('', $this->_sSystem . '-' . $this->_sBrowseType);
            $sBrowseType = $oMenu->getCode();
        }

        $sBrowseFilter = '';
        $bBrowseFilter = (int)$this->_aSystem['is_browse_filter'] == 1;
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

        return $this->_oTemplate->parseHtmlByName('comments_controls.html', array(
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

    protected function _getActionsBox(&$aCmt, $aBp = [], $aDp = [])
    {
    	$bViewOnly = isset($aDp['view_only']) && $aDp['view_only'] === true;
    	$bDynamicMode = isset($aDp['dynamic_mode']) && $aDp['dynamic_mode'] === true;

        $sMenuActions = '';
        if(!$bViewOnly) {
            $oMenuActions = BxDolMenu::getObjectInstance($this->_sMenuObjActions);
            $oMenuActions->setCmtsData($this, $aCmt['cmt_id'], $aBp, $aDp);
            $oMenuActions->setDynamicMode($bDynamicMode);
            $sMenuActions = $oMenuActions->getCode();
        }

        return $this->_oTemplate->parseHtmlByName('comment_actions.html', array(
            'id' => $aCmt['cmt_id'],
            'js_object' => $this->_sJsObjName,
            'style_prefix' => $this->_sStylePrefix,
            'menu_actions' => $sMenuActions,
        ));
    }

    protected function _getFormBox($sType, $aBp, $aDp)
    {
        $iCmtParentId = isset($aBp['parent_id']) ? (int)$aBp['parent_id'] : 0;
        $sPosition = isset($aDp['position']) ? $aDp['position'] : '';
        $bQuote = isset($aDp['quote']) && (bool)$aDp['quote'];
        $bFormMin = $iCmtParentId == 0 && isset($aDp['min_post_form']) && (bool)$aDp['min_post_form'];

        $sPositionSystem = $this->_aSystem['post_form_position'];
        if(!empty($sPosition) && $sPositionSystem != $sPosition)
            return '';

        $sClass = '';
        if(!empty($sPosition))
            $sClass .= ' ' . $this->_sStylePrefix . '-reply-' . $sPosition;
        if($bQuote)
            $sClass .= ' ' . $this->_sStylePrefix . '-reply-quote';
        if($bFormMin)
            $sClass .= ' ' . $this->_sStylePrefix . '-reply-min';
        if(!empty($aDp['class']))
            $sClass .= ' ' . $aDp['class'];

        $sClassBody = '';
        if(!empty($aDp['class_body']))
            $sClassBody .= ' ' . $aDp['class_body'];

        $aTmplVarsFormMin = array();
        if($bFormMin) {
            list($sAuthorName, $sAuthorLink, $sAuthorIcon, $sAuthorUnit) = $this->_getAuthorInfo($this->_getAuthorId());

            $oForm = new BxTemplFormView(array());
            $aInputPlaceholder = array(
                'type' => 'text',
                'name' => 'comment',
                'caption' => '',
                'attrs' => array(
                    'onclick' => 'javascript:' . $this->_sJsObjName . '.cmtShowForm(this)',
                    'placeholder' => _t($this->_aT['txt_min_form_placeholder'], $sAuthorName),
                    'autocomplete' => 'off',
                    'readonly' => 'readonly'
                ),
                'value' => '',
            );

            $sClassBodyMin = '';
            if(!empty($aDp['class_body_min']))
                $sClassBodyMin .= ' ' . $aDp['class_body_min'];

            $aTmplVarsFormMin = array(
                'js_object' => $this->_sJsObjName,
                'class_body_min' => $sClassBodyMin,
                'style_prefix' => $this->_sStylePrefix,
                'author_unit' => $sAuthorUnit,
                'placeholder' => $oForm->genRow($aInputPlaceholder)
            );
        }

        $aForm = $this->{'_getForm' . ucfirst($sType)}($iCmtParentId, $aDp);
        if(empty($aForm['form']))
            return !empty($aForm['msg']) ? MsgBox($aForm['msg']) : '';

        return $this->_oTemplate->parseHtmlByName('comment_reply_box.html', array(
            'js_object' => $this->_sJsObjName,
            'style_prefix' => $this->_sStylePrefix,
            'class' => $sClass,
            'class_body' => $sClassBody,
            'bx_if:show_form_min' => array(
                'condition' => $bFormMin,
                'content' => $aTmplVarsFormMin
            ),
            'form' => $aForm['form'],
            'form_id' => $aForm['form_id'],
        ));
    }

    protected function _getFormAdd($aValues)
    {
        $iCmtAuthorId = isset($aValues['cmt_author_id']) ? (int)$aValues['cmt_author_id'] : $this->_getAuthorId();
        $iCmtParentId = isset($aValues['cmt_parent_id']) ? (int)$aValues['cmt_parent_id'] : 0;

        $oForm = $this->_getForm(BX_CMT_ACTION_POST, $iCmtParentId);
        $oForm->aFormAttrs['method'] = BX_DOL_FORM_METHOD_SPECIFIC;
        $oForm->aParams['csrf']['disable'] = true;
        $oForm->aInputs['cmt_parent_id']['value'] = $iCmtParentId;
        if(!empty($oForm->aParams['db']['submit_name'])) {            
            $sSubmitName = false;
            if(is_array($oForm->aParams['db']['submit_name']))
                foreach($oForm->aParams['db']['submit_name'] as $sVal) {
                    if(isset($oForm->aInputs[$sVal])) {
                        $sSubmitName = $sVal;
                        break;
                    }
                }
            else
                $sSubmitName = $oForm->aParams['db']['submit_name'];

            if($sSubmitName && isset($oForm->aInputs[$sSubmitName]))
                $aValues[$sSubmitName] = $oForm->aInputs[$sSubmitName]['value'];
        }

        $oForm->initChecker(array(), $aValues);
        if(!$oForm->isSubmittedAndValid()) 
            return array('code' => 1, 'message' => '_sys_txt_error_occured');

        $iLevel = 0;
        $iCmtVisualParentId = 0;
        if((int)$iCmtParentId > 0) {
            $aParent = $this->getCommentRow($iCmtParentId);

            $iLevel = (int)$aParent['cmt_level'] + 1;
            $iCmtVisualParentId = $iLevel > $this->getMaxLevel() ? $aParent['cmt_vparent_id'] : $iCmtParentId;
        }

        $iCmtId = (int)$oForm->insert(array('cmt_vparent_id' => $iCmtVisualParentId, 'cmt_object_id' => $this->_iId, 'cmt_author_id' => $iCmtAuthorId, 'cmt_level' => $iLevel, 'cmt_time' => time()));
        if(!$iCmtId) {
            if(!$oForm->isValid())
                return array('code' => 1, 'message' => '_sys_txt_error_occured');
            else
                return array('code' => 2, 'message' => '_cmt_err_cannot_perform_action');
        }

        $iCmtUniqId = $this->_oQuery->getUniqId($this->_aSystem['system_id'], $iCmtId, [
            'author_id' => $iCmtAuthorId, 
            'status_admin' => $this->getStatusAdmin()
        ]);

        if($iCmtParentId) {
            $this->_oQuery->updateRepliesCount($iCmtParentId, 1);

            if(!BxDolModuleQuery::getInstance()->isEnabledByName('bx_notifications'))
                $this->_sendNotificationEmail($iCmtId, $iCmtParentId);
        }

        $this->_triggerComment();

        if($this->_sMetatagsObj && ($oMetatags = BxDolMetatags::getObjectInstance($this->_sMetatagsObj)) !== false)
            $oMetatags->metaAdd($iCmtUniqId, $aValues['cmt_text']);

        $mixedResult = $this->onPostAfter($iCmtId);
        if($mixedResult === false)
            return array('code' => 2, 'message' => '_cmt_err_cannot_perform_action');

        return $mixedResult;
    }

    protected function _getFormPost($iCmtParentId = 0, $aDp = [])
    {
        $bCmtParentId = !empty($iCmtParentId);

        if(!$bCmtParentId && !$this->isPostAllowed()) {
            $sMsg = '';
            if(!isLogged()) {
                $oPermalink = BxDolPermalinks::getInstance();
                $sMsg = _t('_cmt_msg_login_required', $oPermalink->permalink('page.php?i=login'), $oPermalink->permalink('page.php?i=create-account'));
            }
            else
                $sMsg = $this->msgErrPostAllowed();

            return bx_is_api() ? ['id' => 1, 'type' => 'msg', 'data' => $sMsg] : ['msg' => $sMsg];
        }

        if($bCmtParentId && !$this->isReplyAllowed($iCmtParentId))
            return bx_is_api() ? ['id' => 1, 'type' => 'msg', 'data' => $sMsg] : ['msg' => $this->msgErrReplyAllowed()];

        $bDynamic = isset($aDp['dynamic_mode']) && (bool)$aDp['dynamic_mode'];
        $bQuote = isset($aDp['quote']) && (bool)$aDp['quote'];

        $oForm = $this->_getForm(BX_CMT_ACTION_POST, $iCmtParentId);
        $oForm->aInputs['cmt_parent_id']['value'] = $iCmtParentId;

        if($bQuote) {
            $aCmtParent = $this->getCommentRow((int)$iCmtParentId);
            if(!empty($aCmtParent['cmt_text']))
                $oForm->aInputs['cmt_text']['value'] = $this->_oTemplate->parseHtmlByName('comment_quote.html', [
                    'content' => $aCmtParent['cmt_text']
                ]);
        }

        $oForm->initChecker();
        if($oForm->isSubmittedAndValid()) {
            $iCmtAuthorId = $this->_getAuthorId();
            $iCmtParentId = (int)$oForm->getCleanValue('cmt_parent_id');
            
            //--- Process Text ---//
            $sCmtText = $oForm->getCleanValue('cmt_text');
            $bCmtText = !empty($sCmtText);

            //--- Process Media ---//
            $aImageIds = $oForm->getCleanValue('cmt_image');
            $bImageIds = !empty($aImageIds) && is_array($aImageIds);

            if(!$bCmtText && !$bImageIds) {
                $oForm->aInputs['cmt_text']['error'] =  _t('_Please enter characters');
                $oForm->setValid(false);
                return bx_is_api() ? ['form' => $oForm, 'res' => 0] : ['form' => $oForm->getCode($bDynamic), 'form_id' => $oForm->id];
            }

            $aParent = [];
            if($iCmtParentId > 0) {
                $aParent = $this->getCommentRow($iCmtParentId);
                if(empty($aParent) || !is_array($aParent)) {
                    $iCmtParentId = 0;
                    $oForm->setSubmittedValue('cmt_parent_id', $iCmtParentId, $oForm->aFormAttrs['method']);
                }
            }

            $iLevel = 0;
            $iCmtVisualParentId = 0;
            if($iCmtParentId > 0) {
                $iLevel = (int)$aParent['cmt_level'] + 1;
                $iCmtVisualParentId = $iLevel > $this->getMaxLevel() ? $aParent['cmt_vparent_id'] : $iCmtParentId;
            }

            $iCmtId = (int)$oForm->insert(['cmt_vparent_id' => $iCmtVisualParentId, 'cmt_object_id' => $this->_iId, 'cmt_author_id' => $iCmtAuthorId, 'cmt_level' => $iLevel, 'cmt_time' => time()]);
            if($iCmtId != 0) {
                $iCmtUniqId = $this->_oQuery->getUniqId($this->_aSystem['system_id'], $iCmtId, [
                    'author_id' => $iCmtAuthorId,
                    'status_admin' => $this->getStatusAdmin()
                ]);

                if($this->isAttachImageEnabled())
                    $oForm->processImages($this, 'cmt_image', $iCmtUniqId, $iCmtId, $iCmtAuthorId, true);

                if($iCmtParentId > 0) {
                    $this->_oQuery->updateRepliesCount($iCmtParentId, 1);

                    if(!BxDolModuleQuery::getInstance()->isEnabledByName('bx_notifications'))
                        $this->_sendNotificationEmail($iCmtId, $iCmtParentId);
                }

                $this->_triggerComment();

                if($iCmtParentId > 0)
                    $this->isReplyAllowed($iCmtParentId, true);
                else
                    $this->isPostAllowed(true);

                if($this->_sMetatagsObj && ($oMetatags = BxDolMetatags::getObjectInstance($this->_sMetatagsObj)) !== false)
                    $oMetatags->metaAdd($iCmtUniqId, $sCmtText);

                if(($mixedResult = $this->onPostAfter($iCmtId, $aDp)) !== false){
                    if (bx_is_api()){
                        $this->_unsetFormObject(BX_CMT_ACTION_POST);
                        return ['form' => $this->_getForm(BX_CMT_ACTION_POST, $iCmtParentId), 'res' => $iCmtId];
                    }
                    else{
                        return $mixedResult;
                    }
                }
            }
            return bx_is_api() ? ['id' => 1, 'type' => 'msg', 'data' => _t('_cmt_err_cannot_perform_action')] : ['msg' => _t('_cmt_err_cannot_perform_action')];
        }
        return bx_is_api() ? ['form' => $oForm, 'res' => 0] : ['form' => $oForm->getCode($bDynamic), 'form_id' => $oForm->id];
    }

    protected function _getFormEdit($iCmtId, $aDp = [])
    {
        $bDynamic = isset($aDp['dynamic_mode']) && (bool)$aDp['dynamic_mode'];

        $aCmt = $this->getCommentSimple($iCmtId);
        if(!$aCmt)
            return array('msg' => _t('_No such comment'));

        $iCmtAuthorId = $this->_getAuthorId();
        if(!$this->isEditAllowed($aCmt))
            return array('msg' => $aCmt['cmt_author_id'] == $iCmtAuthorId ? strip_tags($this->msgErrEditAllowed()) : _t('_Access denied'));

        $oForm = $this->_getForm(BX_CMT_ACTION_EDIT, $aCmt['cmt_id']);

        $oForm->initChecker($aCmt);
        if($oForm->isSubmittedAndValid()) {
            $sCmtText = $oForm->getCleanValue('cmt_text');

            if($oForm->update($iCmtId) !== false) {
                $iCmtUniqId = $this->_oQuery->getUniqId($this->_aSystem['system_id'], $iCmtId, [
                    'author_id' => (int)$aCmt['cmt_author_id']
                ]);
                
                $sStatusAdmin = $this->getStatusAdmin();
                if($sStatusAdmin !== BX_CMT_STATUS_ACTIVE)
                    $this->_oQuery->updateUniqId(['status_admin' => $sStatusAdmin], ['id' => $iCmtUniqId]);

                if($this->isAttachImageEnabled())
                    $oForm->processImages($this, 'cmt_image', $iCmtUniqId, $iCmtId, $iCmtAuthorId, false);

                $this->isEditAllowed($aCmt, true);

                if($this->_sMetatagsObj && ($oMetatags = BxDolMetatags::getObjectInstance($this->_sMetatagsObj)) !== false)
                    $oMetatags->metaAdd($iCmtUniqId, $sCmtText);

                if(($mixedResult = $this->onEditAfter($iCmtId, $aDp)) !== false)
                    return $mixedResult;
            }

            return array('msg' => _t('_cmt_err_cannot_perform_action'));
        }

        return array('form' => $oForm->getCode($bDynamic), 'form_id' => $oForm->id);
    }

    protected function _getForm($sAction, $iId)
    {
        $oForm = $this->_getFormObject($sAction);
        $oForm->setId(sprintf($oForm->getAttributeMask('id'), $sAction, $this->_sSystem, $this->_iId, $iId));
        $oForm->setName(sprintf($oForm->getAttributeMask('name'), $sAction, $this->_sSystem, $this->_iId, $iId));
        $oForm->aParams['db']['table'] = $this->_aSystem['table'];
        $oForm->aInputs['sys']['value'] = $this->_sSystem;
        $oForm->aInputs['id']['value'] = $this->_iId;
        $oForm->aInputs['action']['value'] = 'Submit' . ucfirst($sAction) . 'Form';

        if(!$this->isAttachImageEnabled())
            unset($oForm->aInputs['cmt_image']);

        if(isset($oForm->aInputs['cmt_text'])) {
            $oForm->aInputs['cmt_text']['html'] = $this->_aSystem['html'];
            $oForm->aInputs['cmt_text']['db']['pass'] = $this->isHtml() ? 'XssHtml' : 'XssMultiline';

            if(isset($oForm->aInputs['cmt_text']['checker']['func']) && strtolower($oForm->aInputs['cmt_text']['checker']['func']) == 'length') {
                $iCmtTextMin = (int)$this->_aSystem['chars_post_min'];
                $iCmtTextMax = (int)$this->_aSystem['chars_post_max'];
                $oForm->aInputs['cmt_text']['checker']['params'] = array($iCmtTextMin, $iCmtTextMax);
                $oForm->aInputs['cmt_text']['checker']['error'] = _t('_Please enter n1-n2 characters', $iCmtTextMin, $iCmtTextMax);
            }
        }

        if($sAction == BX_CMT_ACTION_EDIT && isset($oForm->aInputs['cmt_controls']))
            foreach($oForm->aInputs['cmt_controls'] as $mixedKey => $mixedValue) {
                if(!is_numeric($mixedKey) || empty($mixedValue['name']) || $mixedValue['name'] != 'cmt_cancel')
                    continue;

                if(!isset($oForm->aInputs['cmt_controls'][$mixedKey]['attrs']))
                    $oForm->aInputs['cmt_controls'][$mixedKey]['attrs'] = array();

                $oForm->aInputs['cmt_controls'][$mixedKey]['attrs']['onclick'] = $this->_sJsObjName . '.cmtEdit(this, ' . $iId . ', false)';
            }

        return $oForm;
    }

    protected function _getContent($aCmt, $aBp = [], $aDp = [])
    {
        $bDynamicMode = isset($aDp['dynamic_mode']) && $aDp['dynamic_mode'] === true;

        $sAttachments = $this->_getAttachments($aCmt);

        $oMenuCounters = BxDolMenu::getObjectInstance($this->_sMenuObjCounters);
        $oMenuCounters->setCmtsData($this, $aCmt['cmt_id'], $aBp, $aDp);
        $oMenuCounters->setDynamicMode($bDynamicMode);

        return $this->_oTemplate->parseHtmlByName($this->_sTmplNameItemContent, array_merge(array(
            'style_prefix' => $this->_sStylePrefix,
            'js_object' => $this->_sJsObjName,
            'bx_if:show_attached' => array(
                'condition' => !empty($sAttachments),
                'content' => array(
                    'style_prefix' => $this->_sStylePrefix,
                    'attached' => $sAttachments
                )
            ),
            'counters' => $oMenuCounters->getCode(),
        ), $this->_getTmplVarsText($aCmt)));
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

        $sParentKey = isset($aBp['vparent_id']) ? 'vparent_id' : 'parent_id';
        $bRoot = (int)$aBp[$sParentKey] <= 0;

        $sMore = $this->_oTemplate->parseHtmlByName('comment_more.html', array(
            'js_object' => $this->_sJsObjName,
            'style_prefix' => $this->_sStylePrefix,
            'bx_if:is_root' => array(
                'condition' => $bRoot,
                'content' => array()
            ),
            'parent_id' => $aBp[$sParentKey],
            'start' => $iStart,
            'per_view' => $iPerView,
            'title' => _t('_cmt_load_more_' . ($aBp[$sParentKey] == 0 ? 'comments' : 'replies') . '_' . $aBp['type'])
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

    protected function _getEmpty($aDp = array())
    {
        $sClass = '';
        if(!empty($aDp['class']))
            $sClass .= ' ' . $aDp['class'];

        return $this->_oTemplate->parseHtmlByName('comment_empty.html', array(
            'style_prefix' => $this->_sStylePrefix,
            'class' => $sClass,
            'content' => MsgBox(_t('_Empty'))
        ));
    }

    protected function _getAttachments($aCmt)
    {
        $aTmplImages = array();
        if(!$this->isAttachImageEnabled())
            return ''; 

        $aFiles = $this->_oQuery->getFiles($this->_aSystem['system_id'], $aCmt['cmt_id']);
        if(!empty($aFiles) && is_array($aFiles)) {
            $oStorage = BxDolStorage::getObjectInstance($this->getStorageObjectName());
            $oTranscoder = BxDolTranscoderImage::getObjectInstance($this->getTranscoderPreviewName());

            foreach($aFiles as $aFile) {
                $sFile = $oStorage->getFileUrlById($aFile['image_id']);
                $bImage = $oTranscoder && $oTranscoder->isMimeTypeSupported($aFile['mime_type']);

                $sPreview = '';
                $iWidth = $iHeight = 0;

                if($bImage) {
                    if($oTranscoder)
                        $sPreview = $oTranscoder->getFileUrl($aFile['image_id']);

                    $aImageInfo = BxDolImageResize::getInstance()->getImageSize($sFile);
                    if(isset($aImageInfo['w']))
                        $iWidth = (int)$aImageInfo['w'];
                    if(isset($aImageInfo['h']))
                        $iHeight = (int)$aImageInfo['h'];
                }

                if(!$sPreview)
                    $sPreview = $this->_oTemplate->getIconUrl($oStorage->getIconNameByFileName($aFile['file_name']));

                $aTmplVarsFile = array(
                    'js_object' => $this->_sJsObjName,
                    'preview' => $sPreview,
                    'file' => $sFile,
                    'file_id' => $aFile['id'],
                    'file_name' => $aFile['file_name'],
                    'file_icon' => $oStorage->getFontIconNameByFileName($aFile['file_name']),
                    'file_size' => _t_format_size($aFile['size']),
                    'w' => $iWidth, 
                    'h' => $iHeight
                );

                $aTmplImages[] = array(
                    'style_prefix' => $this->_sStylePrefix,
                    'bx_if:show_image' => array(
                        'condition' => $bImage,
                        'content' => $aTmplVarsFile
                    ),
                    'bx_if:show_file' => array(
                        'condition' => !$bImage,
                        'content' => $aTmplVarsFile
                    ),
                );
            }
        }

        return $this->_oTemplate->parseHtmlByName('comment_attachments.html', array(
            'bx_repeat:attached' => $aTmplImages
        ));
    }

    protected function _getTmplVarsNotes($aCmt)
    {
        $aTmplVars = array();
        
        //--- Pending status related notes.
        if($aCmt['cmt_status_admin'] == BX_CMT_STATUS_PENDING)
            $aTmplVars[] = [
                'style_prefix' => $this->_sStylePrefix,
                'bx_if:show_note_color' => [
                    'condition' => true,
                    'content' => [
                        'item_note_color' => 'red3'
                    ]
                ],
                'item_note' => _t('_cmt_txt_note_approve_pending')
            ];

        return [
            'bx_if:show_notes' => [
                'condition' => !empty($aTmplVars),
                'content' => [
                    'style_prefix' => $this->_sStylePrefix,
                    'bx_repeat:notes' => $aTmplVars
                ]
            ],
        ];
    }

    protected function _getTmplVarsAuthor($aCmt)
    {
    	list($sAuthorName, $sAuthorLink, $sAuthorIcon, $sAuthorUnit) = $this->_getAuthorInfo($aCmt['cmt_author_id']);
    	$bAuthorIcon = !empty($sAuthorIcon);

    	return array(
    	    'author_unit' => $sAuthorUnit,
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
            		'author_title' => bx_html_attribute($sAuthorName),
                    'author_name' => $sAuthorName
                )
            ),
            'bx_if:show_author_text' => array(
                'condition' => empty($sAuthorLink),
                'content' => array(
                    'author_name' => $sAuthorName
                )
            ),
    	);
    }

    protected function _getTmplVarsText($aCmt)
    {
    	$sText = $aCmt['cmt_text'];
        $sTextMore = '';

        if(!$this->isHtml()) {
            $iMaxLength = (int)$this->_aSystem['chars_display_max'];
            if(strlen($sText) > $iMaxLength) {
                $iLength = strpos($sText, ' ', $iMaxLength);
    
                $sTextMore = trim(substr($sText, $iLength));
                $sText = trim(substr($sText, 0, $iLength));
            }
        }

        $sText = $this->_prepareTextForOutput($sText, $aCmt['cmt_id']);
        $sTextMore = $this->_prepareTextForOutput($sTextMore, $aCmt['cmt_id']);

        return array(
			'text' => $sText,
            'bx_if:show_more' => array(
                'condition' => !empty($sTextMore),
                'content' => array(
                    'style_prefix' => $this->_sStylePrefix,
                    'js_object' => $this->_sJsObjName,
                    'text_more' => $sTextMore
                )
            ),
        );
    }
}

/** @} */
