<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

bx_import('BxTemplMenu');

/**
 * Menu representation.
 * @see BxDolMenu
 */
class BxBaseCmtsMenuManage extends BxTemplMenu
{
    protected $_oCmts;
    protected $_aCmt;

    public function __construct ($aObject, $oTemplate)
    {
        parent::__construct ($aObject, $oTemplate);
    }

    public function setCmtsData($oCmts, $iCmtId)
    {
        if(empty($oCmts) || empty($iCmtId))
            return;

        $this->_oCmts = $oCmts;
        $this->_aCmt = $oCmts->getCommentRow($iCmtId);

        $this->addMarkers(array(
            'js_object' => $oCmts->getJsObjectName(),
            'content_id' => $iCmtId,
        ));
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

        $sCheckFuncName = '';
        $aCheckFuncParams = array();
        switch ($a['name']) {
            case 'item-edit':
                $sCheckFuncName = 'isEditAllowed';
                if(!empty($this->_aCmt))
                    $aCheckFuncParams = array($this->_aCmt);
                break;

            case 'item-delete':
                $sCheckFuncName = 'isRemoveAllowed';
                if(!empty($this->_aCmt))
                    $aCheckFuncParams = array($this->_aCmt);
                break;
        }

        if(!$sCheckFuncName || !method_exists($this->_oCmts, $sCheckFuncName))
            return true;

        return call_user_func_array(array($this->_oCmts, $sCheckFuncName), $aCheckFuncParams);
    }
}

/** @} */
