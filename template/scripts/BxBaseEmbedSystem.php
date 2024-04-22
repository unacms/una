<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * Iframely integration.
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
        $aAttrs = array(
            'title' => bx_html_attribute($sTitle),
        );

        // check for external link
        if (strncmp(BX_DOL_URL_ROOT, $sLink, strlen(BX_DOL_URL_ROOT)) !== 0) {
            $aAttrs['target'] = '_blank';

            if (getParam('sys_add_nofollow') == 'on')
                $aAttrs['rel'] = 'nofollow';
        }

        return $this->_oTemplate->parseHtmlByName('embed_iframely_link.html', array(
            'link' => $sLink,
            'title' => $sTitle,
            'attrs' => bx_convert_array2attrs($aAttrs),
            'width' => $sMaxWidth,
        ));
    }

    public function addProcessLinkMethod ()
    {
        return "
        <script>
            function bx_embed_link(e)
            {
                window.iframely && iframely.load(e);
            }
        </script>";
    }

  
    
    public function getDataFromApi ($sUrl, $sTheme)
    {
        $a  = bx_get_site_info($sUrl, array(
            'thumbnailUrl' => array('tag' => 'link', 'content_attr' => 'href'),
            'OGImage' => array('name_attr' => 'property', 'name' => 'og:image'),
            'icon' => array('tag' => 'link', 'name_attr' => 'rel', 'name' => 'shortcut icon', 'content_attr' => 'href'),
            'icon2' => array('tag' => 'link', 'name_attr' => 'rel', 'name' => 'icon', 'content_attr' => 'href'),
            'icon3' => array('tag' => 'link', 'name_attr' => 'rel', 'name' => 'apple-touch-icon', 'content_attr' => 'href'),
        ));

        $a['image'] = $a['OGImage'] ? $a['OGImage'] : $a['thumbnailUrl'];
        $a['logo'] = $a['icon2'] ? $a['icon2'] : ($a['icon3'] ? $a['icon3'] : $a['icon']);
        $a['url'] = $sUrl;


        unset($a['OGImage']);
        unset($a['thumbnailUrl']);
        unset($a['icon2']);
        unset($a['icon3']);
        unset($a['icon']);

        if ($a['image']){
            $oStorage = BxDolStorage::getObjectInstance('sys_images');

            $iMediaId = $oStorage->storeFileFromUrl($a['image'], false);
            if ($iMediaId){
               $a['image'] =  $oStorage->getFileUrlById($iMediaId);
            }
        }
        
        if ($a['image'] == ''){
            $b = json_decode(bx_file_get_contents("https://api.microlink.io/?url=" . $sUrl), true);
            $a = [
                'title' => $b['data']['title'],
                'description' => $b['data']['description'],
                'image' => $b['data']['image']['url'],
                'logo' => $b['data']['logo']['url'],
                'url' => $sUrl,
            ];
        }
        
        $aU = parse_url($sUrl);
        $a['domain'] = $aU['host'];
        return json_encode($a);
    }
    
    public function getDataHtml ($sUrl, $sTheme)
    {
        $aData = $this->getData($sUrl, $sTheme);
        return $aData;
    }
    
    public function getHtml ($sUrl, $sTheme)
    {
        $aData = $this->getData($sUrl, $sTheme);
        
        return '<a href="'.$aData['url'].'" target="_blank" style="text-decoration: none; color: inherit;">
    <div style="display: flex; flex-direction: row; column-gap:  height: 128px; border: 1px solid rgba(107, 114, 128, 0.2); border-radius:16px; align-items: stretch;">
        <div style="flex: 0 0 128px; height: 128px; border-radius:16px 0 0 16px; background: url('. ($aData['image'] ? $aData['image'] : $aData['logo']) .') center center / cover no-repeat;">
        </div>
        <div style="flex-grow: 1; display: flex; flex-direction: column; justify-content: space-between; overflow: hidden;margin: 0 1rem">
            <div style="padding-top: 0.5rem; display: -webkit-box; -webkit-box-orient: vertical; -webkit-line-clamp: 1;  overflow: hidden; text-overflow: ellipsis"><b> '.$aData['title'].'</b></div>
            <div style="display: -webkit-box; -webkit-box-orient: vertical; -webkit-line-clamp: 2;  overflow: hidden; text-overflow: ellipsis;">
                 '.$aData['description'].'
            </div>
             <div style="display: flex; flex-direction: row; column-gap: 8px; align-items: center; padding-bottom: 0.5rem;">
               <img src="'.$aData['logo'].'" width=24 height=24  >'.$aData['domain'].'
            </div>
        </div>
    </div>
</a>';
        
    }
    
    public function parseLinks(&$aLinks) {
        $aResult = [];
        if ($aLinks && is_array($aLinks)) {
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
