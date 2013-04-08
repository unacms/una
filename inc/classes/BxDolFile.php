<?php
/**
 * @package     Dolphin Core
 * @copyright   Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * @license     CC-BY - http://creativecommons.org/licenses/by/3.0/
 */
defined('BX_DOL') or die('hack attempt');

class BxDolFile extends BxDol {
    var $_sPathFrom;
    var $_sPathTo;

    function BxDolFile() {
        parent::BxDol();

        $this->_sPathFrom = BX_DIRECTORY_PATH_ROOT;
        $this->_sPathTo = BX_DIRECTORY_PATH_ROOT;
    }

	/**
     * Prevent cloning the instance
     */
    public function __clone() {
        if (isset($GLOBALS['bxDolClasses'][get_class($this)]))
            trigger_error('Clone is not allowed for the class: ' . get_class($this), E_USER_ERROR);
    }

    /**
     * Get singleton instance of the class
     */
    static function getInstance() {
        if(!isset($GLOBALS['bxDolClasses'][__CLASS__]))
            $GLOBALS['bxDolClasses'][__CLASS__] = new BxDolFile();

        return $GLOBALS['bxDolClasses'][__CLASS__];
    }

    function copy($sFilePathFrom, $sFilePathTo) {
        $sFilePathFrom = $this->_sPathFrom . $sFilePathFrom;
        $sFilePathTo = $this->_sPathTo . $sFilePathTo;
        return $this->_copyFile($sFilePathFrom, $sFilePathTo);
    }

    function delete($sPath) {
        $sPath = $this->_sPathTo . $sPath;
        return $this->_deleteDirectory($sPath);
    }

    protected function _copyFile($sFilePathFrom, $sFilePathTo) {
        if(file_exists($sFilePathTo))
            return true;

        if(substr($sFilePathFrom, -1) == '*')
            $sFilePathFrom = substr($sFilePathFrom, 0, -1);

        $bResult = true;
        if(is_file($sFilePathFrom)) {
            if($this->_isFile($sFilePathTo)) {
                $aFileParts = $this->_parseFile($sFilePathTo);
                if(isset($aFileParts[0]))
                    @mkdir($this->_rStream, $aFileParts[0]);

                $bResult = @copy($sFilePathFrom, $sFilePathTo);
            }
            else if($this->_isDirectory($sFilePathTo)) {
                @mkdir($sFilePathTo);

                $aFileParts = $this->_parseFile($sFilePathFrom);
                if(isset($aFileParts[1]))
                    $bResult = @copy($sFilePathFrom, $this->_validatePath($sFilePathTo) . $aFileParts[1]);
            }
        }
        else if(is_dir($sFilePathFrom) && $this->_isDirectory($sFilePathTo)) {
            @mkdir($sFilePathTo);

            $aInnerFiles = $this->_readDirectory($sFilePathFrom);
            foreach($aInnerFiles as $sFile)
                $bResult = $this->_copyFile($this->_validatePath($sFilePathFrom) . $sFile, $this->_validatePath($sFilePathTo) . $sFile);
        }
        else
            $bResult = false;

        return $bResult;
    }

    protected function _readDirectory($sFilePath) {
        if(!is_dir($sFilePath) || !($rSource = opendir($sFilePath)))
            return false;

        $aResult = array();
        while(($sFile = readdir($rSource)) !== false) {
            if($sFile == '.' || $sFile =='..' || $sFile[0] == '.') 
                continue;
            $aResult[] = $sFile;
        }
        closedir($rSource);

        return $aResult;
    }

    protected function _deleteDirectory($sPath) {
        if(!file_exists($sPath))
            return true;

        if($this->_isDirectory($sPath)) {
            if(substr($sPath, -1) != '/')
                $sPath .= '/';

            $aFiles = $this->_readDirectory($sPath);
            if(is_array($aFiles) && !empty($aFiles))
                foreach($aFiles as $sFile)
                    $this->_deleteDirectory($sPath . $sFile);

            if(!@rmdir($sPath))
                return false;
        }
        else if(!@unlink($sPath))
            return false;

        return true;
    }

    protected function _validatePath($sPath) {
        if($sPath && substr($sPath, -1) != '/' && $this->_isDirectory($sPath))
            $sPath .= '/';

        return $sPath;
    }

    protected function _parseFile($sFilePath) {
        $aParts = array();
        preg_match("/^([a-zA-Z0-9@~_\.\\\\\/:-]+[\\\\\/])([a-zA-Z0-9~_-]+\.[a-zA-Z]{2,8})$/", $sFilePath, $aParts) ? true : false;
        return count($aParts) > 1 ? array_slice($aParts, 1) : false;
    }

    protected function _isFile($sFilePath) {
        return preg_match("/^([a-zA-Z0-9@~_\.\\\\\/:-]+)\.([a-zA-Z]){2,8}$/", $sFilePath) ? true : false;
    }

    protected function _isDirectory($sFilePath) {
        return preg_match("/^([a-zA-Z0-9@~_\.\\\\\/:-]+)[\\\\\/]([a-zA-Z0-9~_-]+)[\\\\\/]?$/", $sFilePath) ? true : false;
    }
}

