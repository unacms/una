<?php defined('BX_DOL_INSTALL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentInstall Trident Install
 * @{
 */

class BxDolInstallView
{
    protected $_sDirPlugins = BX_INSTALL_DIR_PLUGINS;
    protected $_sDirTemplates = BX_INSTALL_DIR_TEMPLATES;

    protected $_sUrlCss = '../template/css/';
    protected $_sPathCss = '../template/css/';
    protected $_sUrlJs = '../';

    protected $_aToolbarItem = array();

    protected $_aFilesCss = array (
        'common.css',
        'default.less',
        'general.css',
        'icons.css',
        'colors.css',
        'forms.css',
        'menu.css',
        'media-desktop.css',
        'media-tablet.css',
        'media-phone.css',
        'media-print.css',
    );

    protected $_aFilesJs = array (
        'plugins_public/jquery/jquery.min.js',
        'plugins_public/jquery-ui/jquery.ui.position.min.js',
        'inc/js/jquery.dolPopup.js',
        'inc/js/jquery.webForms.js',
    );

    function __construct()
    {
    }

    function out ($sTemplate, $aVars = array())
    {
        extract($aVars);
        include($this->_sDirTemplates . $sTemplate);
    }

    function pageStart ()
    {
        ob_start();
    }

    function pageEnd ($sTitle)
    {
        $aToolbarItem = $this->_aToolbarItem;
        $sInlineCSS = $this->_getInlineCSS();
        $sFilesCSS = $this->_getFilesCSS();
        $sFilesJS = $this->_getFilesJS();
        $sCode = ob_get_clean();
        include($this->_sDirTemplates . '_page.php');
    }

    function setToolbarItem($sIcon, $sLink, $sTitle = '', $sTarget = '')
    {
        $this->_aToolbarItem = array(
            'icon' => $sIcon,
            'link' => $sLink,
            'title' => $sTitle,
            'target' => $sTarget,
        );
    }

    protected function _getFilesCSS()
    {
        $s = '';
        foreach ($this->_aFilesCss as $sFile)
            if (substr($sFile, -4) === '.css')
                $s .= '<link rel="stylesheet" href="' . $this->_sUrlCss . $sFile . '" />';
        return $s;
    }

    protected function _getFilesJS()
    {
        $s = '';
        foreach ($this->_aFilesJs as $sFile)
            $s .= '<script src="' . $this->_sUrlJs . $sFile . '"></script>';
        return $s;
    }

    protected function _getInlineCSS()
    {
        require_once($this->_sDirPlugins . 'lessphp/Less.php');
        $oLessParser = new Less_Parser();

        $oConfigBase = new BxBaseConfig();
        $oLessParser->ModifyVars($oConfigBase->aLessConfig);

        foreach ($this->_aFilesCss as $sFile) {
            if (substr($sFile, -5) !== '.less')
                continue;

            $oLessParser->parseFile($this->_sPathCss . $sFile, $this->_sUrlCss);
        }
        return $oLessParser->getCss();
    }
}

/** @} */
