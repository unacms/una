<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseGeneral Base classes for modules
 * @ingroup     UnaModules
 *
 * @{
 */

class BxBaseModGeneralConfig extends BxDolModuleConfig
{
    public $CNF;

    protected $_aObjects;
    protected $_aPrefixes;
    protected $_aJsClasses;
    protected $_aJsObjects;
    protected $_aHtmlIds;
    protected $_aGridObjects;

    protected $_bIsApi;

    /**
     * Delayed Publishing Notification Time (in seconds)
     * If video transcoding takes more than specified amount of time
     * then author will be notified about publishing (failure).
     */
    protected $_iDpnTime;

    function __construct($aModule)
    {
        parent::__construct($aModule);

        $this->CNF = array();

        $this->_aObjects = array();
        $this->_aPrefixes = array();
        $this->_aJsClasses = array();
        $this->_aJsObjects = array();
        $this->_aGridObjects = array();

        $this->_bIsApi = bx_is_api();

        $this->_iDpnTime = 3600;
    }

    public function getCNF()
    {
        /**
         * @hooks
         * @hookdef hook-bx_base_general-get_cnf '{module_name}', 'get_cnf' - hook to override module's configuration array
         * - $unit_name - module name
         * - $action - equals `get_cnf`
         * - $object_id - not used
         * - $sender_id - not used
         * - $extra_params - array of additional params with the following array keys:
         *      - `override_result` - [array] by ref, module's configuration array, can be overridden in hook processing
         * @hook @ref hook-bx_base_general-get_cnf
         */
        bx_alert($this->getName(), 'get_cnf', 0, 0, ['override_result' => &$this->CNF]);
        return $this->CNF;
    }

    public function getObject($sType = '')
    {
    	if(empty($sType))
            return $this->_aObjects;

        return isset($this->_aObjects[$sType]) ? $this->_aObjects[$sType] : '';
    }

    public function getPrefix($sType = '')
    {
    	if(empty($sType))
            return $this->_aPrefixes;

        return isset($this->_aPrefixes[$sType]) ? $this->_aPrefixes[$sType] : '';
    }

    public function getJsClass($sType)
    {
        return isset($this->_aJsClasses[$sType]) ? $this->_aJsClasses[$sType] : '';
    }

    public function getJsObject($sType)
    {
        return isset($this->_aJsObjects[$sType]) ? $this->_aJsObjects[$sType] : '';
    }

    public function getGridObject($sType)
    {
        return isset($this->_aGridObjects[$sType]) ? $this->_aGridObjects[$sType] : '';
    }

    /**
     * Is Auto Approve mode is available.
     */
    public function isAutoApprove()
    {
        return !empty($this->CNF['FIELD_STATUS_ADMIN']) && !empty($this->CNF['PARAM_AUTO_APPROVE']);
    }

    public function isAutoApproveEnabled()
    {
        return !$this->isAutoApprove() || getParam($this->CNF['PARAM_AUTO_APPROVE']) == 'on';
    }

    public function isEqualUrls($sUrl1, $sUrl2)
    {
        $sUrl1 = trim($sUrl1, "/");
        $sUrl2 = trim($sUrl2, "/");

        return strncmp($sUrl1, $sUrl2, strlen($sUrl1)) === 0;
    }

    public function getViewEntryUrl($mixedData)
    {
        if(!isset($this->CNF['URI_VIEW_ENTRY']))
            return '';

        $iId = 0;
        if(is_numeric($mixedData))
            $iId = (int)$mixedData;
        else if(is_array($mixedData) && isset($this->CNF['FIELD_ID'], $mixedData[$this->CNF['FIELD_ID']]))
            $iId = (int)$mixedData[$this->CNF['FIELD_ID']];
        else
            return '';

        return bx_absolute_url(BxDolPermalinks::getInstance()->permalink('page.php?i=' . $this->CNF['URI_VIEW_ENTRY'] . '&id=' . $iId));
    }

    /*
     * Note. The first Transcoder in the array $aTranscoders has the highest priority. 
     */
    public function getImageUrl($iId, $aTranscoders)
    {
        $sResult = '';
        if(empty($iId) || empty($aTranscoders) || !is_array($aTranscoders))
            return $sResult;

        foreach($aTranscoders as $sTranscoder) {
            if(empty($this->CNF[$sTranscoder])) 
                continue;

            $oTranscoder = BxDolTranscoderImage::getObjectInstance($this->CNF[$sTranscoder]);
        	if(!$oTranscoder)
        	    continue;

            $sResult = $oTranscoder->getFileUrl($iId);
            if(!empty($sResult))
                break;
        }

        return $sResult;
    }

    public function getDpnTime()
    {
        return $this->_iDpnTime;
    }
}

/** @} */
