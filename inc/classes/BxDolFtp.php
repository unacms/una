<?php
/**
 * @package     Dolphin Core
 * @copyright   Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * @license     CC-BY - http://creativecommons.org/licenses/by/3.0/
 */
defined('BX_DOL') or die('hack attempt');

bx_import('BxDolFile');

class BxDolFtp extends BxDolFile {
    var $_sHost;
    var $_sLogin;
    var $_sPassword;
    var $_rStream;

    function BxDolFtp($sHost, $sLogin, $sPassword, $sPath = '/') {
        parent::BxDolFile();
        $this->_sHost = $sHost;
        $this->_sLogin = $sLogin;
        $this->_sPassword = $sPassword;
        $this->_sPathTo = $sPath;
    }
    function connect() {
        $this->_rStream = ftp_connect($this->_sHost);
        return ftp_login($this->_rStream, $this->_sLogin, $this->_sPassword);
    }
    protected function _copyFile($sFilePathFrom, $sFilePathTo) {
        if(substr($sFilePathFrom, -1) == '*')
            $sFilePathFrom = substr($sFilePathFrom, 0, -1);

        $bResult = true;
        if(is_file($sFilePathFrom)) {
            if($this->_isFile($sFilePathTo)) {
                $aFileParts = $this->_parseFile($sFilePathTo);
                if(isset($aFileParts[0]))
                    @ftp_mkdir($this->_rStream, $aFileParts[0]);

                $bResult = @ftp_put($this->_rStream, $sFilePathTo, $sFilePathFrom, FTP_BINARY);
            }
            else if($this->_isDirectory($sFilePathTo)) {
                @ftp_mkdir($this->_rStream, $sFilePathTo);

                $aFileParts = $this->_parseFile($sFilePathFrom);
                if(isset($aFileParts[1]))
                    $bResult = @ftp_put($this->_rStream, $this->_validatePath($sFilePathTo) . $aFileParts[1], $sFilePathFrom, FTP_BINARY);
            }
        }
        else if(is_dir($sFilePathFrom) && $this->_isDirectory($sFilePathTo)) {
            @ftp_mkdir($this->_rStream, $sFilePathTo);

            $aInnerFiles = $this->_readDirectory($sFilePathFrom);
            foreach($aInnerFiles as $sFile)
                $bResult = $this->_copyFile($this->_validatePath($sFilePathFrom) . $sFile, $this->_validatePath($sFilePathTo) . $sFile);
        }
        else
            $bResult = false;

        return $bResult;
    }
    protected function _deleteDirectory($sPath) {
        if($this->_isDirectory($sPath)) {
            if(substr($sPath, -1) != '/')
                $sPath .= '/';

            if(($aFiles = @ftp_nlist($this->_rStream, $sPath)) !== false) {
                foreach($aFiles as $sFile)
                    if($sFile != '.' && $sFile != '..')
                        $this->_deleteDirectory($sPath . $sFile);

                if(!@ftp_rmdir($this->_rStream, $sPath))
                    return false;
            }
        }
        else if(!@ftp_delete($this->_rStream, $sPath))
            return false;

        return true;
    }
}