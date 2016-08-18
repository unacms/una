<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Forum Forum
 * @ingroup     TridentModules
 *
 * @{
 */

class BxForumCmts extends BxTemplCmts
{
    function getCommentsBlock($iParentId = 0, $iVParentId = 0, $bInDesignbox = true)
    {
        $mixedBlock = parent::getCommentsBlock($iParentId, $iVParentId, $bInDesignbox);
        if (is_array($mixedBlock) && isset($mixedBlock['title']))
            $mixedBlock['title'] = _t('_bx_forum_page_block_title_entry_comments', $this->getCommentsCount());
        return $mixedBlock;
    }

    function getComment($mixedCmt, $aBp = array(), $aDp = array())
    {
    	return parent::getComment($mixedCmt, $aBp, array_merge($aDp, array(
    		'class_comment' => ' bx-def-box bx-def-padding bx-def-round-corners bx-def-color-bg-box'
    	)));
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
