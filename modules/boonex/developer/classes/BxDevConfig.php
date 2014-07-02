<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Developer Developer
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxDolModuleConfig');

class BxDevConfig extends BxDolModuleConfig
{
    protected $aJsClasses;
    protected $aJsObjects;
    protected $sAnimationEffect;
    protected $iAnimationSpeed;

    function __construct($aModule)
    {
        parent::__construct($aModule);

        $this->aJsClasses = array('polyglot' => 'BxDevPolyglot');
        $this->aJsObjects = array('polyglot' => 'oBxDevPolyglot');
        $this->sAnimationEffect = 'fade';
        $this->iAnimationSpeed = 'slow';
    }

    function getJsClass($sType = 'main')
    {
        if(empty($sType))
            return $this->aJsClasses;

        return $this->aJsClasses[$sType];
    }

    function getJsObject($sType = 'main')
    {
        if(empty($sType))
            return $this->aJsObjects;

        return $this->aJsObjects[$sType];
    }

    function getAnimationEffect()
    {
        return $this->sAnimationEffect;
    }

    function getAnimationSpeed()
    {
        return $this->iAnimationSpeed;
    }
}

/** @} */
