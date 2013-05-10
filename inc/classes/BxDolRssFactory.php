<?php
/**
 * @package     Dolphin Core
 * @copyright   Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * @license     CC-BY - http://creativecommons.org/licenses/by/3.0/
 */
defined('BX_DOL') or die('hack attempt');

class BxDolRssFactory extends BxDol {

    /**
     * default constructor of factory pattern
     */
    function BxDolRssFactory() {
        parent::BxDol();
    }

    /**
     * @param array $aRssData, fields:
     *    UnitID
     *    OwnerID
     *    UnitTitle
     *    UnitLink
     *    UnitDesc
     *    UnitDateTimeUTS
     *    UnitIcon
     */
    function GenRssByData($aRssData, $sUnitTitleC, $sMainLink) {

        return $this->GenRssByCustomData($aRssData, $sUnitTitleC, $sMainLink, array(
            'Link' => 'UnitLink',
            'Guid' => 'UnitID',
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
    function GenRssByCustomData($aRssData, $sUnitTitleC, $sMainLink, $aFields, $sImage = '', $iPID = 0) {
        $sUnits = '';
        $sRSSLast = '';
        if (isset($aRssData[0]))
            $sRSSLast = getLocaleDate($aRssData[0][$aFields['DateTimeUTS']], 6);

        if ($iPID > 0)
            $aPIDOwnerInfo = getProfileInfo($iPID);

        $iUnitLimitChars = 2000;//(int)getParam('max_blog_preview');
        $sUnitRSSFeed = '';
        if ($aRssData) {
            foreach ($aRssData as $iUnitID => $aUnitInfo) {
                $sUnitUrl = $aUnitInfo[$aFields['Link']];
                $sUnitGuid = $aUnitInfo[$aFields['Guid']];

                $sUnitTitle = strmaxwordlen(strip_tags($aUnitInfo[$aFields['Title']]), 100);
                $sUnitDate = getLocaleDate($aUnitInfo[$aFields['DateTimeUTS']], 6);

                $sLinkMore = '';
                if ( strlen( $aUnitInfo[$aFields['Desc']]) > $iUnitLimitChars ) {
                    $sLinkMore = "... <a href=\"".$sUnitUrl."\">"._t('_Read more')."</a>";
                }
                $sUnitDescVal = mb_substr(strip_tags($aUnitInfo[$aFields['Desc']]), 0, $iUnitLimitChars) . $sLinkMore;
                $sUnitDesc = $sUnitDescVal;

                $sUnitRSSFeed .= "<item><title><![CDATA[{$sUnitTitle}]]></title><link><![CDATA[{$sUnitUrl}]]></link><guid><![CDATA[{$sUnitGuid}]]></guid><description><![CDATA[{$sUnitDesc}]]></description><pubDate>{$sUnitDate}</pubDate></item>";
            }
        }

        $sRSSTitle = _t('_RSS_Feed_Title_Common', $sUnitTitleC);
        if ($iPID > 0) {
            $sRSSTitle = _t('_RSS_Feed_Title_Profile', $aPIDOwnerInfo['NickName'], $sUnitTitleC);
        }

        if(substr($sMainLink, 0, 7) != 'http://' && substr($sMainLink, 0, 8) != 'https://')
            $sMainLink = BX_DOL_URL_ROOT . $sMainLink;

        $sRSSImage = '';
        if ($sImage) {
            $sRSSImage = "<image><url>{$sImage}</url><title>{$sRSSTitle}</title><link>{$sMainLink}</link></image>";
        }

        return "<?xml version=\"1.0\" encoding=\"UTF-8\"?><rss version=\"2.0\"><channel><title>{$sRSSTitle}</title><link>{$sMainLink}</link><description>{$sRSSTitle}</description><lastBuildDate>{$sRSSLast}</lastBuildDate>{$sRSSImage}{$sUnitRSSFeed}</channel></rss>";
    }

}

