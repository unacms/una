<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * System (default) integration.
 * 
 * @see BxDolEmbed
 */
class BxBaseEmbedSystem extends BxDolEmbed
{
    public function __construct ($aObject, $oTemplate)
    {
        $this->_sTableName = 'sys_embeded_data';
        parent::__construct ($aObject);

        if ($oTemplate)
            $this->_oTemplate = $oTemplate;
        else
            $this->_oTemplate = BxDolTemplate::getInstance();
    }

    public function getLinkHTML ($sLink, $sTitle = '', $sMaxWidth = '')
    {
        $aData = $this->getData($sLink, '');

        $aAttrs = [
            'title' => bx_html_attribute($sTitle),
        ];

        // check for external link
        if (strncmp(BX_DOL_URL_ROOT, $sLink, strlen(BX_DOL_URL_ROOT)) !== 0) {
            $aAttrs['target'] = '_blank';

            if (getParam('sys_add_nofollow') == 'on')
                $aAttrs['rel'] = 'nofollow';
        }

        return $this->_oTemplate->parseHtmlByName('embed_system_link.html', [
            'link' => $aData['url'],
            'attrs' => bx_convert_array2attrs($aAttrs),
            'width' => $sMaxWidth,
            'image' => $aData['image'] ? $aData['image'] : $aData['logo'],
            'logo' => $aData['logo'],
            'title' => $aData['title'],
            'description' => $aData['description'],
            'domain' => $aData['domain'],
        ]);
    }

    public function getDataFromApi ($sUrl, $sTheme)
    {
        $a  = bx_get_site_info($sUrl, [
            'thumbnailUrl' => ['tag' => 'link', 'content_attr' => 'href'],
            'OGImage' => ['name_attr' => 'property', 'name' => 'og:image'],
            'icon' => ['tag' => 'link', 'name_attr' => 'rel', 'name' => 'shortcut icon', 'content_attr' => 'href'],
            'icon2' => ['tag' => 'link', 'name_attr' => 'rel', 'name' => 'icon', 'content_attr' => 'href'],
            'icon3' => ['tag' => 'link', 'name_attr' => 'rel', 'name' => 'apple-touch-icon', 'content_attr' => 'href'],
        ]);

        $a = array_merge($a, [
           'image' => $a['OGImage'] ? $a['OGImage'] : $a['thumbnailUrl'],
           'logo' => $a['icon2'] ? $a['icon2'] : ($a['icon3'] ? $a['icon3'] : $a['icon']),
           'url' => $sUrl
        ]);

        unset($a['OGImage'], $a['thumbnailUrl'], $a['icon'], $a['icon2'], $a['icon3']);

        if($a['image'] == '') {
            $b = json_decode(bx_file_get_contents("https://api.microlink.io/?url=" . $sUrl), true);
            $a = [
                'title' => $b['data']['title'],
                'description' => $b['data']['description'],
                'image' => $b['data']['image']['url'],
                'logo' => $b['data']['logo']['url'],
                'url' => $sUrl,
            ];
        }
        
        if($a['image'] && ($oStorage = BxDolStorage::getObjectInstance('sys_images')) !== false) {
            $iMediaId = $oStorage->storeFileFromUrl($a['image'], false);
            if($iMediaId)
                $a['image'] =  $oStorage->getFileUrlById($iMediaId);
        }
        
        if($a['logo'] && ($oStorage = BxDolStorage::getObjectInstance('sys_images')) !== false) {
            $iMediaId = $oStorage->storeFileFromUrl($a['logo'], false);
            if($iMediaId)
                $a['logo'] =  $oStorage->getFileUrlById($iMediaId);
        }

        $aUrl = parse_url($sUrl);
        $a['domain'] = $aUrl['host'];

        return json_encode($a);
    }

    public function parseLinks(&$aLinks)
    {
        $aResult = [];

        if($aLinks && is_array($aLinks)) {
            $oEmbera = $this->getEmberaInstance();

            foreach ($aLinks as $sLink) {
                $sHtml = $oEmbera->autoEmbed($sLink);
                //if nothing to change/embed then return empty string to not replace anything on a frontend
                $aResult[] = ['html' => $sHtml != $sLink ? $sHtml : ''];
            }
        }

        return $aResult;
    }
}

/** @} */
