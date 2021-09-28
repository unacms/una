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
    var $_sOEmbedUrl;
    var $_sQuickCheck;

    public function __construct($aObject) {
        $this->_sOEmbedUrl = $aObject['oembed_url'];
        $this->_sQuickCheck = $aObject['quick_check'];
    }

    public function parseLink($sLink) {
        if ($this->_sQuickCheck && stripos($sLink, $this->_sQuickCheck) === false) return false;

        $aResponse = @json_decode(file_get_contents(bx_append_url_params($this->_sOEmbedUrl, ['url' => $sLink])), true);
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
