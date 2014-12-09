<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Invites Invites
 * @ingroup     TridentModules
 *
 * @{
 */

bx_import('BxDolForm');

class BxInvFormCheckerHelper extends BxDolFormCheckerHelper
{
	static public function checkEmails($s)
    {
    	$aEmails = preg_split("/[\s\n,;]+/", $s);
    	foreach($aEmails as $sEmail)
    		if(!empty($sEmail) && !self::checkEmail($sEmail))
    			return false;

        return true;
    }
}

/** @} */
