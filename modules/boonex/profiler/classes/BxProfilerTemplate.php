<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Profiler Profiler
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxDolModuleTemplate');

if ((int)phpversion() < 5)
    require_once(BX_DIRECTORY_PATH_MODULES . 'boonex/profiler/inc/fb4.php');
else
    require_once(BX_DIRECTORY_PATH_MODULES . 'boonex/profiler/inc/fb5.php');

class BxProfilerTemplate extends BxDolModuleTemplate {

    var $_isAjaxOutput = false;

    /**
     * Constructor
     */
    function BxProfilerTemplate(&$oConfig) {
        $oDb = null;
        parent::BxDolModuleTemplate($oConfig, $oDb);
        $this->_isAjaxOutput = $this->_isAjaxRequest();
    }

    function plank($sTitle, $sContent = '') {
        if ($this->_isAjaxOutput) {
            if ($sContent && is_array($sContent))
                fb($sContent, $sTitle, FIREPHP_TABLE);
            else
                fb($sTitle . $sContent);
            return '';
        }
        if ($sContent)
            $sContent = '<div class="bx_profiler_switch" onclick="bx_profiler_switch(this)">+</div><div class="bx_profiler_content">'.$sContent.'</div>';
        return '<div class="bx_profiler_plank"><span class="bx_profiler_plank_title">' . $sTitle . '</span>' . $sContent . '</div>';
    }

    function nameValue ($sName, $sVal) {
        return $this->_isAjaxOutput ? "{$sName}{$sVal} | " : "<u>$sName</u><b>$sVal</b>";
    }

    function table ($a, $sHighlight = '') {

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
            foreach ($r as $k => $v)
            {
                $sClass = '';
                if ($sHighlight && $k == $sHighlight)
                    $sClass = ' class="highlight" ';

                $s .= "<td $sClass>".htmlspecialchars_adv($v)."</td>";
            }
            $s .= '</tr>';
        }
        $s .= '</tbody></table>';
        $s .= '<script type="text/javascript">$(document).ready(function(){ $(\'#'.$sId.'\').tablesorter(); });</script>';
        return $s;
    }

    function _isAjaxRequest () {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) and $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')
            return true;
        if (isset($_GET['bx_profiler_ajax_request']))
            return true;
        if (preg_match('/popup\.php/', bx_html_attribute($_SERVER['PHP_SELF'])))
            return true;
        if (preg_match('/subscription\.php/', bx_html_attribute($_SERVER['PHP_SELF'])))
            return true;
        if (preg_match('/vote\.php/', bx_html_attribute($_SERVER['PHP_SELF'])))
            return true;
        if (!empty($_GET['r']) && preg_match('/^poll\//', $_GET['r']))
            return true;
        if (preg_match('/pageBuilder\.php/', bx_html_attribute($_SERVER['PHP_SELF'])) && $_REQUEST['action'] == 'load')
            return true;
        return false;
    }

}

/** @} */
