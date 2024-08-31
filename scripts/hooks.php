<?php

$bMarkdown = false; // use Markdown format output, when `true` then 'league/html-to-markdown' library need to be installed with `composer require league/html-to-markdown`
$sBaseUrl = 'wiki/alerts-hooks'; // base URL for the generated docs
$sBaseDocUrl = 'https://ci.una.io/docs/';

use League\HTMLToMarkdown\HtmlConverter;
$oConverter = null;
if ($bMarkdown) {
    require 'vendor/autoload.php';
    $oConverter = new HtmlConverter();
}

$sXml = file_get_contents('xml/hook.xml'); // xml folder should contain XML output from doxygen

$oHooks = new SimpleXMLElement($sXml);

$i = 0;
$aOutput = [];
$aUrls = [];
foreach ($oHooks->xpath('//listitem/para/ref') as $oRef) {

    if (!$oRef->asXML())
        continue;

    // get hook reference id
    $sKey = dom_import_simplexml($oRef)->nodeValue;

    // generate URL with hook description and anchor 
    $a = explode('_1', $oRef['refid'], 2);
    $sUrl = $sBaseDocUrl . "{$a[0]}.html";
    $sAnchor = $a[1];

    // get page content with hook desription
    $s = '';
    if (!isset($aUrls[$sUrl])) {
        $s = file_get_contents($sUrl);
        $aUrls[$sUrl] = $s;
    }
    else {
        $s = $aUrls[$sUrl];
    }

    // parse page content to get hook snippet
    $iStart = mb_strpos($s, '"' . $sAnchor . '"');
    if ($iStart === false)
        continue;
    $iEnd = mb_strpos($s, '<dl class="hook"><dt><b><a class="el" href="hook.html', $iStart);
    $sSnippetHtml = mb_substr($s, $iStart - 25, $iEnd - $iStart + 25);
    if ($bMarkdown)
        $sSnippetMd = $oConverter->convert($sSnippetHtml);

    // fix broken HTML
    $oDoc = new DOMDocument();
    @$oDoc->loadHTML($sSnippetHtml);
    $sSnippetHtmlFixed = $oDoc->saveHTML();
    $iBodyStart = mb_strpos($sSnippetHtmlFixed, '<body>');
    $iBodyEnd = mb_strpos($sSnippetHtmlFixed, '</body>');
    if ($iBodyStart !== false && $iBodyEnd !== false)
        $sSnippetHtmlFixed = mb_substr($sSnippetHtmlFixed, $iBodyStart + 6, $iBodyEnd - $iBodyStart - 6);

    // fix links
    $sSnippetHtmlFixed = preg_replace_callback('/href="(.*?)"/', function ($m) use ($sBaseDocUrl) {
        if (false === ($i = mb_strpos($m[1], '#hook-')))
            return 'href="' . $sBaseDocUrl . $m[1] . '"';            
        else
            return 'href="' . mb_substr($m[1], -mb_strlen($m[1]) + $i) . '"';            
    }, $sSnippetHtmlFixed);

    if ($bMarkdown) {
        $sSnippetMd = preg_replace_callback('/\((.*?\.html.*?)\)/', function ($m) use ($sBaseDocUrl, $sBaseUrl) {
            if (false === ($i = mb_strpos($m[1], '#hook-')))
                return '(' . $sBaseDocUrl . $m[1] . ')';
            else
                return '(' . $sBaseUrl . mb_substr($m[1], -mb_strlen($m[1]) + $i) . ')';
        }, $sSnippetMd);
        $sSnippetMd = preg_replace('/\((#hook-.*?)\)/', '(' . $sBaseUrl . '$1)', $sSnippetMd);
        $sSnippetMd = preg_replace('/[=]{3,}/', '-------------------------------', $sSnippetMd);
    }

    // add to output
    if (isset($aOutput[$sKey]))
        echo "\nERROR: duplicate hook detected - " . $sKey . " <br />\n";
    $aOutput[$sKey] = $bMarkdown ? $sSnippetMd : $sSnippetHtmlFixed;

    ++$i;
    // if ($i>10)
    //    break;
}

// sort hooks
asort($aOutput);

// output
echo "<h1>$i Hooks</h1><hr />\n\n";
if ($bMarkdown) {
    echo '<pre>' . implode("\n\n", $aOutput) . '</pre>';
} else {
    echo implode("\n\n", $aOutput);
}
