<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Convos Convos
 * @ingroup     UnaModules
 *
 * @{
 */

class BxCnvCmts extends BxTemplCmts
{
    protected $_sModule;
    protected $_oModule;

    /**
     * Silent Mode for Notification based modules.
     */
    protected $_iSilentMode;

    function __construct($sSystem, $iId, $iInit = true, $oTemplate = false)
    {
        $this->_sModule = 'bx_convos';
        $this->_oModule = BxDolModule::getInstance($this->_sModule);

        parent::__construct($sSystem, $iId, $iInit, $oTemplate);

        $this->_iSilentMode = 21; //--- Absolute for Timeline module only.

        $this->_aT = array_merge($this->_aT, array(
            'txt_min_form_placeholder' => '_bx_cnv_txt_min_form_placeholder'
        ));
    }

    public function getObjectTitle ($iObjectId = 0)
    {
    	$sResult = parent::getObjectTitle($iObjectId);
    	if(!empty($sResult))
    		return strmaxtextlen($sResult, 20, '...');

        return $sResult;
    }

    public function addCssJs()
    {
        parent::addCssJs();

        $this->_oModule->_oTemplate->addCss(array('cmts.css'));
    }

    /**
     * Comments are enabled for collaborators only
     */
    public function isEnabled ()
    {
        if (!parent::isEnabled ())
            return false;

        if (!$this->_oModule->_oDb->getContentInfoById((int)$this->getId()))
            return false;

        $aCollaborators = $this->_oModule->_oDb->getCollaborators((int)$this->getId());
        if (!isset($aCollaborators[bx_get_logged_profile_id()]))
            return false;

        return true;
    }

    public function isRemoveAllowed ($aCmt, $isPerformAction = false)
    {
        if (isAdmin())
            return true;

        return false;
    }

    public function msgErrRemoveAllowed ()
    {
        return _t('_sys_txt_access_denied');
    }

    public function isEditAllowed ($aCmt, $isPerformAction = false)
    {
        return $this->isRemoveAllowed ($aCmt, $isPerformAction);
    }

    public function msgErrEditAllowed ()
    {
        return $this->msgErrRemoveAllowed ();
    }

    public function getCommentsBlock($aBp = array(), $aDp = array())
    {
        $mixedBlock = parent::getCommentsBlock($aBp, $aDp);
        if (is_array($mixedBlock) && isset($mixedBlock['title']))
            $mixedBlock['title'] = _t('_bx_cnv_page_block_title_entry_comments', $this->getCommentsCount());

        return $mixedBlock;
    }

    public function getCommentBlock($iCmtId = 0, $aBp = array(), $aDp = array())
    {
        if(!$this->isEnabled())
            return '';

        return parent::getCommentBlock($iCmtId, $aBp, $aDp);
    }

    protected function _getFormObject($sAction = BX_CMT_ACTION_POST)
    {
        $oForm = parent::_getFormObject($sAction);
        $oForm->aInputs['cmt_submit']['value'] = _t('_sys_send');
        return $oForm;
    }

    public function onPostAfter($iCmtId)
    {
        $iObjId = (int)$this->getId();
        $iObjAthrId = $this->getObjectAuthorId($iObjId);
        $iObjAthrPrivacyView = $this->getObjectPrivacyView($iObjId);

        $aCmt = $this->_oQuery->getCommentSimple($iObjId, $iCmtId);
        if(empty($aCmt) || !is_array($aCmt))
            return false;

        $iCmtUniqId = $this->getCommentUniqId($iCmtId);
        $iCmtPrntId = (int)$aCmt['cmt_parent_id'];
        $iPerformerId = (int)$aCmt['cmt_author_id'];
        bx_alert($this->_sSystem, 'commentPost', $iObjId, $iPerformerId, array(
            'object_author_id' => $iObjAthrId,

            'comment_id' => $iCmtId, 
            'comment_uniq_id' => $iCmtUniqId,
            'comment_author_id' => $aCmt['cmt_author_id'], 
            'comment_text' => $aCmt['cmt_text'],

            'privacy_view' => $iObjAthrPrivacyView,
            'silent_mode' => $this->_iSilentMode
        ));

        bx_audit(
            $this->getId(), 
            $this->_aSystem['module'], 
            '_sys_audit_action_add_comment',  
            $this->_prepareAuditParams($iCmtId, array('comment_author_id' => $aCmt['cmt_author_id'], 'comment_text' => $aCmt['cmt_text']))
        );

        bx_alert('comment', 'added', $iCmtId, $iPerformerId, array(
            'object_system' => $this->_sSystem, 
            'object_id' => $iObjId, 
            'object_author_id' => $iObjAthrId,

            'comment_uniq_id' => $iCmtUniqId,
            'comment_author_id' => $aCmt['cmt_author_id'], 
            'comment_text' => $aCmt['cmt_text'],

            'privacy_view' => $iObjAthrPrivacyView,
            'silent_mode' => $this->_iSilentMode
        ));

        if(!empty($iCmtPrntId)) {
            $aCmtPrnt = $this->_oQuery->getCommentSimple($iObjId, $iCmtPrntId);
            if(!empty($aCmtPrnt) && is_array($aCmtPrnt)) {
                $iCmtPrntUniqId = $this->getCommentUniqId($iCmtPrntId);

                bx_alert($this->_sSystem, 'replyPost', $iCmtPrntId, $iPerformerId, array(
                    'object_id' => $iObjId, 
                    'object_author_id' => $iObjAthrId,

                    'parent_uniq_id' => $iCmtPrntUniqId,
                    'parent_author_id' => $aCmtPrnt['cmt_author_id'],

                    'comment_id' => $iCmtId,
                    'comment_uniq_id' => $iCmtUniqId,
                    'comment_author_id' => $aCmt['cmt_author_id'], 
                    'comment_text' => $aCmt['cmt_text'],

                    'privacy_view' => $iObjAthrPrivacyView,
                    'silent_mode' => $this->_iSilentMode
                ));

                bx_alert('comment', 'replied', $iCmtId, $iPerformerId, array(
                    'object_system' => $this->_sSystem, 
                    'object_id' => $iObjId, 
                    'object_author_id' => $iObjAthrId,

                    'parent_id' => $iCmtPrntId,
                    'parent_uniq_id' => $iCmtPrntUniqId,
                    'parent_author_id' => $aCmtPrnt['cmt_author_id'],

                    'comment_uniq_id' => $iCmtUniqId,
                    'comment_author_id' => $aCmt['cmt_author_id'],  
                    'comment_text' => $aCmt['cmt_text'],

                    'privacy_view' => $iObjAthrPrivacyView,
                    'silent_mode' => $this->_iSilentMode
                ));
            }
        }

        return array('id' => $iCmtId, 'parent_id' => $iCmtPrntId);
    }

    public function onEditAfter($iCmtId)
    {
        $iObjId = (int)$this->getId();
    	$iObjAthrId = $this->getObjectAuthorId($iObjId);
        $iObjAthrPrivacyView = $this->getObjectPrivacyView($iObjId);

    	$aCmt = $this->getCommentRow($iCmtId);
        if(empty($aCmt) || !is_array($aCmt))
            return false;

        $iCmtUniqId = $this->getCommentUniqId($iCmtId);
        $iPerformerId = $this->_getAuthorId();
        bx_alert($this->_sSystem, 'commentUpdated', $iObjId, $iPerformerId, array(
            'object_author_id' => $iObjAthrId,

            'comment_id' => $iCmtId, 
            'comment_uniq_id' => $iCmtUniqId,
            'comment_author_id' => $aCmt['cmt_author_id'], 
            'comment_text' => $aCmt['cmt_text'],

            'privacy_view' => $iObjAthrPrivacyView,
            'silent_mode' => $this->_iSilentMode
        ));

        bx_audit(
            $this->getId(), 
            $this->_aSystem['module'], 
            '_sys_audit_action_edit_comment',  
            $this->_prepareAuditParams($iCmtId, array('comment_author_id' => $aCmt['cmt_author_id'], 'comment_text' => $aCmt['cmt_text']))
        );

        bx_alert('comment', 'edited', $iCmtId, $iPerformerId, array(
            'object_system' => $this->_sSystem, 
            'object_id' => $iObjId, 
            'object_author_id' => $iObjAthrId,

            'comment_uniq_id' => $iCmtUniqId,
            'comment_author_id' => $aCmt['cmt_author_id'],
            'comment_text' => $aCmt['cmt_text'],

            'privacy_view' => $iObjAthrPrivacyView,
            'silent_mode' => $this->_iSilentMode
        ));

        return array('id' => $iCmtId, 'content' => $this->_getContent($aCmt));
    }
}

/** @} */
