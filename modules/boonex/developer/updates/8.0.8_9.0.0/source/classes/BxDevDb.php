<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Developer Developer
 * @ingroup     TridentModules
 *
 * @{
 */

class BxDevDb extends BxDolModuleDb
{
    function __construct(&$oConfig)
    {
        parent::__construct($oConfig);
    }

    function getQueryInsert($sTable, $aItems, $mixedComment = false, $aExclude = array('id'))
    {
        $bFirst = true;
        $sContent = $sComment = "";
        foreach($aItems as $aItem) {
            foreach($aExclude as $sKey)
                if(isset($aItem[$sKey]))
                    unset($aItem[$sKey]);

            $aKeys = array_keys($aItem);
            $aValues = array_values($aItem);
            $iValues = count($aValues);

            foreach ($aValues as $iKey => $sValue)
                $aValues[$iKey] = BxDevFunctions::dbAddSlashes($sValue, true);

            if($bFirst) {
                $sContent .= "INSERT INTO `" . $sTable . "`(`" . implode("`, `", $aKeys) . "`) VALUES \n";
                $bFirst = false;
            }

            $sSql = "(" . implode(", ", array_fill(0, $iValues, "?")) . "),\n";
            $sSql = call_user_func_array(array($this, 'prepareAsString'), array_merge(array($sSql), $aValues));

            $sContent .= $sSql;
        }
        $sContent = substr($sContent, 0, -2) . ";\n\n";

        if($mixedComment === true || (is_string($mixedComment) && $mixedComment != ''))
            $sComment = "--\n-- " . ($mixedComment === true ? "Dumping data for table `" . $sTable . "`" : $mixedComment) . "\n--\n";

        return $sComment . $sContent;
    }

    function getQueryDelete($sTable, $sKey, $aItems, $mixedComment = false)
    {
        $sContent = $sComment = "";
        foreach($aItems as $aItem)
            if(isset($aItem[$sKey])) {
                $sSql = "DELETE FROM `" . $sTable . "` WHERE `" . $sKey . "`=?;\n";
                $sContent .= call_user_func_array(array($this, 'prepareAsString'), array($sSql, $aItem[$sKey]));
            }
        $sContent .= "\n";

        if($mixedComment === true || (is_string($mixedComment) && $mixedComment != ''))
            $sComment = "--\n-- " . ($mixedComment === true ? "Delete data from table `" . $sTable . "`" : $mixedComment) . "\n--\n";

        return $sComment . $sContent;
    }
}

/** @} */
