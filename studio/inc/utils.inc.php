<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentStudio Trident Studio
 * @{
 */

function bx_array_insert_before($aInsert, $aSource, $sKey)
{
    return bx_array_insert($aInsert, $aSource, $sKey, 0);
}

function bx_array_insert_after($aInsert, $aSource, $sKey)
{
    return bx_array_insert($aInsert, $aSource, $sKey, 1);
}

function bx_array_insert($aInsert, $aSource, $sKey, $iDirection = 1)
{
    $iPosition = array_search($sKey, array_keys($aSource)) + $iDirection;

    if($iPosition == 0)
        return $aInsert + $aSource;

    if($iPosition == count($aSource))
        return $aSource + $aInsert;

    return array_slice($aSource, 0, $iPosition, true) + $aInsert + array_slice($aSource, $iPosition, NULL, true);
}

/** @} */
