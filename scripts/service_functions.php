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
class BxDolServiceFunctionsCmd
{
    protected $_sInputFile = '';

    public function main()
    {
        $a = getopt('hi:');

        if (isset($a['h']) || !$a)
            $this->finish(0, $this->getHelp());

        if (isset($a['i']))
            $this->_sInputFile = '/' == $a['i'][1] ? $a['i'] : BX_DIRECTORY_PATH_ROOT . $a['i'];

        if (!$this->_sInputFile || !file_exists($this->_sInputFile))
            $this->finish(1, 'Input old file not exist - ' . $this->_sInputFile);

        if (false === ($a = file($this->_sInputFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES)))
            $this->finish(2, 'Input file read error - ' . $this->_sInputFile);
        
        $this->process($a);
    }

    protected function process($a)
    {
        $aGroups = array(

            // System functions
            'BxDolCmts' => 'System',
            'BxDolRequest' => 'System',
            'BxBaseServiceAccount' => 'System',
            'BxBaseServiceCategory' => 'System',
            'BxBaseServiceConnections' => 'System',
            'BxBaseServiceLogin' => 'System',
            'BxBaseServiceMetatags' => 'System',
            'BxBaseServiceProfiles' => 'System',
            'BxBaseServices' => 'System',
            'BxBaseTemplateServices' => 'System',
            'BxBaseAclServices' => 'System',
            'BxBaseCmtsServices' => 'System',
            'BxBaseUploaderServices' => 'System',
            'BxBaseImageServices' => 'System',
            'BxBaseLanguagesServices' => 'System',
            'BxBaseLiveUpdatesServices' => 'System',
            'BxBasePaymentsServices' => 'System',

            // Functions in all content modules
            'BxBaseModGeneralModule' => 'General content modules', 

            // Functions in Groups based modules (Groups, Events)
            'BxBaseModGroupsModule' => 'Groups based modules', 

            // Functions in Notifocations based modules (Notofocations, Timeline)
            'BxBaseModNotificationsModule' => 'Notifications based modules', 

            // Functions in Payments based modules
            'BxBaseModPaymentCart' => 'Payments based modules', 
            'BxBaseModPaymentDetails' => 'Payments based modules', 
            'BxBaseModPaymentModule' => 'Payments based modules', 
            'BxBaseModPaymentOrders' => 'Payments based modules', 
            'BxBaseModPaymentSubscriptions' => 'Payments based modules',

            // Functions in Profiles based modules (Persons, Organizations)
            'BxBaseModProfileModule' => 'Profiles based modules', 

            // Functions in Text based modules (Posts, Forums)
            'BxBaseModTextModule' => 'Text based modules', 

            // Studio functionality
            'BxDolStudioInstallerUtils' => 'Studio related functionality',
            'BxDolStudioModules' => 'Studio related functionality',
            'BxBaseStudioDashboard' => 'Studio related functionality',
            'BxBaseStudioDesignServices' => 'Studio related functionality',
            'BxBaseStudioLauncher' => 'Studio related functionality',
            'BxBaseStudioSettingsServices' => 'Studio related functionality',
            
            // Individual modules
            '*' => 'Individual modules',

            
        );

        $aGroupedArray = array();
        foreach ($a as $s) {
            if (!preg_match('/(.*):[\s\w]+function\s(.*)/', $s, $m)) {
                $this->finish(2, 'FAIL!!! - ' . $s);
                continue;
            }

            if (!($aPath = pathinfo(BX_DIRECTORY_PATH_ROOT . $m[1])))
                continue;

            $sClass = $aPath['filename'];
            $sFunc = $m[2];

            $sGroup = isset($aGroups[$sClass]) ? $aGroups[$sClass] : $aGroups['*'];
            $aGroupedArray[$sGroup][] = $sClass . '::' . $sFunc . "\n";
        }

        foreach ($aGroupedArray as $sGroup => $aLines) {
            echo "\n// $sGroup\n";
            foreach ($aLines as $s)
                echo "$s";
        }
    }

    protected function getHelp()
    {
        $n = 21;
        $s = "\nUsage: php service_functions.php [options]\n";

        $s .= str_pad("\t -h", $n) . "Print this help\n";
        $s .= str_pad("\t -i <input_file>", $n) . "Input file\n\n";
        $s .= "Input file can be generated form the following command output:\n";
        $s .= str_pad("\t grep -r 'function service' * | grep -v updates | grep -v upgrade  | grep -v BxDol\.php | grep -v hosting_api | grep -v BxBaseModConnectModule | grep -v -i ^bin | grep -v '/vendor/' > scripts/service_functions.txt\n", $n);
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

$o = new BxDolServiceFunctionsCmd();
$o->main();

/** @} */
