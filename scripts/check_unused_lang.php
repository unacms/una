<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Scripts
 * @{
 */

$aPathInfo = pathinfo(__FILE__);
require_once ($aPathInfo['dirname'] . '/../inc/header.inc.php');
require_once(BX_DIRECTORY_PATH_INC . 'design.inc.php');

bx_import('BxDolXmlParser');

/**
 * Command line interface for checking for unused language strings
 */
class BxDolCheckUnusedLangsCmd
{
    protected $_bQuiet = false;
    protected $_sInputFile = '';
    protected $_sOutputFoundFile = '';
    protected $_sOutputUnusedFile = '';
    protected $_bSortOutput = false;
    protected $_sModule = false;
    protected $_iLimit = 0;
    protected $_aOptionsSystem;
    protected $_aOptionsModule;
    protected $_aAlwaysTrue;

    public function __construct() 
    {
        $this->_aAlwaysTrue = array (
            '/^_are you sure\?$/',
            '/^_mma_/',
            '/^_ps_group_/',
            '/^_adm_txt_modules_/',
            '/^_adm_ipbl_Type/',
            '/^_week_/',
            '/^_adm_txt_module_/',
            '/^_adm_block_cpt_/',
            '/^_adm_lmi_cpt_/',
            '/^_adm_block_cpt_/',
            '/^_adm_bp_cpt_type_/',
            '/^_adm_form_txt_field_type_/',
            '/^_adm_form_txt_field_checker_/',
            '/^_sys_uploader_/',
            '/^_adm_[A-Za-z0-9_-]+_cnf_/',
            '/^_sys_request_[A-Za-z0-9_-]+_not_found_(cpt|cnt)/',

            // timeline
            '/^_bx_timeline_alert_module_/',
            '/^_bx_timeline_alert_action_/',

            // sites 
            '/^_bx_sites_txt_status_/',
            '/^_bx_sites_txt_payment_type_/',
            '/^_bx_sites_paypal_duration_/',

            // developer 
            '/^_bx_dev_pgt_txt_manage_/',

            // antispam
            '/^_bx_antispam_chain_/',
            '/^_bx_antispam_type_/',
        );

        $this->_aOptionsSystem = array (
            'dirs' => array (
                '' => array ('*.php'),
                'install/' => array('*.php', '*.sql'),
                'studio/' => array('*.php', '*.html'),
                'inc/' => array ('*.php', '*.js'),
                'modules/' => array('*.php', '*.html', '*.sql'),
                'template/' => array('*.php', '*.html'),
            ),
        );

        $this->_aOptionsModule = array (
            'dirs' => array (
                "modules/{module_dir}/" => array ('*.php'),
                "modules/{module_dir}/install/sql/" => array('*.sql'),
                "modules/{module_dir}/classes/" => array('*.php'),
                "modules/{module_dir}/js/" => array ('*.js'),
                "modules/{module_dir}/template/" => array('*.php', '*.html'),
                "modules/{module_dir}/inc/" => array ('*.php'),
            ),
        );
    }

    public function main()
    {
        $a = getopt('hqsm:l:i:f:u:');

        if (isset($a['h']))
            $this->finish(0, $this->getHelp());

        if (isset($a['q']))
            $this->_bQuiet = true;

        if (isset($a['s']))
            $this->_bSortOutput = true;

        if (isset($a['m']))
            $this->_sModule = $a['m'];

        if (isset($a['l']))
            $this->_iLimit = (int)$a['l'];

        if (isset($a['i']))
            $this->_sInputFile = $a['i'];

        if (!$this->_sInputFile || !file_exists($this->_sInputFile))
            $this->finish(2, 'Input file not exist - ' . $this->_sInputFile);

        if (isset($a['f']))
            $this->_sOutputFoundFile = $a['f'];

        if (isset($a['u']))
            $this->_sOutputUnusedFile = $a['u'];

        $aOptions = $this->_sModule ? $this->prepareModuleConfig($this->_aOptionsModule) : $this->_aOptionsSystem;

        $iTimeStart = microtime ();

        $this->output('Processing ' . ($this->_iLimit > 0 ? "first {$this->_iLimit} strings of " : '') . $this->_sInputFile . ' ' . ($this->_sModule ? 'module(' . $this->_sModule .')' : 'system') . ' language file...');
        $this->output('');

        $oXmlParser = BxDolXmlParser::getInstance();
        $sXmlContent = file_get_contents($this->_sInputFile);
        $LANG = $oXmlParser->getValues($sXmlContent, 'string');
        $LANG_FOUND = array();
        $LANG_LOST = array();
        $iCount = 1;

        foreach ($LANG as $sKey => $sString) {
            if ($this->findLangKey($sKey, $aOptions))
                $LANG_FOUND[$sKey] = $sString;
            else
                $LANG_LOST[$sKey] = $sString;
            if (++$iCount > $this->_iLimit && $this->_iLimit > 0)
                break;
        }

        if (!$this->_bQuiet) {
            $this->output('Found language strings:');
            $this->output('-----------------------');
            empty($LANG_FOUND) ? $this->output('Empty') : $this->output($this->xmlExport($LANG_FOUND), false);
            $this->output('');
        }

        if ($this->_bSortOutput)
            ksort($LANG_FOUND);
        if ($this->_sOutputFoundFile && !empty($LANG_FOUND))
            file_put_contents($this->_sOutputFoundFile, $this->xmlExport($LANG_FOUND));

        if (!$this->_bQuiet) {
            $this->output('Unused language strings:');
            $this->output('------------------------');
            empty($LANG_LOST) ? $this->output('Empty') : $this->output($this->xmlExport($LANG_LOST), false);
            $this->output('');
        }

        if ($this->_bSortOutput)
            ksort($LANG_LOST);
        if ($this->_sOutputUnusedFile && !empty($LANG_LOST))
            file_put_contents($this->_sOutputUnusedFile, $this->xmlExport($LANG_LOST));

        $i1 = explode(' ', microtime ());
        $i2 = explode(' ', $iTimeStart);
        $iSec = round(($i1[0]+$i1[1]) - ($i2[0]+$i2[1]), 3);
        $this->output("Time ($iCount): " . $iSec . ' sec');
        if ($this->_iLimit > 0) {
            $iTotalSec = round(count($LANG) / $iCount * (($i1[0]+$i1[1]) - ($i2[0]+$i2[1])), 3);
            $this->output("Estimated Total Time (" . count($LANG) . "): " . $iTotalSec . ' sec (' . round($iTotalSec / 60.0, 3) . ' min)');
        }

        $this->finish(empty($LANG_LOST) ? 0 : 1);
    }

    protected function getHelp()
    {
        $n = 21;
        $s = "Usage: php check_unused_lang.php [options]\n";

        $s .= str_pad("\t -h", $n) . "Print this help\n";
        $s .= str_pad("\t -q", $n) . "Quiet\n";
        $s .= str_pad("\t -s", $n) . "Sort output\n";
        $s .= str_pad("\t -m <vendor/module>", $n) . "Process as module lang file, vendor and module folder names must be specified\n";
        $s .= str_pad("\t -l <number>", $n) . "Limit number of processing strings (usefull for estimating output time)\n";
        $s .= str_pad("\t -i <input_file>", $n) . "Input XML language file\n";
        $s .= str_pad("\t -f <output_file>", $n) . "Output XML file for found strings\n";
        $s .= str_pad("\t -u <output_file>", $n) . "Output XML file for unused strings\n";
        $s .= "\n";

        $s .= "Return codes:\n";
        $s .= str_pad("\t 0", 5) . "Success - no unused language strings\n";
        $s .= str_pad("\t 1", 5) . "Unused language strings were found\n";
        $s .= str_pad("\t 2", 5) . "Error occured\n";

        return $s;
    }

    protected function findLangKey($sKey, $aOptions)
    {
        foreach ($this->_aAlwaysTrue as $sReg)
            if (preg_match($sReg, $sKey))
                return true;

        foreach ($aOptions['dirs'] as $sDir => $aExt) {

            $sDepth = $sDir ? '' : ' -depth 1 ';
            if ($this->_sModule) {
                $aDir = explode('/', $sDir);
                $sDepth = !$sDir || $this->_sModule == ($aDir[(count($aDir) - 3)] . '/' . $aDir[(count($aDir) - 2)]) ? ' -depth 1 ' : '';
            }
         
            $sDir = BX_DIRECTORY_PATH_ROOT . $sDir;

            if (!file_exists($sDir))
                continue;

            foreach ($aExt as $sExt) {
                $sResults = `find $sDir $sDepth -name "$sExt" -exec grep "\<$sKey\>" {} \;`;
                if ($sResults)
                    return true;
            }
        }

        return false;
    }

    protected function xmlExport($a, $sRoot = 'resources', $bCData = true) 
    {
        $s = "<" . $sRoot . ">\n";
        foreach ($a as $sKey => $sString) {
            if ($bCData)
                $sString = '<![CDATA[' . $sString . ']]>';
            $s .= "\t".'<string name="' . $sKey . '">' . $sString . "</string>\n";
        }
        $s .= "</" . $sRoot . ">\n";
        return $s;
    }

    protected function prepareModuleConfig ($a) {
        if (!$this->_sModule)
            return $a;
        $aRet = array();
        $aRet['dirs'] = array();
        foreach ($a['dirs'] as $sDir => $aPatterns) {
            $sDir = str_replace('{module_dir}', $this->_sModule, $sDir);
            $aRet['dirs'][$sDir] = $aPatterns;
        }
        return $aRet;
    }
    
    protected function finish ($iCode, $sMsg = null, $bAddLn = true)
    {
        if (!$this->_bQuiet && null !== $sMsg)
            echo $sMsg . ($bAddLn ? "\n" : '');
        exit($iCode);
    }

    protected function output ($sMsg, $bAddLn = true)
    {
        if (!$this->_bQuiet)
            echo $sMsg . ($bAddLn ? "\n" : '');
    }
}

$o = new BxDolCheckUnusedLangsCmd();
$o->main();

/** @} */
