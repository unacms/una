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

    public function parseLink($sLink) {
        bx_import('BxDolEmbed');
        $oEmbed = BxDolEmbed::getObjectInstance('sys_oembed');
        $aResponse = $oEmbed->getUrlData($sLink);

        if ($aResponse) {
            $aResponse = $aResponse[$sLink];
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
