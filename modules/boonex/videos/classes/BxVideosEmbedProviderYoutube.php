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
 * Class for parsing youtube links.
 */
class BxVideosEmbedProviderYoutube
{
    public static function parseLink($sLink) {
        if (preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*&)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $sLink, $matches)) {
            return [
                'id' => $matches[1],
                'thumb' => 'https://img.youtube.com/vi/'.$matches[1].'/hqdefault.jpg',
                'embed' => '<iframe src="https://www.youtube.com/embed/'.$matches[1].'" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>',
            ];
        }
        return false;
    }
}

/** @} */
