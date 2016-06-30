<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentUpgrade Trident Upgrade Script
 * @{
 */

class BxDolUpgradeController
{
    protected $oDb;
    protected $oUtil;
    protected $aLogMsgs;
    protected $sError;
    protected $sConclusion;

    public function __construct()
    {
        $this->oDb = BxDolUpgradeDb::getInstance();
        $this->oUtil = new BxDolUpgradeUtil($this->oDb);
    }

    public function getErrorMsg()
    {
        return $this->sError;
    }

    public function getLogMessages()
    {
        return $this->aLogMsgs;
    }

    public function getConclusion()
    {
        return $this->sConclusion;
    }

    public function writeLog()
    {
        if (empty($this->aLogMsgs))
            return true;

        $s = "\n\n\n--------- " . date('c') . "\n";
        $s .= implode("\n", $this->aLogMsgs);

        if ($this->sConclusion)
            $s .= "\nCONCLUSION:" . $this->sConclusion;

        if ($this->sError)
            $s .= "\nERROR:" . $this->sError;

        return file_put_contents(BX_DIRECTORY_PATH_ROOT . 'logs/upgrade.log', $s, FILE_APPEND);
    }

    public function setMaintenanceMode($bSetMaintenanceMode)
    {
        if ($bSetMaintenanceMode) {

            if (false !== file_put_contents(BX_DIRECTORY_PATH_ROOT . BX_MAINTENANCE_FILE, time()))
                return true;

            $this->sError = 'Enabling site maintetance mode failed';
            return false;
            
        } else {

            if (unlink(BX_DIRECTORY_PATH_ROOT . BX_MAINTENANCE_FILE))
                return true;

            $this->sError = 'Disabling site maintetance mode failed';
            return false;
        }
    }

    public function getAllUpgrades ()
    {
        $aTemplateFolders = array ();
        $aFolders = $this->oUtil->readUpgrades();
        foreach ($aFolders as $sFolder) {
            $this->oUtil->setFolder($sFolder);
            $aTemplateFolders[$sFolder] = $this->oUtil->executeCheck();
        }
        return $aTemplateFolders;
    }

    public function getAvailableUpgrade ()
    {
        $aTemplateFolders = array ();
        $aFolders = $this->oUtil->readUpgrades();
        foreach ($aFolders as $sFolder) {
            $this->oUtil->setFolder($sFolder);
            if (true === $this->oUtil->executeCheck())
                return $sFolder;
        }
        return false;
    }

    public function runUpgrade ($sFolder)
    {
        $this->aLogMsgs = array();
        $this->sError = false;

        if (bx_get_ver() != BX_DOL_VERSION) {
            $this->sError = 'Database and files versions are different';
            return false;
        }

        // set current folder
        $this->oUtil->setFolder($sFolder);

        // run oprations with files
        $aFilesOperations = array (
            'executeCheck' => "$sFolder upgrade will be applied",
            'checkPermissions' => "Files permissions are ok and can be overwritten",
            'copyFiles' => "Files copying successfully completed",
            'filesDelete' => "Deprecated files were successfully deleted or there is no files to delete",
            'updateFilesHash' => "System files hash was successfully updated",
        );
    
        foreach ($aFilesOperations as $sFunc => $sSuccessMsg) {
            $mixedResult = $this->oUtil->$sFunc ();
            if (true !== $mixedResult) {
                $this->sError = $mixedResult;
                return false;
            } else {
                $this->aLogMsgs[] = $sSuccessMsg;
            }
        }

        // run system SQL upgrade
        $mixedResult = $this->oUtil->isExecuteSQLAvail ();
        if (true === $mixedResult) {

            $mixedResult = $this->oUtil->executeSQL ();
            if (true !== $mixedResult) {
                $sTemplateMessage = $mixedResult;
                $this->sError = $mixedResult;
                return false;
            } else {
                $this->aLogMsgs[] = "System SQL script was successfully executed";
            }

        } elseif (false === $mixedResult) {
            // just skip if not available found
        } else {
            $this->sError = $mixedResult;
            return false;
        }

        // get list of available language files updates
        if (false === ($aLangs = $this->oUtil->readLangs ())) {
            $this->sError = 'Error reading the directory with language updates';
            return false;
        } else {
            $sTemplateMessage = "The following languages will be affected for system:\n";
            if (!$aLangs) {
                $sTemplateMessage .= " - No languages will be affected\n";
            } else {
                foreach ($aLangs as $sLang) {
                    $sTemplateMessage .= ' - ' . $sLang . "\n";
                }
            }
            $this->aLogMsgs[] = trim($sTemplateMessage);
        }

        // run system langs upgrade
        if ($aLangs) {

            $mixedResult = $this->oUtil->executeLangsAdd ();
            if (true !== $mixedResult) {
                $this->sError = $mixedResult;
                return false;
            } else {
                $this->aLogMsgs[] = "System language strings were successfully added";
            }

        }

        // run system custom script upgrade
        $mixedResult = $this->oUtil->isExecuteScriptAvail ();
        if (true === $mixedResult) {

            $mixedResult = $this->oUtil->executeScript ();
            if (true !== $mixedResult) {
                $this->sError = $mixedResult;
                return false;
            } else {
                $this->aLogMsgs[] = "System after update custom script was successfully executed";
            }

        } elseif (false === $mixedResult) {
            // just skip if not available found
        } else {
            $this->sError = $mixedResult;
            return false;
        }

        // get list of modules updates
        if (false !== ($aModules = $this->oUtil->readModules ())) {
            $sTemplateMessage = "The following modules will be updated:\n";
            if (!$aModules) {
                $sTemplateMessage .= " - No modules will be updated\n";
            } else {
                foreach ($aModules as $sModule) {
                    $sTemplateMessage .= ' - ' . $sModule . "\n";
                }
            }
            $this->aLogMsgs[] = trim($sTemplateMessage);
        }

        if (!$aModules)
            $aModules = array();

        foreach ($aModules as $sModule) {

            // run module SQL upgrade
            $mixedResult = $this->oUtil->isExecuteSQLAvail ($sModule);
            if (true === $mixedResult) {

                $mixedResult = $this->oUtil->executeSQL ($sModule);
                if (true !== $mixedResult) {
                    $this->sError = $mixedResult;
                    return false;
                } else {
                    $this->aLogMsgs[] = "'$sModule' module SQL script was successfully executed";
                }

            } elseif (false === $mixedResult) {
                // just skip if not available found
            } else {
                $this->sError = $mixedResult;
                return false;
            }

            // get list of available language files updates
            if (false === ($aLangs = $this->oUtil->readLangs ($sModule))) {
                $this->sError = 'Error reading the directory with language updates';
                return false;
            } else {
                $sTemplateMessage = "The following languages will be affected for '$sModule' module:\n";
                if (!$aLangs) {
                    $sTemplateMessage .= " - No languages will be affected\n";
                } else {
                    foreach ($aLangs as $sLang) {
                        $sTemplateMessage .= ' - ' . $sLang . "\n";
                    }
                }
                $this->aLogMsgs[] = trim($sTemplateMessage);
            }

            // run module langs upgrade
            if ($aLangs) {

                $mixedResult = $this->oUtil->executeLangsAdd ($sModule);
                if (true !== $mixedResult) {
                    $this->sError = $mixedResult;
                    return false;
                } else {
                    $this->aLogMsgs[] = "'$sModule' module language strings were successfully added";
                }

            }

            // run module custom script upgrade
            $mixedResult = $this->oUtil->isExecuteScriptAvail ($sModule);
            if (true === $mixedResult) {

                $mixedResult = $this->oUtil->executeScript ($sModule);
                if (true !== $mixedResult) {
                    $this->sError = $mixedResult;
                    return false;
                } else {
                    $this->aLogMsgs[] = "'$sModule' module after update custom script was successfully executed";
                }

            } elseif (false === $mixedResult) {
                // just skip if not available
            } else {
                $this->sError = $mixedResult;
                return false;
            }

            // run module custom script upgrade
            $sResult = $this->oUtil->executeConclusion ($sModule);
            if ($sResult)
                $this->aLogMsgs[] = $sResult;
        }

        // run module custom script upgrade
        $sResult = $this->oUtil->executeConclusion ();
        if ($sResult)
            $this->sConclusion = $sResult;

        return true;
    }
}

/** @} */
