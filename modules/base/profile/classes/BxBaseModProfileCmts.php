<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseProfile Base classes for profile modules
 * @ingroup     UnaModules
 *
 * @{
 */

class BxBaseModProfileCmts extends BxTemplCmts
{
    protected $_sModule;
    protected $_oModule;

    function __construct($sSystem, $iId, $iInit = 1)
    {
        parent::__construct($sSystem, $iId, $iInit);
        
        $this->_oModule = BxDolModule::getInstance($this->_sModule);
    }

    public function isPostAllowed ($isPerformAction = false)
    {
    	$bCheckResult = $this->_isPostAllowed ($isPerformAction);

        /**
         * @hooks
         * @hookdef hook-bx_base_profile-comment_post_allowed '{module_name}', 'comment_post_allowed' - hook to override the result of checking whether comment posting action allowed or not
         * - $unit_name - module name
         * - $action - equals `comment_post_allowed`
         * - $object_id - content id
         * - $sender_id - not used
         * - $extra_params - array of additional params with the following array keys:
         *      - `check_result` - [boolean] by ref, check action result, can be overridden in hook processing.
         * @hook @ref hook-bx_base_profile-comment_post_allowed
         */
        bx_alert($this->_sModule, 'comment_post_allowed', $this->getId(), false, [
            'check_result' => &$bCheckResult
        ]);

        return $bCheckResult;
    }

    protected function _isPostAllowed ($isPerformAction = false)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $oProfileOwner = BxDolProfile::getInstanceByContentAndType($this->getId(), $this->_sModule);
    	if($oProfileOwner !== false && $oProfileOwner->checkAllowedPostInProfile() !== CHECK_ACTION_RESULT_ALLOWED)
            return false;

        return parent::isPostAllowed($isPerformAction);
    }
}

/** @} */
