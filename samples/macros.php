<?php
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCoreSamples Samples
 * @{
 */

/**
 * @page samples
 * @section macros Macros list
 */

/**
 * This sample shows list of all available macros with links to the documentation
 */

require_once('./../inc/header.inc.php');
require_once(BX_DIRECTORY_PATH_INC . "design.inc.php");

$oTemplate = BxDolTemplate::getInstance();
$oTemplate->setPageNameIndex (BX_PAGE_DEFAULT);
$oTemplate->setPageHeader ("Macros List");
$oTemplate->setPageContent ('page_main_code', PageCompMainCode());
$oTemplate->getPageCode();

/**
 * page code function
 */
function PageCompMainCode()
{
    ob_start();

    $aBaseClasses = array(
        'BxBaseModGeneralModule' => 'bx_base_general',
    );

    $o = BxDolModuleQuery::getInstance();
    $aModules = $o->getModules();
    foreach ($aModules as $aModule) {
        $oModule = BxDolModule::getInstance($aModule['name']);
        if (!$oModule)
            continue;
        $oModuleReflection = new ReflectionClass(get_class($oModule));

        $a = bx_srv($aModule['name'], 'get_safe_services');
        if (!$a)
            continue;
        echo '<h1>' . $aModule['title'] . '</h1>';
        echo '<p>';
        foreach ($a as $sService) {
            $oMethodReflection = $oModuleReflection->getMethod('service' . $sService);
            $sDeclaringClass = $oMethodReflection->getDeclaringClass()->getName();
            
            $sService = trim(preg_replace_callback('/[A-Z]/', function ($aMatches) {
                return '_' . strtolower($aMatches[0]);
            }, $sService), '_');

            $sModule = isset($aBaseClasses[$sDeclaringClass]) ? $aBaseClasses[$sDeclaringClass] : $aModule['name'];

            echo '<a href="https://ci.una.io/docs/service.html#' . $sModule . '-' . $sService . '">';
            echo '{{!' . $aModule['name']  . ':' . $sService . '[...]}}';
            echo '</a><br />';
        }
        echo '</p>';
    }

    $s = ob_get_clean();
    return DesignBoxContent("Macros List", $s, BX_DB_PADDING_DEF);
}

/** @} */
