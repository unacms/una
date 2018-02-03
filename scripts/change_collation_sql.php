<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCoreScripts Scripts
 * @{
 */

$aPathInfo = pathinfo(__FILE__);
require_once ($aPathInfo['dirname'] . '/../inc/header.inc.php');
require_once(BX_DIRECTORY_PATH_INC . 'design.inc.php');


/**
 * Command line interface for generating a list of service functions
 */
class BxDolChangeCOllationSqlCmd
{
    protected $_sTablesPrefix = '';
    protected $_aTextTypes = array('char', 'varchar', 'tinytext', 'text', 'mediumtext', 'longtext');

    public function main()
    {
        $a = getopt('hp:');

        if (isset($a['h']) || !$a)
            $this->finish(0, $this->getHelp());

        if (isset($a['p']))
            $this->_sTablesPrefix = $a['p'];

        if (!$this->_sTablesPrefix)
            $this->finish(1, 'Tables prefix is mandatory');
        
        $this->process();
    }

    protected function process()
    {
        $oDb = BxDolDb::getInstance();
        $aTables = $oDb->getColumn("SHOW TABLES FROM `" . BX_DATABASE_NAME . "` LIKE ?", [$this->_sTablesPrefix . '%']);

        $s = '';
        foreach ($aTables as $sTable) {
            $s .= "-- TABLE: " . $sTable . "\n\n";
            $s .= "ALTER TABLE `$sTable` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;\n\n";

            $aFields = $oDb->getAll("SHOW COLUMNS FROM `" . $sTable . "`");
            foreach ($aFields as $r) {
                if (!$this->isTextField($r['Type']))
                    continue;

                $s .= "ALTER TABLE `$sTable` CHANGE `{$r['Field']}` `{$r['Field']}` {$r['Type']} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;\n";
            }

            $s .= "\n\n";
        }

        $this->finish(0, $s);
    }

    protected function isTextField($sType)
    {
        foreach ($this->_aTextTypes as $s) {
            if (0 === strpos($sType, $s))
                return true;
        }
        return false;
    }
    
    protected function getHelp()
    {
        $n = 21;
        $s = "\nUsage: php change_collation_sql.php [options]\n";

        $s .= str_pad("\t -h", $n) . "Print this help\n";
        $s .= str_pad("\t -p <prefix>", $n) . "Tables prefix to prins SQL queries for\n\n";
        $s .= "\n";

        return $s;
    }

    protected function finish ($iCode, $sMsg = null, $bAddLn = true)
    {
        if (null !== $sMsg)
            echo $sMsg . ($bAddLn ? "\n" : '');
        exit($iCode);
    }    
}

$o = new BxDolChangeCOllationSqlCmd();
$o->main();

/** @} */
