<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinUpgrade Dolphin Upgrade Script
 * @{
 */

class BxDolUpgradeController
{
    protected $oDb;
    protected $oUtil;
    protected $aLogMsgs;
    protected $sError;

    public function __construct()
    {
        $this->oDb = new BxDolUpgradeDb();
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

        // set current folder
        $this->oUtil->setFolder($sFolder);

        // precheck
        $mixedResult = $this->oUtil->executeCheck ();
        if (true !== $mixedResult) {
            $this->sError = $mixedResult;
            return false;
        } else {
            $this->aLogMsgs[] = "$sFolder upgrade can be applied";
        }

        $mixedResult = $this->oUtil->checkPermissions ();
        if (true !== $mixedResult) {
            $this->sError = $mixedResult;
            return false;
        }

        // TODO: copy new files

        // TODO: delete deprecated files

        // TODO: update files hash

        // run system SQL upgrade
        $mixedResult = $this->oUtil->isExecuteSQLAvail ();
        if (true === $mixedResult) {

            $mixedResult = $this->oUtil->executeSQL ();
            if (true !== $mixedResult) {
                $sTemplateMessage = $mixedResult;
                $this->sError = $mixedResult;
                return false;
            } else {
                $this->aLogMsgs[] = "System SQL script was successfully executed.";
            }

        } elseif (false === $mixedResult) {
            // just skip if not available found
        } else {
            $this->sError = $mixedResult;
            return false;
        }

        // get list of available language files updates
        if (false === ($aLangs = $this->oUtil->readLangs ())) {
            $this->sError = 'Error reading the directory with language updates.';
            return false;
        } else {
            $sTemplateMessage = 'The following languages will be affected for system: <br />';
            if (!$aLangs) {
                $sTemplateMessage .= " - No languages will be affected.";
            } else {
                foreach ($aLangs as $sLang) {
                    $sTemplateMessage .= ' - ' . $sLang . '<br />';
                }
            }
            $this->aLogMsgs[] = $sTemplateMessage;
        }

        // run system langs upgrade
        if ($aLangs) {

            $mixedResult = $this->oUtil->executeLangsAdd ();
            if (true !== $mixedResult) {
                $this->sError = $mixedResult;
                return false;
            } else {
                $this->aLogMsgs[] = "System language strings were successfully added.";
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
                $this->aLogMsgs[] = "System after update custom script was successfully executed.";
            }

        } elseif (false === $mixedResult) {
            // just skip if not available found
        } else {
            $this->sError = $mixedResult;
            return false;
        }

        // get list of modules updates
        if (false === ($aModules = $this->oUtil->readModules ())) {
            // skip modules update
            return true;
        } else {
            $sTemplateMessage = 'The following modules will be updated: <br />';
            if (!$aModules) {
                $sTemplateMessage .= " - No modules will be updated.";
            } else {
                foreach ($aModules as $sModule) {
                    $sTemplateMessage .= ' - ' . $sModule . '<br />';
                }
            }
            $this->aLogMsgs[] = $sTemplateMessage;
        }

        foreach ($aModules as $sModule) {

            // run module SQL upgrade
            $mixedResult = $this->oUtil->isExecuteSQLAvail ($sModule);
            if (true === $mixedResult) {

                $mixedResult = $this->oUtil->executeSQL ($sModule);
                if (true !== $mixedResult) {
                    $this->sError = $mixedResult;
                    return false;
                } else {
                    $this->aLogMsgs[] = "<b>$sModule</b> module SQL script was successfully executed.";
                }

            } elseif (false === $mixedResult) {
                // just skip if not available found
            } else {
                $this->sError = $mixedResult;
                return false;
            }

            // get list of available language files updates
            if (false === ($aLangs = $this->oUtil->readLangs ($sModule))) {
                $this->sError = 'Error reading the directory with language updates.';
                return false;
            } else {
                $sTemplateMessage = "The following languages will be affected for <b>$sModule</b> module: <br />";
                if (!$aLangs) {
                    $sTemplateMessage .= " - No languages will be affected.";
                } else {
                    foreach ($aLangs as $sLang) {
                        $sTemplateMessage .= ' - ' . $sLang . '<br />';
                    }
                }
                $this->aLogMsgs[] = $sTemplateMessage;
            }

            // run module langs upgrade
            if ($aLangs) {

                $mixedResult = $this->oUtil->executeLangsAdd ($sModule);
                if (true !== $mixedResult) {
                    $this->sError = $mixedResult;
                    return false;
                } else {
                    $this->aLogMsgs[] = "<b>$sModule</b> module language strings were successfully added.";
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
                    $this->aLogMsgs[] = "<b>$sModule</b> module after update custom script was successfully executed.";
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
            $this->aLogMsgs[] = $sResult;

        return true;
    }
}

/** @} */
