<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaStudio UNA Studio
 * @{
 */

class BxDolStudioDashboard extends BxTemplStudioWidget
{
    protected $aBlocks;
    protected $aItemsCache;
    protected $aItemsHTools;

    function __construct()
    {
        parent::__construct('dashboard');

        $this->oDb = new BxDolStudioDashboardQuery();

        $this->aBlocks = array(
        	'space' => 'serviceGetBlockSpace',
        	'htools' => 'serviceGetBlockHostTools',
        );

        $this->aItemsCache = array (
            array('name' => 'all'),
            array('name' => 'db'),
            array('name' => 'template'),
            array('name' => 'less'),
            array('name' => 'css'),
            array('name' => 'js'),
            array('name' => 'purifier'),
            array('name' => 'opcache'),
            array('name' => 'custom')
        );

        $this->aItemsHTools = array (
            'PHP' => 'requirementsPHP',
            'MySQL' => 'requirementsMySQL',
            'Web Server' => 'requirementsWebServer',
        );

        //--- Check actions ---//
        if(($sAction = bx_get('dbd_action')) !== false) {
            $sAction = bx_process_input($sAction);

            $aResult = array('code' => 1, 'message' => _t('_adm_err_cannot_process_action'));
            switch($sAction) {
            	case 'get_block':
                    $sValue = bx_get('dbd_value');
                    if($sValue === false)
                        break;

                    $sValue = bx_process_input($sValue);
                    if(!isset($this->aBlocks[$sValue]))
                        break;

                    $aBlock = $this->{$this->aBlocks[$sValue]}(false);
                    if(!empty($aBlock['content']))
                        $aResult = array('code' => 0, 'data' => $aBlock['content']);

                    break;

                case 'check_for_upgrade':
                    $aResult = array('code' => 0, 'data' => $this->getPageCodeVersionAvailable());
                    break;

            	case 'perform_upgrade':
                    $oUpgrader = bx_instance('BxDolUpgrader');
                    if(!$oUpgrader->prepare(false)){
                        $aResult = array('code' => 1, 'message' => $oUpgrader->getError());
					}
                    else{
						BxDolModuleQuery::getInstance()->updateModule(array('updated' => time()), array('name' => 'system'));
                        $aResult = array('code' => 0, 'message' => _t('_adm_dbd_msg_upgrade_started', BX_DOL_URL_STUDIO));
					}
                    break;

                case 'clear_cache':
                    $sValue = bx_get('dbd_value');
                    if($sValue === false)
                        break;

                    $sValue = bx_process_input($sValue);

                    $oCacheUtilities = BxDolCacheUtilities::getInstance();

                    switch ($sValue) {
                        case 'all':
                            $aResult = false;
                            foreach($this->aItemsCache as $aItem) {
                                if($aItem['name'] == 'all')
                                    continue;

                                $aResultClear = $oCacheUtilities->clear($aItem['name']);
                                if($aResultClear === false)
                                    continue;

                                $aResult = $aResultClear;
                                if(isset($aResult['code']) && $aResult['code'] != 0)
                                    break;
                            }
                            break;

                        case 'db':
                        case 'template':
                        case 'less':
                        case 'css':
                        case 'js':
                        case 'purifier':
                        case 'opcache':
                        case 'custom':
                            $aResult = $oCacheUtilities->clear($sValue);
                            break;

                        default:
                            $aResult = array('code' => 1, 'message' => _t('_error occured'));
                    }

                    if($aResult === false)
                        $aResult['data'] = MsgBox(_t('_adm_dbd_msg_c_all_disabled'));
                    else if(isset($aResult['code']) && $aResult['code'] == 0) {
                        bx_alert('system', 'clear_cache', 0, 0, array('type' => $sValue));

                        $aResult['data'] = $this->getCacheChartData(false);
                    }
                    break;

                case 'permissions':                    
                    $oAdmTools = new BxDolStudioTools();

                    header( 'Content-type: text/html; charset=utf-8' );
                    echo $oAdmTools->generateStyles();
                    $oAdmTools->checkPermissions();
                    exit;

                case 'server_audit':
                    $oAudit = new BxDolStudioToolsAudit();

                    header( 'Content-type: text/html; charset=utf-8' );
                    echo $oAudit->generate();
                    exit;
            }

            if(!empty($aResult['message'])) {
                $aResult['message'] = BxDolStudioTemplate::getInstance()->parseHtmlByName('page_action_result.html', array('content' => $aResult['message']));
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
        $oCacheUtilities = BxDolCacheUtilities::getInstance();

    	$aChartData = array();
    	foreach($this->aItemsCache as $aItem) {
            if($aItem['name'] == 'all')
                continue;

            $iSize = $oCacheUtilities->size($aItem['name']);
            if($iSize === false)
                continue;

            $aChartData[] = array(bx_js_string(_t('_adm_dbd_txt_c_' . $aItem['name']), BX_ESCAPE_STR_APOS), array('v' => $iSize, 'f' => bx_js_string(_t_format_size($iSize))));
    	}

    	if(empty($aChartData))
    		return false;

    	return $bAsString ? json_encode($aChartData) : $aChartData;
    }
}

/** @} */
