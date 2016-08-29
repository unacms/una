<?php
class BxPfwLog
{
	protected $sLogDateFormat;
	protected $sPath;

	protected $bLogInfo;
	protected $sFileInfo;

	protected $bLogError;
	protected $sFileError;

	protected function BxPfwLog()
	{
		$oMain = BxDolModule::getInstance('BxPfwModule');

		$this->sLogDateFormat = 'm.d.y H:i:s';
		$this->sPath = $oMain->_oConfig->getLogPath();

		$this->bLogInfo = $oMain->_oConfig->isLog('info');
		$this->sFileInfo = $this->bLogInfo ? $oMain->_oConfig->getLogFile('info') : '';

		$this->bLogError = $oMain->_oConfig->isLog('error');
		$this->sFileError = $this->bLogError ? $oMain->_oConfig->getLogFile('error') : '';
	}

	public static function getInstance()
    {
        if(!isset($GLOBALS['bxDolClasses']['BxPfwLog']))
            $GLOBALS['bxDolClasses']['BxPfwLog'] = new BxPfwLog();

        return $GLOBALS['bxDolClasses']['BxPfwLog'];
    }

	public function logInfo() {
		if(!$this->bLogInfo)
			return;

		$sFile = $this->sFileInfo;
		$sMessage = "--- Info: {date}";

		$this->_log($sFile, $sMessage);

		$aArgs = func_get_args();
		foreach($aArgs as $mixedArg)
			$this->_log($sFile, $mixedArg);

		$this->_log($sFile, $sMessage . "\n");
	}

	public function logError() {
		if(!$this->bLogError)
			return;

		$sFile = $this->sFileError;
		$sMessage = "--- Error Occured: {date}";

		$this->_log($sFile, $sMessage);

		$aArgs = func_get_args();
		foreach($aArgs as $mixedArg)
			$this->_log($sFile, $mixedArg);

		$this->_log($sFile, $sMessage . "\n");
	}

	protected function _log($sFile, $mixedValue)
	{
	    $rHandle = fopen($this->sPath . $sFile, 'a');
	    if(!$rHandle)
	    	return;

        if(is_array($mixedValue) || is_object($mixedValue)) {
            ob_start();
            print_r($mixedValue);
            $sValue = ob_get_contents();
            ob_end_clean();
            fwrite($rHandle, "$sValue\n");
        }
        else {
        	$mixedValue = str_replace('{date}', date($this->sLogDateFormat), $mixedValue);

            fwrite($rHandle, "$mixedValue\n");
        }

		fclose($rHandle);
	}
}