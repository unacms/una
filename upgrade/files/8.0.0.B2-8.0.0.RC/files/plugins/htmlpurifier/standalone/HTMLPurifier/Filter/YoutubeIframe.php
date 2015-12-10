<?php

class HTMLPurifier_Filter_YoutubeIframe extends HTMLPurifier_Filter
{
    public $name = 'YouTubeIframe';

    public function preFilter($html, $config, $context)
    {
        if (strstr($html, '<iframe')) {
            $html = str_ireplace("</iframe>", "", $html);
            if (preg_match_all("/<iframe(.*?)>/si", $html, $result)) {
                foreach ($result[1] as $key => $item) {
                    preg_match('/width="([0-9]+)"/', $item, $width);
                    $width = $width[1];
                    preg_match('/height="([0-9]+)"/', $item, $height);
                    $height = $height[1];
                    preg_match('/((https?:\/\/www\.youtube\.com\/embed\/)|(https?:\/\/www\.youtube-nocookie\.com\/embed\/)|(https?:\/\/www.youtube.com\/v\/))([a-zA-Z0-9_-]+)/', $item, $id);
                    $id = $id[5];
                    $sProto = 0 == strncmp('https', BX_DOL_URL_ROOT, 5) ? 'https' : 'http';
                    $html = str_replace($result[0][$key], '<img class="YouTubeIframe" width="' . $width . '" height="' . $height . '" src="' . $sProto . '://www.youtube-nocookie.com/embed/' . $id . '?rel=0">', $html);
                }
            }
        }
        return $html;
    }

    public function postFilter($html, $config, $context)
    {
        $post_regex = '#<img class="YouTubeIframe" ([^>]+)>#';
        $html = preg_replace_callback($post_regex, array($this, 'postFilterCallback'), $html);
        return $html;
    }

    protected function postFilterCallback($matches)
    {
        return '<iframe frameborder="0" allowfullscreen ' . $matches[1] . '></iframe>';
    }
}

