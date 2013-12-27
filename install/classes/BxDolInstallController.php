<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinInstall Dolphin Install
 * @{
 */

class BxDolInstallController 
{

    protected $_oView;
    
    function __construct() 
    {
        $this->_oView = new BxDolInstallView();
    }

    function run ($sAction = '') 
    {
        $sMethod = 'action' . ucfirst($sAction);
        if ($sAction && method_exists($this, $sMethod))
            $this->$sMethod ();
        else
            $this->actionInitial ();
    }
   
    function actionAudit () 
    {
        $this->_oView->pageStart();
        
        $oAudit = new BxDolStudioToolsAudit();
        $sAuditOutput = $oAudit->generate();

        $this->_oView->out('audit.php', compact('sAuditOutput'));

        $this->_oView->pageEnd('Dolphin 8.0.0 Installation');
    }

    function actionInitial () 
    {
        $this->_oView->pageStart();

        $aLangs = BxDolInstallLang::getInstance()->getAvailableLanguages();

        $oAudit = new BxDolStudioToolsAudit();
        $aErrors = $oAudit->checkRequirements(BX_DOL_AUDIT_FAIL);
        $aWarnings = $oAudit->checkRequirements(BX_DOL_AUDIT_WARN);

        $oAudit->generateStyles();

        if (empty($aErrors))
            $this->_oView->out('initial.php', compact('aLangs', 'aWarnings'));
        else
            $this->_oView->out('initial_fail.php', compact('aLangs', 'aErrors'));

        $this->_oView->pageEnd('Dolphin 8.0.0 Installation');
    }
}

/** @} */
