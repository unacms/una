<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * Wiki page
 */
class BxBasePageWiki extends BxTemplPage
{
    public function __construct($aObject, $oTemplate)
    {
        parent::__construct($aObject, $oTemplate);
    }

    protected function _getPageCodeVars ()
    {
        $aVars = parent::_getPageCodeVars ();
        
        $oWiki = BxDolWiki::getObjectInstance($this->_aObject['module']);
        if ($oWiki->isAllowed('add')) {
            foreach ($aVars as $sKey => $sCell) {
                if (0 !== strncmp('cell_', $sKey, 5))
                    continue;
                $aVars[$sKey] = $sCell . '<div class="bx-def-margin-top bx-def-padding" style="border:1px dashed #aaa; text-align:center;"><a href="javascript:void(0);" onclick="alert(\'TODO: wiki block add form\')"><i class="sys-icon plus"></i> Add Block</a></div>';
            }
        }
        return $aVars;
    }
}

/** @} */
