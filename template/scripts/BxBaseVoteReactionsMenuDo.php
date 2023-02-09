<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * Menu representation.
 * @see BxDolMenu
 */
class BxBaseVoteReactionsMenuDo extends BxTemplMenu
{
    protected $_iValue;
    protected $_oObject;

    public function __construct($aObject, $oTemplate)
    {
        parent::__construct ($aObject, $oTemplate);
    }

    public function setParams($aParams)
    {
        if(empty($aParams['object']) || !is_a($aParams['object'], 'BxTemplVoteReactions'))
            return;

        $this->_oObject = $aParams['object'];

        $this->_iValue = isset($aParams['value']) ? (int)$aParams['value'] : $this->_oObject->getValue();
    }

    protected function getMenuItemsRaw()
    {
        $sJsObject = $this->_oObject->getJsObjectName();
        $aReactions = $this->_oObject->getReactions(true);
        $sDefault = $this->_oObject->getDefault();

        $aMenuItems = array();        
        foreach($aReactions as $sName => $aReaction) {
            if($sName == $sDefault)
                continue;

            $sIcon = '';
            switch($aReaction['use']) {
                case 'icon':
                    $sIcon = $this->_oObject->getIcon($sName);
                    break;
                case 'emoji':
                    $sIcon = $this->_oObject->getEmoji($sName);
                    break;
                case 'image':
                    $sIcon = $this->_oObject->getImage($sName);
                    break;
            }

            $aMenuItems[] = array(
                'id' => $sName, 
                'name' => $sName, 
                'class' => '', 
                'link' => 'javascript:void(0)', 
                'onclick' => 'javascript:' . $this->_oObject->getJsClickDo($sName, $this->_iValue), 
                'target' => '_self', 
                'title' => _t($aReaction['title']), 
                'icon' => $sIcon,
                'active' => 1
            );
        }

        return $aMenuItems;
    }

    protected function _getMenuItem ($a)
    {
        $aResult = parent::_getMenuItem($a);
        if(empty($aResult) || !is_array($aResult))
            return $aResult;

        $aResult['title'] = '';
        $aResult['bx_if:title']['condition'] = false;

        return $aResult;
    }
    
    protected function _getMenuAttrs ($a)
    {
        $sResult = parent::_getMenuAttrs($a);
        $sResult .= 'title = "' . bx_html_attribute(_t($a['title'])) . '"';
        return $sResult;
    }
}

/** @} */
