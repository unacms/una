<?php

class BxDolHTMLPurifierFilterEmbed extends HTMLPurifier_Filter
{

    /**
     * @type string
     */
    public $name = 'Embed';

    /**
     * @param string $html
     * @param HTMLPurifier_Config $config
     * @param HTMLPurifier_Context $context
     * @return string
     */
    public function preFilter($html, $config, $context)
    {
        $pre_regex = '/<div class="bx-embed-link" source="([^"]*)">(.*)<\/div>/i';
        $pre_replace = '<div class="bx-embed-link" source="\1">\1</div>';
        return preg_replace($pre_regex, $pre_replace, $html);

    }

    /**
     * @param string $html
     * @param HTMLPurifier_Config $config
     * @param HTMLPurifier_Context $context
     * @return string
     */
    public function postFilter($html, $config, $context)
    {
        return $html;
    }
}

