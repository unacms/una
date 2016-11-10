<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * Privacy representation.
 * @see BxDolPrivacy
 */
class BxBasePrivacy extends BxDolPrivacy
{
    protected $_oTemplate;

    public function __construct ($aOptions, $oTemplate)
    {
        parent::__construct ($aOptions);

        if ($oTemplate)
            $this->_oTemplate = $oTemplate;
        else
            $this->_oTemplate = BxDolTemplate::getInstance();
    }

    protected function _addJsCss()
    {
        /*
        $this->_oTemplate->addJs('BxDolGrid.js');
        $this->_oTemplate->addCss('grid.css');
        */
    }
}

/** @} */
