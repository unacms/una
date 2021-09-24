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
 * Class for parsing vimeo links.
 */
class BxVideosEmbedProviderVimeo
{
    public static function parseLink($sLink) {
        $aResponse = @json_decode(file_get_contents('https://vimeo.com/api/oembed.json?url='.urlencode($sLink)), true);
        if ($aResponse) {
            return [
                'id' => $aResponse['video_id'],
                'thumb' => $aResponse['thumbnail_url'],
                'embed' => $aResponse['html'],
            ];
        }
        return false;
    }
}

/** @} */
