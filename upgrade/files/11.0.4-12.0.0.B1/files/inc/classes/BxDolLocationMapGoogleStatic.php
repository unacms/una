<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

class BxDolLocationMapGoogleStatic extends BxDolLocationMap
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
        $sMapSize = '1000x144';
        if (!empty($aParams['location_map_google_static_size']))
            $sMapSize = $aParams['location_map_google_static_size'];

        $sZoom = (int)getParam('sys_location_map_zoom_default');
        if (!$sZoom)
            $sZoom = '7';
        if (!empty($aParams['location_map_google_static_zoom']))
            $sZoom = $aParams['location_map_google_static_zoom'];

        $sMapType = 'roadmap';    
        if (!empty($aParams['location_map_google_static_maptype']))
            $sMapType = $aParams['location_map_google_static_maptype'];

		$sMapKey = trim(getParam('sys_maps_api_key'));
        $sLocationEncoded = rawurlencode(trim(strip_tags($sLocationHtml)));
        $iScale = isset($_COOKIE['devicePixelRatio']) && (int)$_COOKIE['devicePixelRatio'] >= 2 ? 2 : 1;
        $sLang = bx_lang_name();

        $this->addCssJs();

        return $this->_oTemplate->parseHtmlByName('location_map_google_static.html', array (
            'map_img' => bx_proto() . '://maps.googleapis.com/maps/api/staticmap?center=' . $sLocationEncoded . '&zoom=' . $sZoom . '&size=' . $sMapSize . '&maptype=' . $sMapType . '&markers=size:small%7C' . $sLocationEncoded . '&scale=' . $iScale . '&language=' . $sLang  . ($sMapKey ? '&key=' . $sMapKey : ''),
            'location_string' => $sLocationHtml,
        ));
    }

    public function addCssJs()
    {
        parent::addCssJs();
        $this->_oTemplate->addCss('location_map_google_static.css');
    }
}

/** @} */
