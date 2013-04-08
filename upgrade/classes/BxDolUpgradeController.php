<?

/***************************************************************************
*                            Dolphin Smart Community Builder
*                              -------------------
*     begin                : Mon Mar 23 2006
*     copyright            : (C) 2007 BoonEx Group
*     website              : http://www.boonex.com
* This file is part of Dolphin - Smart Community Builder
*
* Dolphin is free software; you can redistribute it and/or modify it under
* the terms of the GNU General Public License as published by the
* Free Software Foundation; either version 2 of the
* License, or  any later version.
*
* Dolphin is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
* without even the implied warranty of  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
* See the GNU General Public License for more details.
* You should have received a copy of the GNU General Public License along with Dolphin,
* see license.txt file; if not, write to marketing@boonex.com
***************************************************************************/


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

?>
