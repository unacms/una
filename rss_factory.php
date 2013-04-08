<?php
/**
 * @package     Dolphin Core
 * @copyright   Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * @license     CC-BY - http://creativecommons.org/licenses/by/3.0/
 */

require_once('./inc/header.inc.php');
require_once(BX_DIRECTORY_PATH_INC . "languages.inc.php");
require_once(BX_DIRECTORY_PATH_INC . "params.inc.php");

function actionRSS() {

    $sType = isset($_REQUEST['action']) ? bx_process_input($_REQUEST['action']) : '';
    $iLength = isset($_REQUEST['length']) ? bx_process_input($_REQUEST['length'], BX_DATA_INT) : 0;
    $oDb = BxDolDb::getInstance();

    if(strncmp($sType, 'sys_', 4) == 0) {
        $aRssTitle = '';
        $aRssData = array();

        switch($sType) {
            case 'sys_stats':
                $aRssTitle = getParam('site_title');

                $oCache = $oDb->getDbCacheObject();
                $aStats = $oCache->getData($oDb->genDbCacheKey('sys_stat_site'));
                if (null === $aStats) {
                    genSiteStatCache();
                    $aStats = $oCache->getData($oDb->genDbCacheKey('sys_stat_site'));
                }

                if ($aStats && is_array($aStats)) {
                    foreach ($aStats as $sKey => $aStat) {
                        $iNum = $aStat['query'] ? $oDb->getOne($aStat['query']) : 0;

                        $aRssData[] = array(
                           'UnitID' => $sKey,
                           'OwnerID' => '',
                           'UnitTitle' => $iNum . ' ' . _t('_' . $aStat['capt']),
                           'UnitLink' => $aStat['link'] ? BX_DOL_URL_ROOT . $aStat['link'] : '',
                           'UnitDesc' => '',
                           'UnitDateTimeUTS' => 0,
                           'UnitIcon' => ''
                        );
                    }
                }
                break;

            case 'sys_members':
                bx_import('BxTemplFunctions');
                $oFunctions = BxTemplFunctions::getInstance();

                $aRssTitle = getParam('site_title');
                $iLength = $iLength != 0 ? $iLength : 33;
                $aMembers = $oDb->getAll("SELECT *, UNIX_TIMESTAMP(`DateReg`) AS `DateRegUTS` FROM `Profiles` WHERE 1 AND (`Couple`='0' OR `Couple`>`ID`) AND `Status`='Active' ORDER BY `DateReg` DESC LIMIT " . (int)$iLength);
                foreach($aMembers as $aMember) {
                    $aRssData[] = array(
                       'UnitID' => '',
                       'OwnerID' => '',
                       'UnitTitle' => $aMember['NickName'],
                       'UnitLink' => getProfileLink($aMember['ID']),
                       'UnitDesc' => $oFunctions->getMemberAvatar($aMember['ID']),
                       'UnitDateTimeUTS' => $aMember['DateRegUTS'],
                       'UnitIcon' => ''
                    );
                }
                break;

            case 'sys_news':
                echo BxDolService::call('news', 'news_rss', array($iLength));
                return;
        }

        bx_import('BxDolRssFactory');
        $oRss = new BxDolRssFactory();
        echo $oRss->GenRssByData($aRssData, $aRssTitle, '');

    } else {
        BxDolService::call($sType, $sType . '_rss', array());
    }
}

actionRSS();

