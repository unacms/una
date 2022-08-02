<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Artificer Artificer template
 * @ingroup     UnaModules
 *
 * @{
 */

class BxArtificerStudioOptions extends BxTemplStudioOptions
{
    protected $_sModule;
    protected $_oModule;

    public function __construct($sType = '', $sCategory = '', $sMix = '')
    {
        parent::__construct($sType, $sCategory, $sMix);

        $this->_sModule = 'bx_artificer';
        $this->_oModule = BxDolModule::getInstance($this->_sModule);
    }

    protected function field($aItem, $aItems2Mixes)
    {
        $sPrefix = $this->_oModule->_oConfig->getPrefix('option');

        $aField = parent::field($aItem, $aItems2Mixes);

        switch($aItem['name']) {
            case $sPrefix . 'images_custom':
                $aField = array_merge($aField, [
                    'info' => _t('_bx_artificer_stg_cpt_option_images_custom_inf'),
                    'code' => 1
                ]);
                break;
        }

        return $aField;
    }
}
/** @} */
