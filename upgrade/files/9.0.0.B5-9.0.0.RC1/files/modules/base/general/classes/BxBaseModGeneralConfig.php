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
    protected $_aGridObjects;

    function __construct($aModule)
    {
        parent::__construct($aModule);

        $this->CNF = array();

        $this->_aObjects = array();
        $this->_aPrefixes = array();
        $this->_aJsClasses = array();
        $this->_aJsObjects = array();
        $this->_aGridObjects = array();
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
}

/** @} */
