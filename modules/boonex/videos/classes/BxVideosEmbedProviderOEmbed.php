<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Videos Videos
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * Class for parsing links which support oembed API.
 */
class BxVideosEmbedProviderOEmbed
{
    var $_oModule;
    public function __construct(&$oModule) {
        $this->_oModule = $oModule;
    }

    public function updateEndpoints() {
        $this->_oModule->_oDb->updateOEmbedProviders();
    }

    public function parseLink($sLink) {
        $sEndpointUrl = $this->_oModule->_oDb->getOEmbedEndpoint($sLink);
        if (!$sEndpointUrl) return false;

        $aResponse = @json_decode(bx_file_get_contents(bx_append_url_params($sEndpointUrl, ['url' => $sLink])), true);
        if ($aResponse) {
            if (isset($aResponse['type']) && $aResponse['type'] == 'video' && isset($aResponse['html']) && !empty($aResponse['html']))
                return [
                    'thumb' => isset($aResponse['thumbnail_url']) ? $aResponse['thumbnail_url'] : '',
                    'embed' => $aResponse['html'],
                ];
        }
        return false;
    }
}

/** @} */
