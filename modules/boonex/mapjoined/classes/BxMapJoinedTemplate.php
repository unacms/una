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

bx_import ('BxDolModuleTemplate');

class BxMapJoinedTemplate extends BxBaseModGeneralTemplate
{
    function __construct(&$oConfig, &$oDb)
    {
        parent::__construct($oConfig, $oDb);
    }
    
    function getMap()
    {
        $this->addJs(array('https://openlayers.org/en/v4.6.4/build/ol.js', 'map.js'));
        $this->addCss('https://openlayers.org/en/v4.6.4/css/ol.css');
        return $this->getJsCode('map') . $this->parseHtmlByName('map.html', array());
    }
    
    public function getJsCode($sType, $aParams = array(), $bWrap = true)
    {
        $aParams = array_merge(array(
            'sPathToJsonData' => $this->_oConfig->getHomeUrl() . 'js/continents.geojson',
            'sPathToDotImage' => $this->getImageUrl('dot.png'),
            'iIntervalCheckNewInSeconds' => getParam('bx_mapjoined_interval_refresh_new_users_in_seconds'),
            'fCenterMapLonCoordinate' => getParam('bx_mapjoined_default_center_lat_coordinate'),
            'fCenterMapLatCoordinate' => getParam('bx_mapjoined_default_center_lon_coordinate'),
            'fMapZoom' => getParam('bx_mapjoined_default_zoom')
        ), $aParams);
        
        return parent::getJsCode($sType, $aParams, $bWrap);
    }
}

/** @} */
