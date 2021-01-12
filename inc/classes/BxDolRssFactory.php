<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

class BxDolRssFactory extends BxDol
{
    /**
     * @param array $aRssData, fields:
     *                         UnitID
     *                         OwnerID
     *                         UnitTitle
     *                         UnitLink
     *                         UnitDesc
     *                         UnitDateTimeUTS
     *                         UnitIcon
     */
    function GenRssByData($aRssData, $sUnitTitleC, $sMainLink)
    {
        return $this->GenRssByCustomData($aRssData, $sUnitTitleC, $sMainLink, array(
            'Guid' => 'UnitID',
            'Link' => 'UnitLink',
            'Title' => 'UnitTitle',
            'DateTimeUTS' => 'UnitDateTimeUTS',
            'Desc' => 'UnitDesc',
        ));
    }

    /**
     * Generate rss feed using any custom data fields
     * but you need to describe fields in $aFields array.
     *
     * Required fileds:
     *  Link
     *  Title
     *  DateTimeUTS
     *  Desc
     *
     * Optional fields:
     *  Photo
     */
    function GenRssByCustomData($aRssData, $sUnitTitleC, $sMainLink, $aFields, $sImage = '', $iPID = 0)
    {
        $sRSSLast = '';
        if (isset($aRssData[0]))
            $sRSSLast = gmdate("D, d M Y H:i:s \G\M\T", (int)$aRssData[0][$aFields['DateTimeUTS']]);

        if ($iPID > 0)
            $aPIDOwnerInfo = getProfileInfo($iPID);

        $iUnitLimitChars = 2000;
        $sUnitRSSFeed = '';
        if ($aRssData) {
            $sTxtReadMore = _t('_Read more');

            foreach ($aRssData as $aUnitInfo) {
                $sUnitUrl = $aUnitInfo[$aFields['Link']];
                $sUnitGuid = $aUnitInfo[$aFields['Guid']];

                $sUnitTitle = strip_tags($aUnitInfo[$aFields['Title']]);
                $sUnitDate = gmdate("D, d M Y H:i:s \G\M\T", $aUnitInfo[$aFields['DateTimeUTS']]);

                $sUnitDesc = '';
                if(isset($aFields['Desc']) && !empty($aUnitInfo[$aFields['Desc']])) {
                    $sLinkMore = '';
                    if(strlen($aUnitInfo[$aFields['Desc']]) > $iUnitLimitChars)
                        $sLinkMore = "... <a href=\"" . $sUnitUrl . "\">" . $sTxtReadMore . "</a>";
    
                    $sUnitDesc = "<p>" . mb_substr(strip_tags($aUnitInfo[$aFields['Desc']]), 0, $iUnitLimitChars) . $sLinkMore . "</p>";
                }

                if(isset($aFields['Image']) && !empty($aUnitInfo[$aFields['Image']]))
                    $sUnitDesc .= "<img src=\"" . $aUnitInfo[$aFields['Image']] . "\" />";

                $sUnitRSSFeed .= "<item><title><![CDATA[{$sUnitTitle}]]></title><link><![CDATA[{$sUnitUrl}]]></link><guid><![CDATA[{$sUnitGuid}]]></guid><description><![CDATA[{$sUnitDesc}]]></description><pubDate>{$sUnitDate}</pubDate></item>";
            }
        }

        $sRSSTitle = _t('_RSS_Feed_Title_Common', $sUnitTitleC);
        if ($iPID > 0) {
            $sRSSTitle = _t('_RSS_Feed_Title_Profile', $aPIDOwnerInfo['NickName'], $sUnitTitleC);
        }

        if(substr($sMainLink, 0, 7) != 'http://' && substr($sMainLink, 0, 8) != 'https://')
            $sMainLink = BX_DOL_URL_ROOT . $sMainLink;
        $sMainLink = BxDolPermalinks::getInstance()->permalink($sMainLink);

        $sRSSImage = '';
        if ($sImage) {
            $sRSSImage = "<image><url>{$sImage}</url><title>{$sRSSTitle}</title><link>{$sMainLink}</link></image>";
        }

        return "<?xml version=\"1.0\" encoding=\"UTF-8\"?><rss version=\"2.0\" xmlns:atom=\"http://www.w3.org/2005/Atom\"><channel><title>{$sRSSTitle}</title><link><![CDATA[{$sMainLink}]]></link><atom:link href=\"{$sMainLink}\" rel=\"self\" type=\"application/rss+xml\" /><description>{$sRSSTitle}</description><lastBuildDate>{$sRSSLast}</lastBuildDate>{$sRSSImage}{$sUnitRSSFeed}</channel></rss>";
    }

}

/** @} */
