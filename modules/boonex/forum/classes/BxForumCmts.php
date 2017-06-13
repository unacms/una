<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Forum Forum
 * @ingroup     UnaModules
 *
 * @{
 */

class BxForumCmts extends BxTemplCmts
{
	protected $MODULE;
	protected $_oModule;

	public function __construct($sSystem, $iId, $iInit = 1)
    {
    	$this->MODULE = 'bx_forum';
    	$this->_oModule = BxDolModule::getInstance($this->MODULE);

        parent::__construct($sSystem, $iId, $iInit, $this->_oModule->_oTemplate);

        $this->setTableNameFiles('bx_forum_files');
    }

	public function isPostReplyAllowed($isPerformAction = false)
    {
    	$aContentInfo = $this->_oModule->_oDb->getContentInfoById($this->_iId);
        if(!$aContentInfo || (int)$aContentInfo[$this->_oModule->_oConfig->CNF['FIELD_LOCK']] == 1)
            return false;

    	return parent::isPostReplyAllowed($isPerformAction);
    }

    public function onPostAfter($iId)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if(!parent::onPostAfter($iId))
            return false;

        if(getParam($CNF['PARAM_AUTOSUBSCRIBE_REPLIED']) == 'on')
            BxDolConnection::getObjectInstance($CNF['OBJECT_CONNECTION_SUBSCRIBERS'])->actionAdd((int)$this->getId(), (int)$this->_getAuthorId());
    }

    public function getCommentsBlock($aBp = array(), $aDp = array())
    {
        $mixedBlock = parent::getCommentsBlock($aBp, $aDp);
        if (is_array($mixedBlock) && isset($mixedBlock['title']))
            $mixedBlock['title'] = _t('_bx_forum_page_block_title_entry_comments', $this->getCommentsCount());
        return $mixedBlock;
    }

    public function getComment($mixedCmt, $aBp = array(), $aDp = array())
    {
    	return parent::getComment($mixedCmt, $aBp, array_merge($aDp, array(
    		'class_comment' => ' bx-def-box bx-def-padding bx-def-round-corners bx-def-color-bg-box'
    	)));
    }

	protected function _getFormObject($sAction = BX_CMT_ACTION_POST)
    {
    	$CNF = &$this->_oModule->_oConfig->CNF;

    	$oResult = parent::_getFormObject($sAction);
    	if(!isset($oResult->aInputs['cmt_image']))
    		return $oResult;

		$oResult->aInputs['cmt_image']['storage_object'] = $CNF['OBJECT_STORAGE_CMTS']; 
		$oResult->aInputs['cmt_image']['images_transcoder'] = $CNF['OBJECT_IMAGES_TRANSCODER_PREVIEW_CMTS'];
		$oResult->aInputs['cmt_image']['upload_buttons_titles'] = array('Simple' => 'paperclip');

        return $oResult;
    }

    protected function _getForm($sAction, $iId)
    {
    	$oForm = parent::_getForm($sAction, $iId);

    	if(isset($oForm->aInputs['cmt_text'])) {
    		$oForm->aInputs['cmt_text']['html'] = 3;
    		$oForm->aInputs['cmt_text']['db']['pass'] = 'XssHtml';
    	}

    	return $oForm;
    }
}

/** @} */
