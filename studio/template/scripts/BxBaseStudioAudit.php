<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaView UNA Studio Representation classes
 * @ingroup     UnaStudio
 * @{
 */

class BxBaseStudioAudit extends BxDolStudioAudit
{
    protected $sSubpageUrl;
    protected $aMenuItems;
    protected $aGridObjects;

    function __construct($sPage = '')
    {
        parent::__construct($sPage);

        $this->sSubpageUrl = BX_DOL_URL_STUDIO . 'audit.php?page=';

		$this->aMenuItems = array(
            BX_DOL_STUDIO_AUD_TYPE_GENERAL => array('icon' => 'search')
	    );

		$this->aGridObjects = array(
        	'audit' => 'sys_studio_audit'
    );
    }
	
    function getPageCss()
    {
        return array_merge(parent::getPageCss(), array('forms.css', 'paginate.css'));
    }
	
    function getPageJs()
    {
        return array_merge(parent::getPageJs(), array('settings.js'));
    }

    function getPageJsCode($aOptions = array(), $bWrap = true)
    {
        $aOptions = array_merge($aOptions, array(
            'sActionUrl' => BX_DOL_URL_STUDIO . 'audit.php'
        ));

        return parent::getPageJsCode($aOptions, $bWrap);
    }
    
    function getPageMenu($aMenu = array(), $aMarkers = array())
    {
        $sJsObject = $this->getPageJsObject();

        $aMenu = array();
        foreach($this->aMenuItems as $sMenuItem => $aItem)
            $aMenu[] = array(
                'name' => $sMenuItem,
                'icon' => $aItem['icon'],
                'link' => $this->sSubpageUrl . $sMenuItem,
                'title' => _t('_adm_lmi_cpt_' . $sMenuItem),
                'selected' => $sMenuItem == $this->sPage
            );

        return parent::getPageMenu($aMenu);
    }

    function getPageCode($bHidden = false)
    {
        $sMethod = 'get' . bx_gen_method_name($this->sPage);
        if(!method_exists($this, $sMethod))
            return '';

        return $this->$sMethod();
    }
    
    protected function getGeneral()
    {
        return $this->getGrid($this->aGridObjects['audit']);
    }

    protected function getGrid($sObjectName)
    {
        $oGrid = BxDolGrid::getObjectInstance($sObjectName);
        if(!$oGrid)
            return '';

        return BxDolStudioTemplate::getInstance()->parseHtmlByName('audit.html', array(
            'content' => $this->getBlockCode(array(
				'items' => $oGrid->getCode()
			)),
            'js_content' => ''
        ));
    }
}

/** @} */
