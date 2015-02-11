<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Accounts Accounts
 * @ingroup     TridentModules
 *
 * @{
 */

/**
 * 'Persons manage tools' menu.
 */
class BxAccntMenuManageTools extends BxBaseModGeneralMenuManageTools
{

    public function __construct($aObject, $oTemplate = false)
    {
        $this->MODULE = 'bx_accounts';
        parent::__construct($aObject, $oTemplate);
    }

    /**
     * Check if menu items is visible.
     * @param $a menu item array
     * @return boolean
     */
    protected function _isVisible ($a)
    {
        if(!parent::_isVisible($a))
            return false;

        $aDataEntry = BxDolAccountQuery::getInstance()->getInfoById($this->_iContentId);
        if(empty($aDataEntry) || !is_array($aDataEntry))
        	return false;

        $sCheckFuncName = '';
        switch ($a['name']) {
            case 'delete':
            case 'delete-with-content':
                $sCheckFuncName = 'checkAllowedDelete';
                break;
        }

        if(!$sCheckFuncName || !method_exists($this->_oModule, $sCheckFuncName))
            return true;

        return $this->_oModule->{$sCheckFuncName}($aDataEntry) === CHECK_ACTION_RESULT_ALLOWED;
    }
}

/** @} */
