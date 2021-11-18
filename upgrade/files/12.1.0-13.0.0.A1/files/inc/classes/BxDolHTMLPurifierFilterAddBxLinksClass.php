<?php

class BxDolHTMLPurifierFilterAddBxLinksClass extends HTMLPurifier_Filter
{
    public $name = 'AddBxLinksClass';
    protected $class = BX_DOL_LINK_CLASS;

    public function preFilter($sHtml, $config, $context)
    {
        if (false === strstr($sHtml, '<a '))
            return $sHtml;

        $sId = 'bx-links-' . md5(microtime());
        $dom = new DOMDocument();
        @$dom->loadHTML('<?xml encoding="UTF-8"><div id="' . $sId . '">' . $sHtml . '</div>');
        $xpath = new DOMXpath($dom);

        $oLinks = $xpath->evaluate('//a');
        for ($i = 0; $i < $oLinks->length; $i++) {
            $oLink = $oLinks->item($i);

            $sClasses = $oLink->getAttribute('class');
            if (!$sClasses || false === strpos($sClasses, $this->class))
                $sClasses = ($sClasses ? $sClasses . ' ' : '') . $this->class;

            $oLink->removeAttribute('class');
            $oLink->setAttribute("class", $sClasses);
        }

        if (false === ($s = $dom->saveXML($dom->getElementById($sId)))) // in case of error return original string
            return $sHtml;

        return mb_substr($s, 52, -6); // strip added tags
    }
}

