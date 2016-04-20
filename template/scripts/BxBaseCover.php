<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

/**
 * Cover representation.
 * @see BxDolCover
 */
class BxBaseCover extends BxDolCover
{
    protected $_bJsCssAdded = false;

    protected $_oTemplate;

    public function __construct ($oTemplate)
    {
        parent::__construct ();

        if ($oTemplate)
            $this->_oTemplate = $oTemplate;
        else
            $this->_oTemplate = BxDolTemplate::getInstance();
    }

    /**
     * Display Cover.
     */
    public function display ()
    {
        if (!$this->_aOptions || !$this->_sTemplateName) {

            $this->_addJsCss();

            $oMenuSubmenu = BxDolMenu::getObjectInstance('sys_site_submenu');
            $mixedParams = $oMenuSubmenu->getParamsForCover();

            if (!$mixedParams || !is_array($mixedParams))
                return $this->_oTemplate->parseHtmlByName($this->_sTemplateNameEmpty, array());
            
            return $this->_oTemplate->parseHtmlByName('cover.html', $mixedParams);
        }        

        return $this->_oTemplate->parseHtmlByName($this->_sTemplateName, $this->_aOptions);
    }

    /**
     * Add css/js files which are needed for display and functionality.
     */
    protected function _addJsCss()
    {
        if ($this->_bJsCssAdded)
            return;

        $this->_oTemplate->addCss(array('cover.css'));
        $this->_bJsCssAdded = true;
    }
}

/** @} */
