<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * Oembed integration.
 * @see BxDolEmbed
 */
class BxBaseEmbedOembed extends BxDolEmbed
{
    var $_iCacheTTL = 3600;

    public function __construct ($aObject, $oTemplate)
    {
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

        return $this->_oTemplate->parseHtmlByName('embed_oembed_link.html', array(
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
            function bx_embed_link(e) {                
                bx_oembed_process_links(e);
            }
        </script>";
    }

    public function addJsCss ()
    {
        if ($this->_bCssJsAdded)
            return '';

        $this->_bCssJsAdded = true;

        $this->_oTemplate->addCss('oembed.css');
        return $this->_oTemplate->parseHtmlByName('embed_oembed_integration.html', []);
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

    public function getUrlData($sLink) {
        $oEmbera = $this->getEmberaInstance(['responsive' => false]);
        return $oEmbera->getUrlData([$sLink]);
    }

    protected function getEmberaInstance($aOptions = []) {
        static $oEmbera;
        if (!$oEmbera) {
            $oHttpCache = new Embera\Http\HttpClientCache(new Embera\Http\HttpClient());
            $oHttpCache->setCachingEngine(new Embera\Cache\Filesystem(BX_DIRECTORY_PATH_TMP, $this->_iCacheTTL));

            $aDefaultOptions = [
                'responsive' => true,
            ];
            $oEmbera = new Embera\Embera(array_merge($aDefaultOptions, $aOptions), null, $oHttpCache);
        }

        return $oEmbera;
    }
}

/** @} */
