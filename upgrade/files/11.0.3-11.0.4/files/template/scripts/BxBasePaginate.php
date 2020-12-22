<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * @see BxDolPaginate
 */
class BxBasePaginate extends BxDolPaginate
{
    function __construct($aParams, $oTemplate)
    {
        parent::__construct($aParams, $oTemplate);
    }

    protected function _getButton($sName, $aParams)
    {
        return $this->_oTemplate->parseHtmlByName('paginate_btn.html', array(
            'class' => 'bx-paginate-btn bx-paginate-btn-' . $sName . (!empty($aParams['class']) ? $aParams['class'] : ''),
            'href' => !empty($aParams['href']) ? $aParams['href'] : 'javascript:void(0)',
            'onclick' => !empty($aParams['onclick']) ? $aParams['onclick'] : '',
            'icon' => $this->{'_getButtonIcon' . bx_gen_method_name($sName)}()
        ));
    }
}

/** @} */
