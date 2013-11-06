<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 * 
 * @defgroup    Timeline Timeline
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxTemplCmtsView');

class BxTimelineCmts extends BxTemplCmtsView
{
    function __construct($sSystem, $iId, $iInit = 1)
    {
        parent::BxTemplCmtsView($sSystem, $iId, $iInit);
    }

    public function isAttachImageEnabled()
    {
    	return false;
    }

	public function isPostReplyAllowed ($isPerformAction = false)
	{
		bx_import('BxDolModule');
		$oModule = BxDolModule::getInstance('bx_timeline');
		if(!$oModule->isAllowedComment($isPerformAction))
			return false;

        return parent::isPostReplyAllowed($isPerformAction);
    }
}

/** @} */ 
