<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Invites Invites
 * @ingroup     UnaModules
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
