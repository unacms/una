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
class BxDolLangsMergeCmd
{
    protected $_bQuiet = false;
    protected $_sInputFileOld = '';
    protected $_sInputFileNew = '';
    protected $_bSortOutput = false;
    protected $_sModule = false;
    protected $_iLimit = 0;

    public function __construct() 
    {
    }

    public function main()
    {
        $a = getopt('hso:n:');

        if (isset($a['h']) || !$a)
            $this->finish(0, $this->getHelp());

        if (isset($a['s']))
            $this->_bSortOutput = true;

        if (isset($a['o']))
            $this->_sInputFileOld = '/' == $a['o'][1] ? $a['o'] : BX_DIRECTORY_PATH_ROOT . $a['o'];

        if (isset($a['n']))
            $this->_sInputFileNew = '/' == $a['n'][1] ? $a['n'] : BX_DIRECTORY_PATH_ROOT . $a['n'];

        if (!$this->_sInputFileOld || !file_exists($this->_sInputFileOld))
            $this->finish(2, 'Input old file not exist - ' . $this->_sInputFileOld);

        if (!$this->_sInputFileNew || !file_exists($this->_sInputFileNew))
            $this->finish(2, 'Input new file not exist - ' . $this->_sInputFileNew);

        $oXmlParser = BxDolXmlParser::getInstance();
        $sXmlContentOld = file_get_contents($this->_sInputFileOld);
        $sXmlContentNew = file_get_contents($this->_sInputFileNew);
        $aLangOld = $oXmlParser->getValues($sXmlContentOld, 'string');
        $aLangNew = $oXmlParser->getValues($sXmlContentNew, 'string');

        $this->fixEmptyValues($aLangOld);
        $this->fixEmptyValues($aLangNew);

        $aLangAdd = $this->findAddedKeys($aLangNew, $aLangOld);
        $aLangChange = $this->findChangedKeys($aLangNew, $aLangOld);
        $aLangDel = $this->findDeletedKeys($aLangNew, $aLangOld);

        if (!$aLangAdd && !$aLangChange && !$aLangDel) {
            $this->output("No any changes");
            $this->finish(0);
        }

        if ($this->_bSortOutput) {
            ksort($aLangAdd);
            ksort($aLangChange);
            ksort($aLangDel);
        }

        if ($aLangAdd)
            $this->output($this->xmlExport($aLangAdd, 'string_add', ''), false);
        if ($aLangChange)
            $this->output($this->xmlExport($aLangChange, 'string_upd', ''), false);
        if ($aLangDel)
            $this->output($this->xmlExport($aLangDel, 'string_del', ''), false);

        $this->finish(1);
    }

    function fixEmptyValues (&$a)
    {
        foreach ($a as $k => $v)
            if (NULL === $v)
                $a[$k] = '';
    }

    function findDeletedKeys ($aLangNew, $aLangOld)
    {
        $a = array ();
        foreach ($aLangOld as $k => $v)
            if (!isset($aLangNew[$k]))
                $a[$k] = $v;
        return $a;
    }

    function findAddedKeys ($aLangNew, $aLangOld)
    {
        $a = array ();
        foreach ($aLangNew as $k => $v)
            if (!isset($aLangOld[$k]))
                $a[$k] = $v;
        return $a;
    }

    function findChangedKeys ($aLangNew, $aLangOld)
    {
        $a = array ();
        foreach ($aLangNew as $k => $v)
            if (isset($aLangOld[$k]) && $aLangOld[$k] != $aLangNew[$k])
                $a[$k] = $v;
        return $a;
    }
    
    protected function getHelp()
    {
        $n = 21;
        $s = "Usage: php check_unused_lang.php [options]\n";

        $s .= str_pad("\t -h", $n) . "Print this help\n";
        $s .= str_pad("\t -s", $n) . "Sort output\n";
        $s .= str_pad("\t -o <input_file>", $n) . "Input old XML language file\n";
        $s .= str_pad("\t -n <input_file>", $n) . "Input new XML language file\n";        
        $s .= "\n";

        $s .= "Return codes:\n";
        $s .= str_pad("\t 0", 5) . "Success - no any difference\n";
        $s .= str_pad("\t 1", 5) . "Changes were found\n";
        $s .= str_pad("\t 2", 5) . "Error occured\n";

        return $s;
    }

    protected function xmlExport($a, $sTag = 'string', $sRoot = 'resources', $bCData = true) 
    {
        if (!$sTag)
            $sTag = 'string';
        $s = $sRoot ? "<" . $sRoot . ">\n" : "\n";
        foreach ($a as $sKey => $sString) {
            if ($bCData)
                $sString = '<![CDATA[' . $sString . ']]>';
            $s .= "\t".'<' . $sTag . ' name="' . $sKey . '">' . $sString . "</$sTag>\n";
        }
        $s .= $sRoot ? "</" . $sRoot . ">\n" : '';
        return $s;
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

$o = new BxDolLangsMergeCmd();
$o->main();

/** @} */
