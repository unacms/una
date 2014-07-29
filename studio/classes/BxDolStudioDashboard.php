<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinStudio Dolphin Studio
 * @{
 */

bx_import('BxTemplStudioPage');
bx_import('BxDolStudioStoreQuery');

class BxDolStudioDashboard extends BxTemplStudioPage
{
	protected $aItemsCache;

    function __construct()
    {
        parent::__construct('dashboard');

        $this->aItemsCache = array (
		    array('name' => 'all', 'title' => _t('_adm_dbd_txt_c_all')),
		    array('name' => 'db', 'title' => _t('_adm_dbd_txt_c_db')),
		    array('name' => 'template', 'title' => _t('_adm_dbd_txt_c_template')),
		    array('name' => 'css', 'title' => _t('_adm_dbd_txt_c_css')),
		    array('name' => 'js', 'title' => _t('_adm_dbd_txt_c_js')),
		    array('name' => 'users', 'title' => _t('_adm_dbd_txt_c_users')),
		);

        //--- Check actions ---//
        if(($sAction = bx_get('dbd_action')) !== false) {
            $sAction = bx_process_input($sAction);

            $aResult = array('code' => 1, 'message' => _t('_adm_err_cannot_process_action'));
            switch($sAction) {
                case 'check_update_script':
                    $aResult = array();

                    bx_import('BxDolRss');
                    $sContent = BxDolRss::getObjectInstance('sys_boonex')->getFeed('boonex_version');
                    if(empty($sContent))
                        break;

                    bx_import('BxDolXmlParser');
                    $aContent = BxDolXmlParser::getInstance()->getTags($sContent, 'dolphin', 0);
                    if(empty($aContent) || !is_array($aContent) || empty($aContent['value']))
                        break;

                    $sVersionAvl = $aContent['value'];
                    $sVersionCur = bx_get_ver();
                    if(version_compare($sVersionCur, $sVersionAvl) == -1)
                        $aResult = array('version' => $sVersionAvl);
                    break;

				case 'clear_cache':
					$sValue = bx_get('dbd_value');
					if($sValue === false)
						break;

					$sValue = bx_process_input($sValue);

					bx_import('BxDolCacheUtilities');
					$oCacheUtilities = BxDolCacheUtilities::getInstance();

					switch ($sValue) {
				        case 'all':
				            foreach($this->aItemsCache as $aItem) {
				            	if($aItem['name'] == 'all')
				            		continue;

				                $aResult = $oCacheUtilities->clear($aItem['name']);
				                if($aResult['code'] != 0)
				                    break;
				            }
				            break;

				        case 'users':
				        case 'db':
				        case 'template':
				        case 'css':
				        case 'js':
				            $aResult = $oCacheUtilities->clear($sValue);
				            break;

				        default:
				            $aResult = array('code' => 1, 'message' => _t('_Error Occured'));
				    }

				    if($aResult['code'] == 0)
				        $aResult['data'] = $this->getCacheChartData(false);

					break;

				case 'server_audit':
					bx_import('BxDolStudioToolsAudit');
					$oAudit = new BxDolStudioToolsAudit();
					echo $oAudit->generate();
					exit;
            }

            if(!empty($aResult['message'])) {
                bx_import('BxDolStudioTemplate');
                $aResult['message'] = BxDolStudioTemplate::getInstance()->parseHtmlByName('page_action_result.html', array('content' => $aResult['message']));

                bx_import('BxTemplStudioFunctions');
                $aResult['message'] = BxTemplStudioFunctions::getInstance()->transBox('', $aResult['message']);
            }

            echo json_encode($aResult);
            exit;
        }
    }

    protected function getDbSize()
    {
        $iTotalSize = 0;
        $oDb = BxDolDb::getInstance();

        $aTables = $oDb->getAll('SHOW TABLE STATUS');
        foreach($aTables as $aTable)
            $iTotalSize += $aTable['Data_length'] + $aTable['Index_length'];

        return $iTotalSize;
    }

    protected function getFolderSize($sPath)
    {
        $iTotalSize = 0;
        $aFiles = scandir($sPath);

        $sPath = rtrim($sPath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        foreach($aFiles as $sFile) {
            if(is_dir($sPath . $sFile))
                  if($sFile != '.' && $sFile != '..')
                      $iTotalSize += $this->getFolderSize($sPath . $sFile);
            else
                  $iTotalSize += filesize($sPath . $sFile);
        }

        return $iTotalSize;
    }

    protected function getCacheChartData($bAsString = true)
    {
		bx_import('BxDolCacheUtilities');
		$oCacheUtilities = BxDolCacheUtilities::getInstance();

    	$aChartData = array();
    	foreach($this->aItemsCache as $aItem) {
    		if($aItem['name'] == 'all')
	            continue;

	        $iSize = $oCacheUtilities->size($aItem['name']);
	        $aChartData[] = array(bx_js_string($aItem['title'], BX_ESCAPE_STR_APOS), array('v' => $iSize, 'f' => bx_js_string(_t_format_size($iSize))));
    	}

    	return $bAsString ? json_encode($aChartData) : $aChartData;
    }
}

/** @} */
