<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

class BxDolLocationMapLeaflet extends BxDolLocationMap
{
    /**
     * Get location map for single address
     * @param $aLocation location array with the following indexes: 
     *          lat, lng, country, state, city, zip, street, street_number
     * @param $sLocationHtml formatted address string
     * @param $aParams some specific params
     */
    public function getMapSingle($aLocation, $sLocationHtml = '', $aParams = array())
    {
        $sZoom = (int)getParam('sys_location_map_zoom_default');
        if (!$sZoom)
            $sZoom = '7';
        if (!empty($aParams['location_leaflet_zoom']))
            $sZoom = $aParams['location_leaflet_zoom'];

        $sProvider = getParam('sys_location_leaflet_provider');
        if (!empty($aParams['location_leaflet_provider']))
            $sProvider = $aParams['location_leaflet_provider'];

        $this->addCssJs();
    
        return $this->_oTemplate->parseHtmlByName('location_map_leaflet.html', array_merge($aLocation, array(
            'id' => time().rand(0, PHP_INT_MAX),
            'zoom' => $sZoom,
            'provider' => $sProvider,
            'location_string' => $sLocationHtml,
        )));
    }

    public function addCssJs()
    {
        parent::addCssJs();
        $this->_oTemplate->addCss(array(
            'location_map_leaflet.css',
            BX_DIRECTORY_PATH_PLUGINS_PUBLIC . 'leaflet/|leaflet.css',
        ));
        $this->_oTemplate->addJs(array(
            'leaflet/leaflet.js',
            'leaflet-providers/leaflet-providers.js',
        ));
    }

    static public function getProviders()
    {
        $a = array(
            'OpenStreetMap.Mapnik',
            'OpenStreetMap.DE',
            'OpenStreetMap.CH',
            'OpenStreetMap.France',
            'OpenStreetMap.HOT',
            'OpenStreetMap.BZH',
            'OpenTopoMap',
            'Stadia.AlidadeSmooth',
            'Stadia.AlidadeSmoothDark',
            'Stadia.OSMBright',
            'Stadia.Outdoors',
            'CyclOSM',
            'Stamen.Toner',
            'Stamen.TonerBackground',
            'Stamen.TonerLite',
            'Stamen.Watercolor',
            'Stamen.Terrain',
            'Stamen.TerrainBackground',
            'Esri.WorldStreetMap',
            'Esri.DeLorme',
            'Esri.WorldTopoMap',
            'Esri.WorldImagery',
            'Esri.WorldTerrain',
            'Esri.WorldShadedRelief',
            'Esri.WorldPhysical',
            'Esri.OceanBasemap',
            'Esri.NatGeoWorldMap',
            'Esri.WorldGrayCanvas',
            'MtbMap',
            'CartoDB.Positron',
            'CartoDB.PositronNoLabels',
            'CartoDB.DarkMatter',
            'CartoDB.DarkMatterNoLabels',
            'CartoDB.Voyager',
            'CartoDB.VoyagerNoLabels',
            'CartoDB.VoyagerLabelsUnder',
            'HikeBike.HikeBike',
            'HikeBike.HillShading',
            'USGS.USTopo',
            'USGS.USImagery',
            'USGS.USImageryTopo',
        );
        return array_combine($a, $a);
    }
}

/** @} */
