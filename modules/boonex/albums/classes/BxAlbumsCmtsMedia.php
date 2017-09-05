<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Albums Albums
 * @ingroup     UnaModules
 *
 * @{
 */

class BxAlbumsCmtsMedia extends BxTemplCmts
{
	protected $MODULE;
	protected $_oModule;

    function __construct($sSystem, $iId, $iInit = 1)
    {
    	$this->MODULE = 'bx_albums';
    	$this->_oModule = BxDolModule::getInstance($this->MODULE);

        parent::__construct($sSystem, $iId, $iInit);
    }

    public function isViewAllowed ($isPerformAction = false)
    {
        $CNF = $this->_oModule->_oConfig->CNF;

        $aMedia = $this->_oModule->_oDb->getMediaInfoById($this->getId());

        $mixedResult = BxDolService::call($this->_aSystem['module'], 'check_allowed_comments_view', array($aMedia['content_id'], $CNF['OBJECT_COMMENTS']));
        if($mixedResult !== CHECK_ACTION_RESULT_ALLOWED)
            return $mixedResult;

        return CHECK_ACTION_RESULT_ALLOWED;
    }

    public function isPostReplyAllowed ($isPerformAction = false)
    {
        $CNF = $this->_oModule->_oConfig->CNF;

        $aMedia = $this->_oModule->_oDb->getMediaInfoById($this->getId());

    	$mixedResult = BxDolService::call($this->_aSystem['module'], 'check_allowed_comments_post', array($aMedia['content_id'], $CNF['OBJECT_COMMENTS']));
        if($mixedResult !== CHECK_ACTION_RESULT_ALLOWED)
            return false;

        return $this->checkAction ('comments post', $isPerformAction);
    }
}

/** @} */
