<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

bx_import('BxTemplFormView');

class BxFormConfirmEmailCheckerHelper extends BxDolFormCheckerHelper
{
    /**
     * Check if key exists
     */
    function checkCodeExist ($s)
    {
        bx_import('BxDolKey');
        $oKey = BxDolKey::getInstance();
        return $oKey && $oKey->isKeyExists(trim($s));
    }

}

/**
 * Email Confirmation Form.
 */
class BxBaseFormConfirmEmail extends BxTemplFormView
{
    public function __construct($aInfo, $oTemplate)
    {
        parent::__construct($aInfo, $oTemplate);
    }

}

/** @} */
