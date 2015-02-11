<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Samples
 * @{
 */

/**
 * @page samples
 * @section connections Connections
 */

/**
 * This sample uses profile friends as sample connections.
 *
 * @section usage $_GET params:
 *
 * Display connections:
 * - id: profile ID to display connectiond for
 * - method = array: display connections using array method, just plain list of connections IDs
 * - method = sql: display connections using SQL method, custom SQL query where connection data is inserted as part of SQL query
 * - method = search-results: display connections using SearchResults class, SearchResults class must support connections
 *
 * Generate connections:
 * - action = gen: to generate sample connections for all profiles, by default around ~2 friends for each profile, and around ~1 friend request
 */

require_once('./../inc/header.inc.php');
require_once(BX_DIRECTORY_PATH_INC . "design.inc.php");


$oTemplate = BxDolTemplate::getInstance();
$oTemplate->setPageNameIndex (BX_PAGE_DEFAULT);
$oTemplate->setPageHeader ("Connections");
$oTemplate->setPageContent ('page_main_code', PageCompMainCode());
$oTemplate->getPageCode();

/**
 * page code function
 */
function PageCompMainCode()
{
    $oDb = BxDolDb::getInstance();

    // config: for data generation
    $iMutual = 2; // around 2 mutual connections for each profile
    $iOneWay = 1; // around 1 one-way connections for each profile
    $sTable = "`sys_profiles_conn_connections`"; // to clean table before inserting genrated data

    // config: for displaying and data generation
    $sType = 'bx_persons'; // profiles type
    $sObject = 'sys_profiles_friends'; // connections object

    if ('gen' == bx_get('action') || (isset($_SERVER['argv'][1]) && 'gen' == $_SERVER['argv'][1])) {
        echo "\nConnections Generation:  <br />\n";
        GenerateData($iMutual, $iOneWay, $sTable, $sType, $sObject);
        echo "\nData has been generated";
        exit;
    }

    ob_start();

    $sMethod = bx_get('method');

    $iProfileId = bx_get('id');
    if (!$iProfileId) {
        $sQueryOrig = "SELECT `id` FROM `sys_profiles` WHERE `type` = ? AND `status` = 'active' ORDER BY RAND() LIMIT 1";
        $sQueryPrepared = $oDb->prepare($sQueryOrig, $sType);
        $iProfileId = $oDb->getOne($sQueryPrepared);
    }

    $iProfileId2 = bx_get('id2');
    if (!$iProfileId2) {
        $sQueryOrig = "SELECT `id` FROM `sys_profiles` WHERE `type` = ? AND `status` = 'active' AND `id` != ? ORDER BY RAND() LIMIT 1";
        $sQueryPrepared = $oDb->prepare($sQueryOrig, $sType, $iProfileId);
        $iProfileId2 = $oDb->getOne($sQueryPrepared);
    }

    $oConnection = BxDolConnection::getObjectInstance($sObject);
    if (!$oConnection)
        die ("'$sObject' object is not defined.");

    echo "<h1>Profile: $iProfileId / another one: $iProfileId2 </h1>";
    echo '<hr class="bx-def-hr" />';

    switch($sMethod) {

        default:
        case 'array':

            echo "<h2>Common Content (like mutual Friends between two initiators)</h2>";
            echoDbg($oConnection->getCommonContent($iProfileId, $iProfileId2, 1));

            echo "<h2>Mutual Content (like Friends)</h2>";
            echoDbg($oConnection->getConnectedContent($iProfileId, 1));

            echo "<h2>Connected Content</h2>";
            echoDbg($oConnection->getConnectedContent($iProfileId));

            echo "<h2>Connected Initiators</h2>";
            echoDbg($oConnection->getConnectedInitiators($iProfileId));

            echo "<h2>Connected Content without mutual content (like Friend Requests sent)</h2>";
            echoDbg($oConnection->getConnectedContent($iProfileId, 0));

            echo "<h2>Connected Initiators without mutual content (like Friend Requests received)</h2>";
            echoDbg($oConnection->getConnectedInitiators($iProfileId, 0));

        break;
        case 'sql':

            $f = function ($aSQLParts) use ($oDb, $sType) {
                $sQueryOrig = "
                    SELECT `p`.`id`, `d`.`fullname`
                    FROM `bx_persons_data` AS `d`
                    INNER JOIN `sys_profiles` AS `p` ON (`p`.`content_id` = `d`.`id` AND `p`.`type` = ?)
                    {$aSQLParts['join']}
                ";
                $sQueryPrepared = $oDb->prepare($sQueryOrig, $sType);
                $a = $oDb->getAll($sQueryPrepared);
                foreach ($a as $r)
                    echo "{$r['id']} - {$r['fullname']} <br />\n";
            };

            echo "<h2>Common Content (like mutual Friends between two initiators)</h2>";
            $f($oConnection->getCommonContentAsSQLParts('p', 'id', $iProfileId, $iProfileId2, 1));

            echo "<h2>Mutual Content (like Friends)</h2>";
            $f($oConnection->getConnectedContentAsSQLParts('p', 'id', $iProfileId, 1));

            echo "<h2>Connected Content</h2>";
            $f($oConnection->getConnectedContentAsSQLParts('p', 'id', $iProfileId));

            echo "<h2>Connected Initiators</h2>";
            $f($oConnection->getConnectedInitiatorsAsSQLParts('p', 'id', $iProfileId));

            echo "<h2>Connected Content without mutual content (like Friend Requests sent)</h2>";
            $f($oConnection->getConnectedContentAsSQLParts('p', 'id', $iProfileId, 0));

            echo "<h2>Connected Initiators without mutual content (like Friend Requests received)</h2>";
            $f($oConnection->getConnectedInitiatorsAsSQLParts('p', 'id', $iProfileId, 0));

        break;
        case 'search-results':

            echo "<h2>Common Content (like mutual Friends between two initiators)</h2>";
            echo BxDolService::call('bx_persons', 'browse_connections', array($iProfileId, 'sys_profiles_friends', 'common', 1, BX_DB_CONTENT_ONLY, $iProfileId2));

            echo "<h2>Mutual Content (like Friends)</h2>";
            echo BxDolService::call('bx_persons', 'browse_connections', array($iProfileId, 'sys_profiles_friends', 'content', 1, BX_DB_CONTENT_ONLY));

            echo "<h2>Connected Content</h2>";
            echo BxDolService::call('bx_persons', 'browse_connections', array($iProfileId, 'sys_profiles_friends', 'content', false, BX_DB_CONTENT_ONLY));

            echo "<h2>Connected Initiators</h2>";
            echo BxDolService::call('bx_persons', 'browse_connections', array($iProfileId, 'sys_profiles_friends', 'initiators', false, BX_DB_CONTENT_ONLY));

            echo "<h2>Connected Content without mutual content (like Friend Requests sent)</h2>";
            echo BxDolService::call('bx_persons', 'browse_connections', array($iProfileId, 'sys_profiles_friends', 'content', 0, BX_DB_CONTENT_ONLY));

            echo "<h2>Connected Initiators without mutual content (like Friend Requests received)</h2>";
            echo BxDolService::call('bx_persons', 'browse_connections', array($iProfileId, 'sys_profiles_friends', 'initiators', 0, BX_DB_CONTENT_ONLY));

        break;
    }

    $s = ob_get_clean();
    return DesignBoxContent("Connections", $s, BX_DB_PADDING_DEF);
}

function GenerateData($iMutual = 3, $iOneWay = 1, $sTable, $sType, $sObject)
{
    $oDb = BxDolDb::getInstance();

    $oConnection = BxDolConnection::getObjectInstance($sObject);
    if (!$oConnection)
        die ("'$sObject' object is not defined.");

    $oDb->query("TRUNCATE TABLE $sTable");

    // get all profiles
    $sQueryOrig = "SELECT * FROM `sys_profiles` WHERE `type` = ? AND `status` = 'active'";
    $sQueryPrepared = $oDb->prepare($sQueryOrig, $sType);
    if (!($aAll = $oDb->getAll($sQueryPrepared)))
        die($oDb->getErrorMessage());

    foreach ($aAll as $aProfile) {

        // get random profiles to add as connections
        $sQueryOrig = "SELECT * FROM `sys_profiles` WHERE `type` = ? AND `status` = 'active' AND `id` != ? ORDER BY RAND() LIMIT ?";
        $sQueryPrepared = $oDb->prepare($sQueryOrig, $sType, $aProfile['id'], $iMutual + $iOneWay);
        if (!($a = $oDb->getAll($sQueryPrepared)))
            die($oDb->getErrorMessage());

        $i = 0;
        foreach ($a as $r) {

            echo "{$aProfile['id']} " . ($i < $iMutual ? '&lt;=&gt;' : '=&gt;') . " {$r['id']}  <br />\n";

            if ($i < $iMutual) {
                // mutual
                if (!$oConnection->isConnected($aProfile['id'], $r['id'], true)) {
                    $oConnection->addConnection($aProfile['id'], $r['id']);
                    $oConnection->addConnection($r['id'], $aProfile['id']);
                }
            } elseif ($i >= $iMutual) {
                // one-way
                if (!$oConnection->isConnected($aProfile['id'], $r['id']))
                    $oConnection->addConnection($aProfile['id'], $r['id']);
            }

            $i++;
        }
    }

}

/** @} */
