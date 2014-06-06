<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinUpgrade Dolphin Upgrade Script
 * @{
 */

class BxDolUpgradeController {

    var $oDb;
    var $oUtil;

    function BxDolUpgradeController() {
        $this->oDb = new BxDolUpgradeDb();
        $this->oUtil = new BxDolUpgradeUtil($this->oDb);
    }


    function showAvailableUpgrades () {

        $aTemplateFolders = array ();
        $aFolders = $this->oUtil->readUpgrades();
        foreach ($aFolders as $sFolder) {
            $this->oUtil->setFolder($sFolder);
            $aTemplateFolders[$sFolder] = $this->oUtil->executeCheck ();
        }

        require (BX_UPGRADE_DIR_TEMPLATES . 'show_available_updates.php');
    }

    function runUpgrade ($sFolder) {

        // set current folder
        $this->oUtil->setFolder($sFolder);

        // precheck
        $mixedResult = $this->oUtil->executeCheck ();
        if (true !== $mixedResult) {
            $sTemplateMessage = $mixedResult;
            require (BX_UPGRADE_DIR_TEMPLATES . 'message_error.php');
            return;
        } else {
            $sTemplateMessage = "$sFolder upgrade can be applied";
            require (BX_UPGRADE_DIR_TEMPLATES . 'message_success_step.php');
        }

        // run system SQL upgrade
        $mixedResult = $this->oUtil->isExecuteSQLAvail ();
        if (true === $mixedResult) {

            $mixedResult = $this->oUtil->executeSQL ();
            if (true !== $mixedResult) {
                $sTemplateMessage = $mixedResult;
                require (BX_UPGRADE_DIR_TEMPLATES . 'message_error.php');
                return;
            } else {
                $sTemplateMessage = "System SQL script was successfully executed.";
                require (BX_UPGRADE_DIR_TEMPLATES . 'message_success_step.php');
            }

        } elseif (false === $mixedResult) {
            // just skip if not available found
        } else {
            $sTemplateMessage = $mixedResult;
            require (BX_UPGRADE_DIR_TEMPLATES . 'message_error.php');
            return;
        }

        // get list of available language files updates
        if (false === ($aLangs = $this->oUtil->readLangs ())) {
            $sTemplateMessage = 'Error reading the directory with language updates.';
            require (BX_UPGRADE_DIR_TEMPLATES . 'message_error.php');
            return;
        } else {
            $sTemplateMessage = 'The following languages will be affected for system: <br />';
            if (!$aLangs)
                $sTemplateMessage .= " - No languages will be affected.";
            else
                foreach ($aLangs as $sLang) {
                    $sTemplateMessage .= ' - ' . $sLang . '<br />';
                }
            require (BX_UPGRADE_DIR_TEMPLATES . 'message_success_step.php');
        }

        // run system langs upgrade
        if ($aLangs) {

            $mixedResult = $this->oUtil->executeLangsAdd ();
            if (true !== $mixedResult) {
                $sTemplateMessage = $mixedResult;
                require (BX_UPGRADE_DIR_TEMPLATES . 'message_error.php');
                return;
            } else {
                $sTemplateMessage = "System language strings were successfully added.";
                require (BX_UPGRADE_DIR_TEMPLATES . 'message_success_step.php');
            }

        }

        // run system custom script upgrade
        $mixedResult = $this->oUtil->isExecuteScriptAvail ();
        if (true === $mixedResult) {

            $mixedResult = $this->oUtil->executeScript ();
            if (true !== $mixedResult) {
                $sTemplateMessage = $mixedResult;
                require (BX_UPGRADE_DIR_TEMPLATES . 'message_error.php');
                return;
            } else {
                $sTemplateMessage = "System after update custom script was successfully executed.";
                require (BX_UPGRADE_DIR_TEMPLATES . 'message_success_step.php');
            }

        } elseif (false === $mixedResult) {
            // just skip if not available found
        } else {
            $sTemplateMessage = $mixedResult;
            require (BX_UPGRADE_DIR_TEMPLATES . 'message_error.php');
            return;
        }

        // get list of modules updates
        if (false === ($aModules = $this->oUtil->readModules ())) {
            $sTemplateMessage = 'Error reading modules updates.';
            require (BX_UPGRADE_DIR_TEMPLATES . 'message_error.php');
            return;
        } else {
            $sTemplateMessage = 'The following modules will be updated: <br />';
            if (!$aModules)
                $sTemplateMessage .= " - No modules will be updated.";
            else
                foreach ($aModules as $sModule) {
                    $sTemplateMessage .= ' - ' . $sModule . '<br />';
                }
            require (BX_UPGRADE_DIR_TEMPLATES . 'message_success_step.php');
        }

        foreach ($aModules as $sModule) {

            // run module SQL upgrade
            $mixedResult = $this->oUtil->isExecuteSQLAvail ($sModule);
            if (true === $mixedResult) {

                $mixedResult = $this->oUtil->executeSQL ($sModule);
                if (true !== $mixedResult) {
                    $sTemplateMessage = $mixedResult;
                    require (BX_UPGRADE_DIR_TEMPLATES . 'message_error.php');
                    return;
                } else {
                    $sTemplateMessage = "<b>$sModule</b> module SQL script was successfully executed.";
                    require (BX_UPGRADE_DIR_TEMPLATES . 'message_success_step.php');
                }

            } elseif (false === $mixedResult) {
                // just skip if not available found
            } else {
                $sTemplateMessage = $mixedResult;
                require (BX_UPGRADE_DIR_TEMPLATES . 'message_error.php');
                return;
            }

            // get list of available language files updates
            if (false === ($aLangs = $this->oUtil->readLangs ($sModule))) {
                $sTemplateMessage = 'Error reading the directory with language updates.';
                require (BX_UPGRADE_DIR_TEMPLATES . 'message_error.php');
                return;
            } else {
                $sTemplateMessage = "The following languages will be affected for <b>$sModule</b> module: <br />";
                if (!$aLangs)
                    $sTemplateMessage .= " - No languages will be affected.";
                else
                    foreach ($aLangs as $sLang) {
                        $sTemplateMessage .= ' - ' . $sLang . '<br />';
                    }
                require (BX_UPGRADE_DIR_TEMPLATES . 'message_success_step.php');
            }

            // run module langs upgrade
            if ($aLangs) {

                $mixedResult = $this->oUtil->executeLangsAdd ($sModule);
                if (true !== $mixedResult) {
                    $sTemplateMessage = $mixedResult;
                    require (BX_UPGRADE_DIR_TEMPLATES . 'message_error.php');
                    return;
                } else {
                    $sTemplateMessage = "<b>$sModule</b> module language strings were successfully added.";
                    require (BX_UPGRADE_DIR_TEMPLATES . 'message_success_step.php');
                }

            }

            // run module custom script upgrade
            $mixedResult = $this->oUtil->isExecuteScriptAvail ($sModule);
            if (true === $mixedResult) {

                $mixedResult = $this->oUtil->executeScript ($sModule);
                if (true !== $mixedResult) {
                    $sTemplateMessage = $mixedResult;
                    require (BX_UPGRADE_DIR_TEMPLATES . 'message_error.php');
                    return;
                } else {
                    $sTemplateMessage = "<b>$sModule</b> module after update custom script was successfully executed.";
                    require (BX_UPGRADE_DIR_TEMPLATES . 'message_success_step.php');
                }

            } elseif (false === $mixedResult) {
                // just skip if not available
            } else {
                $sTemplateMessage = $mixedResult;
                require (BX_UPGRADE_DIR_TEMPLATES . 'message_error.php');
                return;
            }

            // run module custom script upgrade
            $sResult = $this->oUtil->executeConclusion ($sModule);
            if ($sResult) {
                $sTemplateMessage = $sResult;
                require (BX_UPGRADE_DIR_TEMPLATES . 'message_success_step.php');
            }

        }

        // run module custom script upgrade
        $sResult = $this->oUtil->executeConclusion ();
        if ($sResult) {
            $sTemplateMessage = $sResult;
            require (BX_UPGRADE_DIR_TEMPLATES . 'message_success_step.php');
        }

    }

}

/** @} */
