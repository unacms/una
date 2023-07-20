<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Profiler Profiler
 * @ingroup     UnaModules
 *
 * @{
 */

class BxProfilerTemplate extends BxDolModuleTemplate
{
    protected $_isAjaxOutput = false;

    function __construct(&$oConfig)
    {
        $oDb = null;
        parent::__construct($oConfig, $oDb);
        $this->_isAjaxOutput = $this->_isAjaxRequest();
    }

    function plank($sTitle, $sContent = '')
    {
        static $i = 0;
        $i++;
        if ($this->_isAjaxOutput) {
            if (!headers_sent() && function_exists('fb')) {
                if ($sContent && is_array($sContent))
                    fb($sContent, $sTitle, FirePHP::TABLE);
                else
                    fb($sTitle . $sContent);
            }
            elseif (!headers_sent()) {
                if ($sContent && is_array($sContent)) {
                    $sContentEnc = json_encode($sContent);
                    header("X-Una-Profiler-$i: $sTitle" . ($sContent && $sContentEnc < 4000 ? '; ' . $sContentEnc : ''));
                } else {
                    header("X-Una-Profiler-$i: $sTitle" . ($sContent && $sContent < 4000 ? '; ' . $sContent : ''));
                }
            }
            return '';
        }
        if ($sContent)
            $sContent = '<div class="bx_profiler_switch" onclick="bx_profiler_switch(this)">+</div><div class="bx_profiler_content">'.$sContent.'</div>';
        return '<div class="bx_profiler_plank"><span class="bx_profiler_plank_title">' . $sTitle . '</span>' . $sContent . '</div>';
    }

    function nameValue ($sName, $sVal)
    {
        return $this->_isAjaxOutput ? "{$sName}{$sVal} | " : "<u>$sName</u><b>$sVal</b>";
    }

    function table ($a, $sHighlight = '')
    {
        if ($this->_isAjaxOutput) {
            $table = array();
            foreach ($a as $r) {

                if (!$table)
                    $table[] = array_keys($r);

                $rr = array_values($r);
                if (false !== strpos($rr[0], '&#160;'))
                    $rr[0] = str_replace('&#160;', '-', $rr[0]);
                $table[] = $rr;
            }
            return $table;
        }

        $sId = md5(time() . rand());
        $s = '<table id="'.$sId.'" class="bx_profiler_table">';
        $th = '';
        foreach ($a as $r) {
            if (!$th) {
                foreach ($r as $k => $v)
                    $th .= "<th>$k</th>";
                $s .= "<thead><tr>$th</tr></thead><tbody>";
            }
            $s .= '<tr>';
            foreach ($r as $k => $v) {
                $sClass = '';
                if ($sHighlight && $k == $sHighlight)
                    $sClass = ' class="highlight" ';

                $s .= "<td $sClass>".htmlspecialchars_adv($v)."</td>";
            }
            $s .= '</tr>';
        }
        $s .= '</tbody></table>';
        $s .= '<script language="javascript">$(document).ready(function(){ $(\'#'.$sId.'\').tablesorter(); });</script>';
        return $s;
    }

    function _isAjaxRequest ()
    {
        if ('application/json' == bx_profiler_get_header_content_type()) 
            return true;

        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) and $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')
            return true;
        if (isset($_GET['bx_profiler_ajax_request']))
            return true;

        if (preg_match('/vote\.php/', bx_html_attribute($_SERVER['PHP_SELF'])))
            return true;
        if (preg_match('/image_transcoder\.php/', bx_html_attribute($_SERVER['PHP_SELF'])))
            return true;
        if (preg_match('/storage\.php/', bx_html_attribute($_SERVER['PHP_SELF'])))
            return true;
        if (preg_match('/storage_uploader\.php/', bx_html_attribute($_SERVER['PHP_SELF'])))
            return true;
        if (preg_match('/searchKeywordContent\.php/', bx_html_attribute($_SERVER['PHP_SELF'])))
            return true;        
        if (preg_match('/menu\.php/', bx_html_attribute($_SERVER['PHP_SELF'])))
            return true;
        if (preg_match('/gzip_loader\.php/', $_SERVER['PHP_SELF']))
            return true;
        if (preg_match('/get_rss_feed\.php/', $_SERVER['PHP_SELF']))
            return true;
        return false;
    }

    function addCss($mixedFiles, $bDynamic = false)
    {
        if ($this->_isAjaxOutput)
            return '';
        return parent::addCss($mixedFiles, $bDynamic);
    }

    function addJs($mixedFiles, $bDynamic = false)
    {
        if ($this->_isAjaxOutput)
            return '';
        return parent::addJs($mixedFiles, $bDynamic);
    }
}

/** @} */
