<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Sites Sites
 * @ingroup     TridentModules
 *
 * @{
 */

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

    function getJs($bWrap = false)
    {
        $sJsMainClass = $this->_oConfig->getJsClass();
        $sJsMainObject = $this->_oConfig->getJsObject();
        ob_start();
?>
        var <?php echo $sJsMainObject; ?> = new <?php echo $sJsMainClass; ?>({
            sActionUrl: '<?php echo BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri(); ?>',
            sObjName: '<?php echo $sJsMainObject; ?>',
            sAnimationEffect: '<?php echo $this->_oConfig->getAnimationEffect(); ?>',
            iAnimationSpeed: '<?php echo $this->_oConfig->getAnimationSpeed(); ?>'
        });
<?php
        $sContent = ob_get_clean();
        return $bWrap ? $this->_wrapInTagJsCode($sContent) : $sContent;
    }
}

/** @} */
