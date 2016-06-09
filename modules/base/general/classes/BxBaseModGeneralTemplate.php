<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    BaseGeneral Base classes for modules
 * @ingroup     TridentModules
 *
 * @{
 */

/**
 * Module representation.
 */
class BxBaseModGeneralTemplate extends BxDolModuleTemplate
{
    protected $MODULE;
    protected $_oModule;

    function __construct(&$oConfig, &$oDb)
    {
        parent::__construct($oConfig, $oDb);        
        $this->addCss ('main.css');
    }
    
    public function getModule()
    {
        if (!$this->_oModule) {
            $sName = $this->_oConfig->getName();
            $this->_oModule = BxDolModule::getInstance($sName);
        }
        return $this->_oModule;
    }

	public function getJsCode($sType, $aParams = array(), $bWrap = true)
    {
        $sBaseUri = $this->_oConfig->getBaseUri();
        $sJsClass = $this->_oConfig->getJsClass($sType);
        $sJsObject = $this->_oConfig->getJsObject($sType);

        $aParams = array_merge(array(
            'sActionUri' => $sBaseUri,
            'sActionUrl' => BX_DOL_URL_ROOT . $sBaseUri,
            'sObjName' => $sJsObject,
        	'aHtmlIds' => array(),
            'oRequestParams' => array()
        ), $aParams);
        $sContent = "var " . $sJsObject . " = new " . $sJsClass . "(" . json_encode($aParams) . ");";

        return !$bWrap ? $sContent : $this->_wrapInTagJsCode($sContent);
    }

    function entryLocation ($iContentId)
    {
        $CNF = &$this->getModule()->_oConfig->CNF;

        if (empty($CNF['OBJECT_METATAGS']))
            return '';

        $oMetatags = BxDolMetatags::getObjectInstance($CNF['OBJECT_METATAGS']);        

        if (!($sLocationString = $oMetatags->locationsString($iContentId)))
            return '';

        $aVars = array (
            'location' => $sLocationString
        );
        return $this->parseHtmlByName('entry-location.html', $aVars);
    }

	public function entryInfo($aData, $aValues = array())
    {
    	$CNF = $this->_oConfig->CNF;
        $aValuesDefault = array();

        if (isset($aData[$CNF['FIELD_ADDED']]))
            $aValuesDefault[] = array(
                'title' => _t('_sys_txt_field_created'),
                'value' => bx_time_js($aData[$CNF['FIELD_ADDED']]),
            );

        if (isset($aData[$CNF['FIELD_CHANGED']]))
            $aValuesDefault[] = array(
                'title' => _t('_sys_txt_field_updated'),
                'value' => bx_time_js($aData[$CNF['FIELD_CHANGED']]),
            );

        $aValues = array_merge($aValuesDefault, $aValues);

    	return $this->parseHtmlByName('entry-info.html', array(
    		'bx_repeat:info' => $aValues,
    	));
    }
}

/** @} */
