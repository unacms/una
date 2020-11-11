<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaTemplate UNA Template Classes
 * @{
 */

/**
 * @see BxDolMenu
 */
class BxTemplMenu extends BxBaseMenu
{
    public function __construct ($aObject, $oTemplate = false)
    {
        parent::__construct ($aObject, $oTemplate);
        
        $this->_aOptionalParams['popup'] = '';
    }

    protected function _getTemplateVars()
    {
        return array_merge(parent::_getTemplateVars(), array(
            'html_id' => '',
            'bx_if:show_more_auto_class' => array(
                'condition' => false,
                'content' => array()
            ),
            'js_code' => ''
        ));
    }
}

/** @} */
