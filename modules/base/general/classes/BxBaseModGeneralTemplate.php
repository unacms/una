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

	function entryText ($aData, $sTemplateName = 'entry-text.html')
    {
        $CNF = &$this->getModule()->_oConfig->CNF;

        $aVars = $aData;
        $aVars['entry_title'] = !empty($CNF['FIELD_TITLE']) && isset($aData[$CNF['FIELD_TITLE']]) ? bx_process_output($aData[$CNF['FIELD_TITLE']]) : '';
        $aVars['entry_text'] = !empty($CNF['FIELD_TEXT']) && isset($aData[$CNF['FIELD_TEXT']]) ? bx_process_output($aData[$CNF['FIELD_TEXT']], BX_DATA_HTML) : '';

        if (!empty($CNF['OBJECT_METATAGS'])) {
            $oMetatags = BxDolMetatags::getObjectInstance($CNF['OBJECT_METATAGS']);

            // keywords
            if ($oMetatags->keywordsIsEnabled()) {
                $aFields = array_merge($oMetatags->keywordsFields($aData, $CNF, $CNF['OBJECT_FORM_ENTRY_DISPLAY_VIEW']), array('entry_title', 'entry_text'));
                foreach ($aFields as $sField)
                    $aVars[$sField] = $oMetatags->keywordsParse($aData[$CNF['FIELD_ID']], $aVars[$sField]);
            }

            // location
            $aVars['location'] = $oMetatags->locationsIsEnabled() ? $oMetatags->locationsString($aData[$CNF['FIELD_ID']]) : '';
        }

        if (empty($aVars['entry_text']))
            return false;

        return $this->parseHtmlByName($sTemplateName, $aVars);
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
