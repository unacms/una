<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseTemplate Base classes for template modules
 * @ingroup     UnaModules
 *
 * @{
 */

bx_import ('BxBaseModGeneralModule');

class BxBaseModTemplateModule extends BxBaseModGeneralModule
{
    function __construct(&$aModule)
    {
        parent::__construct($aModule);

        $this->_oConfig->init($this->_oDb);
    }

    public function serviceGetSafeServices()
    {
        return array();
    }

    public function serviceGetOptionsDefaultMix()
    {
        $aResult = array(
            array('key' => '', 'value' => _t('_Select_one'))
        );

        $aMixes = $this->_oDb->getMixes($this->_oConfig->getName());
        foreach($aMixes as $aMix) {
            $aResult[] = array(
                'key' => $aMix['id'],
                'value' => $aMix['title']
            );
        }

        return $aResult;
    }
}

/** @} */
