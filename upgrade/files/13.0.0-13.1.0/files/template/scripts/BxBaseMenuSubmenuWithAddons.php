<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

require_once(BX_DIRECTORY_PATH_INC . "design.inc.php");

/**
 * Menu representation.
 * @see BxDolMenu
 */
class BxBaseMenuSubmenuWithAddons extends BxTemplMenu
{
    public function __construct ($aObject, $oTemplate)
    {
       
        parent::__construct ($aObject, $oTemplate);
        $this->setDisplayAddons(true);
    }
    
    protected function _getTmplVarsAddon($mixedAddon, $aMenuItem)
    {
        $sAddonF = '';
        if(!empty($mixedAddon))
            $sAddonF = $this->_oTemplate->parseHtmlByTemplateName('menu_item_addon', array(
                'content' => $mixedAddon
            ));

        return array(
            'addon' => $mixedAddon,
            'addonf' => $sAddonF
        );
    }
}

/** @} */
