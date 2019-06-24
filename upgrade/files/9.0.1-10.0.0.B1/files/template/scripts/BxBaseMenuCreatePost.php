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
class BxBaseMenuCreatePost extends BxTemplMenuInteractive
{
	protected $_sJsObject;

    public function __construct ($aObject, $oTemplate)
    {
        parent::__construct ($aObject);

        $this->_aObject['menu_id'] = 'sys-create-post-menu';

        $this->_bShowDivider = false;
        $this->_sJsObject = 'oBxDolCreatePost';
    }

	protected function getMenuItemsRaw ()
    {
    	$aModules = BxDolModuleQuery::getInstance()->getModulesBy(array('type' => 'all_pairs_name_uri', 'active' => 1));

    	$aMenuItems = $this->_oQuery->getMenuItems();
    	foreach($aMenuItems as $iKey => $aMenuItem) {
            $sModule = $aMenuItem['module'];
            if(!isset($aModules[$sModule]))
                continue;

            $aMenuItems[$iKey]['id'] = $sModule;
            $aMenuItems[$iKey]['name'] = $sModule;
            $aMenuItems[$iKey]['onclick'] = "return " . $this->_sJsObject . ".getForm('" . $sModule . "', '" . $aModules[$sModule] . "', this)";
    	}

        return $aMenuItems;
    }

    protected function _isVisible ($a)
    {
    	if(BxDolRequest::serviceExists($a['module'], 'act_as_profile'))
			return false;

		return parent::_isVisible($a);
    }
}

/** @} */
