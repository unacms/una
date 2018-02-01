<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    MapJoined Display last joined users on map
 * @ingroup     UnaModules
 *
 * @{
 */
define('BX_MAP_JOINED_IP_2_LOCATION_URL', 'http://freegeoip.net/json/');

class BxMapJoinedModule extends BxDolModule
{
    function __construct(&$aModule)
    {
        parent::__construct($aModule);
    }
    
    public function addIpInfoToDb($iAccountId, $sIp)
    {
        $sLngLatInfo = $this->callApi($sIp);
        if ($sLngLatInfo != ''){
            $oLngLatInfo = json_decode($sLngLatInfo);
            if (isset($oLngLatInfo->longitude) && isset($oLngLatInfo->latitude)){
                $sLng = $oLngLatInfo->longitude;
                $sLat = $oLngLatInfo->latitude;
                if ($sLng != "0" && $sLat != "0")
                    $this->_oDb->addIpInfo($iAccountId, $sIp, $sLng, $sLat);
            }
        }
    }
    
    /**
     * @page service Service Calls
     * @section bx_mapjoined MapJoined 
     * @subsection bx_mapjoined-other Other
     * @subsubsection bx_mapjoined-get_map
     * 
     * @code bx_srv('bx_mapjoined', 'get_map', [...]); @endcode
     * 
     * Get html for map
     * 
     * @return string - html for map .
     * 
     * @see BxMapJoinedModule::serviceGetMap
     */
    /** 
     * @ref bx_mapjoined-get_map "get_map"
     */
    public function serviceGetMap()
    {
        return $this->_oTemplate->getMap();
    }
    
    public function actionGetMapPoints($Id = 0)
    {
        $aData = $this->_oDb->getLngLatData($Id);
        header('Content-Type: application/json');
        echo json_encode($aData);
    }
    
    private function callApi($sIp)
    {
        $sApiUrl = BX_MAP_JOINED_IP_2_LOCATION_URL . $sIp;
        return bx_file_get_contents($sApiUrl);
    }
}

/** @} */
