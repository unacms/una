<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 * 
 * @defgroup    Sites Sites
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxDolModuleTemplate');

class BxSitesTemplate extends BxDolModuleTemplate
{
    /**
     * Constructor
     */
    function __construct(&$oConfig, &$oDb)
    {
        parent::__construct($oConfig, $oDb);        
        $this->addJs('main.js');
        $this->addCss('main.css');
    }

    function getJs($bWrap = false) {
        $sJsMainClass = $this->_oConfig->getJsClass();
        $sJsMainObject = $this->_oConfig->getJsObject();
        ob_start();
?>
        var <?=$sJsMainObject; ?> = new <?=$sJsMainClass; ?>({
            sActionUrl: '<?=BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri(); ?>',
            sObjName: '<?=$sJsMainObject; ?>',
            sAnimationEffect: '<?=$this->_oConfig->getAnimationEffect(); ?>',
            iAnimationSpeed: '<?=$this->_oConfig->getAnimationSpeed(); ?>'
        });
<?
        $sContent = ob_get_clean();
        return $bWrap ? $this->_wrapInTagJsCode($sContent) : $sContent;
    }
}

/** @} */ 

